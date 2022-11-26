<?php
$by_id = Specific::Filter($_GET['by_id']);
if(empty($by_id)){
    $by_id = Specific::Filter($_POST['by_id']);
}
$deliver['status'] = 400;
if (empty($by_id) || $TEMP['#loggedin'] === false) {
    $deliver = array(
        'status' => 400,
        'error' => $TEMP['#word']['error']
    );
    echo json_encode($deliver);
    exit();
} else if ($one == 'upload') {
    $user_data = Specific::Data($by_id);
    $update_data = '';
    $resized = true;
    if (Specific::IsOwner($by_id) && !empty($user_data['id'])) {
        if (!empty($_FILES['avatar']['tmp_name'])) {
            $file_info = array(
                'file' => $_FILES['avatar']['tmp_name'],
                'size' => $_FILES['avatar']['size'],
                'name' => $_FILES['avatar']['name'],
                'type' => $_FILES['avatar']['type'],
                'from' => 'avatar',
                'crop' => array('width' => 400, 'height' => 400)
            );
            $file_data = Specific::UploadImage($file_info);
            if (!empty($file_data)) {
                if(!empty($user_data['ex_avatar']) && $user_data['avatar'] != 'images/default-avatar.jpg' && $user_data['avatar'] != 'images/default-favatar.jpg'){
                    unlink($user_data['ex_avatar']);
                }
                $update_data = 'avatar = "'.$file_data.'"';
            }
        }
        if (!empty($_FILES['watermark']['tmp_name']) && $_FILES['avatar']['size'] < 1000000) {
            $file_info = array(
                'file' => $_FILES['watermark']['tmp_name'],
                'size' => $_FILES['watermark']['size'],
                'name' => $_FILES['watermark']['name'],
                'type' => $_FILES['watermark']['type'],
                'from' => 'watermark',
                'crop' => array('width' => 150, 'height' => 150)
            );
            $file_data = Specific::UploadImage($file_info);
            if (!empty($file_data)) {
                if(!empty($user_data['ex_watermark'])){
                    unlink($user_data['ex_watermark']);
                }
                $update_data = "watermark = '".json_encode(array('url' => $file_data, 'time' => 'all'))."'";
            }
        }
        if (!empty($_FILES['cover']['tmp_name'])) {
            $file_info = array(
                'file' => $_FILES['cover']['tmp_name'],
                'size' => $_FILES['cover']['size'],
                'name' => $_FILES['cover']['name'],
                'type' => $_FILES['cover']['type'],
                'from' => 'cover',
                'crop' => array('width' => 1200, 'height' => 200)
            );
            $file_data = Specific::UploadImage($file_info);
            if (!empty($file_data)) {
                if(!empty($user_data['ex_cover']) && $user_data['cover'] != 'images/default-cover.jpg'){
                    unlink($user_data['ex_cover']);
                    unlink($user_data['full_cover']);
                }
                $resized           = true;
                $full_url_image    = Specific::GetFile($file_data);
                $default_image     = explode('.', $file_data);
                $full_cover_media  = $default_image[0] . '_full.' . $default_image[1];
                $data_resize = array(
                    'original' => $full_cover_media,
                    'cover' => $file_data,
                    'top' => Specific::Filter($_GET['pos']),
                    'width' => 1200,
                    'height' => 200
                );
                $resized = Specific::ResizeCover($data_resize);

                $update_data = 'cover = "'.$file_data.'", cover_changed = 0';
            }
        }
        if($resized == true){
            if ($dba->query('UPDATE users SET '.$update_data.' WHERE id = '.$by_id)->returnStatus()) {
               $deliver = array(
                    'status' => 200,
                    'message' => $TEMP['#word']['setting_updated'],
                    'media' => $full_url_image
                );
            }
        }
        
    }
} else if ($one == 'cover-position') {
    if (Specific::IsOwner($by_id)) {
        $user_data            = Specific::Data($by_id);
        $cover_changed        = time();
        $full_url_image       = $user_data['cover'];
        $data_resize = array(
            'original' => $user_data['full_cover_media'],
            'cover' => $user_data['ex_cover'],
            'top' => Specific::Filter($_POST['pos']),
            'width' => 1200,
            'height' => 200
        );
        $resized = Specific::ResizeCover($data_resize);

        if($resized == true){
            if($dba->query('UPDATE users SET cover_changed = '.$cover_changed.' WHERE id = '.$by_id)->returnStatus()){
                $deliver = array(
                   'status' => 200,
                   'media' => $full_url_image . "?t=$cover_changed"
                );
            }
        }
    }
} else if($one == 'water-start') {
    $user_data = Specific::Data($by_id);
    $watermark_start = Specific::Filter($_POST['start']);
    if (Specific::IsOwner($by_id) && !empty($by_id) && !empty($user_data['watermark'])) {
        if($dba->query("UPDATE users SET watermark = '".json_encode(array('url' => $user_data['ex_watermark'], 'time' => $watermark_start))."' WHERE id = $by_id")->returnStatus()){
           $deliver = array(
                'status' => 200,
                'message' => $TEMP['#word']['start_time_has_been_updated']
            ); 
        }  
    } else {
        $deliver = array(
            'status' => 400,
            'error' => $TEMP['#word']['make_sure_to_save_your_watermark_first']
        );
    }
} else if($one == 'darkmode') {
    $deliver['status'] = 400;
    $darkmode = Specific::Filter($_POST['darkmode']);
    if(isset($darkmode) && is_numeric($darkmode)){
        if($dba->query('UPDATE users SET dark = "'.$darkmode.'" WHERE id = '.$TEMP['#user']['id'])->returnStatus()){
            $deliver['status'] = 200;
        }
    }
}
?>