<?php
$type = Specific::Filter($_POST['type']);
$types = array('share-video','more-options','warning-alert');

if ($TEMP['#loggedin'] === false && !in_array($type, $types)) {
    $deliver = array(
        'status' => 400,
        'error' => $TEMP['#word']['error']
    );
    echo json_encode($deliver);
    exit();
}
if (!empty($_POST['type']) && (in_array($_POST['type'], $types) || in_array($_POST['next_cont'], $types))) {
	if($type == 'share-video' || $type == 'more-options'){
		
		$id = Specific::Filter($_POST['video_id']);
		$TEMP['#video'] = Specific::Video($id);
		$TEMP['#live'] = $dba->query('SELECT live FROM broadcasts WHERE video_id = "'.$TEMP['#video']['video_id'].'"')->fetchArray();
		$TEMP['#is_approved'] = true;
		if ($TEMP['#settings']['approve_videos'] == 'on') {
		    if ($TEMP['#video']['approved'] == 0) {
		        $TEMP['#is_approved'] = false;
		    }
		}
		if ($TEMP['#loggedin'] === true) {
            if ($TEMP['#video']['data']['id'] == $TEMP['#user']['id'] || Specific::Admin()) {
                $TEMP['#video']['is_owner'] = true;
            }
        }

		$TEMP['actual_form'] = $_POST['actual_form'];
		$TEMP['id'] = $id;
		$TEMP['video_id'] = $TEMP['#video']['video_id'];
		$TEMP['title'] = $TEMP['#video']['title'];
		$TEMP['encoded_url'] = urlencode($TEMP['#video']['url']);
		$TEMP['short_id'] = $TEMP['#video']['short_id'];
	   	$TEMP['thumbnail'] = $TEMP['#video']['thumbnail'];

		$TEMP['content'] = Specific::Maket('modals/'.$type);
		$html = Specific::Maket('modals/content');
	}else if($type == 'warning-alert'){

		if (!empty($_POST['next_cont'])) {
			$TEMP['#cont_p'] = Specific::Filter($_POST['next_cont']);
		}
		if (!empty($_POST['txt_btn'])) {
			$button_txt = Specific::Filter($_POST['txt_btn']);
		}
		if (!empty($_POST['class_n'])) {
			$class_n = Specific::Filter($_POST['class_n']);
		}
		if(!empty($_POST['by_id'])){
			$cont_t = $_POST['by_id'];
		}
		if(!empty($_POST['extra'])){
			$extra = Specific::Filter($_POST['extra']);
		}
		$TEMP['#close_mode'] = ($class_n == 'close_alert') ? 1 : $class_n;
		$TEMP['#mode_alert'] = Specific::Filter($_POST['price']);
		$extra_new = $_POST['extra_new'];
		$TEMP['#show_t'] = Specific::Filter($_POST['video_id']);

		$TEMP['cont_p'] = (is_numeric($extra) || empty($extra)) ? $extra_new : $TEMP['#cont_p'];
		$TEMP['button_txt'] = $button_txt;
		$TEMP['class_n'] = $class_n;
		$TEMP['cont_t'] = $cont_t;
		$TEMP['mode_alert'] = $TEMP['#mode_alert'];
		$TEMP['extra'] = (is_numeric($extra) && $extra > 0) ? $extra : 0;

		$html = Specific::Maket('modals/'.$type);
	}
	if (!empty($html)) {
		$deliver['status'] = 200;
		$deliver['html'] = $html;
	}
}else{
	$deliver['status'] = 400;
	$deliver['error'] = $TEMP['#word']['error'];
}
?>