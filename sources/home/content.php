<?php

if($TEMP['#settings']['carrousel_players'] == 'on'){
    $query = 'AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 1 OR (SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0)';
    if($TEMP['#settings']['type_carrousel'] == 'videos'){
        $query = 'AND broadcast = 0';
    } else if($TEMP['#settings']['type_carrousel'] == 'broadcast'){
        $query = 'AND broadcast = 1 AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 1 OR (SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3)';
    }
    $videos = $dba->query("SELECT MAX(id) FROM videos v WHERE converted = 1 AND deleted = 0 ".$query)->fetchArray();
    if(!empty($videos)){
        $TEMP['carrousel_players'] = '';
        $json_carrousel = array();
        $index = 0;
        $count = 5;
        $ids = array();
        $j = 0;
        for ($a = 0; $a < $count; $a++) {
            $rand = rand(1, $videos);
            if (!in_array($rand, $ids)) {
                if($dba->query('SELECT COUNT(*) FROM videos v WHERE id = '.$rand.' AND converted = 1 AND deleted = 0 '.$query)->fetchArray() > 0){
                    $ids[] = $rand;
                } else {
                    $a = $a-1;
                }
            } else {
                if($j >= 15){
                    $count = 5;
                } else {
                    $a = $a-1;
                }
                $j++;
            }
        }
        $TEMP['#carrousel'] = $dba->query('SELECT * FROM videos v WHERE converted = 1 AND id IN ('.implode(',', $ids).') '.$query.' AND privacy = 0 AND deleted = 0 AND by_id NOT IN ('.$TEMP['#blocked_users'].') ORDER BY id DESC LIMIT 5')->fetchAll();
        if(!empty($TEMP['#carrousel']) && count($TEMP['#carrousel']) == 5) {
            foreach ($TEMP['#carrousel'] as $value) {
                $video = Specific::Video($value);
                $video_id = $video['id'];

                $TEMP['!data'] = $video['data'];
                $json_carrousel[$video_id]['is_sub'] = 0;
                if ($TEMP['#loggedin'] === true) {
                    if ($dba->query('SELECT COUNT(*) FROM subscriptions WHERE by_id = '.$video['by_id'].' AND subscriber_id = '.$TEMP['#user']['id'])->fetchArray() > 0) {
                        $json_carrousel[$video_id]['is_sub'] = 1;
                    }
                }
                $json_carrousel[$video_id]['force_live'] = false;
                $json_carrousel[$video_id]['force_progress'] = 0;

                $json_path = array();
                if($video['broadcast'] == 0 || $stream_data['live'] > 1){
                    $TEMP['!word_views'] = $TEMP['#word']['views'];
                    $TEMP['!views'] = ($video['views'] >= 1000000) ? Specific::Number($video['views']) : number_format($video['views']);
                    $video_path = explode('_video', $video['path']);
                    if ($video['2160p'] == 1) {
                        $json_path[] = array("src" => explode('videos', $video_path[0])[1] . '_video_2160p', "data-quality" => '4K', "title" => '4K', "res" => '2160');
                    }
                    if ($video['1440p'] == 1) {
                        $json_path[] = array("src" => explode('videos', $video_path[0])[1] . '_video_1440p', "data-quality" => '2K', "title" => '2K', "res" => '1440');
                    }
                    if ($video['1080p'] == 1) {
                        $json_path[] = array("src" => explode('videos', $video_path[0])[1] . '_video_1080p', "data-quality" => '1080p', "title" => '1080p', "res" => '1080');
                    }
                    if ($video['720p'] == 1) {
                        $json_path[] = array("src" => explode('videos', $video_path[0])[1] . '_video_720p', "data-quality" => '720p', "title" => '720p', "res" => '720');
                    }
                    if ($video['480p'] == 1) {
                        $json_path[] = array("src" => explode('videos', $video_path[0])[1] . '_video_480p', "data-quality" => '480p', "title" => '480p', "res" => '480');
                    }
                    if ($video['360p'] == 1) {
                        $json_path[] = array("src" => explode('videos', $video_path[0])[1] . '_video_360p', "data-quality" => '360p', "title" => '360p', "res" => '360');
                    }
                    if ($video['240p'] == 1) {
                        $json_path[] = array("src" => explode('videos', $video_path[0])[1] . '_video_240p', "data-quality" => '240p', "title" => '240p', "res" => '240');
                    }
                    if ($video['144p'] == 1) {
                        $json_path[] = array("src" => explode('videos', $video_path[0])[1] . '_video_144p', "data-quality" => '144p', "title" => '144p', "res" => '144');
                    }
                } else {
                    $data_stream = Specific::DataStream();
                    $TEMP['!word_views'] = $TEMP['#word']['viewers'];
                    $TEMP['!views'] = $data_stream['server']['application']['live']['stream']['nclients'];
                    $json_carrousel[$video_id]['force_live'] = true;
                    $json_carrousel[$video_id]['force_progress'] = $data_stream['server']['application']['live']['stream']['time'] / 1000;
                    $stream_width = $data_stream['server']['application']['live']['stream']['meta']['video']['height'];
                    $json_path[] = array("src" => $video['video_id'], "data-quality" => '144p', "title" => '144p', "res" => '144');

                    if ($stream_width >= 426 || $stream_width == 0) {
                        $json_path[0]['data-quality'] = $json_path[0]['title'] = '240p'; 
                        $json_path[0]['res'] = '240'; 
                    } else if ($stream_width >= 640 || $stream_width == 0) {
                        $json_path[0]['data-quality'] = $json_path[0]['title'] = '360p'; 
                        $json_path[0]['res'] = '360'; 
                    } else if ($stream_width >= 854 || $stream_width == 0) {
                        $json_path[0]['data-quality'] = $json_path[0]['title'] = '480p'; 
                        $json_path[0]['res'] = '480';
                    } else if ($stream_width >= 1280 || $stream_width == 0) {
                        $json_path[0]['data-quality'] = $json_path[0]['title'] = '720p'; 
                        $json_path[0]['res'] = '720';
                    } else if ($stream_width >= 1920 || $stream_width == 0) {
                        $json_path[0]['data-quality'] = $json_path[0]['title'] = '1080p';
                        $json_path[0]['res'] = '1080'; 
                    } else if ($stream_width >= 1440) {
                        $json_path[0]['data-quality'] = $json_path[0]['title'] = '2K'; 
                        $json_path[0]['res'] = '1440';
                    } else if ($stream_width >= 2160) {
                         $json_path[0]['data-quality'] = $json_path[0]['title'] = '4K'; 
                         $json_path[0]['res'] = '2160';
                    }
                }
         
                $TEMP['!item_active'] = '';
                $json_carrousel[$video_id]['autoplay'] = false;
                if($index == 0){
                    $TEMP['!item_active'] = ' item-active';
                   $json_carrousel[$video_id]['autoplay'] = true; 
                }
                $json_carrousel[$video_id]['id'] = $index;
                $json_carrousel[$video_id]['video_id'] = $video['video_id'];
                $json_carrousel[$video_id]['short_id'] = $video['short_id'];
                $json_carrousel[$video_id]['json_path'] = $json_path;
                $json_carrousel[$video_id]['user_viewer'] = !empty($TEMP['#user']['id']) ? $TEMP['#user']['id'] : 0;
                $json_carrousel[$video_id]['thumbnail'] = $video['thumbnail'];
                $json_carrousel[$video_id]['duration'] = $video['duration'];
                $json_carrousel[$video_id]['watermark'] = $TEMP['!data']['watermark'];
                $json_carrousel[$video_id]['start_watermark'] = $TEMP['!data']['watermark_start'] != 'end' && $TEMP['!data']['watermark_start'] != 'all' && !empty($TEMP['!data']['watermark_start']) ? Specific::DurationToSeconds($TEMP['!data']['watermark_start']) : $TEMP['!data']['watermark_start'];

                $json_carrousel[$video_id]['user_owner_id'] = $TEMP['!data']['id'];
                $json_carrousel[$video_id]['user_photo'] = $TEMP['!data']['avatar'];
                $json_carrousel[$video_id]['user_link'] = $TEMP['!data']['url'];

                $TEMP['!id'] = $video_id;
                $TEMP['!title'] = $video['title'];
                $TEMP['!description'] = Specific::GetComposeText($video['description']);
                $TEMP['!url'] = $video['url'];
                $TEMP['!time'] = Specific::DateString($video['time']);
                $TEMP['!index'] = $index;
                $TEMP['carrousel_players'] .= Specific::Maket('home/includes/carrousel-players');
                Specific::DestroyMaket();
                $index++;
            }
            $TEMP['json_carrousel'] = json_encode($json_carrousel);
        }
    }
}

