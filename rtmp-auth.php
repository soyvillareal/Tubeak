<?php
require_once('./includes/rtmp_init.php');

if(!empty($_GET["key"])){
	$live_key = Specific::Filter($_GET["key"]);
	$name = Specific::Filter($_POST["name"]);
	if($dba->query('SELECT COUNT(*) FROM users WHERE live_key = "'.$live_key.'"')->fetchArray() > 0 && $dba->query('SELECT COUNT(*) FROM broadcasts WHERE video_id = "'.$name.'" AND live = 0')->fetchArray() > 0 && $TEMP['#settings']['live_broadcast'] == 'on'){
	  	if($dba->query('UPDATE videos SET time = '.time().' WHERE video_id = "'.$name.'"')->returnStatus()){
	  		if (!file_exists('uploads/temp')) {
                mkdir('uploads/temp', 0777, true);
            }
            if (!file_exists('uploads/streaming')) {
                mkdir('uploads/streaming', 0777, true);
            }
	  		http_response_code(201);
	  	}
	} else {
	  http_response_code(404);
	}
}
?>