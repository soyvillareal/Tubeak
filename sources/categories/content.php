<?php
$page = Specific::Filter($_GET['two']);

if (!in_array($_GET['two'], array_keys($TEMP['#categories'])) || empty($_GET['two'])) {
    header("Location: " . Specific::Url('404'));
    exit();
}

$videos = $dba->query('SELECT * FROM videos v WHERE privacy = 0 AND category = "'.$page.'" AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 1 OR (SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND deleted = 0 AND by_id NOT IN ('.$TEMP['#blocked_users'].') ORDER BY id DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();

if (!empty($videos)) {
    foreach ($videos as $value) {
    	$video = Specific::Video($value);
        
        $TEMP['!live'] = $video['live'];
        $TEMP['!get_progress'] = Specific::GetProgress($video['id']);
        $TEMP['!id'] = $video['id'];
        $TEMP['!title'] = $video['title'];
        $TEMP['!views'] = Specific::Number($video['views']);
        $TEMP['!data'] = $video['data'];
        $TEMP['!thumbnail'] = $video['thumbnail'];
        $TEMP['!url'] = $video['url'];
        $TEMP['!time'] = $video['time_string'];
        $TEMP['!progress_value'] = $TEMP['!get_progress']['percent_loaded'];
        $TEMP['!duration'] = $video['duration'];
        $TEMP['!video_id'] = $video['video_id'];
        $TEMP['!animation'] = $video['animation'];

        $TEMP['videos'] .= Specific::Maket('categories/includes/list');
    }
    Specific::DestroyMaket();
} else {
    $TEMP['videos'] = Specific::Maket('not-found/contribute');
}

$TEMP['#videos']      = count($videos);
$TEMP['#page']        = $page;
$TEMP['#title']       = $TEMP['#categories'][$page] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url']    = Specific::Url('categories/'.$page);

$TEMP['#content'] = Specific::Maket('categories/content');
?>