$latest_data = $dba->query('SELECT * FROM videos v WHERE converted = 1 AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 1 OR (SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND privacy = 0 AND deleted = 0 AND by_id NOT IN ('.$TEMP['#blocked_users'].') ORDER BY id DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();

if(!empty($latest_data)){
    foreach ($latest_data as $value) {
        $video = Specific::Video($value);

        $TEMP['!live'] = $video['live'];
        $TEMP['!get_progress'] = Specific::GetProgress($video['id']);
        $TEMP['!id'] = $video['id'];
        $TEMP['!title'] = $video['title'];
        $TEMP['!views'] = Specific::Number($video['views']);
        $TEMP['!data'] = $video['data'];
        $TEMP['!thumbnail'] = $video['thumbnail'];
        $TEMP['!url'] = $video['url'];
        $TEMP['!time'] = $video['time_string'];
        $TEMP['!progress_value'] = $TEMP['!get_progress']['percent_loaded'];
        $TEMP['!duration'] = $video['duration'];
        $TEMP['!video_id'] = $video['video_id'];
        $TEMP['!animation'] = $video['animation'];

        $TEMP['latest_list'] .= Specific::Maket('home/includes/list');
    }
    Specific::DestroyMaket();
} else {
    $TEMP['latest_list'] = Specific::Maket('not-found/contribute');
}

$TEMP['#videos']       = count($latest_data);

$TEMP['#page']         = 'home';
$TEMP['#title']        = $TEMP['#settings']['title'];
$TEMP['#description']  = $TEMP['#settings']['description'];
$TEMP['#keyword']      = $TEMP['#settings']['keyword'];
$TEMP['#load_url']     = Specific::Url();
$TEMP['#content']      = Specific::Maket('home/content');
?>