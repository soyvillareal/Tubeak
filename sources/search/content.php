<?php
if (empty($_GET['keyword'])) {
    header("Location: " . Specific::Url('404'));
    exit();
}
$keyword = Specific::Filter($_GET['keyword']);
$category = Specific::Filter($_GET['category']);
$date = Specific::Filter($_GET['date']);
$features = Specific::Filter($_GET['features']);
$order = Specific::Filter($_GET['order']);

$date_url = $date ? "&date=$date" : "";
$category_url = $category ? "&category=$category" : "";
$features_url = $features ? "&features=$features" : "";
$order_url = $order ? "&order=$order" : "";

$suggestionCk = json_decode($_COOKIE['searchSuggestionHistory'], true);
$arrSuggestion = $suggestionCk['id'] == $TEMP['#user'] ? $suggestionCk['suggestions'] : [];
$inSuggetions = false;
$delSuggetion = false;
$by_id = $TEMP['#loggedin'] === true ? $TEMP['#user']['id'] : "";
$keywordWer = strtolower($keyword);
if(!empty($suggestionCk)){
    foreach ($arrSuggestion as $key => $value) {
        if(time() >= (int)($arrSuggestion[$key]['expire'])){
            $delSuggetion = true;
            unset($arrSuggestion[$key]);
        }
    }
    foreach ($arrSuggestion as $value) {
        if(in_array($keywordWer, $value)){
            $inSuggetions = true;
            break;
        }
    }
    if($inSuggetions == false){
        $arrSuggestion[] = array('suggestion' => $keywordWer, 'expire' => time() + (3600 * 12));
        $arrSuggestion = array("id" => $by_id, "suggestions" => $arrSuggestion);
    } else if($delSuggetion == false){
        $arrSuggestion = $suggestionCk;
    }
    
} else {
    $arrSuggestion = array('id' => $by_id, 'suggestions' => [array('suggestion' => $keywordWer, 'expire' => time() + (3600 * 12))]);
}

setcookie("searchSuggestionHistory", json_encode($arrSuggestion), time() + (3600 * 12), "/");

if (isset($category) && !empty($category)) {
    $category_sql = " AND category = '".$category."' ";
}
if (isset($date) && !empty($date)) {
    if ($date == 'last_hour') {
        $time = time()-(60*60);
        $date_sql = " AND time >= ".$time." ";
    }
    elseif ($date == 'today') {
        $time = time()-(60*60*24);
        $date_sql = " AND time >= ".$time." ";
    }
    elseif ($date == 'this_week') {
        $time = time()-(60*60*24*7);
        $date_sql = " AND time >= ".$time." ";
    }
    elseif ($date == 'this_month') {
        $time = time()-(60*60*24*30);
        $date_sql = " AND time >= ".$time." ";
    }
    elseif ($date == 'this_year') {
        $time = time()-(60*60*24*365);
        $date_sql = " AND time >= ".$time." ";
    }
}
if (isset($features) && !empty($features)) {
    if ($features == 'hd_feature') {
        $features_sql = " AND 720p = 1";
    }else if($features == '4k_feature'){
        $features_sql = " AND 2160p = 1";
    }
}

if (isset($order) && !empty($order)) {
    if ($order == 'date_upload') {
        $order_sql = " ORDER BY time DESC";
    }else if($order == 'count_views'){
        $order_sql = " ORDER BY views DESC";
    }
}else{
    $order_sql = " ORDER BY id ASC";
}
$videos = $dba->query('SELECT * FROM videos v WHERE (title LIKE "%'.$keyword.'%" OR tags LIKE "%'.$keyword.'%" OR description LIKE "%'.$keyword.'%") AND privacy = 0 AND deleted = 0 AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 1 OR (SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) '.$category_sql.$date_sql.$features_sql.$order_sql.' LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();

if (!empty($videos)) {
    $users = $dba->query("SELECT * FROM users WHERE (`username` LIKE '%$keyword%') ORDER BY id ASC")->fetchArray();
    if (!empty($users)) {
        $user = Specific::Data($users['id']);
        $subs = $dba->query('SELECT COUNT(*) FROM subscriptions WHERE by_id = '.$user['id'])->fetchArray();
        $count = $dba->query('SELECT COUNT(*) FROM videos WHERE by_id = '.$user['id'].' AND deleted = 0')->fetchArray();

        $TEMP['!subscribe_button'] = Specific::SubscribeButton($user['id']);
        $TEMP['!id'] = $user['id'];
        $TEMP['!data'] = $user;
        $TEMP['!subs'] = $subs == 0 ? $TEMP['#word']['empty_subscribers'] : $subs . ' ' . $TEMP['#word']['subscribers'];
        $TEMP['!count'] = $count;

        $TEMP['content'] .= Specific::Maket('search/includes/user-list');   
    }
    foreach ($videos as $value) {
        $video = Specific::Video($value);
        $progress = Specific::GetProgress($video['id']);

        $TEMP['!live'] = $video['live'];
        $TEMP['!get_progress'] = $progress;
        $TEMP['!id'] = $video['id'];
        $TEMP['!title'] = $video['title'];
        $TEMP['!views'] = Specific::Number($video['views']);
        $TEMP['!data'] = $video['data'];
        $TEMP['!thumbnail'] = $video['thumbnail'];
        $TEMP['!description'] = Specific::GetUncomposeText($video['description'], 130);
        $TEMP['!url'] = $video['url'];
        $TEMP['!time'] = $video['time_string'];
        $TEMP['!progress_value'] = $progress['percent_loaded'];
        $TEMP['!duration'] = $video['duration'];
        $TEMP['!video_id'] = $video['video_id'];
        $TEMP['!animation'] = $video['animation'];

        $TEMP['content'] .= Specific::Maket('search/includes/list');
    }
    Specific::DestroyMaket();
} else {
    $users = $dba->query("SELECT * FROM users WHERE (`username` LIKE '%$keyword%') ORDER BY id ASC")->fetchAll();
    if (!empty($users)) {
        foreach ($users as $value) {
            $user = Specific::Data($value['id']);
            $subs = $dba->query('SELECT COUNT(*) FROM subscriptions WHERE by_id = '.$user['id'])->fetchArray();
            $count = $dba->query('SELECT COUNT(*) FROM videos WHERE by_id = '.$user['id'].' AND deleted = 0')->fetchArray();

            $TEMP['!id'] = $user['id'];
            $TEMP['!subscribe_button'] = Specific::SubscribeButton($user['id']);
            $TEMP['!data'] = $user;
            $TEMP['!subs'] = $subs == 0 ? $TEMP['#word']['empty_subscribers'] : $subs . ' ' . $TEMP['#settings']['subscribers'];
            $TEMP['!count'] = $count;

            $TEMP['content'] .= Specific::Maket('search/includes/user-list'); 
        } 
        Specific::DestroyMaket(); 
    } else {
        $TEMP['content'] = Specific::Maket('not-found/videos');
    }
}

$TEMP['#videos']      = count($videos);
$TEMP['#users']       = count($users);

$TEMP['#page']        = 'search';
$TEMP['#title']       = $keyword . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url']    = Specific::Url('search?keyword='.$keyword.$date_url.$category_url.$features_url.$order_url);

$TEMP['key_encode'] = urlencode($keyword);
$TEMP['keyword'] = $keyword;
$TEMP['#content'] = Specific::Maket('search/content');
?>