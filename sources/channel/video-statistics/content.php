<?php 
if (empty($_GET['id'])) {
	header("Location: " . Specific::Url('404'));
	exit();
}

$id = Specific::Filter($_GET['id']);
$video = Specific::Video($id);

if ($TEMP['#loggedin'] === false || empty($video) || !$video['is_owner']) {
	header("Location: " . Specific::Url('login'));
	exit();
}

$live_broadcast = $dba->query('SELECT live FROM broadcasts WHERE video_id = "'.$video['video_id'].'"')->fetchArray();
if ($video['broadcast'] == 1 && $live_broadcast < 2) {
	header("Location: " . Specific::Url('404'));
	exit();
}

$time = time();
$id = $video['id'];
$types = array('today','this_week','this_month','this_year');
$TEMP['#type'] = 'today';
$type_url = '';

if (!empty($_GET['type']) && in_array($_GET['type'], $types)) {
	$TEMP['#type'] = $_GET['type'];
	$type_url = '&type='.$TEMP['#type'];
}

if ($TEMP['#type'] == 'today') {
	$strtime = 'G';
	$start = strtotime('today, 12:00am', $time);
	$end = strtotime('today, 11:59pm', $time);
	$high_array_one = $high_array_two = $high_array_three = $high_array_four = array('0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0, '13' => 0, '14' => 0, '15' => 0, '16' => 0, '17' => 0, '18' => 0, '19' => 0, '20' => 0, '21' => 0, '22' => 0, '23' => 0);
    
    $TEMP['high_title'] = $TEMP['#word']['today'];
    $TEMP['high_text'] = $TEMP['#word'][strtolower(date("l"))];
} elseif ($TEMP['#type'] == 'this_week') {
	$strtime = 'N';
	if (date('D') == 'Sat'){
     	$start = strtotime(date("Y-m-d").' 12:00am');
	} else {
	    $start = strtotime(date("Y-m-d", strtotime('last Saturday, 11:59pm', $time)));
	}
	if(date('D') == 'Fri'){
		$end = strtotime(date("Y-m-d").' 11:59pm');
	} else {
		$end = strtotime('next Friday, 11:59pm', $time);
	}
	$high_array_one = $high_array_two = $high_array_three = $high_array_four = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0);

    $TEMP['high_title'] = $TEMP['#word']['this_week'];
    $TEMP['high_text'] = Specific::DateFormat($start) . ' - ' . Specific::DateFormat($end);
} elseif ($TEMP['#type'] == 'this_month') {
	$strtime = 'j';
	$start = strtotime("1 ".date('M')." ".date('Y')." 12:00am");
	$end = strtotime(cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'))." ".date('M')." ".date('Y')." 11:59pm");
	$days_month = date('t', strtotime(date('Y-m-d')));
	$high_array = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0, '13' => 0, '14' => 0, '15' => 0, '16' => 0, '17' => 0, '18' => 0, '19' => 0, '20' => 0, '21' => 0, '22' => 0, '23' => 0,'24' => 0, '25' => 0, '26' => 0, '27' => 0, '28' => 0, '29' => 0, '30' => 0, '31' => 0);
	if($days_month < 31){
		unset($high_array['31']);
		if($days_month < 30){
			unset($high_array['30']);
			if($days_month < 29){
				unset($high_array['29']);
			}
		}
	}
	$high_array_one = $high_array_two = $high_array_three = $high_array_four = $high_array;

    $TEMP['high_title'] = $TEMP['#word']['this_month'];
    $TEMP['high_text'] = $TEMP['#word'][strtolower(date("F"))];
    $TEMP['month_days'] = implode(', ', array_keys($high_array));
} elseif ($TEMP['#type'] == 'this_year') {
	$strtime = 'n';
	$start = strtotime('first day of January, 12:00am', $time);
	$end = strtotime('last day of December, 11:59pm', $time);
	$high_array_one = $high_array_two = $high_array_three = $high_array_four = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0);

    $TEMP['high_title'] = $TEMP['#word']['this_year'];
    $TEMP['high_text'] = date("Y");
}

$comments_count = $dba->query('SELECT COUNT(*) FROM comments WHERE video_id = '.$id)->fetchArray();

$high_data_one = $dba->query('SELECT `time` FROM reactions WHERE type = 1 AND place = "video" AND to_id = '.$id.' AND time >= '.$start.' AND time <= '.$end)->fetchAll(FALSE);
$high_data_two = $dba->query('SELECT `time` FROM reactions WHERE type = 2 AND place = "video" AND to_id = '.$id.' AND time >= '.$start.' AND time <= '.$end)->fetchAll(FALSE);
$high_data_three = $dba->query('SELECT `time` FROM views WHERE video_id = '.$id.' AND time >= '.$start.' AND time <= '.$end)->fetchAll(FALSE);
$high_data_four = $dba->query('SELECT `time` FROM comments WHERE video_id = '.$id.' AND time >= '.$start.' AND time <= '.$end)->fetchAll(FALSE);

for ($i=0; $i < count($high_data_one); $i++) { 
	$time = date($strtime, $high_data_one[$i]);
	if (in_array($time, array_keys($high_array_one))) {
		$high_array_one[$time] += 1; 
	}
}

for ($i=0; $i < count($high_data_two); $i++) { 
	$time = date($strtime, $high_data_two[$i]);
	if (in_array($time, array_keys($high_array_two))) {
		$high_array_two[$time] += 1; 
	}
}

for ($i=0; $i < count($high_data_three); $i++) { 
	$time = date($strtime, $high_data_three[$i]);
	if (in_array($time, array_keys($high_array_three))) {
		$high_array_three[$time] += 1; 
	}
}

for ($i=0; $i < count($high_data_four); $i++) { 
	$time = date($strtime, $high_data_four[$i]);
	if (in_array($time, array_keys($high_array_four))) {
		$high_array_four[$time] += 1; 
	}
}

$TEMP['high_array_one'] = implode(', ', $high_array_one);
$TEMP['high_array_two'] = implode(', ', $high_array_two);
$TEMP['high_array_three'] = implode(', ', $high_array_three);
$TEMP['high_array_four'] = implode(', ', $high_array_four);

$TEMP['video_id'] = $video['video_id'];
$TEMP['title'] = $video['title'];
$TEMP['description'] = Specific::GetUncomposeText($video['description'], 130);
$TEMP['url'] = $video['url'];
$TEMP['thumbnail'] = $video['thumbnail'];
$TEMP['views'] = Specific::Number($video['views']);
$TEMP['time'] = $video['time_string'];
$TEMP['duration'] = $video['duration'];
$TEMP['likes'] = Specific::Number($video['likes']);
$TEMP['dislikes'] = Specific::Number($video['dislikes']);
$TEMP['comments'] = Specific::Number($comments_count);

$TEMP['#page'] = 'video-statistics';
$TEMP['#title'] = $TEMP['#word']['statistics'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword'] = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] = Specific::Url('channel?page=video-statistics&id='.Specific::Filter($_GET['id']).$type_url);

$TEMP['second_page'] = Specific::Maket("channel/video-statistics/content");
$TEMP['#content'] = Specific::Maket("channel/content");
?>