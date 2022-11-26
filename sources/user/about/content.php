<?php
$id = Specific::Filter($_GET['two']);
$user_data   = Specific::Data($id, 2);
if (empty($user_data)) {
    header("Location: " . Specific::Url('404'));
    exit();
}
$TEMP['#is_owner'] = Specific::IsOwner($user_data['id'], 2);

$TEMP['#blocked'] = !in_array($user_data['id'], Specific::BlockedUsers(2));
$TEMP['#data']   = $user_data;
$TEMP['#repos_enabled'] = !preg_match('/themes/i', $TEMP['#data']['cover']);
$TEMP['text'] = $TEMP['#word']['block'];
if ($dba->query('SELECT COUNT(*) FROM blocked WHERE by_id = "'.$TEMP['#user']['id'].'" AND to_id = '.$user_data['id'])->fetchArray() > 0) {
    $TEMP['text'] = $TEMP['#word']['unblock'];
}

$TEMP['#views'] = Specific::Number($dba->query('SELECT SUM(views) FROM videos v WHERE by_id = '.$user_data['id'].' AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND deleted = 0')->fetchArray());
$TEMP['data'] = $user_data;
$TEMP['subscribe_button'] = Specific::SubscribeButton($user_data['id']);
$TEMP['date_time'] = $TEMP['#data']['date_time'];
$TEMP['block_id'] = $user_data['id'];

$TEMP['#page']          = 'about';
$TEMP['#title']         = $user_data['username'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description']   = $TEMP['#settings']['description'];
$TEMP['#keyword']       = $TEMP['#settings']['keyword'];
$TEMP['#load_url']      = Specific::Url("user/$id?page=about");

$TEMP['second_page']    = Specific::Maket('user/about/content');
$TEMP['#content']       = Specific::Maket('user/content');
?>