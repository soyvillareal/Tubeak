<?php
$id    = Specific::Filter($_GET['two']);
$video = Specific::Video($id);
if ($TEMP['#loggedin'] === false) {
    header("Location: " . Specific::Url('login'));
    exit();
} else if(empty($_GET['two']) || empty($video) || (!Specific::Admin() && $dba->query('SELECT COUNT(*) FROM videos WHERE id = '.$id.' AND deleted = 0 AND by_id = '.$TEMP['#user']['id'])->fetchArray() == 0 || ($video['broadcast'] == 1 && $dba->query('SELECT COUNT(*) FROM broadcasts WHERE video_id = "'.$video['video_id'].'" AND live = 3')->fetchArray() == 0))){
    header("Location: " . Specific::Url('404'));
    exit();
}

$TEMP['checked_chat'] = $dba->query('SELECT chat FROM broadcasts WHERE video_id = "'.$video['video_id'].'"')->fetchArray() == 1 ? ' checked' : '';

if (strpos($video['thumbnail'], '_thumbnail.')){
    $TEMP['ac_draft'] = ' active';
    $TEMP['nw_chg'] = ' chg-tb';  
} else if (strpos($video['thumbnail'], 'video_thumbnail_') && empty($video['thumbnail_draft'])){ 
    $TEMP['nw_chg'] = ' nw-tb';
} else if (strpos($video['thumbnail'], 'video_thumbnail_')){
    $TEMP['nw_chg'] = ' chg-tb';
}

if (strpos($video['thumbnail'], '_thumbnail.')) { 
    $TEMP['thumbnail_cus'] = $video['thumbnail']; 
    $TEMP['#thumb_img'] = $video['ex_thumbnail'];
    $TEMP['ac_draft'] = ' active';
}  if (strpos($video['thumbnail'], 'video_thumbnail_')){ 
    $TEMP['thumbnail_cus'] = $video['thumbnail_draft']; 
    $TEMP['#thumb_img'] = $video['ex_thumbnail_draft'];
}

$TEMP['id'] = $video['id'];
$TEMP['data'] = $video['data'];
$TEMP['thumbnail'] = $video['ex_thumbnail'];
$TEMP['thumbnail_1'] = $video['thumbnail_1'];
$TEMP['thumbnail_2'] = $video['thumbnail_2'];
$TEMP['thumbnail_3'] = $video['thumbnail_3'];
$TEMP['thumbnail_s1'] = ($video['thumbnail'] == $video['thumbnail_1']) ? ' active' : '';
$TEMP['thumbnail_s2'] = ($video['thumbnail'] == $video['thumbnail_2']) ? ' active' : '';
$TEMP['thumbnail_s3'] = ($video['thumbnail'] == $video['thumbnail_3']) ? ' active' : '';
$TEMP['video_id'] = $video['video_id'];
$TEMP['title'] = $video['title'];
$TEMP['description'] = Specific::GetUncomposeText($video['description'], 0, true);
$TEMP['time'] = $video['time_string'];
$TEMP['tags'] = $video['tags'];

$TEMP['#video']       = $video;
$TEMP['#tags']        = explode(",", $video['tags']);
$TEMP['#is_thumb_cus']  = (!empty($video['thumbnail_draft']) || strpos($video['thumbnail'], '_thumbnail.'));

$TEMP['#title']       = $TEMP['#word']['edit_video'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#page']        = 'edit-video';
$TEMP['#load_url']    = Specific::Url('edit-video/'.$id);

$TEMP['#content']     = Specific::Maket('edit-video/content');
?>