<?php
if (!empty($_POST['video_id']) && is_numeric($_POST['video_id']) && (!empty($_POST['comment']) && is_numeric($_POST['comment']) || !empty($_POST['reply']) && is_numeric($_POST['reply']))) {
	$comment = '';
	$replies = '';
    $TEMP['#video_owner'] = false;
    $id = Specific::Filter($_POST['video_id']);
    $video = Specific::Video($id);

    if(!empty($_POST['reply'])){
		$reply = $dba->query('SELECT * FROM replies WHERE id = '.Specific::Filter($_POST['reply']).' AND by_id NOT IN ('.$TEMP['#blocked_users'].')')->fetchArray();
	}
	$last_id  = !empty($_POST['comment']) ? Specific::Filter($_POST['comment']) : $reply['comment_id'];
    if ($TEMP['#loggedin'] === true) {
    	$TEMP['#video_owner'] = ($dba->query('SELECT COUNT(*) FROM videos WHERE id = '.$id.' AND by_id = '.$TEMP['#user']['id'])->fetchArray() > 0);
    }
    $comment = $dba->query('SELECT * FROM comments WHERE video_id = '.$id.' AND id = '.$last_id.' AND by_id NOT IN ('.$TEMP['#blocked_users'].') ORDER BY id DESC')->fetchArray();

    if ($comment) {
	    $TEMP['#is_comment_owner'] = false;
	    $TEMP['#header_answer_badge'] = false;
        $TEMP['#cont_replies'] 	  = $dba->query('SELECT * FROM replies WHERE comment_id = '.$comment['id'].' AND by_id NOT IN ('.$TEMP['#blocked_users'].')')->fetchAll();
	    $comm_id = $comment['id'];
        $TEMP['!owner_username']   = $video['by_id'] == $comment['by_id'] ? ' owner-username' : '';
	    $TEMP['#header_comment_badge'] = !empty($_POST['comment']) && is_numeric($_POST['comment']) ? true : false;
	    $TEMP['#pin_comment'] = $comment['pinned'] == 1 ? true : false;

	    if(!empty($_POST['reply']) && is_numeric($_POST['reply'])){
	        $like_active_reply = '';
		    $dislike_active_reply = '';
		    $like_active_comment = '';
		    $dislike_active_comment = '';
        	$TEMP['#is_reply_owner'] = false;
            $TEMP['!owner_username']   = $video['by_id'] == $reply['by_id'] ? ' owner-username' : '';
		    $TEMP['#header_answer_badge'] = true;
		    $comm_id = $reply['comment_id'];
		            
		   	if ($TEMP['#loggedin'] === true) {
		        if($dba->query('SELECT COUNT(*) FROM reactions WHERE type = 1 AND to_id = '.$reply['id'].' AND place = "reply" AND by_id = '.$TEMP['#user']['id'])->fetchArray() > 0){
		        	$like_active_reply = ' active';
		        }
		        if($dba->query('SELECT COUNT(*) FROM reactions WHERE type = 2 AND to_id = '.$reply['id'].' AND place = "reply" AND by_id ='.$TEMP['#user']['id'])->fetchArray() > 0){
		        	$dislike_active_reply = ' active';
		        }

	            if ($TEMP['#user']['id'] == $reply['by_id'] || $TEMP['#video_owner'] == true) {
		            $TEMP['#is_reply_owner'] = true;
		        }
		    }

		    $likes = $dba->query('SELECT COUNT(*) FROM reactions WHERE type = 1 AND to_id = '.$reply['id'].' AND place = "reply"')->fetchArray();
		    $dislikes = $dba->query('SELECT COUNT(*) FROM reactions WHERE type = 2 AND to_id = '.$reply['id'].' AND place = "reply"')->fetchArray();

		    $reply_likes = '';
		    if($likes >= 1000000){
		    	$reply_likes = Specific::Number($likes);
		    } else if($likes > 0) {
		    	$reply_likes = number_format($likes);
		    }

		    $reply_dislikes = '';
		    if($dislikes >= 1000000){
		    	$reply_dislikes = Specific::Number($dislikes);
		    } else if($dislikes > 0){
		    	$reply_dislikes = number_format($dislikes);
		    }

	        $TEMP['!id'] = $reply['id'];
	        $TEMP['!text'] = Specific::GetComposeText($reply['text']);
		    $TEMP['!time'] = Specific::DateString($reply['time']);
		    $TEMP['!data'] = Specific::Data($reply['by_id']);
	        $TEMP['!reply_url'] = $video['url'].'&rl='.$reply['id'];
	        $TEMP['!comm_id'] = $reply['comment_id'];
	       	$TEMP['!norm_likes'] = $likes;
    		$TEMP['!norm_dislikes'] = $dislikes;
		    $TEMP['!likes'] = $reply_likes;
	        $TEMP['!dislikes'] = $reply_dislikes;
		   	$TEMP['!liked']  = $like_active_reply;
		    $TEMP['!disliked']  = $dislike_active_reply;
		    $TEMP['!isliked'] = !empty($like_active_reply) ? 'true' : 'false';
    		$TEMP['!isdisliked'] = !empty($dislike_active_reply) ? 'true' : 'false';
			$TEMP['!like_active_word'] = !empty($like_active_reply) ? $TEMP['#word']['i_dont_like_it_enymore'] : $TEMP['#word']['i_like_it'];
			$TEMP['!dis_active_word'] = !empty($dislike_active_reply) ? $TEMP['#word']['remove_i_dont_like'] : $TEMP['#word']['i_dont_like'];
	   
	        $replies .= Specific::Maket('watch/includes/replies');
	    }

	    if ($TEMP['#loggedin'] === true) {
	    	if($dba->query('SELECT COUNT(*) FROM reactions WHERE type = 1 AND to_id = '.$comment['id'].' AND place = "commentary" AND by_id = '.$TEMP['#user']['id'])->fetchArray() > 0){
	    		$like_active_comment = ' active';
	    	}
	    	if($dba->query('SELECT COUNT(*) FROM reactions WHERE type = 2 AND to_id = '.$comment['id'].' AND place = "commentary" AND by_id = '.$TEMP['#user']['id'])->fetchArray() > 0){
	    		$dislike_active_comment = ' active';
	    	}

	        if ($TEMP['#user']['id'] == $comment['by_id'] || $TEMP['#video_owner'] == true) {
	            $TEMP['#is_comment_owner'] = true;
	        }
	    }

	    $likes = $dba->query('SELECT COUNT(*) FROM reactions WHERE type = 1 AND to_id = '.$comment['id'].' AND place = "commentary"')->fetchArray();
        $dislikes = $dba->query('SELECT COUNT(*) FROM reactions WHERE type = 2 AND to_id = '.$comment['id'].' AND place = "commentary"')->fetchArray();

        if($likes >= 1000000){
            $comm_likes = Specific::Number($likes);
        } else if($likes > 0) {
            $comm_likes = number_format($likes);
        } else {
            $comm_likes = '';
        }

        if($dislikes >= 1000000){
            $comm_dislikes = Specific::Number($dislikes);
        } else if($dislikes > 0){
            $comm_dislikes = number_format($dislikes);
        } else {
            $comm_dislikes = '';
        }

	    $TEMP['!id'] = $comment['id'];
	    $TEMP['!text'] = Specific::GetComposeText($comment['text']);
	    $TEMP['!time'] = Specific::DateString($comment['time']);
	    $TEMP['!data'] = Specific::Data($comment['by_id']);
	   	$TEMP['!comment_url'] = $video['url'].'&cl='.$comment['id'];
	    $TEMP['!username_cmt'] = $video['data']['username'];
        $TEMP['!all_r'] = count($TEMP['#cont_replies']);
	    $TEMP['!norm_likes'] = $likes;
        $TEMP['!norm_dislikes'] = $dislikes;
        $TEMP['!likes'] = $comm_likes;
        $TEMP['!dislikes'] = $comm_dislikes;
	    $TEMP['!liked' ] = $like_active_comment;
	    $TEMP['!disliked' ] = $dislike_active_comment;
        $TEMP['!isliked'] = !empty($like_active_comment) ? 'true' : 'false';
        $TEMP['!isdisliked'] = !empty($dislike_active_comment) ? 'true' : 'false';
		$TEMP['!like_active_word'] = !empty($like_active_comment) ? $TEMP['#word']['i_dont_like_it_enymore'] : $TEMP['#word']['i_like_it'];
		$TEMP['!dis_active_word'] = !empty($dislike_active_comment) ? $TEMP['#word']['remove_i_dont_like'] : $TEMP['#word']['i_dont_like'];

	    $comment = Specific::Maket('watch/includes/comments');
	    
	    $deliver = array(
	    	'status' => 200,
	    	'pin_comment' => $TEMP['#pin_comment'],
	    	'comment' => $comment,
	    	'comm_id' => $comm_id,
	    	'reply' => $replies
	    );
    } else {
    	$deliver['status'] = 400;
    }
}
?>