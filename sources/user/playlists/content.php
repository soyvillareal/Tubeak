<?php
$id = Specific::Filter($_GET['two']);
$user_data   = Specific::Data($id, 2);
if (empty($user_data)) {
    header("Location: " . Specific::Url('404'));
    exit();
}
$TEMP['#is_owner'] = Specific::IsOwner($user_data['id'], 2);

$query = ' AND by_id NOT IN ('.$TEMP['#blocked_users'].')';
if ($TEMP['#is_owner'] === true) {
    $watch_later = $dba->query('SELECT video_id FROM watch_later WHERE by_id = '.$user_data['id'].$query.' ORDER BY video_id ASC')->fetchAll(false);
    if(!empty($watch_later)){
        $video = Specific::Video($watch_later[0]);
        
        $TEMP['!title'] = $TEMP['#word']['watch_later'];
        $TEMP['!video_id'] = $video['video_id'];
        $TEMP['!count'] = count($watch_later);
        $TEMP['!thumbnail'] = $video['thumbnail'];
        $TEMP['!url'] = Specific::WatchSlug($video['video_id'], 'playlist', 'wl');

        $TEMP['lists'] .= Specific::Maket('user/playlists/includes/watch-later');
    }
} else {
    $query .= ' AND privacy = 1';
}

$lists = $dba->query('SELECT * FROM lists l WHERE by_id = '.$user_data['id'].$query.' AND (SELECT COUNT(*) FROM playlists WHERE l.list_id = list_id) > 0 LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();
foreach ($lists as $value) {
    $lists = $dba->query('SELECT *, COUNT(*) as count FROM playlists WHERE list_id = "'.$value['list_id'].'" ORDER BY video_id ASC LIMIT 1')->fetchArray();
    $video = Specific::Video($lists['video_id']);

    $TEMP['!id'] = $value['id'];
    $TEMP['!title'] = $value['title'];  

    $TEMP['!count'] = $lists['count'];
    $TEMP['!thumbnail'] = $video['thumbnail'];
    $TEMP['!url'] = Specific::WatchSlug($video['video_id'], 'playlist', $value['list_id']);
    $TEMP['!video_id'] = $video['video_id'];

    $TEMP['lists'] .= Specific::Maket('user/playlists/includes/playlists');    
}
Specific::DestroyMaket();

if(empty($TEMP['lists'])){
    $TEMP['lists'] = Specific::Maket('not-found/playlists');
}

$TEMP['#blocked'] = !in_array($user_data['id'], Specific::BlockedUsers(2));
$TEMP['#lists'] = count($lists);
$TEMP['#data'] = $TEMP['data'] = $user_data;
$TEMP['#repos_enabled'] = !preg_match('/themes/i', $TEMP['#data']['cover']);

$TEMP['subscribe_button'] = Specific::SubscribeButton($user_data['id']);

$TEMP['#page']          = 'playlists';
$TEMP['#title']         = $user_data['username'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description']   = $TEMP['#settings']['description'];
$TEMP['#keyword']       = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 		= Specific::Url("user/$id?page=playlists");

$TEMP['second_page']    = Specific::Maket('user/playlists/content');
$TEMP['#content']       = Specific::Maket('user/content');
?>