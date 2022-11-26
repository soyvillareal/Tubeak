<?php 
if ($TEMP['#settings']['embed_video'] == 'off' || empty($_GET['two'])) {
    header("Location: " . Specific::Url('404'));
    exit();
}

$id = Specific::Filter($_GET['two']);
$video_data = $TEMP['#video'] = Specific::Video($id);
$stream_data = $dba->query('SELECT * FROM broadcasts WHERE video_id = "'.$video_data['video_id'].'"')->fetchArray();
if (empty($video_data) || ($video_data['broadcast'] == 1 && !empty($stream_data) && $stream_data['live'] == 0)) {
    header("Location: " . Specific::Url('404'));
    exit();
}

$data = $TEMP['data'] = Specific::Data($video_data['by_id']);

$TEMP['#is_deleted'] = false;
if($video_data['deleted'] == 1){
    $TEMP['#is_deleted'] = true;
}

$TEMP['#is_approved'] = true;
if ($TEMP['#settings']['approve_videos'] == 'on' && $video_data['approved'] == 0) {
    $TEMP['#is_approved'] = false;
}

$TEMP['#is_public'] = true;
if ($video_data['privacy'] == 1) {
    if ($TEMP['#loggedin'] === false) {
        $TEMP['#is_public'] = false;
    } else if (($video_data['by_id'] != $TEMP['#user']['id']) && ($TEMP['#user']['role'] == 0)) {
        $TEMP['#is_public'] = false;
    }
} 

$TEMP['converted'] == true;
if($video_data['converted'] == 0){
    $TEMP['converted'] == false;
}

