<?php
if ($TEMP['#loggedin'] === false) {
    $deliver = array(
        'status' => 400,
        'error' => $TEMP['#word']['error']
    );
    echo json_encode($deliver);
    exit();
} else if($one == 'add-comment'){
    $deliver['status'] = 400;
    if (!empty($_POST['video_id']) && !empty($_POST['text'])) {
        $video_id = Specific::Filter($_POST['video_id']);
        $video = Specific::Video($video_id);
        if (!empty($video)) {
            $comment = $dba->query('INSERT INTO comments (by_id, video_id, `text`, `time`) VALUES ('.$TEMP['#user']['id'].','.$video_id.',"'.Specific::ComposeText($_POST['text'], $video['duration']).'",'.time().')')->insertId();
            if ($comment) {
                $comment = $dba->query('SELECT * FROM comments WHERE id = '.$comment)->fetchArray();
                $user_data = Specific::Data($comment['by_id']);
                $TEMP['#is_comment_owner'] = true;
                $TEMP['#is_verified']      = ($user_data['verified'] == 1) ? true : false;
                $TEMP['#video_owner']    = $video['by_id'] == $TEMP['#user']['id'] ? true : false;
                $TEMP['!owner_username']   = $video['by_id'] == $comment['by_id'] ? ' owner-username' : '';

                $TEMP['!id'] = $comment['id'];
                $TEMP['!text'] = Specific::GetComposeText($comment['text']);
                $TEMP['!time'] = Specific::DateString($comment['time']);
                $TEMP['!data'] = $user_data;
                $TEMP['!comment_url'] = $video['url'].'&cl='.$comment['id'];
                $TEMP['!like_active_word'] = $TEMP['#word']['i_like_it'];
                $TEMP['!dis_active_word'] = $TEMP['#word']['i_dont_like'];

                $deliver = array(
                    'status' => 200,
                    'html' => Specific::Maket('watch/includes/comments')
                );

                Specific::SendNotification(array(
                    'from_id' => $TEMP['#user']['id'],
                    'to_id' => $video['by_id'],
                    'type' => "'commentary'",
                    'notify_key' => "'{$video['video_id']}&cl={$comment['id']}'",
                    'time' => time()
                ));
            }
        }
    }
} else if($one == 'add-reply'){
    $deliver['status'] = 400;
    if (!empty($_POST['id']) && is_numeric($_POST['id']) && !empty($_POST['video_id']) && is_numeric($_POST['video_id']) && !empty($_POST['text'])) {
        $video_id = Specific::Filter($_POST['video_id']);
        $comm_id  = Specific::Filter($_POST['id']);
        $reply_id = (!empty($_POST['reply']) && is_numeric($_POST['reply'])) ? $_POST['reply'] : 0;
        $video_data = Specific::Video($video_id);
        $comm_data  = $dba->query('SELECT * FROM comments WHERE id = '.$comm_id)->fetchArray();
        if (!empty($video_data) && !empty($comm_data)) {

            $insert = $dba->query('INSERT INTO replies (by_id, comment_id, video_id, `text`, `time`) VALUES ('.$TEMP['#user']['id'].','.$comm_id.','.$video_id.',"'.Specific::ComposeText($_POST['text'], $video['duration']).'",'.time().')')->insertId();
            if ($insert) {
                $reply = $dba->query('SELECT * FROM replies WHERE id = '.$insert)->fetchArray();
                $TEMP['#is_reply_owner'] = true;
                $TEMP['!owner_username']   = $video_data['by_id'] == $reply['by_id'] ? ' owner-username' : '';

                $TEMP['!id'] = $reply['id'];
                $TEMP['!text'] = Specific::GetComposeText($reply['text']);
                $TEMP['!time'] = Specific::DateString($reply['time']);
                $TEMP['!data'] = Specific::Data($reply['by_id']);
                $TEMP['!comm_id'] = $comm_id;
                $TEMP['!reply_url'] = $video_data['url'].'&rl='.$reply['id'];
                $TEMP['!like_active_word'] = $TEMP['#word']['i_like_it'];
                $TEMP['!dis_active_word'] = $TEMP['#word']['i_dont_like'];

                $deliver = array(
                    'status' => 200,
                    'html' => Specific::Maket('watch/includes/replies')
                );
                Specific::SendNotification(array(
                    'from_id' => $TEMP['#user']['id'],
                    'to_id' => $insert,
                    'type' => "'reply'",
                    'notify_key' => "'{$video_data['video_id']}&rl={$reply['id']}'",
                    'time' => time()
                ));
            }
        }
    }
} else if($one == 'pin-comment'){
    $deliver['status'] = 400;
    if (!empty($_POST['id']) && is_numeric($_POST['id'])) {
        $id = Specific::Filter($_POST['id']);
        $comment = $dba->query('SELECT * FROM comments WHERE id = '.$id)->fetchArray();
        if (!empty($comment)) {
            $dba->query('UPDATE comments SET pinned = 0 WHERE video_id = '.$comment['video_id']);
            $dba->query('UPDATE comments SET pinned = '.($comment['pinned'] == 0 ? 1 : 0).' WHERE id = '.$id);
            $deliver['status'] = ($comment['pinned'] == 0) ? 200 : 304;

            $content = 'includes/comments';
            if($comment['pinned'] == 0){
                $content = 'includes/pinned-comments';
            }

            $like_active_class  = '';
            $dislike_active_class  = '';
            $TEMP['#video_owner'] = false;
            $TEMP['#is_comment_owner'] = false;
            $video = Specific::Video($comment['video_id']);
            $user_data = Specific::Data($comment['by_id']);
            $TEMP['#cont_replies']     = $dba->query('SELECT * FROM replies WHERE comment_id = '.$comment['id'].' AND by_id NOT IN ('.$TEMP['#blocked_users'].')')->fetchAll();
            $TEMP['#is_verified']      = ($user_data['verified'] == 1) ? true : false;
            $TEMP['!owner_username']   = $video['by_id'] == $comment['by_id'] ? ' owner-username' : '';

            if ($TEMP['#loggedin'] === true) {
                $TEMP['#video_owner'] = $dba->query('SELECT COUNT(*) FROM videos WHERE id = '.$comment['video_id'].' AND by_id = '.$TEMP['#user']['id'])->fetchArray() > 0;
                if($dba->query('SELECT COUNT(*) FROM reactions WHERE type = 1 AND to_id = '.$comment['id'].' AND place = "commentary" AND by_id = '.$TEMP['#user']['id'])->fetchArray() > 0){
                    $like_active_class = ' active';
                }
                if($dba->query('SELECT COUNT(*) FROM reactions WHERE type = 2 AND to_id = '.$comment['id'].' AND place = "commentary" AND by_id = '.$TEMP['#user']['id'])->fetchArray() > 0){
                    $dislike_active_class  = ' active';
                }
                if ($TEMP['#user']['id'] == $comment['by_id'] || $TEMP['#video_owner'] == true) {
                    $TEMP['#is_comment_owner'] = true;
                }
            }

            $likes = $dba->query('SELECT COUNT(*) FROM reactions WHERE type = 1 AND to_id = '.$comment['id'].' AND place = "commentary"')->fetchArray();

            $dislikes = $dba->query('SELECT COUNT(*) FROM reactions WHERE type = 2 AND to_id = '.$comment['id'].' AND place = "commentary"')->fetchArray();

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

            $TEMP['!id'] = $comment['id'];
            $TEMP['!text'] = Specific::GetComposeText($comment['text']);
            $TEMP['!time'] = Specific::DateString($comment['time']);
            $TEMP['!data'] = $user_data;
            $TEMP['!username_cmt'] = $video['data']['username'];
            $TEMP['!comment_url'] = $video['url'].'&cl='.$comment['id'];
            $TEMP['!all_r'] = count($TEMP['#cont_replies']);
            $TEMP['!norm_likes'] = $likes;
            $TEMP['!norm_dislikes'] = $dislikes;
            $TEMP['!likes'] = $comm_likes;
            $TEMP['!dislikes'] = $comm_dislikes;
            $TEMP['!liked' ] = $like_active_class;
            $TEMP['!disliked' ] = $dislike_active_class;
            $TEMP['!isliked'] = !empty($like_active_class) ? 'true' : 'false';
            $TEMP['!isdisliked'] = !empty($dislike_active_class) ? 'true' : 'false';
            $TEMP['!like_active_word'] = !empty($like_active_class) ? $TEMP['#word']['i_dont_like_it_enymore'] : $TEMP['#word']['i_like_it'];
            $TEMP['!dis_active_word'] = !empty($dislike_active_class) ? $TEMP['#word']['remove_i_dont_like'] : $TEMP['#word']['i_dont_like'];


            $deliver['html'] = Specific::Maket("watch/$content");
        }
    }
} else if($one == 'reply-reaction'){
    $deliver['status'] = 400;
    if (!empty($_POST['id'])) {
        $id = Specific::Filter($_POST['id']);
        $type = Specific::Filter($_POST['type']);
        $reply = $dba->query('SELECT * FROM replies WHERE id = '.$id)->fetchArray();
        $video = $dba->query('SELECT * FROM videos WHERE id = '.$reply['video_id'].' AND deleted = 0')->fetchArray();
        if (in_array($type, array('like', 'dislike')) && !empty($reply) && !empty($video)) {
            if ($type == 'like') {
                if ($dba->query('SELECT COUNT(*) FROM reactions WHERE type = 1 AND by_id = '.$TEMP['#user']['id'].' AND to_id = '.$id.' AND place = "reply"')->fetchArray() > 0) {
                    $dba->query('DELETE FROM reactions WHERE type = 1 AND by_id = '.$TEMP['#user']['id'].' AND to_id = '.$id.' AND place = "reply"');
                    $deliver['status'] = 200;
                } else {
                    $dba->query('DELETE FROM reactions WHERE type = 2 AND by_id = '.$TEMP['#user']['id'].' AND to_id = '.$id.' AND place = "reply"');
                    if ($dba->query('INSERT INTO reactions (by_id, to_id, type, place, `time`) VALUES ('.$TEMP['#user']['id'].','.$id.',1,"reply",'.time().')')->insertId()) {
                        $deliver['status'] = 200;
                    }
                }
            } else if ($type == 'dislike') {
                if ($dba->query('SELECT COUNT(*) FROM reactions WHERE type = 2 AND by_id = '.$TEMP['#user']['id'].' AND to_id = '.$id.' AND place = "reply"')->fetchArray() > 0) {
                    $dba->query('DELETE FROM reactions WHERE type = 2 AND by_id = '.$TEMP['#user']['id'].' AND to_id = '.$id.' AND place = "reply"');
                    $deliver['status'] = 200;
                } else {
                    $dba->query('DELETE FROM reactions WHERE type = 1 AND by_id = '.$TEMP['#user']['id'].' AND to_id = '.$id.' AND place = "reply"');
                    if ($dba->query('INSERT INTO reactions (by_id, to_id, type, place, `time`) VALUES ('.$TEMP['#user']['id'].','.$id.',2,"reply",'.time().')')->insertId()) {
                        $deliver['status'] = 200;
                    }
                }
            }
            Specific::SendNotification(array(
                'from_id' => $TEMP['#user']['id'],
                'to_id' => $reply['by_id'],
                'type' => "'$type'",
                'notify_key' => "'{$video['video_id']}&rl=$id'",
                'time' => time()
            ));
        }
    }
} else if($one == 'comment-reaction'){
    $deliver['status'] = 400;
    if (!empty($_POST['id'])) {
        $id = Specific::Filter($_POST['id']);
        $type = Specific::Filter($_POST['type']);
        $comment = $dba->query('SELECT * FROM comments WHERE id = '.$id)->fetchArray();
        $video = $dba->query('SELECT * FROM videos WHERE id = '.$comment['video_id'].' AND deleted = 0')->fetchArray();
        if (in_array($type, array('like', 'dislike')) && !empty($comment) && !empty($video)) {
            if ($type == 'like') {
                if ($dba->query('SELECT COUNT(*) FROM reactions WHERE type = 1 AND by_id = '.$TEMP['#user']['id'].' AND to_id = '.$id.' AND place = "commentary"')->fetchArray() > 0) {
                    $dba->query('DELETE FROM reactions WHERE type = 1 AND by_id = '.$TEMP['#user']['id'].' AND to_id = '.$id.' AND place = "commentary"');
                    $deliver['status'] = 200;
                } else {
                    if ($dba->query('SELECT COUNT(*) FROM reactions WHERE type = 2 AND by_id = '.$TEMP['#user']['id'].' AND to_id = '.$id.' AND place = "commentary"')->fetchArray() > 0) {
                        $dba->query('DELETE FROM reactions WHERE type = 2 AND by_id = '.$TEMP['#user']['id'].' AND to_id = '.$id.' AND place = "commentary"');
                    }
                    if ($dba->query('INSERT INTO reactions (by_id, to_id, type, place, `time`) VALUES ('.$TEMP['#user']['id'].','.$id.',1,"commentary",'.time().')')->insertId()) {
                        $deliver['status'] = 200;
                    }
                }
            } else if ($type == 'dislike') {
                if ($dba->query('SELECT COUNT(*) FROM reactions WHERE type = 2 AND by_id = '.$TEMP['#user']['id'].' AND to_id = '.$id.' AND place = "commentary"')->fetchArray() > 0) {
                    $dba->query('DELETE FROM reactions WHERE type = 2 AND by_id = '.$TEMP['#user']['id'].' AND to_id = '.$id.' AND place = "commentary"');
                    $deliver['status'] = 200;
                } else {
                    if ($dba->query('SELECT COUNT(*) FROM reactions WHERE type = 1 AND by_id = '.$TEMP['#user']['id'].' AND to_id = '.$id.' AND place = "commentary"')->fetchArray() > 0) {
                        $dba->query('DELETE FROM reactions WHERE type = 1 AND by_id = '.$TEMP['#user']['id'].' AND to_id = '.$id.' AND place = "commentary"');
                    }
                    if ($dba->query('INSERT INTO reactions (by_id, to_id, type, place, `time`) VALUES ('.$TEMP['#user']['id'].','.$id.',2,"commentary",'.time().')')->insertId()) {
                        $deliver['status'] = 200;
                    }
                }
            }
            Specific::SendNotification(array(
                'from_id' => $TEMP['#user']['id'],
                'to_id' => $comment['by_id'],
                'type' => "'$type'",
                'notify_key' => "'{$video['video_id']}&cl=$id'",
                'time' => time()
            ));
        }
    }
}
?>