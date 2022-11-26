<?php
if ($TEMP['#loggedin'] === false) {
    $deliver = array(
        'status' => 400,
        'error' => $TEMP['#word']['error']
    );
    echo json_encode($deliver);
    exit();
} else if (!empty($_POST['id'])) {
    $deliver['status'] = 400;
    $id = Specific::Filter($_POST['id']);
    $type = Specific::Filter($_POST['type']);
    $video = $dba->query('SELECT * FROM videos WHERE id = '.$id.' AND deleted = 0')->fetchArray();
    if (in_array($type, array('liked_video', 'disliked_video')) && !empty($video)) {
        if ($type == 'liked_video') {
            if ($dba->query('SELECT COUNT(*) FROM reactions WHERE type = 1 AND place = "video" AND by_id = '.$TEMP['#user']['id'].' AND to_id = '.$id)->fetchArray() > 0) {
                if($dba->query('UPDATE videos SET likes = '.($video['likes']-1).' WHERE id = '.$id)->returnStatus()){
                    if($dba->query('DELETE FROM reactions WHERE type = 1 AND place = "video" AND by_id = '.$TEMP['#user']['id'].' AND to_id = '.$id)->returnStatus()){
                        $deliver['status'] = 200;
                    }  
                }
            } else {
                if ($dba->query('SELECT COUNT(*) FROM reactions WHERE type = 2 AND place = "video" AND by_id = '.$TEMP['#user']['id'].' AND to_id = '.$id)->fetchArray() > 0) {
                    $dba->query('UPDATE videos SET dislikes = '.($video['dislikes']-1).' WHERE id = '.$id);
                    $dba->query('DELETE FROM reactions WHERE type = 2 AND place = "video" AND by_id = '.$TEMP['#user']['id'].' AND to_id = '.$id);
                }
                if($dba->query('UPDATE videos SET likes = '.($video['likes']+1).' WHERE id = '.$id)->returnStatus()){
                    if ($dba->query('INSERT INTO reactions (by_id, to_id, type, place, time) VALUES ('.$TEMP['#user']['id'].','.$id.',1, "video",'.time().')')->insertId()) {
                        $deliver['status'] = 200;
                    }
                }
                    
            }
        } else if ($type == 'disliked_video') {
            if ($dba->query('SELECT COUNT(*) FROM reactions WHERE type = 2 AND place = "video" AND by_id = '.$TEMP['#user']['id'].' AND to_id = '.$id)->fetchArray() > 0) {
                if($dba->query('UPDATE videos SET dislikes = '.($video['dislikes']-1).' WHERE id = '.$id)->returnStatus()){
                    if($dba->query('DELETE FROM reactions WHERE type = 2 AND place = "video" AND by_id = '.$TEMP['#user']['id'].' AND to_id = '.$id)->returnStatus()){
                        $deliver['status'] = 200;
                    }
                }
            } else {
                if ($dba->query('SELECT COUNT(*) FROM reactions WHERE type = 1 AND place = "video" AND by_id = '.$TEMP['#user']['id'].' AND to_id = '.$id)->fetchArray() > 0) {
                    $dba->query('UPDATE videos SET likes = '.($video['likes']-1).' WHERE id = '.$id);
                    $dba->query('DELETE FROM reactions WHERE type = 1 AND place = "video" AND by_id = '.$TEMP['#user']['id'].' AND to_id = '.$id);
                }
                if($dba->query('UPDATE videos SET dislikes = '.($video['dislikes']+1).' WHERE id = '.$id)->returnStatus()){
                    if ($dba->query('INSERT INTO reactions (by_id, to_id, type, place, time) VALUES ('.$TEMP['#user']['id'].','.$id.',2,"video",'.time().')')->insertId()) {
                        $deliver['status'] = 200;
                    }
                }
            }
        }

        Specific::SendNotification(array(
            'from_id' => $TEMP['#user']['id'],
            'to_id' => $video['by_id'],
            'type' => "'$type'",
            'notify_key' => "'{$video['video_id']}'",
            'time' => time()
        ));
    }
}
?>