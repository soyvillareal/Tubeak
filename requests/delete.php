<?php
if ($TEMP['#loggedin'] === false) {
    $deliver = array(
        'status' => 400,
        'error' => $TEMP['#word']['error']
    );
    echo json_encode($deliver);
    exit();
} else if($one == 'video'){
    $id = Specific::Filter($_POST['id']);
    $video = $dba->query('SELECT * FROM videos WHERE id = "'.$id.'" AND deleted = 0')->fetchArray();
    if ((Specific::Admin() == true || $dba->query('SELECT COUNT(*) FROM videos WHERE id = "'.$id.'" AND by_id = '.$TEMP['#user']['id'].' AND deleted = 0')->fetchArray() > 0) && !empty($video)) {
        $delete = Specific::DeleteVideo($id);
        if($delete){
            $deliver['status'] = 200;
        }
    }
} else if ($one == 'reply'){
    $id = Specific::Filter($_POST['id']);
    if(!empty($id)){
        $reply = $dba->query('SELECT * FROM replies WHERE id = '.$id)->fetchArray();
        if (!empty($reply)) {
            $dba->query('SELECT * FROM replies WHERE id = '.$id);
            $replies = $dba->numRows();
            $comment_id = $dba->fetchArray()['comment_id'];

            if ($dba->query('SELECT COUNT(*) FROM videos WHERE id = '.$reply['video_id'].' AND by_id = '.$TEMP['#user']['id'])->fetchArray() > 0 || $TEMP['#user']['id'] == $reply['by_id']) {
                $dba->query('DELETE FROM reactions WHERE to_id = '.$id.' AND place = "reply"');
                if ($dba->query('DELETE FROM replies WHERE id = '.$id)->returnStatus()) {
                    $deliver = array(
                        'status' => 200,
                        'message' => $TEMP['#word']['reply_deleted'],
                        'replies' => $replies,
                        'comment_id' => $comment_id
                    );
                }
            }
        }
    }
} else if ($one == 'comment'){
    $id = Specific::Filter($_POST['id']);
    if(!empty($id)){
        $comment = $dba->query('SELECT * FROM comments WHERE id = '.$id)->fetchArray();
        if (!empty($comment)) {
            if ($dba->query('SELECT COUNT(*) FROM videos WHERE id = '.$comment['video_id'].' AND by_id = '.$TEMP['#user']['id'])->fetchArray() > 0 || $TEMP['#user']['id'] == $comment['by_id']) {
                if ($dba->query('DELETE FROM comments WHERE id = '.$id)->returnStatus()) {
                    $replies = $dba->query('SELECT * FROM replies WHERE comment_id = '.$id)->fetchAll();
                    foreach ($replies as $value) {
                        $dba->query('DELETE FROM reactions WHERE to_id = '.$value['id'].' AND place = "reply"');
                    }
                    $dba->query('DELETE FROM reactions WHERE to_id = '.$id.' AND place = "commentary"');
                    $dba->query('DELETE FROM replies WHERE comment_id = '.$id);

                    $deliver = array(
                        'status' => 200,
                        'message' => $TEMP['#word']['comment_deleted']
                    );
                }
            }
        }
    }
} else if ($one == 'history'){
    $id = Specific::Filter($_POST['id']);
    if(!empty($id)){
        if ($dba->query('DELETE FROM history WHERE by_id = '.$TEMP['#user']['id'].' AND id = '.$id)->returnStatus()) {
            $deliver['status'] = 200;
        }
    }
}  else if ($one == 'watermark'){
    $by_id = Specific::Filter($_POST['by_id']);
    if (Specific::IsOwner($by_id) && !empty($by_id)) {
        unlink(Specific::Data($by_id)['ex_watermark']);
        if($dba->query('UPDATE users SET watermark = NULL WHERE id = '.$by_id)->returnStatus()){
           $deliver = array(
                'status' => 200,
                'message' => $TEMP['#word']['your_watermark_was_removed']
            ); 
        }
    }
} else if ($one == 'session'){
    $id = Specific::Filter($_POST['id']);
    if (!empty($id)) {
        $sessions = $dba->query('SELECT * FROM sessions WHERE id = '.$id)->fetchArray();
        if (!empty($sessions)) {
            $deliver['reload'] = false;
            if (($sessions['by_id'] == $TEMP['#user']['id']) || Specific::Admin()) {
                if ((!empty($_SESSION['session_id']) && $_SESSION['session_id'] == $sessions['session_id']) || (!empty($_COOKIE['session_id']) && $_COOKIE['session_id'] == $sessions['session_id'])) {
                    setcookie('session_id', null, -1, '/');
                    session_destroy();
                    $deliver['reload'] = true;
                }

                if ($dba->query('DELETE FROM sessions WHERE id = '.$id)->returnStatus()) {
                    $deliver['status'] = 200;
                }
            }
        }
    }
}
?>