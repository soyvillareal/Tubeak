<?php
if($one == 'sort-commens'){
	if (!empty($_POST['video_id']) && is_numeric($_POST['video_id'])) {
	    $query = '';
		$html = '';
	    $sort = 0;
	    $video_id = Specific::Filter($_POST['video_id']);
	    $comment_id = Specific::Filter($_POST['comment']);
	    $video = Specific::Video($video_id);

	    if(!empty($_POST['sort_by']) && is_numeric($_POST['sort_by'])){
	    	$sort = Specific::Filter($_POST['sort_by']);
	    }
		if($comment_id != 0){
		    $query .= ' AND id != '.$comment_id;
		}

	    if ($sort == 1) {
	    	$comms_order = $dba->query('SELECT to_id, COUNT(*) AS likes FROM reactions c WHERE type = 1 AND place = "commentary" AND (SELECT id FROM comments WHERE id = c.to_id AND pinned != 1 AND video_id = '.$video_id.$query.') = to_id GROUP BY to_id ORDER BY likes DESC LIMIT '.$TEMP['#settings']['comments_load_limit'])->fetchAll();
	    	if(!empty($comms_order)){
	    		foreach ($comms_order as $value) {
		    		$comments[] = $dba->query('SELECT * FROM comments WHERE id = '.$value['to_id'])->fetchArray();
		    	}
	    	} else {
				$comments = $dba->query('SELECT * FROM comments WHERE pinned != 1 AND video_id = '.$video_id.$query.' ORDER BY id DESC LIMIT '.$TEMP['#settings']['comments_load_limit'])->fetchAll();
			}
	    } else {
	    	$comments = $dba->query('SELECT * FROM comments WHERE pinned != 1 AND video_id = '.$video_id.$query.' ORDER BY id DESC LIMIT '.$TEMP['#settings']['comments_load_limit'])->fetchAll();
	    }
	    
	    if (count($comments) > 0) {
	        foreach ($comments as $value) {
	        	$like_active_class  = '';
			    $dislike_active_class  = '';
			    $TEMP['#video_owner'] = false;
		        $TEMP['#is_comment_owner'] = false;
		        $TEMP['!owner_username']   = $video['by_id'] == $value['by_id'] ? ' owner-username' : '';
		        $TEMP['#cont_replies'] = $dba->query('SELECT * FROM replies WHERE comment_id = '.$value['id'].' AND by_id NOT IN ('.$TEMP['#blocked_users'].')')->fetchAll();

		        if ($TEMP['#loggedin'] === true) {
		        	$TEMP['#video_owner'] = ($dba->query('SELECT COUNT(*) FROM videos WHERE by_id = '.$TEMP['#user']['id'].' AND id = '.$video_id)->fetchArray() > 0);

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
} else if($one == 'video-status'){
	$deliver['status'] = 400;
	$id = Specific::Filter($_POST['video_id']);
	if (!empty($id)) {
		$video = Specific::Video($id);
		if($video['converted'] == 0){
			if($TEMP['#loggedin'] === true && $video['by_id'] == $TEMP['#user']['id']) {
				$ffmpeg = Specific::FfmpegProcessing($video['video_id']);
				$deliver = array(
			   	    'status' => $ffmpeg['status'],
			   	    'progress' => $ffmpeg['progress'],
			        'reload' => $ffmpeg['reload']
			    );
				if ($ffmpeg['status'] == 400 && $video['converted'] == 0) {
					if ($TEMP['#settings']['max_queue'] > 0) {
						$video_in_queue = $dba->query('SELECT * FROM queue WHERE video_id = '.$video['id'])->fetchArray();
						$process_queue = $dba->query('SELECT video_id FROM queue LIMIT '.$TEMP['#settings']['max_queue'])->fetchAll(FALSE);
						if ($TEMP['#settings']['max_queue'] == 1) {
						    if ($process_queue[0] == $video['id'] && $video_in_queue['processing'] == 1) {
						    	$dba->query('UPDATE queue SET processing = 2 WHERE video_id = '.$video['id']);
						       	$deliver = array(
						            'status' => 200,
						            'progress' => 0,
						   	        'reload' => 1
						        );
							}
						}elseif ($TEMP['#settings']['max_queue'] > 1) {
							if (in_array($video['id'], $process_queue) && $video_in_queue['processing'] == 1) {
								$dba->query('UPDATE queue SET processing = 2 WHERE video_id = '.$video['id']);
						        $deliver = array(
						            'status' => 200,
						            'progress' => 0,
						   	        'reload' => 1
						        );
							}
						}
					}	
				}
			}
		} else {
			$deliver = array(
				'status' => 400,
				'reload' => 2
			);
		}	
	}
} else if($one == 'rtmp-viewers'){
	$deliver['status'] = 400;
	$video_id = Specific::Filter($_POST['video_id']);
	$fingerprint = Specific::Filter($_POST['fingerprint']);

	if(!empty($video_id)){
		$video = $dba->query('SELECT * FROM videos WHERE video_id = "'.$video_id.'" AND deleted = 0 AND broadcast = 1')->fetchArray();
		if($dba->query('SELECT COUNT(*) FROM broadcasts WHERE video_id = "'.$video_id.'" AND live = 1')->fetchArray() > 0 && !empty($video)){
			$rtmp_data = Specific::DataStream();
			$streams = $rtmp_data['server']['application']['live']['stream'];
			if(in_array($video_id, array_values($streams))){
				if(!empty($streams[0])){
					foreach ($streams as $value) {
						if($value['name'] == $video_id){
							$viewers = $value['nclients'];
						}
					}
				} else {
					if($streams['name'] == $video_id){
						$viewers = $streams['nclients'];
					}
				}
				if(!empty($fingerprint)){
					$fingers = $dba->query('SELECT COUNT(*) FROM views WHERE fingerprint = "'.md5($fingerprint).'" AND by_id = "'.$TEMP['#user']['id'].'" AND video_id = '.$video['id'])->fetchArray();
					$views = $video['views'] + 1;
					if($TEMP['#loggedin'] === true && $fingers == 0){
						$dba->query('INSERT INTO views (video_id, by_id, fingerprint, time) VALUES ('.$video['id'].','.$TEMP['#user']['id'].',"'.md5($fingerprint).'",'.time().')');
						$update = $dba->query('UPDATE videos SET views = '.$views.' WHERE video_id = "'.$video_id.'"')->returnStatus();
					} else if($TEMP['#loggedin'] === false && empty($_SESSION['finger'])){
						$_SESSION['finger'] = md5($fingerprint);
						$update = $dba->query('UPDATE videos SET views = '.$views.' WHERE video_id = "'.$video_id.'"')->returnStatus();
					}
				} else {
					$update = $dba->query('UPDATE videos SET views = '.$views.' WHERE video_id = "'.$video_id.'"')->returnStatus();
				}
				$deliver = array(
					'status' => 200,
					'viewers' => $viewers
				);
			}
		} else {
			$deliver = array(
				'status' => 200,
				'reload' => 1
			);
		}
	}
} else if($one == 'set-views'){
	$deliver['status'] = 400;
	$fingerprint = Specific::Filter($_POST['fingerprint']);
	$video_id = Specific::Filter($_POST['video_id']);
	if(!empty($video_id)){
		$video = $dba->query('SELECT * FROM videos WHERE id = '.$video_id.' AND deleted = 0')->fetchArray();
		if(!empty($video)){
			$update = false;
			if(!empty($fingerprint)){
				$fingers = $dba->query('SELECT COUNT(*) FROM views WHERE fingerprint = "'.md5($fingerprint).'" AND by_id = "'.$TEMP['#user']['id'].'" AND video_id = '.$video_id)->fetchArray();
				$views = $video['views'] + 1;
				if($TEMP['#loggedin'] === true && $fingers == 0){
					$dba->query('INSERT INTO views (video_id, by_id, fingerprint, time) VALUES ('.$video_id.','.$TEMP['#user']['id'].',"'.md5($fingerprint).'",'.time().')');
					$update = $dba->query('UPDATE videos SET views = '.$views.' WHERE id = '.$video_id)->returnStatus();
				} else if($TEMP['#loggedin'] === false && empty($_SESSION['finger'])){
					$_SESSION['finger'] = md5($fingerprint);
					$update = $dba->query('UPDATE videos SET views = '.$views.' WHERE id = '.$video_id)->returnStatus();
				}
			} else {
				$update = $dba->query('UPDATE videos SET views = '.$views.' WHERE id = '.$video_id)->returnStatus();
			}
			if($update){
				$deliver = array(
					'status' => 200,
					'count' => $views
				);
			}
		}
	}
}
?>