<?php
$id = Specific::Filter($_GET['two']);
$user_data   = Specific::Data($id, 2);
if (empty($user_data)) {
    header("Location: " . Specific::Url('404'));
    exit();
}
$TEMP['#is_owner'] = Specific::IsOwner($user_data['id'], 2);

$query = '';
if($TEMP['#is_owner'] == false){
    $query = ' AND privacy = 0';
}
$videos = $dba->query('SELECT id FROM videos v WHERE by_id = '.$user_data['id'].$query.' AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 1 OR (SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND deleted = 0 AND by_id NOT IN ('.$TEMP['#blocked_users'].') ORDER BY id DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();

if(!empty($videos)){
    foreach ($videos as $value) {
        $video = Specific::Video($value['id']);

        $TEMP['!live'] = $video['live'];
        $TEMP['!get_progress'] = Specific::GetProgress($video['id']);
        $TEMP['!id'] = $video['id'];
        $TEMP['!title'] = $video['title'];
        $TEMP['!views'] = Specific::Number($video['views']);
        $TEMP['!thumbnail'] = $video['thumbnail'];
        $TEMP['!url'] = $video['url'];
        $TEMP['!time'] = $video['time_string'];
        $TEMP['!progress_value'] = $TEMP['!get_progress']['percent_loaded'];
        $TEMP['!duration'] = $video['duration'];
        $TEMP['!video_id'] = $video['video_id'];
        $TEMP['!animation'] = $video['animation'];

        $TEMP['videos'] .= Specific::Maket('user/videos/includes/list');
    }
    Specific::DestroyMaket();
} else {
    $TEMP['videos'] = Specific::Maket('not-found/videos');
}

$TEMP['#blocked'] = !in_array($user_data['id'], Specific::BlockedUsers(2));
$TEMP['#data'] = $TEMP['data'] = $user_data;
$TEMP['#repos_enabled'] = !preg_match('/themes/i', $TEMP['#data']['cover']);
$TEMP['#videos'] = count($videos);

$TEMP['subscribe_button'] = Specific::SubscribeButton($user_data['id']);

$TEMP['#page']          = 'user';
$TEMP['#title']         = $user_data['username'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description']   = $TEMP['#settings']['description'];
$TEMP['#keyword']       = $TEMP['#settings']['keyword'];
$TEMP['#load_url']      = Specific::Url("user/$id");

$TEMP['second_page']    = Specific::Maket('user/videos/content');
$TEMP['#content']       = Specific::Maket('user/content');
?>