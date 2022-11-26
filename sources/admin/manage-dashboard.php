<?php 
if ($TEMP['#loggedin'] === false || Specific::Admin() === false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$time = time();
if ($TEMP['#settings']['last_statistics'] < ($time - 900)) {
    $total_videos = $dba->query('SELECT COUNT(*) FROM videos WHERE deleted = 0')->fetchArray();
    $total_views = $dba->query('SELECT SUM(views) FROM videos WHERE deleted = 0')->fetchArray();
    $total_users = $dba->query('SELECT COUNT(*) FROM users')->fetchArray();
    $total_subs = $dba->query('SELECT COUNT(*) FROM subscriptions')->fetchArray();
    $total_comments = $dba->query('SELECT COUNT(*) FROM comments')->fetchArray();
    $total_likes = $dba->query('SELECT COUNT(*) FROM reactions WHERE type = 1 AND place = "video"')->fetchArray();
    $total_dislikes = $dba->query('SELECT COUNT(*) FROM reactions WHERE type = 2 AND place = "video"')->fetchArray();
    $total_saved = $dba->query('SELECT COUNT(*) FROM lists')->fetchArray();

    $json_statistics = json_encode(array('total_videos' => $total_videos, 'total_views' => $total_views, 'total_users' => $total_users, 'total_subs' => $total_subs, 'total_comments' => $total_comments, 'total_likes' => $total_likes, 'total_dislikes' => $total_dislikes, 'total_saved' => $total_saved));
    $dba->query('UPDATE settings SET value = ? WHERE name = "statistics"', $json_statistics);
}

$types = array('today','this_week','this_month','this_year');
$TEMP['#type'] = 'today';
$type_url = '';

if (!empty($_GET['type']) && in_array($_GET['type'], $types)) {
	$TEMP['#type'] = $_GET['type'];
	$type_url = '?type='.$TEMP['#type'];
}

if ($TEMP['#type'] == 'today') {
	$strtime = 'G';
	$start = strtotime('today, 12:00am', $time);
	$end = strtotime('today, 11:59pm', $time);
	$high_array_one = $high_array_two = array('0' => 0, '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0, '13' => 0, '14' => 0, '15' => 0, '16' => 0, '17' => 0, '18' => 0, '19' => 0, '20' => 0, '21' => 0, '22' => 0, '23' => 0);

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
	$high_array_one = $high_array_two = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0);

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
	$high_array_one = $high_array_two = $high_array;

    $TEMP['high_title'] = $TEMP['#word']['this_month'];
    $TEMP['high_text'] = $TEMP['#word'][strtolower(date("F"))];
    $TEMP['month_days'] = implode(', ', array_keys($high_array));
} elseif ($TEMP['#type'] == 'this_year') {
	$strtime = 'n';
	$start = strtotime('first day of January, 12:00am', $time);
	$end = strtotime('last day of December, 11:59pm', $time);
	$high_array_one = $high_array_two = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0);

    $TEMP['high_title'] = $TEMP['#word']['this_year'];
    $TEMP['high_text'] = date("Y");
}

$statistics = json_decode($TEMP['#settings']['statistics'], true);

$high_data_one = $dba->query('SELECT `time` FROM users WHERE time >= '.$start.' AND time <= '.$end)->fetchAll(FALSE);
$high_data_two = $dba->query('SELECT `time` FROM videos WHERE time >= '.$start.' AND time <= '.$end.' AND deleted = 0')->fetchAll(FALSE);

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
	
$TEMP['high_array_one'] = implode(', ', $high_array_one);
$TEMP['high_array_two'] = implode(', ', $high_array_two);

$TEMP['total_videos'] = Specific::Number($statistics['total_videos']);
$TEMP['total_views'] = Specific::Number($statistics['total_views']);
$TEMP['total_users'] = Specific::Number($statistics['total_users']);
$TEMP['total_subs'] = Specific::Number($statistics['total_subs']);
$TEMP['total_comments'] = Specific::Number($statistics['total_comments']);
$TEMP['total_likes'] = Specific::Number($statistics['total_likes']);
$TEMP['total_dislikes'] = Specific::Number($statistics['total_dislikes']);
$TEMP['total_saved'] = Specific::Number($statistics['total_saved']);

$TEMP['#page']        = 'manage-dashboard';
$TEMP['#title']       = $TEMP['#word']['dashboard'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	  = Specific::Url('admin/manage-dashboard'.$type_url);

$TEMP['#content']     = Specific::Maket("admin/manage-dashboard");
?>