<?php 
if ($TEMP['#loggedin'] === false) {
	header("Location: " . Specific::Url('login'));
	exit();
}

$this_month_comments = $dba->query('SELECT COUNT(*) FROM comments c WHERE (SELECT id FROM videos WHERE by_id = '.$TEMP['#user']['id'].' AND id = c.video_id AND deleted = 0) = video_id')->fetchArray();
$this_month_views = $dba->query('SELECT COUNT(*) FROM views v WHERE (SELECT id FROM videos WHERE by_id = '.$TEMP['#user']['id'].' AND id = v.video_id AND deleted = 0) = video_id')->fetchArray();
$this_month_likes = $dba->query('SELECT COUNT(*) FROM reactions l WHERE type = 1 AND place = "video" AND (SELECT id FROM videos WHERE by_id = '.$TEMP['#user']['id'].' AND id = l.to_id AND deleted = 0) = to_id')->fetchArray();
$this_month_dislikes = $dba->query('SELECT COUNT(*) FROM reactions l WHERE type = 2 AND place = "video" AND (SELECT id FROM videos WHERE by_id = '.$TEMP['#user']['id'].' AND id = l.to_id AND deleted = 0) = to_id')->fetchArray();
$this_month_subscribers = $dba->query('SELECT COUNT(*) FROM subscriptions l WHERE by_id = '.$TEMP['#user']['id'])->fetchArray();

$time = time();
$TEMP['#type'] = 'today';
$type_url = '';

if (!empty($_GET['type']) && in_array($_GET['type'], array('today','this_week','this_month','this_year'))) {
	$TEMP['#type']= $_GET['type'];
	$type_url = '?type='.$TEMP['#type'];
}

if ($TEMP['#type'] == 'today') {
	$strtime = 'G';
	$start = strtotime('today, 12:00am', $time);
	$end = strtotime('today, 11:59pm', $time);
	$high_array_one = $high_array_two = $high_array_three = $high_array_four = $high_array_five = array('0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0, '13' => 0, '14' => 0, '15' => 0, '16' => 0, '17' => 0, '18' => 0, '19' => 0, '20' => 0, '21' => 0, '22' => 0, '23' => 0);
    
    $TEMP['high_title'] = $TEMP['#word']['today'];
    $TEMP['high_text']  = $TEMP['#word'][strtolower(date("l"))];
} else if ($TEMP['#type'] == 'this_week') {
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
	$high_array_one = $high_array_two = $high_array_three = $high_array_four = $high_array_five = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0);

    $TEMP['high_title'] = $TEMP['#word']['this_week'];
    $TEMP['high_text'] = Specific::DateFormat($start) . ' - ' . Specific::DateFormat($end);
} else if ($TEMP['#type'] == 'this_month') {
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
	$high_array_one = $high_array_two = $high_array_three = $high_array_four = $high_array_five = $high_array;

    $TEMP['high_title'] = $TEMP['#word']['this_month'];
    $TEMP['high_text'] = $TEMP['#word'][strtolower(date("F"))];
    $TEMP['month_days'] = implode(', ', array_keys($high_array));
} else if ($TEMP['#type'] == 'this_year') {
	$strtime = 'n';
	$start = strtotime('first day of January, 12:00am', $time);
	$end = strtotime('last day of December, 11:59pm', $time);
	$high_array_one = $high_array_two = $high_array_three = $high_array_four = $high_array_five = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0);

    $TEMP['high_title'] = $TEMP['#word']['this_year'];
    $TEMP['high_text'] = date("Y");
}

$high_data_one = $dba->query('SELECT `time` FROM comments c WHERE (SELECT id FROM videos WHERE by_id = '.$TEMP['#user']['id'].' AND id = c.video_id AND deleted = 0) = video_id AND time >= '.$start.' AND time <= '.$end)->fetchAll(FALSE);
$high_data_two = $dba->query('SELECT `time` FROM views v WHERE (SELECT id FROM videos WHERE by_id = '.$TEMP['#user']['id'].' AND id = v.video_id AND deleted = 0) = video_id AND time >= '.$start.' AND time <= '.$end)->fetchAll(FALSE);	
$high_data_three = $dba->query('SELECT `time` FROM reactions l WHERE type = 1 AND place = "video" AND (SELECT id FROM videos WHERE by_id = '.$TEMP['#user']['id'].' AND id = l.to_id AND deleted = 0) = to_id AND time >= '.$start.' AND time <= '.$end)->fetchAll(FALSE);
$high_data_four = $dba->query('SELECT `time` FROM reactions l WHERE type = 2 AND place = "video" AND (SELECT id FROM videos WHERE by_id = '.$TEMP['#user']['id'].' AND id = l.to_id AND deleted = 0) = to_id AND time >= '.$start.' AND time <= '.$end)->fetchAll(FALSE);
$high_data_five = $dba->query('SELECT `time` FROM subscriptions WHERE by_id = '.$TEMP['#user']['id'].' AND time >= '.$start.' AND time <= '.$end)->fetchAll(FALSE);

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

for ($i=0; $i < count($high_data_five); $i++) { 
	$time = date($strtime, $high_data_five[$i]);
	if (in_array($time, array_keys($high_array_five))) {
		$high_array_five[$time] += 1; 
	}
}
	
$TEMP['high_array_one'] = implode(', ', $high_array_one);
$TEMP['high_array_two'] = implode(', ', $high_array_two);
$TEMP['high_array_three'] = implode(', ', $high_array_three);
$TEMP['high_array_four'] = implode(', ', $high_array_four);
$TEMP['high_array_five'] = implode(', ', $high_array_five);

$TEMP['total_likes'] = Specific::Number($this_month_likes);
$TEMP['total_dislikes'] = Specific::Number($this_month_dislikes);
$TEMP['total_views'] = Specific::Number($this_month_views);
$TEMP['total_comments'] = Specific::Number($this_month_comments);
$TEMP['total_subs'] = Specific::Number($this_month_subscribers);

$TEMP['#page'] = 'dashboard';
$TEMP['#title'] = $TEMP['#word']['dashboard'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword'] = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] = Specific::Url('channel'.$type_url);

$TEMP['second_page'] = Specific::Maket("channel/dashboard/content");
$TEMP['#content'] = Specific::Maket("channel/content");
?>