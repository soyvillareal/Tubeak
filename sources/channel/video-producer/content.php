<?php 
if ($TEMP['#loggedin'] === false) {
	header("Location: " . Specific::Url('login'));
	exit();
}
$type = Specific::Filter($_GET['type']);
$order_url = $type ? "&type=$type" : "";

if (isset($type) && !empty($type)) {
    if ($type == 'views') {
        $videos = $dba->query('SELECT * FROM videos v WHERE by_id = '.$TEMP['#user']['id'].' AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND deleted = 0 ORDER BY views DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();
    } else if ($type == 'likes') {
    	$videos = $dba->query('SELECT * FROM videos v WHERE by_id = '.$TEMP['#user']['id'].' AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND deleted = 0 ORDER BY likes DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();
    } else if ($type == 'dislikes') {
    	$videos = $dba->query('SELECT * FROM videos v WHERE by_id = '.$TEMP['#user']['id'].' AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND deleted = 0 ORDER BY dislikes DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();
    } else if ($type == 'comments') {
    	$more_ids = array(0);
    	$comments = $dba->query('SELECT video_id, COUNT(*) AS comments FROM comments c WHERE (SELECT id FROM videos WHERE by_id = '.$TEMP['#user']['id'].' AND id = c.video_id AND deleted = 0) = video_id GROUP BY video_id ORDER BY comments DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();
    	if(!empty($comments)){
			foreach ($comments as $value) {
	    		$videos[] = $dba->query('SELECT * FROM videos WHERE id = '.$value['video_id'])->fetchArray();
	    		$more_ids[] = $value['video_id'];
	    	}
	    	$more_videos = $dba->query('SELECT * FROM videos v WHERE by_id = '.$TEMP['#user']['id'].' AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND id NOT IN ('.implode(',', $more_ids).') AND deleted = 0 ORDER BY id DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();
	    	if(!empty($more_videos)){
	    		$videos = array_merge($videos, $more_videos);
	    	}
		}

    }
} else {
	$videos = $dba->query('SELECT * FROM videos v WHERE by_id = '.$TEMP['#user']['id'].' AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND deleted = 0 ORDER BY id DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();
}

if (!empty($videos)) {
	foreach ($videos as $value) {
		$video = Specific::Video($value);  
	    $comments_count = $dba->query('SELECT COUNT(*) FROM comments WHERE video_id = '.$video['id'])->fetchArray();

	    $TEMP['!live'] = $video['live'];
		$TEMP['!id'] = $video['id'];
		$TEMP['!data'] = $video['data'];
		$TEMP['!thumbnail'] = $video['thumbnail'];
		$TEMP['!url'] = $video['url'];
		$TEMP['!title'] = $video['title'];
		$TEMP['!description'] = Specific::GetUncomposeText($video['description'], 130);
		$TEMP['!views'] = Specific::Number($video['views']);
		$TEMP['!time'] = $video['time_string'];
		$TEMP['!duration'] = $video['duration'];
		$TEMP['!video_id'] = $video['video_id'];
		$TEMP['!likes'] = Specific::Number($video['likes']);
		$TEMP['!dislikes'] = Specific::Number($video['dislikes']);
		$TEMP['!comments'] = Specific::Number($comments_count);
	    $TEMP['videos'] .= Specific::Maket('channel/video-producer/includes/list');
	}
	Specific::DestroyMaket();
} else {
	$TEMP['videos'] = Specific::Maket("not-found/contribute");
}

$TEMP['#videos'] = count($videos);
$TEMP['#page'] = 'video-producer';
$TEMP['#title'] = $TEMP['#word']['video_studio'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword'] = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] = Specific::Url('channel?page=video-producer'.$order_url);

$TEMP['second_page'] = Specific::Maket("channel/video-producer/content");
$TEMP['#content'] = Specific::Maket("channel/content");
?>