if($TEMP['#is_deleted'] == false){
    $end_related  = array();
    $limit_random = 6;
    $limit        = 12;

    if(empty($_SESSION['related_videos'])){
        $_SESSION['related_videos'] = array(0);
    }

    $random_videos = $dba->query('SELECT id FROM videos WHERE MATCH(title) AGAINST ("'.$video_data['title'].'") AND id != '.$video_data['id'].' AND by_id NOT IN ('.$TEMP['#blocked_users'].') AND id NOT IN ('.implode(',', $_SESSION['related_videos']).') AND privacy = 0 AND deleted = 0 ORDER BY rand() LIMIT '.$limit_random)->fetchAll(FALSE);

    $related_videos = $dba->query('SELECT id FROM videos WHERE MATCH(title) AGAINST ("'.$video_data['title'].'") AND id != '.$video_data['id'].' AND by_id NOT IN ('.$TEMP['#blocked_users'].') AND id NOT IN ('.implode(',', $_SESSION['related_videos']).') AND privacy = 0 AND category = "'.$video_data['category'].'" AND deleted = 0 LIMIT '.$limit)->fetchAll(FALSE);

    if(!empty($random_videos)){
        foreach ($related_videos as $key => $value) { 
            foreach ($random_videos as $k => $val) {
                if($key == rand(0, count($related_videos)) && !in_array($val, $related_videos)){
                    $related_videos[$key] = $val;
                    unset($random_videos[$k]);
                }
            }
        }
    }

    if(count($related_videos) < 2){
        $related_videos = $dba->query('SELECT * FROM videos WHERE title NOT LIKE "%'.$video_data['title'].'%" AND id != '.$video_data['id'].' AND by_id NOT IN ('.$TEMP['#blocked_users'].') AND privacy = 0 AND deleted = 0 ORDER BY rand() LIMIT '.$limit)->fetchAll();
    }

    if(count($_SESSION['related_videos']) > $TEMP['#settings']['data_load_limit'] || count($related_videos) < $limit_random){
        $_SESSION['related_videos'] = array(0);
    }

    foreach ($related_videos as $value) {
        $video  = Specific::Video($value);
        $end_related[] = array(
            'title' => addslashes($video['title']),
            'url' => $video['url'],
            'thumbnail' => $video['thumbnail'],
            'user_name' => $video['data']['username'],
            'views' => Specific::Number($video['views']),
            'duration' => $video['duration'],
            'video_id' => $video['video_id']
        );
    }

    $TEMP['#video_144'] = 0;
    $TEMP['#video_240'] = 0;
    $TEMP['#video_360'] = 0;
    $TEMP['#video_480'] = 0;
    $TEMP['#video_720'] = 0;
    $TEMP['#video_1080'] = 0;
    $TEMP['#video_1440'] = 0;
    $TEMP['#video_2160'] = 0;
    $json_path = array();

    $video_path = explode('_video', $video_data['path']);

    if ($video_data['2160p'] == 1) {
        $TEMP['#video_2160'] = explode('videos', $video_path[0])[1] . '_video_2160p';
        $json_path[] = array("src" => $TEMP['#video_2160'], "data-quality" => '4K', "title" => '4K', "res" => '2160');
    }
    if ($video_data['1440p'] == 1) {
        $TEMP['#video_1440'] = explode('videos', $video_path[0])[1] . '_video_1440p';
        $json_path[] = array("src" => $TEMP['#video_1440'], "data-quality" => '2K', "title" => '2K', "res" => '1440');
    }
    if ($video_data['1080p'] == 1) {
        $TEMP['#video_1080'] = explode('videos', $video_path[0])[1] . '_video_1080p';
        $json_path[] = array("src" => $TEMP['#video_1080'], "data-quality" => '1080p', "title" => '1080p', "res" => '1080');
    }
    if ($video_data['720p'] == 1) {
        $TEMP['#video_720'] = explode('videos', $video_path[0])[1] . '_video_720p';
        $json_path[] = array("src" => $TEMP['#video_720'], "data-quality" => '720p', "title" => '720p', "res" => '720');
    }
    if ($video_data['480p'] == 1) {
        $TEMP['#video_480'] = explode('videos', $video_path[0])[1] . '_video_480p';
        $json_path[] = array("src" => $TEMP['#video_480'], "data-quality" => '480p', "title" => '480p', "res" => '480');
    }
    if ($video_data['360p'] == 1) {
        $TEMP['#video_360'] = explode('videos', $video_path[0])[1] . '_video_360p';
        $json_path[] = array("src" => $TEMP['#video_360'], "data-quality" => '360p', "title" => '360p', "res" => '360');
    }
    if ($video_data['240p'] == 1) {
        $TEMP['#video_240'] = explode('videos', $video_path[0])[1] . '_video_240p';
        $json_path[] = array("src" => $TEMP['#video_240'], "data-quality" => '240p', "title" => '240p', "res" => '240');
    }
    if ($video_data['144p'] == 1) {
        $TEMP['#video_144'] = explode('videos', $video_path[0])[1] . '_video_144p';
        $json_path[] = array("src" => $TEMP['#video_144'], "data-quality" => '144p', "title" => '144p', "res" => '144');
    }

    $TEMP['#in_queue'] = false;
    if ($video_data['converted'] == 0) {
        $is_in_queue = $dba->query('SELECT COUNT(*) FROM queue WHERE video_id = '.$video_data['id'])->fetchArray();
        $process_queue = $dba->query('SELECT video_id FROM queue LIMIT '.$TEMP['#settings']['max_queue'])->fetchAll(FALSE);
        if ($TEMP['#settings']['max_queue'] == 1) {
            if ($process_queue[0] != $video_data['id']) {
                $TEMP['#in_queue'] = true;
            }
        } else if ($TEMP['#settings']['max_queue'] > 1) {
            if ($is_in_queue > 0 && !in_array($video_data['id'], $process_queue)) {
                $TEMP['#in_queue'] = true;
            }
        } 
    }

    $progress_value = 0;
    $percent_value = 0;
    $progress_start = Specific::Filter($_GET['t']);
    $TEMP['#get_progress'] = Specific::GetProgress($video_data['id']);
    $TEMP['is_sub'] = 0;
    if ($TEMP['#loggedin'] === true) {
        if ($dba->query('SELECT COUNT(*) FROM subscriptions WHERE by_id = '.$video_data['by_id'].' AND subscriber_id = '.$TEMP['#user']['id'])->fetchArray() > 0) {
            $TEMP['is_sub'] = 1;
        }
    }

    $TEMP['id'] = $video_data['id'];
    $TEMP['video_id'] = $video_data['video_id'];
    $TEMP['short_id'] = $video_data['short_id'];
    $TEMP['title'] = $video_data['title'];
    $TEMP['category_key'] = $video_data['category'];
    $TEMP['thumbnail'] = $video_data['thumbnail'];
    $TEMP['encoded_url'] = urlencode($video_data['url']);
    $TEMP['duration'] = $video_data['duration'];
    $TEMP['json_path'] = json_encode($json_path);
    $TEMP['no_progress'] = '"music"';
    $TEMP['end_related'] = json_encode($end_related);
    $TEMP['user_viewer'] = !empty($TEMP['#user']['id']) ? $TEMP['#user']['id'] : 0;
    $TEMP['progress_value'] = $progress_start ? $progress_start : !empty($TEMP['#get_progress']) ? $TEMP['#get_progress']['loaded_progress'] : 0;
    $TEMP['percent_value'] = $progress_start ? 'Get' : !empty($TEMP['#get_progress']) ? $TEMP['#get_progress']['percent_loaded'] : 0;
    $TEMP['watermark'] = $data['watermark'];
    $TEMP['start_watermark'] = $data['watermark_start'] != 'end' && $data['watermark_start'] != 'all' && !empty($data['watermark_start']) ? Specific::DurationToSeconds($data['watermark_start']) : $data['watermark_start'];
} else {
    $TEMP['title'] = $TEMP['#settings']['title'];
}
$TEMP['icon_favicon'] = Specific::GetIcon($TEMP['#settings']['icon_favicon'], 'favicon');
echo Specific::Maket('embed/content');
exit();
?>