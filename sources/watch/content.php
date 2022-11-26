<?php
$id = Specific::Filter($_GET['v']);
if (!empty($id)) {
    $video_data = Specific::Video($id, true);
    if (!empty($video_data)) {
        header("Location: ".$video_data['url']);
        exit();
    }
}

$video_data = $TEMP['#video'] = Specific::Video($id);
$stream_data = $dba->query('SELECT * FROM broadcasts WHERE video_id = "'.$video_data['video_id'].'"')->fetchArray();
if (empty($video_data) || ($video_data['broadcast'] == 1 && !empty($stream_data) && $stream_data['live'] == 0)) {
    header("Location: " . Specific::Url('404'));
    exit();
}


$TEMP['#page'] = $TEMP['#type_watch'] = 'watch';
$TEMP['#is_mobile'] = Specific::BrowserDetails()['validate']['is_mobile'];

$TEMP['#is_deleted'] = false;
if($video_data['deleted'] == 1){
    $TEMP['#is_deleted'] = true;
}

if($TEMP['#is_deleted'] == false) {
    $end_related = array();
    $outscom = !empty($_GET['cl']) ? "&cl=".Specific::Filter($_GET['cl']) : "";
    $outsrep = !empty($_GET['rl']) ? "&rl=".Specific::Filter($_GET['rl']) : "";
    $TEMP['#type_list'] = 'list';
    $TEMP['#list_owner'] = false;
    $TEMP['video_sidebar']  = '';
    $TEMP['next_video'] = '';
    $TEMP['playlist'] = '';
    $TEMP['list_count'] = 0;
    $TEMP['video_index'] = 0;
    $TEMP['#load_url'] = Specific::WatchSlug($video_data['video_id'].$outscom.$outsrep);
    $data = $TEMP['data'] = $TEMP['#data'] = $video_data['data'];

    $TEMP['word_published'] = $TEMP['#word']['published_on'];
    $TEMP['#is_streaming'] = false;
    if($video_data['broadcast'] == 1){
        $TEMP['word_published'] = $TEMP['#word']['transmitted_the'];
        $TEMP['#is_streaming'] = true;
    }

    $TEMP['#is_live_now'] = false;
    if($stream_data['live'] == 1){
        $TEMP['#is_live_now'] = true;
    }

    $TEMP['#is_live_processig'] = false;
    if($stream_data['live'] == 2){
        $TEMP['#is_live_processig'] = true;
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

    $TEMP['#is_adult'] = true;
    if ($video_data['adults_only'] == 1) {
        if ($TEMP['#loggedin'] === false) {
            $TEMP['#is_adult'] = false;
        } else {
            if (($video_data['by_id'] != $TEMP['#user']['id']) && !Specific::AdultsOnly($TEMP['#user']['id'])) {
                $TEMP['#is_adult'] = false;
            }
        }
    }

    $TEMP['is_sub'] = 0;
    if ($TEMP['#loggedin'] === true) {
        if ($dba->query('SELECT COUNT(*) FROM subscriptions WHERE by_id = '.$video_data['by_id'].' AND subscriber_id = '.$TEMP['#user']['id'])->fetchArray() > 0) {
            $TEMP['is_sub'] = 1;
        }
    }

    $TEMP['#video_owner'] = false;
    if($TEMP['#loggedin'] === true && $video_data['by_id'] == $TEMP['#user']['id']){
        $TEMP['#video_owner'] = true;
    }

    if (!empty($_GET['list']) && $_GET['list'] == 'wl' && $TEMP['#loggedin'] === true) {
        if ($dba->query('SELECT COUNT(*) FROM watch_later WHERE video_id = '.$video_data['id'].' AND by_id = '.$TEMP['#user']['id'])->fetchArray() == 0) {
            header("Location: " . Specific::WatchSlug($video_data['video_id']));
            exit();
        }
        $TEMP['#type_watch'] = 'playlist';
        $TEMP['#type_list'] = 'later';
        $TEMP['#load_url'] = Specific::WatchSlug($video_data['video_id'], 'playlist', $_GET['list']);
        if($TEMP['#loggedin'] === true){
            $videos = $dba->query('SELECT * FROM videos v WHERE (SELECT video_id FROM watch_later WHERE video_id = v.id AND by_id = '.$TEMP['#user']['id'].') = id AND deleted = 0 AND by_id NOT IN ('.$TEMP['#blocked_users'].') LIMIT 250')->fetchAll();

            $index = 1;
            $TEMP['playlist_title'] = $TEMP['#word']['watch_later'];
            $TEMP['list_count'] = count($videos);
            $TEMP['list_ownername'] = $TEMP['#user']['username'];
            $TEMP['icon_privacy'] = '<svg class="icon-little color-grey" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M 9.556 11.364 L 14.444 11.364 L 14.444 9.455 C 14.444 8.752 14.206 8.152 13.728 7.655 C 13.251 7.158 12.675 6.909 12 6.909 C 11.325 6.909 10.749 7.158 10.272 7.655 C 9.794 8.152 9.556 8.752 9.556 9.455 L 9.556 11.364 Z M 17.5 12.318 L 17.5 18.045 C 17.5 18.311 17.411 18.536 17.233 18.722 C 17.054 18.907 16.838 19 16.583 19 L 7.417 19 C 7.162 19 6.946 18.907 6.767 18.722 C 6.589 18.536 6.5 18.311 6.5 18.045 L 6.5 12.318 C 6.5 12.053 6.589 11.828 6.767 11.642 C 6.946 11.456 7.162 11.364 7.417 11.364 L 7.722 11.364 L 7.722 9.455 C 7.722 8.235 8.142 7.187 8.983 6.312 C 9.823 5.437 10.829 5 12 5 C 13.171 5 14.177 5.437 15.017 6.312 C 15.858 7.187 16.278 8.235 16.278 9.455 L 16.278 11.364 L 16.583 11.364 C 16.838 11.364 17.054 11.456 17.233 11.642 C 17.411 11.828 17.5 12.053 17.5 12.318 Z"></path></svg>';
            $TEMP['privacy'] = $TEMP['#word']['private'];


            $TEMP['#list_owner'] = true;

            foreach ($videos as $value) {
                $video = Specific::Video($value);
                $video['url'] = Specific::WatchSlug($video['video_id'], 'playlist', 'wl');

                $TEMP['!get_progress'] = Specific::GetProgress($video['id']);
                $TEMP['!live'] = $video['live'];
                $TEMP['!id'] = $video['id'];
                $TEMP['!video_id'] = $video['video_id'];
                $TEMP['!title'] = $video['title'];
                $TEMP['!url'] = $video['url'];
                $TEMP['!thumbnail'] = $video['thumbnail'];
                $TEMP['!index'] = $index;
                $TEMP['!rotate_class'] = '';
                if($video['video_id'] == $id){
                    $TEMP['!index'] = '▶';
                    if($TEMP['#language_dir'] == 'rtl'){
                        $TEMP['!rotate_class'] = 'rotate-180 ';
                    }
                }
                $TEMP['!list_active_class'] = ($video['video_id'] == $id) ? ' active' : '';
                $TEMP['!data'] = $video['data'];
                $TEMP['!duration'] = $video['duration'];
                $TEMP['!progress_value'] = $TEMP['!get_progress']['percent_loaded'];

                $TEMP['playlist'] .= Specific::Maket('watch/includes/video-list');

                if ($video['video_id'] == $id) {
                    $TEMP['video_index'] = $index;
                }
                if($TEMP['video_index'] == $index - 1){
                    $TEMP['id_nextend'] = $video['video_id'];
                    $TEMP['title_nextend'] = addslashes($video['title']);
                    $TEMP['thumbnail_nextend'] = $video['thumbnail'];
                    $TEMP['url_nextend'] = $video['url'];
                    $TEMP['lid_nextend'] = $video['video_id'].'&list=wl';
                    $TEMP['dura_nextend'] = $video['duration'];
                }
                if($TEMP['video_index'] == 0){
                    $TEMP['id_prevend'] = $video['video_id'];
                    $TEMP['title_prevend'] = addslashes($video['title']);
                    $TEMP['thumbnail_prevend'] = $video['thumbnail'];
                    $TEMP['url_prevend'] = $video['url'];
                    $TEMP['lid_prevend'] = $video['video_id'].'&list=wl';
                    $TEMP['dura_prevend'] = $video['duration'];
                }
                $index++;
            }
            Specific::DestroyMaket();
        }
    } else if (!empty($_GET['list'])) {
        $list_id = $TEMP['list_id'] = Specific::Filter($_GET['list']);

        $TEMP['#list_data']  = $dba->query('SELECT * FROM lists WHERE list_id = "'.$list_id.'" AND by_id NOT IN ('.$TEMP['#blocked_users'].')')->fetchArray();
        if ($dba->query('SELECT COUNT(*) FROM lists WHERE list_id = "'.$list_id.'"')->fetchArray() == 0 || $dba->query('SELECT COUNT(*) FROM playlists WHERE list_id = "'.$list_id.'" AND video_id = '.$video_data['id'])->fetchArray() == 0 || ($TEMP['#list_data']['privacy'] == 0 && $TEMP['#list_data']['by_id'] != $TEMP['#user']['id'])) {
            header("Location: " . Specific::WatchSlug($video_data['video_id']));
            exit();
        }

        $list = $dba->query('SELECT * FROM lists WHERE list_id = "'.$list_id.'" AND by_id NOT IN ('.$TEMP['#blocked_users'].')')->fetchArray();
        $videos = $dba->query('SELECT * FROM videos v WHERE (SELECT video_id FROM playlists WHERE video_id = v.id AND list_id = "'.$list_id.'") = id AND deleted = 0 AND by_id NOT IN ('.$TEMP['#blocked_users'].') LIMIT 250')->fetchAll();
        $index = 1;
        $TEMP['#type_watch'] = 'playlist';
        $TEMP['playlist_title'] = $list['title'];
        $TEMP['list_count'] = count($videos);
        $TEMP['list_ownername'] = Specific::Data($list['by_id'])['username'];
        $TEMP['icon_privacy'] = ($list['privacy'] == 0) ? '<svg class="icon-little color-grey" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M 9.556 11.364 L 14.444 11.364 L 14.444 9.455 C 14.444 8.752 14.206 8.152 13.728 7.655 C 13.251 7.158 12.675 6.909 12 6.909 C 11.325 6.909 10.749 7.158 10.272 7.655 C 9.794 8.152 9.556 8.752 9.556 9.455 L 9.556 11.364 Z M 17.5 12.318 L 17.5 18.045 C 17.5 18.311 17.411 18.536 17.233 18.722 C 17.054 18.907 16.838 19 16.583 19 L 7.417 19 C 7.162 19 6.946 18.907 6.767 18.722 C 6.589 18.536 6.5 18.311 6.5 18.045 L 6.5 12.318 C 6.5 12.053 6.589 11.828 6.767 11.642 C 6.946 11.456 7.162 11.364 7.417 11.364 L 7.722 11.364 L 7.722 9.455 C 7.722 8.235 8.142 7.187 8.983 6.312 C 9.823 5.437 10.829 5 12 5 C 13.171 5 14.177 5.437 15.017 6.312 C 15.858 7.187 16.278 8.235 16.278 9.455 L 16.278 11.364 L 16.583 11.364 C 16.838 11.364 17.054 11.456 17.233 11.642 C 17.411 11.828 17.5 12.053 17.5 12.318 Z"></path></svg>' : '<svg class="icon-little color-grey" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M 12 3 C 13.633 3 15.139 3.402 16.518 4.207 C 17.896 5.012 18.988 6.104 19.793 7.482 C 20.598 8.861 21 10.367 21 12 C 21 13.633 20.598 15.139 19.793 16.518 C 18.988 17.896 17.896 18.988 16.518 19.793 C 15.139 20.598 13.633 21 12 21 C 10.367 21 8.861 20.598 7.482 19.793 C 6.104 18.988 5.012 17.896 4.207 16.518 C 3.402 15.139 3 13.633 3 12 C 3 10.367 3.402 8.861 4.207 7.482 C 5.012 6.104 6.104 5.012 7.482 4.207 C 8.861 3.402 10.367 3 12 3 Z M 15.211 9.105 C 15.195 9.113 15.158 9.15 15.1 9.217 C 15.041 9.283 14.988 9.32 14.941 9.328 C 14.957 9.328 14.975 9.309 14.994 9.27 C 15.014 9.23 15.033 9.188 15.053 9.141 C 15.072 9.094 15.086 9.066 15.094 9.059 C 15.141 9.004 15.227 8.945 15.352 8.883 C 15.461 8.836 15.664 8.789 15.961 8.742 C 16.227 8.68 16.426 8.723 16.559 8.871 C 16.543 8.855 16.58 8.805 16.67 8.719 C 16.76 8.633 16.816 8.586 16.84 8.578 C 16.863 8.563 16.922 8.545 17.016 8.525 C 17.109 8.506 17.168 8.477 17.191 8.438 L 17.215 8.18 C 17.121 8.188 17.053 8.16 17.01 8.098 C 16.967 8.035 16.941 7.953 16.934 7.852 C 16.934 7.867 16.91 7.898 16.863 7.945 C 16.863 7.891 16.846 7.859 16.811 7.852 C 16.775 7.844 16.73 7.848 16.676 7.863 C 16.621 7.879 16.586 7.883 16.57 7.875 C 16.492 7.852 16.434 7.822 16.395 7.787 C 16.355 7.752 16.324 7.688 16.301 7.594 C 16.277 7.5 16.262 7.441 16.254 7.418 C 16.238 7.379 16.201 7.336 16.143 7.289 C 16.084 7.242 16.047 7.203 16.031 7.172 C 16.023 7.156 16.014 7.135 16.002 7.107 C 15.99 7.08 15.979 7.055 15.967 7.031 C 15.955 7.008 15.939 6.986 15.92 6.967 C 15.9 6.947 15.879 6.938 15.855 6.938 C 15.832 6.938 15.805 6.957 15.773 6.996 C 15.742 7.035 15.713 7.074 15.686 7.113 C 15.658 7.152 15.641 7.172 15.633 7.172 C 15.609 7.156 15.586 7.15 15.563 7.154 C 15.539 7.158 15.521 7.162 15.51 7.166 C 15.498 7.17 15.48 7.182 15.457 7.201 C 15.434 7.221 15.414 7.234 15.398 7.242 C 15.375 7.258 15.342 7.27 15.299 7.277 C 15.256 7.285 15.223 7.293 15.199 7.301 C 15.316 7.262 15.313 7.219 15.188 7.172 C 15.109 7.141 15.047 7.129 15 7.137 C 15.07 7.105 15.1 7.059 15.088 6.996 C 15.076 6.934 15.043 6.879 14.988 6.832 L 15.047 6.832 C 15.039 6.801 15.006 6.768 14.947 6.732 C 14.889 6.697 14.82 6.664 14.742 6.633 C 14.664 6.602 14.613 6.578 14.59 6.563 C 14.527 6.523 14.395 6.486 14.191 6.451 C 13.988 6.416 13.859 6.414 13.805 6.445 C 13.766 6.492 13.748 6.533 13.752 6.568 C 13.756 6.604 13.771 6.658 13.799 6.732 C 13.826 6.807 13.84 6.855 13.84 6.879 C 13.848 6.926 13.826 6.977 13.775 7.031 C 13.725 7.086 13.699 7.133 13.699 7.172 C 13.699 7.227 13.754 7.287 13.863 7.354 C 13.973 7.42 14.012 7.504 13.98 7.605 C 13.957 7.668 13.895 7.73 13.793 7.793 C 13.691 7.855 13.629 7.902 13.605 7.934 C 13.566 7.996 13.561 8.068 13.588 8.15 C 13.615 8.232 13.656 8.297 13.711 8.344 C 13.727 8.359 13.732 8.375 13.729 8.391 C 13.725 8.406 13.711 8.424 13.688 8.443 C 13.664 8.463 13.643 8.479 13.623 8.49 C 13.604 8.502 13.578 8.516 13.547 8.531 L 13.512 8.555 C 13.426 8.594 13.346 8.57 13.271 8.484 C 13.197 8.398 13.145 8.297 13.113 8.18 C 13.059 7.984 12.996 7.867 12.926 7.828 C 12.746 7.766 12.633 7.77 12.586 7.84 C 12.547 7.738 12.387 7.637 12.105 7.535 C 11.91 7.465 11.684 7.449 11.426 7.488 C 11.473 7.48 11.473 7.422 11.426 7.313 C 11.371 7.195 11.297 7.148 11.203 7.172 C 11.227 7.125 11.242 7.057 11.25 6.967 C 11.258 6.877 11.262 6.824 11.262 6.809 C 11.285 6.707 11.332 6.617 11.402 6.539 C 11.41 6.531 11.438 6.498 11.484 6.439 C 11.531 6.381 11.568 6.328 11.596 6.281 C 11.623 6.234 11.625 6.211 11.602 6.211 C 11.875 6.242 12.07 6.199 12.188 6.082 C 12.227 6.043 12.271 5.977 12.322 5.883 C 12.373 5.789 12.414 5.723 12.445 5.684 C 12.516 5.637 12.57 5.615 12.609 5.619 C 12.648 5.623 12.705 5.645 12.779 5.684 C 12.854 5.723 12.91 5.742 12.949 5.742 C 13.059 5.75 13.119 5.707 13.131 5.613 C 13.143 5.52 13.113 5.441 13.043 5.379 C 13.137 5.387 13.148 5.32 13.078 5.18 C 13.047 5.125 13.016 5.09 12.984 5.074 C 12.891 5.043 12.785 5.063 12.668 5.133 C 12.605 5.164 12.613 5.195 12.691 5.227 C 12.684 5.219 12.646 5.26 12.58 5.35 C 12.514 5.439 12.449 5.508 12.387 5.555 C 12.324 5.602 12.262 5.582 12.199 5.496 C 12.191 5.488 12.17 5.436 12.135 5.338 C 12.1 5.24 12.063 5.188 12.023 5.18 C 11.961 5.18 11.898 5.238 11.836 5.355 C 11.859 5.293 11.816 5.234 11.707 5.18 C 11.598 5.125 11.504 5.094 11.426 5.086 C 11.574 4.992 11.543 4.887 11.332 4.77 C 11.277 4.738 11.197 4.719 11.092 4.711 C 10.986 4.703 10.91 4.719 10.863 4.758 C 10.824 4.813 10.803 4.857 10.799 4.893 C 10.795 4.928 10.814 4.959 10.857 4.986 C 10.9 5.014 10.941 5.035 10.98 5.051 C 11.02 5.066 11.064 5.082 11.115 5.098 C 11.166 5.113 11.199 5.125 11.215 5.133 C 11.324 5.211 11.355 5.266 11.309 5.297 C 11.293 5.305 11.26 5.318 11.209 5.338 C 11.158 5.357 11.113 5.375 11.074 5.391 C 11.035 5.406 11.012 5.422 11.004 5.438 C 10.98 5.469 10.98 5.523 11.004 5.602 C 11.027 5.68 11.02 5.734 10.98 5.766 C 10.941 5.727 10.906 5.658 10.875 5.561 C 10.844 5.463 10.816 5.398 10.793 5.367 C 10.848 5.438 10.75 5.461 10.5 5.438 L 10.383 5.426 C 10.352 5.426 10.289 5.434 10.195 5.449 C 10.102 5.465 10.021 5.469 9.955 5.461 C 9.889 5.453 9.836 5.422 9.797 5.367 C 9.766 5.305 9.766 5.227 9.797 5.133 C 9.805 5.102 9.82 5.094 9.844 5.109 C 9.813 5.086 9.77 5.049 9.715 4.998 C 9.66 4.947 9.621 4.914 9.598 4.898 C 9.238 5.016 8.871 5.176 8.496 5.379 C 8.543 5.387 8.59 5.383 8.637 5.367 C 8.676 5.352 8.727 5.326 8.789 5.291 C 8.852 5.256 8.891 5.234 8.906 5.227 C 9.172 5.117 9.336 5.09 9.398 5.145 L 9.457 5.086 C 9.566 5.211 9.645 5.309 9.691 5.379 C 9.637 5.348 9.52 5.344 9.34 5.367 C 9.184 5.414 9.098 5.461 9.082 5.508 C 9.137 5.602 9.156 5.672 9.141 5.719 C 9.109 5.695 9.064 5.656 9.006 5.602 C 8.947 5.547 8.891 5.504 8.836 5.473 C 8.781 5.441 8.723 5.422 8.66 5.414 C 8.535 5.414 8.449 5.418 8.402 5.426 C 7.262 6.051 6.344 6.918 5.648 8.027 C 5.703 8.082 5.75 8.113 5.789 8.121 C 5.82 8.129 5.84 8.164 5.848 8.227 C 5.855 8.289 5.865 8.332 5.877 8.355 C 5.889 8.379 5.934 8.367 6.012 8.32 C 6.082 8.383 6.094 8.457 6.047 8.543 C 6.055 8.535 6.227 8.641 6.563 8.859 C 6.711 8.992 6.793 9.074 6.809 9.105 C 6.832 9.191 6.793 9.262 6.691 9.316 C 6.684 9.301 6.648 9.266 6.586 9.211 C 6.523 9.156 6.488 9.141 6.48 9.164 C 6.457 9.203 6.459 9.275 6.486 9.381 C 6.514 9.486 6.555 9.535 6.609 9.527 C 6.555 9.527 6.518 9.59 6.498 9.715 C 6.479 9.84 6.469 9.979 6.469 10.131 C 6.469 10.283 6.465 10.375 6.457 10.406 L 6.48 10.418 C 6.457 10.512 6.479 10.646 6.545 10.822 C 6.611 10.998 6.695 11.074 6.797 11.051 C 6.695 11.074 6.773 11.242 7.031 11.555 C 7.078 11.617 7.109 11.652 7.125 11.66 C 7.148 11.676 7.195 11.705 7.266 11.748 C 7.336 11.791 7.395 11.83 7.441 11.865 C 7.488 11.9 7.527 11.941 7.559 11.988 C 7.59 12.027 7.629 12.115 7.676 12.252 C 7.723 12.389 7.777 12.48 7.84 12.527 C 7.824 12.574 7.861 12.652 7.951 12.762 C 8.041 12.871 8.082 12.961 8.074 13.031 C 8.066 13.031 8.057 13.035 8.045 13.043 C 8.033 13.051 8.023 13.055 8.016 13.055 C 8.039 13.109 8.1 13.164 8.197 13.219 C 8.295 13.273 8.355 13.324 8.379 13.371 C 8.387 13.395 8.395 13.434 8.402 13.488 C 8.41 13.543 8.422 13.586 8.438 13.617 C 8.453 13.648 8.484 13.656 8.531 13.641 C 8.547 13.484 8.453 13.242 8.25 12.914 C 8.133 12.719 8.066 12.605 8.051 12.574 C 8.027 12.535 8.006 12.475 7.986 12.393 C 7.967 12.311 7.949 12.254 7.934 12.223 C 7.949 12.223 7.973 12.229 8.004 12.24 C 8.035 12.252 8.068 12.266 8.104 12.281 C 8.139 12.297 8.168 12.313 8.191 12.328 C 8.215 12.344 8.223 12.355 8.215 12.363 C 8.191 12.418 8.199 12.486 8.238 12.568 C 8.277 12.65 8.324 12.723 8.379 12.785 C 8.434 12.848 8.5 12.922 8.578 13.008 C 8.656 13.094 8.703 13.145 8.719 13.16 C 8.766 13.207 8.82 13.283 8.883 13.389 C 8.945 13.494 8.945 13.547 8.883 13.547 C 8.953 13.547 9.031 13.588 9.117 13.67 C 9.203 13.752 9.27 13.828 9.316 13.898 C 9.355 13.961 9.387 14.063 9.41 14.203 C 9.434 14.344 9.453 14.438 9.469 14.484 C 9.484 14.539 9.518 14.592 9.568 14.643 C 9.619 14.693 9.668 14.73 9.715 14.754 L 9.902 14.848 L 10.055 14.93 C 10.094 14.945 10.166 14.986 10.271 15.053 C 10.377 15.119 10.461 15.164 10.523 15.188 C 10.602 15.219 10.664 15.234 10.711 15.234 C 10.758 15.234 10.814 15.225 10.881 15.205 C 10.947 15.186 11 15.172 11.039 15.164 C 11.156 15.148 11.27 15.207 11.379 15.34 C 11.488 15.473 11.57 15.555 11.625 15.586 C 11.906 15.734 12.121 15.777 12.27 15.715 C 12.254 15.723 12.256 15.752 12.275 15.803 C 12.295 15.854 12.326 15.914 12.369 15.984 C 12.412 16.055 12.447 16.111 12.475 16.154 C 12.502 16.197 12.523 16.23 12.539 16.254 C 12.578 16.301 12.648 16.359 12.75 16.43 C 12.852 16.5 12.922 16.559 12.961 16.605 C 13.008 16.574 13.035 16.539 13.043 16.5 C 13.02 16.562 13.047 16.641 13.125 16.734 C 13.203 16.828 13.273 16.867 13.336 16.852 C 13.445 16.828 13.5 16.703 13.5 16.477 C 13.258 16.594 13.066 16.523 12.926 16.266 C 12.926 16.258 12.916 16.236 12.896 16.201 C 12.877 16.166 12.861 16.133 12.85 16.102 C 12.838 16.07 12.828 16.037 12.82 16.002 C 12.813 15.967 12.813 15.938 12.82 15.914 C 12.828 15.891 12.848 15.879 12.879 15.879 C 12.949 15.879 12.988 15.865 12.996 15.838 C 13.004 15.811 12.996 15.762 12.973 15.691 C 12.949 15.621 12.934 15.57 12.926 15.539 C 12.918 15.477 12.875 15.398 12.797 15.305 C 12.719 15.211 12.672 15.152 12.656 15.129 C 12.617 15.199 12.555 15.23 12.469 15.223 C 12.383 15.215 12.32 15.18 12.281 15.117 C 12.281 15.125 12.275 15.146 12.264 15.182 C 12.252 15.217 12.246 15.242 12.246 15.258 C 12.145 15.258 12.086 15.254 12.07 15.246 C 12.078 15.223 12.088 15.154 12.1 15.041 C 12.111 14.928 12.125 14.84 12.141 14.777 C 12.148 14.746 12.17 14.699 12.205 14.637 C 12.24 14.574 12.27 14.518 12.293 14.467 C 12.316 14.416 12.332 14.367 12.34 14.32 C 12.348 14.273 12.33 14.236 12.287 14.209 C 12.244 14.182 12.176 14.172 12.082 14.18 C 11.934 14.188 11.832 14.266 11.777 14.414 C 11.77 14.438 11.758 14.479 11.742 14.537 C 11.727 14.596 11.707 14.641 11.684 14.672 C 11.66 14.703 11.625 14.73 11.578 14.754 C 11.523 14.777 11.43 14.785 11.297 14.777 C 11.164 14.77 11.07 14.75 11.016 14.719 C 10.914 14.656 10.826 14.543 10.752 14.379 C 10.678 14.215 10.641 14.07 10.641 13.945 C 10.641 13.867 10.65 13.764 10.67 13.635 C 10.689 13.506 10.701 13.408 10.705 13.342 C 10.709 13.275 10.688 13.18 10.641 13.055 C 10.664 13.039 10.699 13.002 10.746 12.943 C 10.793 12.885 10.832 12.844 10.863 12.82 C 10.879 12.812 10.896 12.807 10.916 12.803 C 10.936 12.799 10.953 12.799 10.969 12.803 C 10.984 12.807 11 12.801 11.016 12.785 C 11.031 12.77 11.043 12.746 11.051 12.715 C 11.043 12.707 11.027 12.695 11.004 12.68 C 10.98 12.656 10.965 12.645 10.957 12.645 C 11.012 12.668 11.123 12.662 11.291 12.627 C 11.459 12.592 11.566 12.598 11.613 12.645 C 11.73 12.73 11.816 12.723 11.871 12.621 C 11.871 12.613 11.861 12.576 11.842 12.51 C 11.822 12.443 11.82 12.391 11.836 12.352 C 11.875 12.563 11.988 12.598 12.176 12.457 C 12.199 12.48 12.26 12.5 12.357 12.516 C 12.455 12.531 12.523 12.551 12.563 12.574 C 12.586 12.59 12.613 12.611 12.645 12.639 C 12.676 12.666 12.697 12.684 12.709 12.691 C 12.721 12.699 12.74 12.697 12.768 12.686 C 12.795 12.674 12.828 12.648 12.867 12.609 C 12.945 12.719 12.992 12.812 13.008 12.891 C 13.094 13.203 13.168 13.375 13.23 13.406 C 13.285 13.43 13.328 13.438 13.359 13.43 C 13.391 13.422 13.408 13.385 13.412 13.318 C 13.416 13.252 13.416 13.197 13.412 13.154 C 13.408 13.111 13.402 13.063 13.395 13.008 L 13.383 12.914 L 13.383 12.703 L 13.371 12.609 C 13.254 12.586 13.182 12.539 13.154 12.469 C 13.127 12.398 13.133 12.326 13.172 12.252 C 13.211 12.178 13.27 12.105 13.348 12.035 C 13.355 12.027 13.387 12.014 13.441 11.994 C 13.496 11.975 13.557 11.949 13.623 11.918 C 13.689 11.887 13.738 11.855 13.77 11.824 C 13.934 11.676 13.992 11.539 13.945 11.414 C 14 11.414 14.043 11.379 14.074 11.309 C 14.066 11.309 14.047 11.297 14.016 11.273 C 13.984 11.25 13.955 11.23 13.928 11.215 C 13.9 11.199 13.883 11.191 13.875 11.191 C 13.945 11.152 13.953 11.09 13.898 11.004 C 13.938 10.98 13.967 10.937 13.986 10.875 C 14.006 10.813 14.035 10.773 14.074 10.758 C 14.145 10.852 14.227 10.859 14.32 10.781 C 14.383 10.719 14.387 10.656 14.332 10.594 C 14.371 10.539 14.451 10.498 14.572 10.471 C 14.693 10.443 14.766 10.406 14.789 10.359 C 14.844 10.375 14.875 10.367 14.883 10.336 C 14.891 10.305 14.895 10.258 14.895 10.195 C 14.895 10.133 14.906 10.086 14.93 10.055 C 14.961 10.016 15.02 9.98 15.105 9.949 C 15.191 9.918 15.242 9.898 15.258 9.891 L 15.457 9.762 C 15.48 9.73 15.48 9.715 15.457 9.715 C 15.598 9.73 15.719 9.688 15.82 9.586 C 15.898 9.5 15.875 9.422 15.75 9.352 C 15.773 9.305 15.762 9.268 15.715 9.24 C 15.668 9.213 15.609 9.191 15.539 9.176 C 15.563 9.168 15.607 9.166 15.674 9.17 C 15.74 9.174 15.781 9.168 15.797 9.152 C 15.914 9.074 15.887 9.012 15.715 8.965 C 15.582 8.926 15.414 8.973 15.211 9.105 Z M 13.301 19.383 C 14.91 19.102 16.281 18.363 17.414 17.168 C 17.391 17.145 17.342 17.127 17.268 17.115 C 17.193 17.104 17.145 17.09 17.121 17.074 C 16.98 17.02 16.887 16.988 16.84 16.98 C 16.848 16.926 16.838 16.875 16.811 16.828 C 16.783 16.781 16.752 16.746 16.717 16.723 C 16.682 16.699 16.633 16.668 16.57 16.629 C 16.508 16.59 16.465 16.562 16.441 16.547 C 16.426 16.531 16.398 16.508 16.359 16.477 C 16.32 16.445 16.293 16.424 16.277 16.412 C 16.262 16.4 16.232 16.383 16.189 16.359 C 16.146 16.336 16.113 16.328 16.09 16.336 C 16.066 16.344 16.027 16.348 15.973 16.348 L 15.938 16.359 C 15.914 16.367 15.893 16.377 15.873 16.389 C 15.854 16.4 15.832 16.412 15.809 16.424 C 15.785 16.436 15.77 16.447 15.762 16.459 C 15.754 16.471 15.754 16.48 15.762 16.488 C 15.598 16.355 15.457 16.27 15.34 16.23 C 15.301 16.223 15.258 16.201 15.211 16.166 C 15.164 16.131 15.123 16.104 15.088 16.084 C 15.053 16.064 15.014 16.059 14.971 16.066 C 14.928 16.074 14.883 16.102 14.836 16.148 C 14.797 16.187 14.773 16.246 14.766 16.324 C 14.758 16.402 14.75 16.453 14.742 16.477 C 14.688 16.438 14.688 16.369 14.742 16.271 C 14.797 16.174 14.805 16.102 14.766 16.055 C 14.742 16.008 14.701 15.99 14.643 16.002 C 14.584 16.014 14.537 16.031 14.502 16.055 C 14.467 16.078 14.422 16.111 14.367 16.154 C 14.313 16.197 14.277 16.223 14.262 16.23 C 14.246 16.238 14.213 16.26 14.162 16.295 C 14.111 16.33 14.078 16.359 14.063 16.383 C 14.039 16.414 14.016 16.461 13.992 16.523 C 13.969 16.586 13.949 16.629 13.934 16.652 C 13.918 16.621 13.873 16.596 13.799 16.576 C 13.725 16.557 13.688 16.535 13.688 16.512 C 13.703 16.59 13.719 16.727 13.734 16.922 C 13.75 17.117 13.77 17.266 13.793 17.367 C 13.848 17.609 13.801 17.797 13.652 17.93 C 13.441 18.125 13.328 18.281 13.313 18.398 C 13.281 18.57 13.328 18.672 13.453 18.703 C 13.453 18.758 13.422 18.838 13.359 18.943 C 13.297 19.049 13.27 19.133 13.277 19.195 C 13.277 19.242 13.285 19.305 13.301 19.383 Z"/>';
        $TEMP['privacy'] = $list['privacy'] == 0 ? $TEMP['#word']['private'] : $TEMP['#word']['public'];

        if ($TEMP['#loggedin'] === true && ($list['by_id'] == $TEMP['#user']['id'])) {
            $TEMP['#list_owner'] = true;
        }

        foreach ($videos as $value) {
            $video = Specific::Video($value);
            $video['url'] = Specific::WatchSlug($video['video_id'], 'playlist', $list_id);

            $TEMP['!get_progress'] = Specific::GetProgress($video['id']);
            $TEMP['!live'] = $video['live'];
            $TEMP['!id'] = $video['id'];
            $TEMP['!video_id'] = $video['video_id'];
            $TEMP['!title'] = $video['title'];
            $TEMP['!url'] = $video['url'];
            $TEMP['!thumbnail'] = $video['thumbnail'];
            $TEMP['!index'] = $index;
            $TEMP['!rotate_class'] = '';
            if($video['video_id'] == $id){
                $TEMP['!index'] = '▶';
                if($TEMP['#language_dir'] == 'rtl'){
                    $TEMP['!rotate_class'] = 'rotate-180 ';
                }
            }
            $TEMP['!list_active_class'] = ($video['video_id'] == $id) ? ' active' : '';
            $TEMP['!data'] = $video['data'];
            $TEMP['!duration'] = $video['duration'];
            $TEMP['!progress_value'] = $TEMP['!get_progress']['percent_loaded'];

            $TEMP['playlist'] .= Specific::Maket('watch/includes/video-list');

            if ($video['video_id'] == $id) {
                $TEMP['video_index'] = $index;
            }
            if($TEMP['video_index'] == $index - 1){
                $TEMP['id_nextend'] = $video['video_id'];
                $TEMP['title_nextend'] = addslashes($video['title']);
                $TEMP['thumbnail_nextend'] = $video['thumbnail'];
                $TEMP['url_nextend'] = $video['url'];
                $TEMP['lid_nextend'] = $TEMP['id_nextend'].'&list='.$list_id;
                $TEMP['dura_nextend'] = $video['duration'];
            }
            if($TEMP['video_index'] == 0){
                $TEMP['id_prevend'] = $video['video_id'];
                $TEMP['title_prevend'] = addslashes($video['title']);
                $TEMP['thumbnail_prevend'] = $video['thumbnail'];
                $TEMP['url_prevend'] = $video['url'];
                $TEMP['lid_prevend'] = $TEMP['id_prevend'].'&list='.$list_id;
                $TEMP['dura_prevend'] = $video['duration'];
            }
            $index++;
        }
        Specific::DestroyMaket();
        $TEMP['#load_url'] = Specific::WatchSlug($video_data['video_id'], 'playlist', $_GET['list']);
    }


    $limit_random = $TEMP['#settings']['data_load_limit'] / 2;
    $has_next = false;

    if(empty($_SESSION['related_videos'])){
        $_SESSION['related_videos'] = array(0);
    }
    if(empty($_SESSION['next_video'])){
        $_SESSION['next_video'] = array(0);
    }

    $random_videos = $dba->query('SELECT id FROM videos v WHERE converted = 1 AND MATCH(title) AGAINST ("'.$video_data['title'].'") AND id != '.$video_data['id'].' AND by_id NOT IN ('.$TEMP['#blocked_users'].') AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 1 OR (SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND id NOT IN ('.implode(',', $_SESSION['related_videos']).') AND privacy = 0 AND deleted = 0 ORDER BY rand() LIMIT '.$limit_random)->fetchAll(FALSE);

    $related_videos = $dba->query('SELECT id FROM videos v WHERE converted = 1 AND MATCH(title) AGAINST ("'.$video_data['title'].'") AND id != '.$video_data['id'].' AND by_id NOT IN ('.$TEMP['#blocked_users'].') AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 1 OR (SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND id NOT IN ('.implode(',', $_SESSION['related_videos']).') AND privacy = 0 AND category = "'.$video_data['category'].'" AND deleted = 0 LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll(FALSE);

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

    $video_id = $related_videos[0];
    if(count($related_videos) < 2){
        $has_next       = true;
        $related_videos = $dba->query('SELECT * FROM videos v WHERE converted = 1 AND title NOT LIKE "%'.$video_data['title'].'%" AND id != '.$video_data['id'].' AND by_id NOT IN ('.$TEMP['#blocked_users'].') AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 1 OR (SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND privacy = 0 AND deleted = 0 ORDER BY rand() LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();
        $video_id       = $related_videos[0]['id'];
    }

    if(count($_SESSION['related_videos']) > $TEMP['#settings']['data_load_limit'] || count($related_videos) < $limit_random){
        $_SESSION['related_videos'] = array(0);
    }
    if(count($_SESSION['next_video']) > $TEMP['#settings']['data_load_limit']){
        $_SESSION['next_video'] = array(0);
    }
    
    if(!in_array($video_id, $_SESSION['next_video'])){
        $_SESSION['next_video'][$video_data['id']] = $video_id;
    }
    $TEMP['#related_videos'] = count($related_videos);
    foreach ($related_videos as $value) {
        $video  = Specific::Video($value);
        if(!in_array($video['id'], $_SESSION['related_videos'])){
            $_SESSION['related_videos'][] = $video['id'];    
        }
        $TEMP['!live'] = $video['live'];
        $TEMP['!get_progress'] = Specific::GetProgress($video['id']);
        $TEMP['!id'] = $video['id'];
        $TEMP['!title'] = $video['title'];
        $TEMP['!url'] = $video['url'];
        $TEMP['!thumbnail'] = $video['thumbnail'];
        $TEMP['!data'] = $video['data'];
        $TEMP['!views'] = Specific::Number($video['views']);
        $TEMP['!duration'] = $video['duration'];
        $TEMP['!progress_value'] = $TEMP['!get_progress']['percent_loaded'];
        $TEMP['!video_id'] = $video['video_id'];
        $TEMP['!animation'] = $video['animation'];

        $TEMP['video_sidebar'] .= Specific::Maket('watch/includes/video-sidebar');
        if(count($end_related) < 12){
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
        $next = -1;
        if((empty($TEMP['next_video']) && in_array($video['id'], $_SESSION['next_video']) && in_array($video_data['id'], array_keys($_SESSION['next_video']))) || ($has_next == true && empty($TEMP['next_video']))){
            $next = 0;
        }
        if ((in_array($video_data['id'], array_keys($_SESSION['next_video'])) || !in_array($video['id'], $_SESSION['next_video']) || empty($_SESSION['next_video'])) && $next == 0 && $TEMP['#settings']['autoplay'] == 'on') {
            $TEMP['next_video'] = $TEMP['video_sidebar'];
            $TEMP['video_sidebar'] = '';
            if(($TEMP['video_index'] == $index - 1) || ($TEMP['video_index'] == 0) ){
                $TEMP['id_nextend'] = $video['id'];
                $TEMP['title_nextend'] = addslashes($video['title']);
                $TEMP['thumbnail_nextend'] = $video['thumbnail'];
                $TEMP['owner_nextend'] = $video['data']['username'];
                $TEMP['url_nextend'] = $video['url'];
                $TEMP['lid_nextend'] = $video['video_id'];
                $TEMP['dura_nextend'] = $video['duration'];
            }
        }
        $next++;
    }
    Specific::DestroyMaket();

    $TEMP['#pin'] = false;
    $TEMP['pinned_comment'] = '';
    $pinned_comment = $dba->query('SELECT * FROM comments WHERE pinned = 1 AND video_id = '.$video_data['id'])->fetchArray();

    if (!empty($pinned_comment)) {
        $like_active_class  = '';
        $dislike_active_class  = '';
        $TEMP['#pin'] = true;
        $TEMP['#is_comment_owner'] = false;
        $video = Specific::Video($pinned_comment['video_id']);
        $TEMP['#cont_replies']     = $dba->query('SELECT * FROM replies WHERE comment_id = '.$pinned_comment['id'].' AND by_id NOT IN ('.$TEMP['#blocked_users'].')')->fetchAll();
        $TEMP['!owner_username']   = $video['by_id'] == $pinned_comment['by_id'] ? ' owner-username' : '';
        $TEMP['#header_comment_badge'] = !empty($_GET['cl']) && $_GET['cl'] == $pinned_comment['id'] ? true : false;

        if ($TEMP['#loggedin'] === true) {
            if($dba->query('SELECT COUNT(*) FROM reactions WHERE type = 1 AND to_id = '.$pinned_comment['id'].' AND place = "commentary" AND by_id = '.$TEMP['#user']['id'])->fetchArray() > 0){
                $like_active_class = ' active';
            }
            if($dba->query('SELECT COUNT(*) FROM reactions WHERE type = 2 AND to_id = '.$pinned_comment['id'].' AND place = "commentary" AND by_id = '.$TEMP['#user']['id'])->fetchArray() > 0){
                $dislike_active_class  = ' active';
            }
            if ($TEMP['#user']['id'] == $pinned_comment['by_id'] || $dba->query('SELECT COUNT(*) FROM videos WHERE id = '.$pinned_comment['video_id'].' AND by_id = '.$TEMP['#user']['id'])->fetchArray() > 0) {
                $TEMP['#is_comment_owner'] = true;
            }
        }

        $likes = $dba->query('SELECT COUNT(*) FROM reactions WHERE type = 1 AND to_id = '.$pinned_comment['id'].' AND place = "commentary"')->fetchArray();

        $dislikes = $dba->query('SELECT COUNT(*) FROM reactions WHERE type = 2 AND to_id = '.$pinned_comment['id'].' AND place = "commentary"')->fetchArray();

        $comm_likes = '';
        if($likes >= 1000000){
            $comm_likes = Specific::Number($likes);
        } else if($likes > 0) {
            $comm_likes = number_format($likes);
        }

        $comm_dislikes = '';
        if($dislikes >= 1000000){
            $comm_dislikes = Specific::Number($dislikes);
        } else if($dislikes > 0){
            $comm_dislikes = number_format($dislikes);
        }

        $TEMP['!id'] = $pinned_comment['id'];
        $TEMP['!text'] = Specific::GetComposeText($pinned_comment['text']);
        $TEMP['!time'] = Specific::DateString($pinned_comment['time']);
        $TEMP['!data'] = Specific::Data($pinned_comment['by_id']);
        $TEMP['!username_cmt'] = $video['data']['username'];
        $TEMP['!comment_url'] = $video['url'].'&cl='.$pinned_comment['id'];
        $TEMP['!all_r'] = count($TEMP['#cont_replies']);
        $TEMP['!norm_likes'] = $likes;
        $TEMP['!norm_dislikes'] = $dislikes;
        $TEMP['!likes'] = $comm_likes;
        $TEMP['!dislikes'] = $comm_dislikes;
        $TEMP['!liked'] = $like_active_class;
        $TEMP['!disliked'] = $dislike_active_class;
        $TEMP['!isliked'] = !empty($like_active_class) ? 'true' : 'false';
        $TEMP['!isdisliked'] = !empty($dislike_active_class) ? 'true' : 'false';
        $TEMP['!like_active_word'] = !empty($like_active_class) ? $TEMP['#word']['i_dont_like_it_enymore'] : $TEMP['#word']['i_like_it'];
        $TEMP['!dis_active_word'] = !empty($dislike_active_class) ? $TEMP['#word']['remove_i_dont_like'] : $TEMP['#word']['i_dont_like'];
        
        $TEMP['pinned_comment'] = Specific::Maket("watch/includes/pinned-comments");
    }

    $TEMP['count_comments']  = $dba->query('SELECT COUNT(*) FROM comments WHERE video_id = '.$video_data['id'].' AND by_id NOT IN ('.$TEMP['#blocked_users'].')')->fetchArray();
    if ($TEMP['#loggedin'] === true) {
        if ($TEMP['#settings']['history'] == 'on') {
            if ($dba->query('SELECT COUNT(*) FROM history WHERE video_id = '.$video_data['id'].' AND by_id = '.$TEMP['#user']['id'])->fetchArray() == 0) {
                $dba->query('INSERT INTO history (by_id, video_id, time) VALUES ('.$TEMP['#user']['id'].','.$video_data['id'].','.time().')');
            }
        }
    }

    $TEMP['checked'] = '';
    if (!empty($_COOKIE['autoplay'])) { 
        if ($_COOKIE['autoplay'] == 'true') {
            $TEMP['checked'] = 'checked';
        } 
    } else if (empty($_COOKIE['autoplay'])) {
        $TEMP['checked'] = 'checked';
    }

    $sidebar_ad_website = Specific::GetAd('sidebar_ad');
    $TEMP['watch_sidebar_ad'] = $sidebar_ad_website['active'] == 1 ? $sidebar_ad_website['content'] : '';

    $TEMP['force_live'] = false;
    $TEMP['force_progress'] = 0;
    $TEMP['#video_144'] = 0;
    $TEMP['#video_240'] = 0;
    $TEMP['#video_360'] = 0;
    $TEMP['#video_480'] = 0;
    $TEMP['#video_720'] = 0;
    $TEMP['#video_1080'] = 0;
    $TEMP['#video_1440'] = 0;
    $TEMP['#video_2160'] = 0;

    $json_path = array();
    if($video_data['broadcast'] == 0 || $stream_data['live'] > 1){
        $TEMP['word_views'] = $TEMP['#word']['views'];
        $TEMP['views'] = ($video_data['views'] >= 1000000) ? Specific::Number($video_data['views']) : number_format($video_data['views']);
        $video_path = explode('_video', $video_data['path']);
        if ($video_data['2160p'] == 1) {
            $TEMP['#video_2160'] = explode('videos', $video_path[0])[1] . '_video_2160p';
            $TEMP['download_path_2160'] = urlencode(Specific::Url('download-video').'/'.$video_data['video_id'].'?quality=2160');
            $json_path[] = array("src" => $TEMP['#video_2160'], "data-quality" => '4K', "title" => '4K', "res" => '2160');
        }
        if ($video_data['1440p'] == 1) {
            $TEMP['#video_1440'] = explode('videos', $video_path[0])[1] . '_video_1440p';
            $TEMP['download_path_1440'] = urlencode(Specific::Url('download-video').'/'.$video_data['video_id'].'?quality=1440');
            $json_path[] = array("src" => $TEMP['#video_1440'], "data-quality" => '2K', "title" => '2K', "res" => '1440');
        }
        if ($video_data['1080p'] == 1) {
            $TEMP['#video_1080'] = explode('videos', $video_path[0])[1] . '_video_1080p';
            $TEMP['download_path_1080'] = urlencode(Specific::Url('download-video').'/'.$video_data['video_id'].'?quality=1080');
            $json_path[] = array("src" => $TEMP['#video_1080'], "data-quality" => '1080p', "title" => '1080p', "res" => '1080');
        }
        if ($video_data['720p'] == 1) {
            $TEMP['#video_720'] = explode('videos', $video_path[0])[1] . '_video_720p';
            $TEMP['download_path_720'] = urlencode(Specific::Url('download-video').'/'.$video_data['video_id'].'?quality=720');
            $json_path[] = array("src" => $TEMP['#video_720'], "data-quality" => '720p', "title" => '720p', "res" => '720');
        }
        if ($video_data['480p'] == 1) {
            $TEMP['#video_480'] = explode('videos', $video_path[0])[1] . '_video_480p';
            $TEMP['download_path_480'] = urlencode(Specific::Url('download-video').'/'.$video_data['video_id'].'?quality=480');
            $json_path[] = array("src" => $TEMP['#video_480'], "data-quality" => '480p', "title" => '480p', "res" => '480');
        }
        if ($video_data['360p'] == 1) {
            $TEMP['#video_360'] = explode('videos', $video_path[0])[1] . '_video_360p';
            $TEMP['download_path_360'] = urlencode(Specific::Url('download-video').'/'.$video_data['video_id'].'?quality=360');
            $json_path[] = array("src" => $TEMP['#video_360'], "data-quality" => '360p', "title" => '360p', "res" => '360');
        }
        if ($video_data['240p'] == 1) {
            $TEMP['#video_240'] = explode('videos', $video_path[0])[1] . '_video_240p';
            $TEMP['download_path_240'] = urlencode(Specific::Url('download-video').'/'.$video_data['video_id'].'?quality=240');
            $json_path[] = array("src" => $TEMP['#video_240'], "data-quality" => '240p', "title" => '240p', "res" => '240');
        }
        if ($video_data['144p'] == 1) {
            $TEMP['#video_144'] = explode('videos', $video_path[0])[1] . '_video_144p';
            $TEMP['download_path_144'] = urlencode(Specific::Url('download-video').'/'.$video_data['video_id'].'?quality=144');
            $json_path[] = array("src" => $TEMP['#video_144'], "data-quality" => '144p', "title" => '144p', "res" => '144');
        }
    } else {
        $data_stream = Specific::DataStream();
        $TEMP['word_views'] = $TEMP['#word']['viewers'];
        $TEMP['views'] = $data_stream['server']['application']['live']['stream']['nclients'];
        $TEMP['force_live'] = true;
        $TEMP['force_progress'] = $data_stream['server']['application']['live']['stream']['time'] / 1000;
        $stream_width = $data_stream['server']['application']['live']['stream']['meta']['video']['height'];
        $json_path[] = array("src" => $video_data['video_id'], "data-quality" => '144p', "title" => '144p', "res" => '144');

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

    $TEMP['#chat_broadcast'] = $stream_data['chat'];
    $TEMP['json_broadcast_text'] = 0;
    if($stream_data['live'] == 1){
        $TEMP['broadcast_text'] = Specific::BroadcastChat($video_data['id']);
    } else if($stream_data['live'] == 3){
        $json_broadcast_text = Specific::BroadcastChat($video_data['id'], 'json');
        if(!empty($json_broadcast_text)){
            $TEMP['json_broadcast_text'] = $json_broadcast_text;
        }
    }
    
    $TEMP['#in_queue'] = false;
    if ($video_data['converted'] == 0) {
        $is_in_queue = $dba->query('SELECT COUNT(*) FROM queue WHERE video_id = '.$video_data['id'])->fetchArray();
        $process_queue = $dba->query('SELECT video_id FROM queue LIMIT '.$TEMP['#settings']['max_queue'])->fetchAll(FALSE);
        if ($TEMP['#settings']['max_queue'] == 1) {
            if ($process_queue[0] != $video_data['id']) {
                $TEMP['#in_queue'] = true;
            }
        }elseif ($TEMP['#settings']['max_queue'] > 1) {
            if ($is_in_queue > 0 && !in_array($video_data['id'], $process_queue)) {
                $TEMP['#in_queue'] = true;
            }
        } 
    }

    $TEMP['#resize_on'] = 0;
    $progress_start = Specific::Filter($_GET['t']);
    $TEMP['#get_progress'] = Specific::GetProgress($video_data['id']);
    if(!empty($_COOKIE['resize'])){
        if($TEMP['#is_mobile'] && $_COOKIE['resize'] == 1){
            setcookie('resize', 0, time() + (86400 * 365), "/");
        } else {
            $TEMP['#resize_on'] = $_COOKIE['resize'];
        }
    } 

    $comments_ad = Specific::GetAd('comments_ad');

    $TEMP['id'] = $video_data['id'];
    $TEMP['video_id'] = $video_data['video_id'];
    $TEMP['short_id'] = $video_data['short_id'];
    $TEMP['animation'] = $video_data['animation'];
    $TEMP['description'] = Specific::GetComposeText($video_data['description']);
    $TEMP['ffmpeg_progress'] = Specific::FfmpegProcessing($video_data['video_id'])['progress'].'%';
    $TEMP['duration'] = $video_data['duration'];
    $TEMP['json_path'] = json_encode($json_path);
    $TEMP['user_viewer'] = !empty($TEMP['#user']['id']) ? $TEMP['#user']['id'] : 0;
    $TEMP['subscribe_button'] = Specific::SubscribeButton($data['id']);
    $TEMP['no_progress'] = '"music"';
    $TEMP['end_related'] = json_encode(array_reverse($end_related));
    $TEMP['progress_value'] = $progress_start ? $progress_start : !empty($TEMP['#get_progress']) ? $TEMP['#get_progress']['loaded_progress'] : 0;
    $TEMP['percent_value'] = $progress_start ? 'Get' : !empty($TEMP['#get_progress']) ? $TEMP['#get_progress']['percent_loaded'] : 0;
    $TEMP['likes'] = ($video_data['likes'] >= 1000000) ? Specific::Number($video_data['likes']) : number_format($video_data['likes']);
    $TEMP['dislikes'] = ($video_data['dislikes'] >= 1000000) ? Specific::Number($video_data['dislikes']) : number_format($video_data['dislikes']);

    $total_reactions = $video_data['likes'] + $video_data['dislikes'];
    $TEMP['likes_p'] = 0;
    if ($video_data['likes'] > 0) {
        $TEMP['likes_p'] = round(($video_data['likes'] / $total_reactions) * 100);
    }
    $TEMP['dislikes_p'] = 0;
    if ($video_data['dislikes'] > 0) {
        $TEMP['dislikes_p'] = round(($video_data['dislikes'] / $total_reactions) * 100);
    }
    if ($TEMP['likes_p'] == 0 && $TEMP['dislikes_p'] == 0) {
        $TEMP['dislikes_p'] = 50;
        $TEMP['likes_p'] = 50;
    }

    $TEMP['norm_likes'] = $video_data['likes'];
    $TEMP['norm_dislikes'] = $video_data['dislikes'];
    $TEMP['isliked'] = ($video_data['is_liked'] > 0) ? 'true' : 'false';
    $TEMP['isdisliked'] = ($video_data['is_disliked'] > 0) ? 'true' : 'false';
    $TEMP['like_active_class'] = ($video_data['is_liked'] > 0) ? ' active' : '';
    $TEMP['dis_active_class'] = ($video_data['is_disliked'] > 0) ? ' active' : '';
    $TEMP['likes_active_word'] = ($video_data['is_liked'] > 0) ? $TEMP['#word']['i_dont_like_it_enymore'] : $TEMP['#word']['i_like_it'];
    $TEMP['dis_active_word'] = ($video_data['is_disliked'] > 0) ? $TEMP['#word']['remove_i_dont_like'] : $TEMP['#word']['i_dont_like'];
    $TEMP['category_name'] = $video_data['category_name'];
    $TEMP['category_key'] = $video_data['category'];
    $TEMP['time'] = $video_data['time_format'];
    $TEMP['watermark'] = $data['watermark'];
    $TEMP['start_watermark'] = $data['watermark_start'] != 'end' && $data['watermark_start'] != 'all' && !empty($data['watermark_start']) ? Specific::DurationToSeconds($data['watermark_start']) : $data['watermark_start'];
    $TEMP['comment_ad'] = $comments_ad['active'] == 1 ? $comments_ad['content'] : '';
    $TEMP['video_comments'] = Specific::Maket('watch/includes/watch-comments');
    $TEMP['broadcast_chat'] = Specific::Maket('live-broadcast/broadcast-chat');

    $TEMP['#thumbnail'] = $video_data['thumbnail'];
    $TEMP['#url'] = $video_data['url'];
    
    $TEMP['#title'] = $video_data['title'];
    $TEMP['#description'] = Specific::GetUncomposeText($video_data['description'], 220);
    $TEMP['#keyword'] = $video_data['tags'];
} else {
    $TEMP['#title']       = $TEMP['#settings']['title'];
    $TEMP['#description'] = $TEMP['#settings']['description'];
    $TEMP['#keyword']     = $TEMP['#settings']['keyword'];
}
$TEMP['#content'] = Specific::Maket("watch/content");
?>