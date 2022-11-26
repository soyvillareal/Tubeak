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
$TEMP['#load_url'] = Specific::Url('settings?page=security');

if (!empty($user_id)) {
    $TEMP['href_setting'] = "?id=$user_id";
    $TEMP['href_settings'] = "&id=$user_id";
    $TEMP['#load_url'] = Specific::Url('settings?page=security'.$TEMP['href_settings']);
}


$user_sessions = $dba->query('SELECT * FROM sessions WHERE by_id = '.$TEMP['#data']['id'].' ORDER BY id DESC LIMIT ? OFFSET ?', $TEMP['#settings']['data_load_limit'], 1)->fetchAll();
$TEMP['#total_pages'] = $dba->totalPages;

if (!empty($user_sessions)) {
    foreach ($user_sessions as $value) {
        $session = Specific::GetSessions($value);

        $TEMP['!id'] = $value['id'];
        $TEMP['!ip'] = $session['ip'];
        $TEMP['!browser'] = $session['browser'];
        $TEMP['!platform'] = $session['platform'];
        $TEMP['!time'] = Specific::DateFormat($value['time']);

        $TEMP['sessions'] .= Specific::Maket("settings/security/includes/sessions");
    }
    Specific::DestroyMaket();
} else {
    $TEMP['sessions'] = Specific::Maket("not-found/sessions");
}

$TEMP['#unverified']  = ($dba->query('SELECT COUNT(*) FROM subscriptions WHERE by_id = '.$TEMP['#data']['id'])->fetchArray() >= $TEMP['#settings']['verification_subscribers_cap'] && $dba->query('SELECT COUNT(*) FROM requests WHERE status = 0 AND by_id = '.$TEMP['#user']['id'])->fetchArray() == 0 && $TEMP['#data']['verified'] == 0);
$TEMP['data'] = $TEMP['#data'];
$TEMP['settings_id'] = $TEMP['#data']['id'];

$TEMP['#page']        = 'security';
$TEMP['#title']       = $TEMP['#word']['settings'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];

$TEMP['second_page'] = Specific::Maket('settings/security/content');
$TEMP['#content']     = Specific::Maket("settings/content");
?>