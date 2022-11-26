<?php 
if ($TEMP['#loggedin'] === false) {
	header("Location: " . Specific::Url('login'));
	exit();
}

if($TEMP['#settings']['live_broadcast'] == 'off'){
    header("Location: " . Specific::Url('404'));
    exit();
}

$TEMP['#is_live_now'] = true;
$TEMP['json_broadcast_text'] = 0;
$TEMP['thumbnail'] = '';
$broadcast_data = $dba->query('SELECT * FROM broadcasts WHERE by_id = '.$TEMP['#user']['id'].' AND (live = 0 OR live = 1)')->fetchArray();
if(empty($broadcast_data)){
	$video_id = $TEMP['video_id'] = Specific::RandomKey(12, 16);
    if ($dba->query('SELECT COUNT(*) FROM videos WHERE video_id = "'.$video_id.'"') > 0) {
        $video_id = $TEMP['video_id'] = Specific::RandomKey(12, 16);
    }
    $short_id = Specific::RandomKey(4, 8);
    if ($dba->query('SELECT COUNT(*) FROM videos WHERE short_id = "'.$short_id.'"') > 0) {
         $short_id = $TEMP['short_id'] = Specific::RandomKey(4, 8);
    }
    $saved_broadcast = 0;
    $chat_broadcast = 1;
    $TEMP['title'] = $TEMP['#word']['live_broadcast_title'].' '.$TEMP['#word']['of'].' '.$TEMP['#user']['username'];
    $TEMP['description'] = '';
    $TEMP['#thumbnail'] = '';
    $TEMP['#category'] = 'others';
    $TEMP['#privacy'] = 0;
    $TEMP['#adults_only'] = 0;
    $TEMP['#tags'] = array();
	$dba->query('INSERT INTO broadcasts (by_id, video_id, save, live, time) VALUES ('.$TEMP['#user']['id'].',"'.$video_id.'",0,0,'.time().')');
	$TEMP['id'] = $dba->query('INSERT INTO videos (video_id, short_id, by_id, title, adults_only, broadcast, thumbnail, thumbnail_1, thumbnail_2, thumbnail_3) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', $video_id, $short_id, $TEMP['#user']['id'], $TEMP['title'], 0, 1, NULL, NULL, NULL, NULL)->insertId();
} else {
	$saved_broadcast = $broadcast_data['save'];
	$chat_broadcast = $broadcast_data['chat'];
	$video_data = $dba->query('SELECT * FROM videos WHERE video_id = "'.$broadcast_data['video_id'].'"')->fetchArray();
	$TEMP['id'] 	  = $video_data['id'];
	$TEMP['video_id'] = $video_data['video_id'];
	$TEMP['title']	  = $video_data['title'];
	$TEMP['description'] = $video_data['description'];
	$TEMP['tags']      = $video_data['tags'];
	$TEMP['#thumbnail'] = $video_data['thumbnail'];
	$TEMP['#category'] = $video_data['category'];
	$TEMP['#privacy']  = $video_data['privacy'];
	$TEMP['#adults_only'] = $video_data['adults_only'];
	$TEMP['#tags']     = !empty($video_data['tags']) ? explode(",", $video_data['tags']) : array();
}

$json_path = array();
$data_stream = Specific::DataStream();
$TEMP['viewers'] = $data_stream['server']['application']['live']['stream']['nclients'];
$TEMP['force_progress'] = $data_stream['server']['application']['live']['stream']['time'] / 1000;
$TEMP['update_media_source'] = file_exists("uploads/temp/{$TEMP['video_id']}.m3u8") ? false : true;
$stream_width = $data_stream['server']['application']['live']['stream']['meta']['video']['height'];
$json_path[] = array("src" => $TEMP['video_id'], "data-quality" => '144p', "title" => '144p', "res" => '144');

if ($stream_width >= 426 || $stream_width == 0) {
    $json_path[0]['data-quality'] = $json_path[0]['title'] = '240p'; 
    $json_path[0]['res'] = '240'; 
} else if ($stream_width >= 640 || $stream_width == 0) {
    $json_path[0]['data-quality'] = $json_path[0]['title'] = '360p'; 
    $json_path[0]['res'] = '360'; 
} else if ($stream_width >= 854 || $stream_width == 0) {
    $json_path[0]['data-quality'] = $json_path[0]['title'] = '480p'; 
    $json_path[0]['res'] = '480';
} else if ($stream_width >= 1280 || $stream_width == 0) {
    $json_path[0]['data-quality'] = $json_path[0]['title'] = '720p'; 
    $json_path[0]['res'] = '720';
} else if ($stream_width >= 1920 || $stream_width == 0) {
    $json_path[0]['data-quality'] = $json_path[0]['title'] = '1080p';
    $json_path[0]['res'] = '1080'; 
} else if ($stream_width >= 1440) {
    $json_path[0]['data-quality'] = $json_path[0]['title'] = '2K'; 
    $json_path[0]['res'] = '1440';
} else if ($stream_width >= 2160) {
    $json_path[0]['data-quality'] = $json_path[0]['title'] = '4K'; 
    $json_path[0]['res'] = '2160';
}

$TEMP['json_path'] = json_encode($json_path);

$TEMP['broadcast_is_live'] = '';
$TEMP['hidden_img'] = '';
$TEMP['broadast_status'] = $TEMP['#word']['stream_start_sending_streaming_software'];
$TEMP['word_thumbnail'] = $TEMP['#word']['change'];
$TEMP['#enable_clear'] = true;
if(empty($TEMP['#thumbnail'])){
	$TEMP['hidden_img'] = ' hidden';
	$TEMP['word_thumbnail'] = "{$TEMP['#word']['upload_thumbnail']} ({$TEMP['#word']['optional']})";
}
if($broadcast_data['live'] == 1){
	$TEMP['broadcast_is_live'] = ' broadcast-is-live';
	$TEMP['broadast_status'] = $TEMP['#word']['successful_connection_now_you_are_broadcasting'];
	if(!empty($TEMP['#thumbnail'])){
		$TEMP['#enable_clear'] = false;
	}
}

$TEMP['#page']        = 'live-broadcast';
$TEMP['#title']       = $TEMP['#word']['live_broadcast_title'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url']    = Specific::Url('live-broadcast');

$TEMP['checked_chat'] = $chat_broadcast == 1 ? ' checked' : '';
$TEMP['disabled_chat'] = $broadcast_data['live'] == 1 ? ' disabled' : '';

$TEMP['checked_save'] = $saved_broadcast == 1 ? ' checked' : '';
$TEMP['broadcast_text'] = Specific::BroadcastChat($TEMP['id']);
$TEMP['rtmp_url'] = 'rtmp://'.$_SERVER['SERVER_NAME'].'/live?key='.$TEMP['#user']['live_key'];

$TEMP['broadcast_chat'] = Specific::Maket('live-broadcast/broadcast-chat');

$TEMP['#content']     = Specific::Maket("live-broadcast/content");
?>