<?php
if ($TEMP['#loggedin'] === false) {
    header("Location: " . Specific::Url('login'));
    exit();
}
$by_id = $TEMP['#user']['id'];
$user_id = Specific::Filter($_GET['id']);
if (isset($_GET['id']) && !empty($user_id) && Specific::Admin() === true) {
    if ($dba->query('SELECT COUNT(*) FROM users WHERE user_id = "'.$user_id.'"')->fetchArray() == 0) {
        header("Location: " . Specific::Url('404'));
        exit();
    }
    $by_id = $dba->query('SELECT id FROM users WHERE user_id = "'.$user_id.'"')->fetchArray();
}

$TEMP['#data']     = Specific::Data($by_id);
$TEMP['#load_url'] = Specific::Url('settings?page=blocked-users');

if (!empty($user_id)) {
    $TEMP['href_setting'] = "?id=$user_id";
    $TEMP['href_settings'] = "&id=$user_id";
    $TEMP['#load_url'] = Specific::Url('settings?page=blocked-users'.$TEMP['href_settings']);
}

$users = $dba->query('SELECT * FROM blocked WHERE by_id = '.$by_id.' ORDER BY to_id ASC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();
if(!empty($users)){
    foreach ($users as $value) {
        $TEMP['!id']   = $value['to_id'];
        $TEMP['!data'] = Specific::Data($value['to_id']);
        $TEMP['!text'] = $TEMP['#word']['unblock'];
        $TEMP['!time'] = Specific::DateString($value['time']);
        $TEMP['blocked_users'] .= Specific::Maket("settings/blocked-users/includes/users-list");
    }
    Specific::DestroyMaket();
} else {
    $TEMP['blocked_users'] = Specific::Maket("not-found/users");
}

$TEMP['data'] = $TEMP['#data'];
$TEMP['#users'] = count($users);
$TEMP['#unverified']  = ($dba->query('SELECT COUNT(*) FROM subscriptions WHERE by_id = '.$TEMP['#data']['id'])->fetchArray() >= $TEMP['#settings']['verification_subscribers_cap'] && $dba->query('SELECT COUNT(*) FROM requests WHERE status = 0 AND by_id = '.$TEMP['#user']['id'])->fetchArray() == 0 && $TEMP['#data']['verified'] == 0);

$TEMP['#page']        = 'blocked-users';
$TEMP['#title']       = $TEMP['#word']['settings'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];

$TEMP['second_page'] = Specific::Maket('settings/blocked-users/content');
$TEMP['#content']     = Specific::Maket("settings/content");
?>