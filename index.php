<?php
require_once('./includes/autoload.php');
$ip_banned = Specific::IsBanned($_SERVER["REMOTE_ADDR"]);
$email_banned = Specific::IsBanned($TEMP['#user']['email']);
if (($ip_banned == true || $email_banned == true) && $_SERVER['REQUEST_URI'] != '/logout') {
    $TEMP['#email_banned'] = $email_banned;
    $TEMP['text'] = $email_banned == true ? $TEMP['#user']['email'] : $_SERVER["REMOTE_ADDR"];
    echo Specific::Maket('banned');
} else {
    $page = 'home/content.php';
    if (isset($_GET['one'])) {
        if($_GET['one'] != 'admin' || ($_GET['one'] == 'admin' && !isset($_GET['two']))){
            $page = $_GET['one'].'/'.$_GET['page'].'/content.php'; 
        } else {
            if(!empty($_GET['three'])){
                $page = $_GET['one'].'/'.$_GET['two'].'/'.$_GET['three'].'.php';
            } else {
                $page = $_GET['one'].'/'.$_GET['two'].'.php';
            }
        }
    }
    $now_url = (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on' ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $TEMP['#now_url']  = urlencode($now_url);
    $TEMP['#language_dir'] = 'ltr';
    if($TEMP['#language'] == 'ar'){
        $TEMP['#language_dir'] = 'rtl';
    }
    $TEMP['#set_cookie'] = false;
    if(!isset($_COOKIE['set_cookie']) || empty($_COOKIE['set_cookie'])){
        $TEMP['#set_cookie'] = true;
    }
    $TEMP['#more_subs'] = 0;
    $TEMP['#admin_pages'] = array('admin', 'manage-dashboard', 'manage-settings', 'manage-languages', 'manage-pages', 'manage-users', 'manage-videos', 'manage-categories', 'manage-commens', 'manage-repors', 'manage-ads', 'change-log');
    $profile_id = Specific::Filter($_GET['two']);
    if(!empty($profile_id)){
        $TEMP['#profile'] = Specific::Data($profile_id, 2);
    }
    if($TEMP['#loggedin'] === true){
        if ($TEMP['#user']['status'] != 1) {
            if (isset($_COOKIE['session_id'])) {
                setcookie('session_id', null, -1,'/');
            }
            session_destroy();
        }
        $TEMP['#darkmode'] = $TEMP['#user']['dark'];
        $live_notify = $dba->query('SELECT COUNT(*) FROM notifications WHERE seen = 0 AND to_id = '.$TEMP['#user']['id'])->fetchArray();
        if(!empty($live_notify) && is_numeric($live_notify)){
            $TEMP['#notifications'] = $live_notify > 9 ? '9+' : $live_notify;
        }
        $more_subs = array();
        $TEMP['#subscribers'] = $dba->query('SELECT * FROM subscriptions WHERE subscriber_id = "'.$TEMP['#user']['id'].'" AND by_id NOT IN ('.$TEMP['#blocked_users'].') ORDER BY id DESC LIMIT 6')->fetchAll();
        foreach ($TEMP['#subscribers'] as $value) {
            $sub = Specific::Data($value['by_id']); 
            if (!empty($sub)) {
                $TEMP['!point_active'] = $dba->query('SELECT COUNT(*) FROM broadcasts WHERE live = 1 AND by_id = '.$sub['id'])->fetchArray() > 0 ? ' background-red' : '';
                $TEMP['!data'] = $sub;
                $TEMP['!active_class'] = $TEMP['#profile']['id'] == $sub['id'] ? ' active' : '';

                $TEMP['user_subscribers'] .= Specific::Maket('header/subscribers');
                $more_subs[] = array('username' => $sub['username'], 'by_id' => $sub['id']); 
            }
        }
        Specific::DestroyMaket();
        $TEMP['#more_subs'] = JSON_encode($more_subs);
    }
    if ($TEMP['#settings']['live_broadcast'] == 'on' && !preg_match('/nginx/i', $_SERVER['SERVER_SOFTWARE'])) {
        $TEMP['#settings']['live_broadcast'] = 'off';
    }
    if (file_exists("./sources/$page")) {
        require_once("./sources/$page");
    } else {
        require_once("./sources/404/content.php");
    }
    $TEMP['#lang_names'] = array();
    $languages = Specific::Languages('name');
    foreach ($TEMP['#languages'] as $key => $value) {
        $TEMP['#lang_names'][$value] = $languages[$key];
    }
    $TEMP['footer_list'] = '';
    $pages = $dba->query('SELECT * FROM pages')->fetchAll();
    foreach ($pages as $key => $value) {
        if($value['active'] == 1){
            $TEMP['footer_list'] .= '<li class="item-footer"><a class="color-tertiary" href="'.Specific::Url("pages/{$value['type']}").'" target="_self">'.$TEMP['#word'][str_replace('-', '_', $value['type'])].'</a></li>';
        }
    }
    $header_ad = Specific::GetAd('header_ad');
    $TEMP['lang_url'] = $now_url . (strpos($_SERVER['REQUEST_URI'], '?') !== false ? '&' : '?') . 'language=';
    if(strpos($_SERVER['REQUEST_URI'], 'language') !== false){
        $TEMP['lang_url'] = preg_replace('/language=(.+?)$/i', 'language=', $_SERVER['REQUEST_URI']);
    }
    $TEMP['global_title'] = $TEMP['#title'];
    $TEMP['global_description'] = $TEMP['#description'];
    $TEMP['global_keywords'] = $TEMP['#keyword'];
    $TEMP['icon_favicon'] = Specific::GetIcon($TEMP['#settings']['icon_favicon'], 'favicon');
    $TEMP['icon_logo'] = Specific::GetIcon($TEMP['#settings']['icon_logo'], 'logo');
    $TEMP['content'] = $TEMP['#content'];
    $TEMP['year_now'] = date('Y');
    $TEMP['header_ad'] = $TEMP['#page'] == 'home' && $header_ad['active'] == 1 ? $header_ad['content'] : '';
    $TEMP['admin_pages'] = json_encode($TEMP['#admin_pages']);
    $TEMP['profile_id'] = $TEMP['#user']['id'];
    $TEMP['search_keyword'] = Specific::Filter($_GET['keyword']);
    $TEMP['check_mode'] = $TEMP['#darkmode'] == 1 ? ' checked' : '';
    $TEMP['control_panel'] = Specific::Admin() === true ? $TEMP['#word']['administration_panel'] : $TEMP['#word']['moderator_panel'];
    $TEMP['title_logo'] = Specific::Admin() === true ? $TEMP['#word']['admin'] : $TEMP['#word']['moderator'];
    $TEMP['options_left'] = Specific::Maket("header/options-left");
    $TEMP['header_html'] = Specific::Maket('header/content');
    echo Specific::Maket('wrapper');
}
$dba->close();
unset($TEMP);
?>