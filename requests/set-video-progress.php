<?php 
if ($TEMP['#loggedin'] === false) {
    $deliver = array(
        'status' => 400,
        'error' => $TEMP['#word']['error']
    );
    echo json_encode($deliver);
    exit();
}
$deliver['status'] = 400;
if(!empty($_POST['by_id']) && is_numeric($_POST['by_id']) && !empty($_POST['video_id']) && is_numeric($_POST['video_id'])){

	$by_id = Specific::Filter($_POST['by_id']);
	$video_id = Specific::Filter($_POST['video_id']);
	$loaded_progress = Specific::Filter($_POST['loaded_progress']);
	$percent_loaded = Specific::Filter($_POST['percent_loaded']);
	$exist_progress = $dba->query('SELECT COUNT(*) FROM progress WHERE by_id = '.$by_id.' AND video_id = '.$video_id)->fetchArray();
	$get_progress = Specific::GetProgress($video_id, $by_id);

	if($exist_progress == 0){
		$data_key = $data_value = '';
		if($loaded_progress > $get_progress['last_progress']){
			$data_key = ', last_progress';
			$data_value = ", $loaded_progress";
		}
		$insert = $dba->query('INSERT INTO progress (by_id, video_id, loaded_progress, percent_loaded'.$data_key.') VALUES ('.$by_id.','.$video_id.','.$loaded_progress.','.$percent_loaded.$data_value.')')->insertId();
	} else {
		$data_update = '';
		if($loaded_progress > $get_progress['last_progress']){
			$data_update = ', last_progress = '.$loaded_progress;
		}
		$insert = $dba->query('UPDATE progress SET by_id = '.$by_id.', video_id = '.$video_id.', loaded_progress = '.$loaded_progress.', percent_loaded = '.$percent_loaded.$data_update.' WHERE by_id = '.$by_id.' AND video_id = '.$video_id)->returnStatus();
	}
	if ($insert) {
	    $deliver = array(
	        'status' => 200,
	        'message' => $get_progress['loaded_progress']
	    );
	}
}
?>