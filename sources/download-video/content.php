<?php
$video_id = Specific::Filter($_GET['video_id']);
$quality = Specific::Filter($_GET['quality']);
$video_data = Specific::Video($video_id);
if($TEMP['#settings']['download_videos'] != 'on' || empty($video_id) || empty($video_data) || empty($quality) || !in_array($quality, array(2160, 1440, 1080, 720, 480, 360, 240, 144))){
    header("Location: " . Specific::Url('404')); 
    exit();
}
if(($TEMP['#loggedin'] === true && $TEMP['#settings']['download_type'] == '1') || $TEMP['#settings']['download_type'] == '0' || Specific::Admin()){
    // If the file is found in the file's array
    // Get the file data
    $token_name = md5(rand(111111,999999))."_$quality";
    $explode_video = explode('_video', $video_data['path']);

    if ($video_data['2160p'] == 1 && $quality == '2160') {
        $filePath = $explode_video[0] . '_video_' . $video_data['download_key'] . '_2160p_unload.mp4';
    } else if ($video_data['1440p'] == 1 && $quality == '1440') {
        $filePath = $explode_video[0] . '_video_' . $video_data['download_key'] . '_1440p_unload.mp4';
    } else if ($video_data['1080p'] == 1 && $quality == '1080') {
        $filePath = $explode_video[0] . '_video_' . $video_data['download_key'] . '_1080p_unload.mp4';
    } else if ($video_data['720p'] == 1 && $quality == '720') {
        $filePath = $explode_video[0] . '_video_' . $video_data['download_key'] . '_720p_unload.mp4';
    } else if ($video_data['480p'] == 1 && $quality == '480') {
        $filePath = $explode_video[0] . '_video_' . $video_data['download_key'] . '_480p_unload.mp4';
    } else if ($video_data['360p'] == 1 && $quality == '360') {
        $filePath = $explode_video[0] . '_video_' . $video_data['download_key'] . '_360p_unload.mp4';
    } else if ($video_data['240p'] == 1 && $quality == '240') {
        $filePath = $explode_video[0] . '_video_' . $video_data['download_key'] . '_240p_unload.mp4';
    } else if ($video_data['144p'] == 1 && $quality == '144') {
        $filePath = $explode_video[0] . '_video_' . $video_data['download_key'] . '_144p_unload.mp4';
    } else {
        header('Location: ' . Specific::Url('404'));
        exit;
    }

    $filePathComplete = 'uploads/'.explode('uploads', $filePath)[1];

    // Force the browser to download the file
    header("Content-Description: File Transfer");
    header("Content-type: video/mp4");
    header("Content-Disposition: attachment; filename={$token_name}.mp4");
    header("Content-Length: " . filesize($filePathComplete));
    header('Pragma: public');
    header("Expires: 0");
    readfile($filePathComplete);
    // Write the key to the keys list
    exit();
} else {
    header("Location: " . Specific::Url('login')); 
    exit();
}
?>