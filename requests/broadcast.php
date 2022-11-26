<?php
if ($TEMP['#loggedin'] === false || $TEMP['#settings']['live_broadcast'] == 'off') {
    $deliver = array(
        'status' => 400,
        'error' => $TEMP['#word']['error']
    );
    echo json_encode($deliver);
    exit();
} else if ($one == 'new-text-chat') {
	$deliver['status'] = 400;
	$video_id = Specific::Filter($_POST['video_id']);
	$text = Specific::Filter($_POST['text']);
	if (!empty($video_id) && !empty($text) && strlen($text) <= 200) {
		$data_stream = Specific::DataStream();
		$broadcast_time = $data_stream['server']['application']['live']['stream']['time'] / 1000;
		$insert = $dba->query('INSERT INTO broadcasts_chat (by_id, video_id, `text`, broadcast_time, `time`) VALUES ('.$TEMP['#user']['id'].','.$video_id.',"'.$text.'",'.$broadcast_time.','.time().')')->insertId();
		$TEMP['!owner_username'] = $TEMP['#user']['id'] == $dba->query('SELECT by_id FROM videos WHERE id = '.$video_id)->fetchArray() ? ' owner-username' : '';
		$TEMP['!data'] = Specific::Data($TEMP['#user']['id']);
		$TEMP['!id']   = $insert;
		$TEMP['!text'] = Specific::Censor($text);
		if($insert){
			$deliver = array(
				'status' => 200,
				'html' => Specific::Maket('live-broadcast/includes/broadcast-text')
			);
		}
	}
} else if($one == 'edit-broadcast'){
	$errors = array();
	$video_id = Specific::Filter($_POST['video_id']);
	$title = Specific::Filter($_POST['title']);
	$category = Specific::Filter($_POST['category']);
	$privacy = Specific::Filter($_POST['privacy']);
	$adults_only = Specific::Filter($_POST['adults_only']);
	$description = Specific::Filter($_POST['description']);
	$tags = Specific::Filter($_POST['tags']);
	if(!empty($video_id) && $dba->query('SELECT COUNT(*) FROM broadcasts WHERE video_id = "'.$video_id.'" AND live < 2')->fetchArray() > 0){
		if (empty($title)){
			$errors[] = array('type' => 'empty', 'el' => 'title');
		}
	    if(empty($tags)){
	    	$errors[] = array('type' => 'empty', 'el' => 'tags');
	    }
		if (!in_array($category, array_keys($TEMP['#categories']))) {
	        $errors[] = array('type' => 'error', 'el' => 'category');
	    }
	    if (!in_array($privacy, array(0, 1, 2)) || ($privacy != $dba->query('SELECT privacy FROM videos WHERE video_id = "'.$video_id.'"')->fetchArray() && $dba->query('SELECT COUNT(*) FROM broadcasts WHERE video_id = "'.$video_id.'" AND live = 1')->fetchArray() > 0)) {
	        $errors[] = array('type' => 'error', 'el' => 'privacy');
	    }
	    if (!in_array($adults_only, array(0, 1))) {
	        $errors[] = array('type' => 'error', 'el' => 'adults_only');
	    }
		if(empty($errors)){
			$dba->query('UPDATE videos SET title = "'.$title.'", category = "'.$category.'", privacy = '.$privacy.', adults_only = '.$adults_only.', description = "'.Specific::ComposeText($description).'", tags = "'.$tags.'" WHERE video_id = "'.$video_id.'"');
			$deliver['status'] = 200;
		} else {
			$deliver = array(
				'status' => 400,
			    'errors' => $errors
			);
		}
	}
} else if($one == 'save-broadcast'){
	$deliver['status'] = 400;
	$video_id = Specific::Filter($_POST['video_id']);
	$save_broadcast = Specific::Filter($_POST['save-broadcast']);
	if(!empty($video_id) && !empty($save_broadcast)){
		if($dba->query('UPDATE broadcasts SET save = '.$save_broadcast.' WHERE video_id = "'.$video_id.'"')->returnStatus()){
			$deliver['status'] = 200;
		}
	}
} else if($one == 'chat-broadcast-status'){
	$deliver['status'] = 400;
	$video_id = Specific::Filter($_POST['video_id']);
	$chat_broadcast_status = Specific::Filter($_POST['chat-broadcast-status']);
	if(!empty($video_id) && !empty($chat_broadcast_status)){
		if($dba->query('SELECT live FROM broadcasts WHERE video_id = "'.$video_id.'"')->fetchArray() != 1){
			if($dba->query('UPDATE broadcasts SET chat = '.$chat_broadcast_status.' WHERE video_id = "'.$video_id.'"')->returnStatus()){
				$deliver['status'] = 200;
			}
		}
		
	}
} else if ($one == 'broadcast-status'){
	$deliver['status'] = 400;
	$video_id = Specific::Filter($_POST['video_id']);
	if(!empty($video_id)){
		$broadcast_data = $dba->query('SELECT * FROM broadcasts WHERE video_id = "'.$video_id.'" AND (live = 1 OR live = 2)')->fetchArray();
		if(!empty($broadcast_data)){
			$deliver = array(
				'status' => 200,
				'live' => $broadcast_data['live'],
				'save' => $broadcast_data['save']
			);
		}
	}
} else if ($one == 'upload-thumbnail'){
	$video_id = Specific::Filter($_GET['video_id']);
	$video_data = $dba->query('SELECT * FROM videos WHERE id = '.$video_id.' AND by_id = '.$TEMP['#user']['id'])->fetchArray();
    if (!empty($video_data)) {
        $file_info = array(
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
        $file_data = Specific::UploadImage($file_info);
        if(empty($file_data['error'])){
        	if ($dba->query('UPDATE videos SET thumbnail = "'.$file_data.'" WHERE id = '.$video_id)->returnStatus()) {
        		if(!empty($video_data['thumbnail']) && $video_data['thumbnail'] != 'uploads/images/thumbnail.jpg'){
	                unlink($video_data['thumbnail']);
	            }
	            $deliver = array(
	                'status' => 200,
	                'message' => $TEMP['#word']['setting_updated']
	            );
	        }
        } else {
        	$deliver = array(
                'status' => 400,
                'error' => $file_data['error']
            );
        }
    }
} else if ($one == 'delete-thumbnail'){
	$video_id = Specific::Filter($_POST['video_id']);
	if(!empty($video_id)){
		$live_new = $dba->query('SELECT COUNT(*) FROM broadcasts WHERE video_id = "'.$video_id.'" AND live = 0')->fetchArray();
		if($live_new > 0){
			$thumbnail = $dba->query('SELECT thumbnail FROM videos WHERE video_id = "'.$video_id.'" AND by_id = '.$TEMP['#user']['id'])->fetchArray();
		    if (!empty($thumbnail)) {
				if(!empty($thumbnail) && $thumbnail != 'uploads/images/thumbnail.jpg'){
		            unlink($thumbnail);
		        }
		        if ($dba->query('UPDATE videos SET thumbnail = ? WHERE video_id = "'.$video_id.'"', NULL)->returnStatus()) {
		            $deliver = array(
		                'status' => 200,
		                'message' => $TEMP['#word']['setting_updated']
		            );
		        } 
		    }
		}	
	}	
}
?>