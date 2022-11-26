<?php
use Gregwar\Image\Image;
if ($TEMP['#loggedin'] === false || $TEMP['#settings']['upload_videos'] == 'off') {
    $deliver = array(
        'status' => 400,
        'error' => $TEMP['#word']['error']
    );
    echo json_encode($deliver);
    exit();
} else if($one == 'upload-video'){
    if (!empty($_FILES['video']['tmp_name'])) {
        $uploads = $TEMP['#user']['uploads'] + $_FILES['video']['size'];
        if(Specific::Admin() || (!Specific::Admin() && $TEMP['#user']['upload_limit'] == 0 && ($uploads < $TEMP['#user']['upload_limit'] || $uploads < $TEMP['#settings']['upload_limit']) && $_FILES['video']['size'] < $TEMP['#settings']['max_upload_size'])){
            $file_data   = Specific::UploadVideo(array(
                'file'    => $_FILES['video']['tmp_name'],
                'size'    => $_FILES['video']['size'],
                'name'    => $_FILES['video']['name'],
                'type'    => $_FILES['video']['type']
            ));
            if (!empty($file_data['filename'])) {
                $filename  = $file_data['filename'];
                $_SESSION['uploads']['videos'][] = $filename;
                $metadata = Specific::FfprobeData($filename);
                if(!empty($metadata)){
                    $images = array();
                    $duration_seconds = $metadata['seconds'];
                    $thumb_1_duration = $duration_seconds / 2;
                    $thumb_2_duration = $duration_seconds / 3;
                    $thumb_3_duration = $duration_seconds / 4;
                    $thumbnails = array($thumb_1_duration, $thumb_2_duration, $thumb_3_duration);
                    $filepath = Specific::CreateDirImage();
                    foreach ($thumbnails as $value) { 
                        $thumbnail = $filepath . '/' . Specific::RandomKey() . sha1(time()) . '_video_thumbnail_'.intval($value).'.jpeg';
                        exec($TEMP['#settings']['binary_path']."/ffmpeg -loglevel quiet -ss $value -i {$file_data['filename']} -t 1 -f image2 $thumbnail");
                        if (file_exists($thumbnail) && !empty(getimagesize($thumbnail))) {
                            Image::open($thumbnail)->zoomCrop(1076, 604)->save($thumbnail, 'jpeg', 80);
                            $images[] = $thumbnail;
                        } else {
                            unlink($thumbnail);
                        }
                    }
                    $_SESSION['uploads']['images'] = $images;
                    if($dba->query('UPDATE users SET uploads = '.($TEMP['#user']['uploads'] += $_FILES['video']['size']).' WHERE id = '.$TEMP['#user']['id'])->returnStatus()){
                        $deliver = array(
                            'status' => 200,
                            'path' => $filename,
                            'filename' => $file_data['name'],
                            'images' => $images
                        );
                    };
                }
            } else {
                $deliver = array(
                    'status' => 400,
                    'error' => $file_data['error']
                );
            }
        } else {
            $limit = $TEMP['#user']['upload_limit'] != 0 ? $TEMP['#user']['upload_limit'] : $TEMP['#settings']['upload_limit'];
            $error = $TEMP['#word']['upload_limit_reached'] . " (".Specific::BytesFormat($limit).")";
            if($_FILES['video']['size'] >= $TEMP['#settings']['max_upload_size']){
                $error = $TEMP['#word']['file_is_too_large_the_maximum'] ." (".Specific::BytesFormat($TEMP['#settings']['max_upload_size']).")";
            }
            $deliver = array(
                'status' => 403,
                'error' => $error
            );
        }
    }
} else if($one == 'send-video'){
    $error = false;
    $errors = array();
    if (empty($_POST['path'])) {
        $error = $TEMP['#word']['video_not_found_please_try_again'];
    } else if (empty($_POST['video-thumbnail_1']) || empty($_POST['video-thumbnail_2']) || empty($_POST['video-thumbnail_3']) || !in_array($_POST['path'], $_SESSION['uploads']['videos']) || !in_array($_POST['video-thumbnail_1'], $_SESSION['uploads']['images']) || !in_array($_POST['video-thumbnail_2'], $_SESSION['uploads']['images']) || !in_array($_POST['video-thumbnail_3'], $_SESSION['uploads']['images']) || !file_exists($_POST['path'])) {
        $error = $TEMP['#word']['error'];
    }
    if (empty($error)) {
        $title = Specific::Filter($_POST['title']);
        $tags = Specific::Filter($_POST['tags']);
        $category = Specific::Filter($_POST['category']);
        $privacy = Specific::Filter($_POST['privacy']);
        $adults_only = Specific::Filter($_POST['adults_only']);
        if (empty($title)){
            $errors[] = array('type' => 'empty', 'el' => 'title');
        }
        if(empty($tags)){
            $errors[] = array('type' => 'empty', 'el' => 'tags');
        }
        if (!in_array($category, array_keys($TEMP['#categories']))) {
            $errors[] = array('type' => 'error', 'el' => 'category');
        }
        if (!in_array($privacy, array(0, 1, 2))) {
            $errors[] = array('type' => 'error', 'el' => 'privacy');
        }
        if (!in_array($adults_only, array(0, 1))) {
            $errors[] = array('type' => 'error', 'el' => 'adults_only');
        }
        if(empty($errors)){
            $path = Specific::Filter($_POST['path']);
            $metadata = Specific::FfprobeData($path);
            $video_width = $metadata['width'];
            $video_id = Specific::RandomKey(12, 16);
            if ($dba->query('SELECT COUNT(*) FROM videos WHERE video_id = "'.$video_id.'"') > 0) {
                $video_id = Specific::RandomKey(12, 16);
            }
            $short_id = Specific::RandomKey(4, 8);
            if ($dba->query('SELECT COUNT(*) FROM videos WHERE short_id = "'.$short_id.'"') > 0) {
                $short_id = Specific::RandomKey(4, 8);
            }

            $thumbnail   = Specific::Filter($_POST['video-thumbnail']);
            $thumbnail_1 = Specific::Filter($_POST['video-thumbnail_1']);
            $thumbnail_2 = Specific::Filter($_POST['video-thumbnail_2']);
            $thumbnail_3 = Specific::Filter($_POST['video-thumbnail_3']);

            $approved = 1;
            if($TEMP['#settings']['approve_videos'] == 'on' && !Specific::Admin()){
                $approved = 0;
            }
            $thumbnail_draft = NULL;
            if(!empty($_POST['draft_thumb']) && strpos($thumbnail, 'video_thumbnail_')){
                $thumbnail_draft = Specific::Filter($_POST['draft_thumb']);
            }
            $description = NULL;
            if(!empty($_POST['description'])){
                $description = Specific::ComposeText($_POST['description']);
            }

            $insert = $dba->query('INSERT INTO videos (video_id, by_id, short_id, title, description, tags, duration, category, thumbnail, thumbnail_draft, thumbnail_1, thumbnail_2, thumbnail_3, converted, size, privacy, approved, adults_only, `time`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', $video_id, $TEMP['#user']['id'], $short_id, $title, $description, $tags, $metadata['duration'], $category, $thumbnail, $thumbnail_draft, $thumbnail_1, $thumbnail_2, $thumbnail_3, 0, $metadata['size'], $privacy, $approved, $adults_only, time())->insertId();
            
            if ($insert) {
                unset($_SESSION['uploads']);
                Specific::PostCreate(array(
                    'status' => 200,
                    'video_id' => $video_id
                ));

                if ($TEMP['#settings']['max_queue'] > 0) {
                    $process_queue = $dba->query('SELECT video_id FROM queue LIMIT '.$TEMP['#settings']['max_queue'])->fetchAll(FALSE);
                }
                
                if ((count($process_queue) < $TEMP['#settings']['max_queue'] && !in_array($insert, $process_queue)) || $TEMP['#settings']['max_queue'] == 0) {
                    if ($TEMP['#settings']['max_queue'] > 0) {
                        $dba->query('INSERT INTO queue (video_id, video_width, processing) VALUES ('.$insert.','.$video_width.',2)');
                    }
                    Specific::ConvertVideo(array(
                        'insert' => $insert,
                        'video_id' => $video_id,
                        'path' => $path,
                        'video_width' => $video_width
                    ));
                    require('./process-queue.php');
                }else{
                    $dba->query('INSERT INTO queue (video_id, video_width, processing) VALUES ('.$insert.','.$video_width.',0)');
                    $dba->query('UPDATE videos SET `path` = "'.$path.'" WHERE id = '.$insert);
                }
            } else {
                $deliver = array(
                    'status' => 400,
                    'message' => $TEMP['#word']['error']
                );
            }
        } else {
            $deliver = array(
                'status' => 400,
                'errors' => $errors
            );
        }
    } else {
        $deliver = array(
            'status' => 400,
            'message' => $error
        );
    }
} else if($one == 'upload-thumbnail') {
    if (!empty($_FILES['thumbnail']['tmp_name'])) {
        $file_info   = array(
            'file' => $_FILES['thumbnail']['tmp_name'],
            'size' => $_FILES['thumbnail']['size'],
            'name' => $_FILES['thumbnail']['name'],
            'type' => $_FILES['thumbnail']['type'],
            'from' => 'thumbnail',
            'crop' => array(
                'width' => 1076,
                'height' => 604
            )
        );
        $thumb_draft = Specific::Filter($_GET['draft_thumb']);
        if (!empty($thumb_draft) && strpos($thumb_draft, '_thumbnail')) {
            unlink($thumb_draft);
        }
        $file_data = Specific::UploadImage($file_info);
        if (!empty($file_data)) {
            $thumbnail = $file_data;
            $_SESSION['uploads']['images'][] = $thumbnail;
            $deliver = array(
                'status' => 200,
                'thumbnail' => $thumbnail,
                'media_thumbnail' => Specific::GetFile($thumbnail)
            );
        }
    }
} else if ($one == 'delete-thumbnail') {
    $deliver['status'] = 400;
    $thumb_draft = Specific::Filter($_POST['thumbnail-draft']);
    if(file_exists($thumb_draft) && !empty($thumb_draft) && in_array($thumb_draft, $_SESSION['uploads']['images'])){
        unlink($thumb_draft);
    }
} else if($one == 'delete-video'){
    $path = Specific::Filter($_POST['path']);
    $thumbnail = Specific::Filter($_POST['video-thumbnail']);
    $thumbnai_1 = Specific::Filter($_POST['video-thumbnail_1']);
    $thumbnai_2 = Specific::Filter($_POST['video-thumbnail_2']);
    $thumbnai_3 = Specific::Filter($_POST['video-thumbnail_3']);
    if (file_exists($path) && !empty($path) && in_array($path, $_SESSION['uploads']['videos'])) {
        unlink($path);
    }
    if (file_exists($thumbnail) && !empty($thumbnail) && in_array($thumbnail, $_SESSION['uploads']['images'])) {
        unlink($thumbnail);
    }
    if (file_exists($thumbnai_1) && !empty($thumbnai_1) && in_array($thumbnai_1, $_SESSION['uploads']['images'])) {
        unlink($thumbnai_1);
    }
    if (file_exists($thumbnai_2) && !empty($thumbnai_2) && in_array($thumbnai_2, $_SESSION['uploads']['images'])) {
        unlink($thumbnai_2);
    }
    if (file_exists($thumbnai_3) && !empty($thumbnai_3) && in_array($thumbnai_3, $_SESSION['uploads']['images'])) {
        unlink($thumbnai_3);
    }
    if(!empty($_SESSION['uploads'])){
        unset($_SESSION['uploads']);
    }
}
?>