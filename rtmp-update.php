<?php
require_once('./includes/rtmp_init.php');

use Gregwar\Image\Image;
if(!empty($_GET["key"])){
	$live_key = Specific::Filter($_GET["key"]);
	$name = Specific::Filter($_POST["name"]);
	$file = 'uploads/temp/'.$name.'.m3u8';
	$video_data = $dba->query('SELECT * FROM videos WHERE broadcast = 1 AND video_id = "'.$name.'"')->fetchArray();
	if($dba->query('SELECT COUNT(*) FROM users WHERE live_key = "'.$live_key.'"')->fetchArray() > 0 && !empty($video_data) && $dba->query('SELECT COUNT(*) FROM broadcasts WHERE live = 0 AND video_id = "'.$name.'"')->fetchArray() > 0 && file_exists($file)){
		if(empty($video_data['thumbnail'])){
			$data_stream = Specific::DataStream();
			$broadcast_time = ($data_stream['server']['application']['live']['stream']['time'] / 1000) / 5;
		    $thumbnail = Specific::CreateDirImage() . '/' . sha1(time()) . '_' . date('d') . '_' . Specific::RandomKey() . '_thumbnail.jpg';
		    exec($TEMP['#settings']['binary_path']."/ffmpeg -loglevel quiet -ss $broadcast_time -i uploads/streaming/$name.flv -t 1 -f image2 $thumbnail");
		   	if (file_exists($thumbnail) && !empty(getimagesize($thumbnail))) {
		        Image::open($thumbnail)->zoomCrop(1076, 604)->save($thumbnail, 'jpeg', 80);
		    } else {
		        unlink($thumbnail);
		    }
		    $dba->query('UPDATE videos SET thumbnail = "'.$thumbnail.'" WHERE video_id = "'.$name.'"');
		}
	    $dba->query('UPDATE broadcasts SET live = 1 WHERE video_id = "'.$name.'"');
	}
}
?>