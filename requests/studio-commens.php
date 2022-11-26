<?php
if ($TEMP['#loggedin'] === false) {
    $deliver = array(
    	'status' => 400,
    	'error' => $TEMP['#word']['error']
    );
    echo json_encode($deliver);
    exit();
} else if ($one == 'get-comment') {
	$deliver['status'] = 400;
	$id = Specific::Filter($_POST['id']);
	if(!empty($id)){
		$comment = $dba->query('SELECT * FROM comments WHERE id = '.$id)->fetchArray();
		if (!empty($comment)) {
	        $deliver['status'] = 200;
	        $deliver['text'] = Specific::GetUncomposeText($comment['text'], 0, false, false);
		}
	}
} else if ($one == 'update-comment') {
	$deliver['status'] = 400;
	if(!empty($_POST['id']) && !empty($_POST['text'])){
		$id = Specific::Filter($_POST['id']);
		$comment = $dba->query('SELECT * FROM comments WHERE id = '.$id)->fetchArray();
		$video = $dba->query('SELECT * FROM videos WHERE id = '.$comment['video_id'].' AND deleted = 0')->fetchArray();
		if ($comment['by_id'] == $TEMP['#user']['id'] || $video['by_id'] == $TEMP['#user']['id']) {
			$text = Specific::ComposeText($_POST['text'], $video['duration']);
			$dba->query('UPDATE comments SET text = "'.$text.'" WHERE id = '.$id);
		    $deliver['status'] = 200;
		    $deliver['text'] = Specific::GetComposeText($text);
		}
	}
}
?>