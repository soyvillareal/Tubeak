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
$TEMP['#load_url'] = Specific::Url('settings?page=change-password');

if (!empty($user_id)) {
    $TEMP['href_setting'] = "?id=$user_id";
    $TEMP['href_settings'] = "&id=$user_id";
    $TEMP['#load_url'] = Specific::Url('settings?page=change-password'.$TEMP['href_settings']);
}

$TEMP['data'] = $TEMP['#data'];
$TEMP['#unverified']  = ($dba->query('SELECT COUNT(*) FROM subscriptions WHERE by_id = '.$TEMP['#data']['id'])->fetchArray() >= $TEMP['#settings']['verification_subscribers_cap'] && $dba->query('SELECT COUNT(*) FROM requests WHERE status = 0 AND by_id = '.$TEMP['#user']['id'])->fetchArray() == 0 && $TEMP['#data']['verified'] == 0);

$TEMP['#page']        = 'change-password';
$TEMP['#title']       = $TEMP['#word']['settings'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];

$TEMP['second_page'] = Specific::Maket('settings/change-password/content');
$TEMP['#content']     = Specific::Maket("settings/content");
?>