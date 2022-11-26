<?php
if (isset($_GET['one'])) {
    $page = 'home/content.php';
    if($_GET['one'] != 'admin' || ($_GET['one'] == 'admin' && !isset($_GET['two']))){
        $page = $_GET['one'].'/'.$_GET['page'].'/content.php';
    } else {
        if(!empty($_GET['three'])){
            $page = $_GET['one'].'/'.$_GET['two'].'/'.$_GET['three'].'.php';
        } else {
            $page = $_GET['one'].'/'.$_GET['two'].'.php';
        }
    }
    if (file_exists("./sources/$page")) {
        require_once("./sources/$page");
    } else {
        require_once("./sources/404/content.php");
    }
    $deliver['title'] = $TEMP['#title'];
    $deliver['description'] = $TEMP['#description'];
    $deliver['keyword'] = $TEMP['#keyword'];
    $deliver['page'] = $TEMP['#page'];
    if(in_array($TEMP['#page'], array('user', 'playlists', 'liked-videos', 'about'))){
        $deliver['profile_id'] = $TEMP['#data']['id'];
    }
    if(!empty($TEMP['#second_page'])){
        $deliver['second_page'] = $TEMP['#second_page'];
    }
    $deliver['url'] = $TEMP['#load_url'];
    $deliver['html'] = $TEMP['#content'];
    $deliver['status'] = 200;
} else {
    $deliver['status'] = 400;
}
?>