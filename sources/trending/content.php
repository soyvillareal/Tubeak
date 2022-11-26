<?php
$end = strtotime('-1 minute', strtotime(date("h:i:s A")));
$start = strtotime('-24 hour', strtotime(date("h:i:s A")));
$videos = $dba->query('SELECT video_id, COUNT(*) AS count FROM views v WHERE `time` >= '.$start.' AND `time` <= '.$end.'  AND (SELECT id FROM videos b WHERE id = v.video_id AND privacy = 0 AND ((SELECT live FROM broadcasts WHERE video_id = b.video_id) = 1 OR (SELECT live FROM broadcasts WHERE video_id = b.video_id) = 3 OR broadcast = 0) AND deleted = 0 AND by_id NOT IN ('.$TEMP['#blocked_users'].')) = video_id GROUP BY video_id ORDER BY count DESC LIMIT ' . $TEMP['#settings']['data_load_limit'])->fetchAll();

if (!empty($videos)) {
    foreach ($videos as $value) {
    	$video = Specific::Video($value['video_id']);
        
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

        $TEMP['videos'] .= Specific::Maket('trending/includes/list');
    }
    Specific::DestroyMaket();
} else {
    $TEMP['videos'] = Specific::Maket('not-found/contribute');
}

$TEMP['#videos']      = count($videos);
$TEMP['#page']        = 'trending';
$TEMP['#title']       = $TEMP['#word']['trending'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url']    = Specific::Url('trending');

$TEMP['#content'] = Specific::Maket('trending/content');
?>