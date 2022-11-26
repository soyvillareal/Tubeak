<?php
if ($TEMP['#loggedin'] === false || $TEMP['#settings']['history'] == 'off') {
    header("Location: " . Specific::Url('login'));
    exit();
}

$videos = $dba->query('SELECT * FROM history WHERE by_id = '.$TEMP['#user']['id'].' AND video_id NOT IN ('.Specific::BlockedVideos().') ORDER BY id DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();

if (!empty($videos)) {
    foreach ($videos as $value) {
        $video = Specific::Video($value['video_id']);

        $TEMP['!live'] = $video['live'];
        $TEMP['!get_progress'] = Specific::GetProgress($video['id']);
        $TEMP['!id'] = $value['id'];
        $TEMP['!title'] = $video['title'];
        $TEMP['!description'] = Specific::GetUncomposeText($video['description'], 130);
        $TEMP['!views'] = Specific::Number($video['views']);
        $TEMP['!data'] = $video['data'];
        $TEMP['!thumbnail'] = $video['thumbnail'];
        $TEMP['!url'] = $video['url'];
        $TEMP['!time'] = $video['time_string'];
        $TEMP['!progress_value'] = $TEMP['!get_progress']['percent_loaded'];
        $TEMP['!duration'] = $video['duration'];
        $TEMP['!video_id'] = $video['video_id'];
        $TEMP['!animation'] = $video['animation'];

        $TEMP['history_list'] .= Specific::Maket('history/includes/list');
    }
    Specific::DestroyMaket();
} else {
	$TEMP['history_list'] = Specific::Maket('not-found/history');
}

$TEMP['#videos']      = count($videos);
$TEMP['#page']        = 'history';
$TEMP['#title']       = $TEMP['#word']['history'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url']    = Specific::Url('history');

$TEMP['#content']     = Specific::Maket('history/content');
?>