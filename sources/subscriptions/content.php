<?php
if ($TEMP['#loggedin'] === false) {
    header("Location: ".Specific::Url('login'));
    exit();
}

$subscriptions = $dba->query('SELECT by_id FROM subscriptions WHERE subscriber_id = '.$TEMP['#user']['id'].' AND by_id NOT IN ('.$TEMP['#blocked_users'].')')->fetchAll(FALSE);

if (!empty($subscriptions)) {
    $videos = $dba->query('SELECT * FROM videos v WHERE by_id IN ('.implode(',', $subscriptions).') AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 1 OR (SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND privacy = 0 AND deleted = 0 ORDER BY `id` DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();
}
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
        $TEMP['subscriptions_list'] .= Specific::Maket('subscriptions/includes/list');
    }
    Specific::DestroyMaket();
} else {
	$TEMP['subscriptions_list'] = Specific::Maket("not-found/subscriptions");
}

$TEMP['#videos']      = count($videos);
$TEMP['#page']        = 'subscriptions';
$TEMP['#title']       = $TEMP['#word']['subscriptions'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url']    = Specific::Url('subscriptions');

$TEMP['#content']     = Specific::Maket('subscriptions/content');
?>