<?php
if (!empty($_POST['video_id']) && is_numeric($_POST['video_id']) && (!empty($_POST['this_comm']) && is_numeric($_POST['this_comm']) || !empty($_POST['reply']) && is_numeric($_POST['reply']))) {
    $this_comm  = !empty($_POST['reply']) ? Specific::Filter($_POST['reply']) : Specific::Filter($_POST['this_comm']);
    $video_id = Specific::Filter($_POST['video_id']);

    $query = '';
    $html = '';

   	if (!empty($_POST['replys'])) {
    	$rep_ids = Specific::Filter($_POST['replys']);
    	$query = ' AND id NOT IN ('.$rep_ids.')';
    }

	if(!empty($_POST['reply'])){
		$query .= ' AND id = '.$this_comm;
	}else{
		$query .= ' AND comment_id = '.$this_comm;
	}
	$replies = $dba->query('SELECT * FROM replies WHERE video_id = '.$video_id.$query.' AND by_id NOT IN ('.$TEMP['#blocked_users'].') ORDER BY time DESC LIMIT '.$TEMP['#settings']['comments_load_limit'])->fetchAll();

	if (count($replies) > 0) {
	    foreach ($replies as $value) {
	    	$like_active_class = '';
		    $dislike_active_class  = '';
		    $TEMP['#video_owner'] = false;
		    $TEMP['#is_reply_owner'] = false;
	    	$video = Specific::Video($video_id);

            $TEMP['!owner_username']   = $video['by_id'] == $value['by_id'] ? ' owner-username' : '';
		            
		    if ($TEMP['#loggedin'] === true) {
		    	$TEMP['#video_owner'] = ($dba->query('SELECT COUNT(*) FROM videos WHERE id = '.$video_id.' AND by_id = '.$TEMP['#user']['id'])->fetchArray() > 0);

		    	if($dba->query('SELECT COUNT(*) FROM reactions WHERE type = 1 AND to_id = '.$value['id'].' AND place = "reply" AND by_id = '.$TEMP['#user']['id'])->fetchArray() > 0){
		    		$like_active_class = ' active';
		    	}
		    	if($dba->query('SELECT COUNT(*) FROM reactions WHERE type = 2 AND to_id = '.$value['id'].' AND place = "reply" AND by_id = '.$TEMP['#user']['id'])->fetchArray() > 0){
		    		$dislike_active_class  = ' active';
		    	}
		        if ($TEMP['#user']['id'] == $value['by_id'] || $TEMP['#video_owner'] == true) {
		            $TEMP['#is_reply_owner'] = true;
		        }
		    }

		    $likes = $dba->query('SELECT COUNT(*) FROM reactions WHERE type = 1 AND to_id = '.$value['id'].' AND place = "reply"')->fetchArray();

		    $dislikes = $dba->query('SELECT COUNT(*) FROM reactions WHERE type = 2 AND to_id = '.$value['id'].' AND place = "reply"')->fetchArray();

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

		    $TEMP['!id'] = $value['id'];
		    $TEMP['!text'] = Specific::GetComposeText($value['text']);
		    $TEMP['!time'] = Specific::DateString($value['time']);
		    $TEMP['!data'] = Specific::Data($value['by_id']);
		    $TEMP['!comm_id'] = $value['comment_id'];
		    $TEMP['!reply_url'] = $video['url'].'&rl='.$value['id'];
		    $TEMP['!norm_likes'] = $likes;
    		$TEMP['!norm_dislikes'] = $dislikes;
		    $TEMP['!likes'] = $reply_likes;
	        $TEMP['!dislikes'] = $reply_dislikes;
		   	$TEMP['!liked']  = $like_active_class;
		    $TEMP['!disliked']  = $dislike_active_class;
		    $TEMP['!isliked'] = !empty($like_active_class) ? 'true' : 'false';
    		$TEMP['!isdisliked'] = !empty($dislike_active_class) ? 'true' : 'false';
			$TEMP['!like_active_word'] = !empty($like_active_class) ? $TEMP['#word']['i_dont_like_it_enymore'] : $TEMP['#word']['i_like_it'];
			$TEMP['!dis_active_word'] = !empty($dislike_active_class) ? $TEMP['#word']['remove_i_dont_like'] : $TEMP['#word']['i_dont_like'];
	   
		    $html .= Specific::Maket('watch/includes/replies');
		}
		Specific::DestroyMaket();
		$deliver = array(
			'status' => 200,
			'html' => $html,
			'count_r' => count($replies)
		);
	} else {
	    $deliver['status'] = 400;
	}
}
?>