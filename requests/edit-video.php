<?php
if ($TEMP['#loggedin'] === false) {
    $deliver = array(
    	'status' => 400,
    	'error' => $TEMP['#word']['error']
    );
    echo json_encode($deliver);
    exit();
} else {
	$deliver['status'] = 400;
	if (!empty($_POST['thumbnail']) && !empty($_POST['video-id'])) {
		$errors = array();
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
		    $id = Specific::Filter($_POST['video-id']);
		    $video = Specific::Video($id);
		    if ((Specific::Admin() == true || $dba->query('SELECT COUNT(*) FROM videos WHERE by_id = '.$TEMP['#user']['id'].' AND id = '.$id.' AND deleted = 0')->fetchArray() > 0) && !empty($video)) {

		        $thumbnail   = Specific::Filter($_POST['thumbnail']);
		        $now_draft 	 = Specific::Filter($_POST['draft_thumb']);
		        if (!empty($thumbnail) && strpos($video['ex_thumbnail'], '_thumbnail.')) {
		        	if ($video['ex_thumbnail_1'] == $thumbnail) {
		        		$thumbnail_draft = $video['ex_thumbnail'];
		        		$thumbnail_chg = $thumbnail;
		        	} else if ($video['ex_thumbnail_2'] == $thumbnail) {
		        		$thumbnail_draft = $video['ex_thumbnail'];
		        		$thumbnail_chg = $thumbnail;
		        	} else if ($video['ex_thumbnail_3'] == $thumbnail) {
		        		$thumbnail_draft = $video['ex_thumbnail'];
		        		$thumbnail_chg = $thumbnail;
		        	} else if ($thumbnail != $video['ex_thumbnail_draft']){
		        		if($thumbnail != $video['ex_thumbnail']){
		        			unlink($video['ex_thumbnail']);
		        			$thumbnail_chg = $thumbnail;
					       	$thumbnail_draft = NULL;
				       } else if ($thumbnail == $video['ex_thumbnail']) {
				       		$thumbnail_chg = $thumbnail;
					       	$thumbnail_draft = NULL;
				       }
			        } else if ($thumbnail != $video['ex_thumbnail']) {
			            unlink($video['ex_thumbnail']);
			        }
		        } else if (!empty($thumbnail) && $video['ex_thumbnail_draft'] == $thumbnail) {
		        	$thumbnail_chg = $video['ex_thumbnail_draft'];
		        	$thumbnail_draft = NULL;
		        } else if (!empty($thumbnail) && strpos($video['ex_thumbnail'], 'video_thumbnail_')) {
		        	if ($video['ex_thumbnail_draft'] != $thumbnail && strpos($thumbnail, '_thumbnail.')) {
		        		unlink($video['ex_thumbnail_draft']);
		        	}
		        	$thumbnail_chg = $thumbnail;
		        	$thumbnail_draft = ($video['ex_thumbnail_draft'] != $thumbnail && strpos($thumbnail, '_thumbnail.')) ? NULL : !empty($now_draft) ? $now_draft : $video['ex_thumbnail_draft'];
		        }
			    $dba->query('UPDATE videos SET thumbnail = ?, thumbnail_draft = ?, title = ?, description = ?, tags = ?, category = ?, privacy = ?, adults_only = ? WHERE id = '.$id, $thumbnail_chg, $thumbnail_draft, $title, Specific::ComposeText($_POST['description']), $tags, "$category", $privacy, $adults_only)->returnStatus();

			    $deliver = array(
			        'status' => 200,
			        'message' => $TEMP['#word']['video_saved']
			    );
		    }
		} else {
			$deliver = array(
				'status' => 400,
				'errors' => $errors
			);
		}
	}
}
?>