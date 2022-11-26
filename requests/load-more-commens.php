<?php
if (!empty($_POST['video_id']) && is_numeric($_POST['video_id'])) {
    $html = '';
    $query = '';
    $sort = 2;
    $last_id  = Specific::Filter($_POST['last_id']);
    $video_id = Specific::Filter($_POST['video_id']);
    $comment_ids = Specific::Filter($_POST['comment_ids']);

    $video = Specific::Video($video_id);
    if(!empty($_POST['sort_by']) && is_numeric($_POST['sort_by'])){
        $sort = Specific::Filter($_POST['sort_by']);
    }

    $query = ' AND pinned != 1 AND video_id = '.$video_id;
    if(!empty($comment_ids)){
        $query .= ' AND id NOT IN ('.$comment_ids.')';
    }
    
    if ($sort == 1) {
        $comms_order = $dba->query('SELECT to_id, COUNT(*) AS likes FROM reactions c WHERE type = 1 AND place = "commentary" AND (SELECT id FROM comments WHERE by_id NOT IN ('.$TEMP['#blocked_users'].') AND id = c.to_id'.$query.') = to_id GROUP BY to_id ORDER BY likes DESC LIMIT '.$TEMP['#settings']['comments_load_limit'])->fetchAll();
        if(!empty($comms_order)){
            foreach ($comms_order as $value) {
                $comments[] = $dba->query('SELECT * FROM comments WHERE id = '.$value['to_id'])->fetchArray();
            }
        } else {
            $comments = $dba->query('SELECT * FROM comments WHERE pinned != 1 AND video_id = '.$video_id.$query.' ORDER BY id DESC LIMIT '.$TEMP['#settings']['comments_load_limit'])->fetchAll();
        }
    } else {
    	if(!empty($_POST['last_id']) && is_numeric($_POST['last_id'])){
    		$query .= ' AND id < '.$last_id;
    	}
        $comments = $dba->query('SELECT * FROM comments WHERE by_id NOT IN ('.$TEMP['#blocked_users'].') '.$query.' ORDER BY id DESC LIMIT '.$TEMP['#settings']['comments_load_limit'])->fetchAll();
    }
    
    if (count($comments) > 0) {
        foreach ($comments as $value) {
            $like_active_class = '';
            $dislike_active_class = '';
            $TEMP['#video_owner'] = false;
		    $TEMP['#is_comment_owner'] = false;
		    $TEMP['#cont_replies'] 	  = $dba->query('SELECT * FROM replies WHERE comment_id = '.$value['id'].' AND by_id NOT IN ('.$TEMP['#blocked_users'].')')->fetchAll();

            $TEMP['!owner_username']   = $video['by_id'] == $value['by_id'] ? ' owner-username' : '';
		    if ($TEMP['#loggedin'] === true) {
                $TEMP['#video_owner'] = ($dba->query('SELECT COUNT(*) FROM videos WHERE id = '.$video_id.' AND by_id = '.$TEMP['#user']['id'])->fetchArray() > 0);

                if($dba->query('SELECT COUNT(*) FROM reactions WHERE type = 1 AND to_id = '.$value['id'].' AND place = "commentary" AND by_id = '.$TEMP['#user']['id'])->fetchArray() > 0){
                    $like_active_class = ' active';
                }
                if($dba->query('SELECT COUNT(*) FROM reactions WHERE type = 2 AND to_id = '.$value['id'].' AND place = "commentary" AND by_id = '.$TEMP['#user']['id'])->fetchArray() > 0){
                    $dislike_active_class = ' active';
                }
		        if ($TEMP['#user']['id'] == $value['by_id'] || $TEMP['#video_owner'] == true) {
		            $TEMP['#is_comment_owner'] = true;
		        }
		    }

            $likes = $dba->query('SELECT COUNT(*) FROM reactions WHERE type = 1 AND to_id = '.$value['id'].' AND place = "commentary"')->fetchArray();

            $dislikes = $dba->query('SELECT COUNT(*) FROM reactions WHERE type = 2 AND to_id = '.$value['id'].' AND place = "commentary"')->fetchArray();

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

		    $TEMP['!id'] = $value['id'];
		    $TEMP['!text'] = Specific::GetComposeText($value['text']);
		    $TEMP['!time'] = Specific::DateString($value['time']);
		    $TEMP['!data'] = Specific::Data($value['by_id']);
            $TEMP['!comment_url'] = $video['url'].'&cl='.$value['id'];
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

		    $html .= Specific::Maket('watch/includes/comments');
	    }
        Specific::DestroyMaket();
	    $deliver = array(
	    	'status' => 200,
	    	'html' => $html
	    );
    } else {
    	$deliver['status'] = 400;
    }
}
?>