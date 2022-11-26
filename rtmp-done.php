<?php
require_once('./includes/rtmp_init.php');

use Gregwar\Image\Image;
if(!empty($_GET["key"])){
	$live_key = Specific::Filter($_GET["key"]);
	$name = Specific::Filter($_POST["name"]);
	$path = 'uploads/streaming/'.$name.'.flv';
	$metadata = Specific::FfprobeData($path);
    $video_width = $metadata['width'];
	if($dba->query('SELECT COUNT(*) FROM users WHERE live_key = "'.$live_key.'"')->fetchArray() > 0 && $dba->query('SELECT COUNT(*) FROM broadcasts WHERE video_id = "'.$name.'" AND live = 1')->fetchArray() > 0){
		$broadcast = $dba->query('SELECT * FROM broadcasts WHERE live = 1 AND video_id = "'.$name.'"')->fetchArray();
		if($broadcast['save'] == 1){
			$images           = array();
            $duration_seconds = $metadata['seconds'];
            $thumb_1_duration = $duration_seconds / 2;
            $thumb_2_duration = $duration_seconds / 3;
            $thumb_3_duration = $duration_seconds / 4;
            $thumbnails = array($thumb_1_duration, $thumb_2_duration, $thumb_3_duration);
            $filepath = Specific::CreateDirImage();
            foreach ($thumbnails as $value) { 
                $thumbnail = $filepath . '/' . Specific::RandomKey() . sha1(time()) . '_video_thumbnail_'.intval($value).'.jpeg';
                exec($TEMP['#settings']['binary_path']."/ffmpeg -loglevel quiet -ss $value -i $path -t 1 -f image2 $thumbnail");
                if (file_exists($thumbnail) && !empty(@getimagesize($thumbnail))) {
                    Image::open($thumbnail)->zoomCrop(1076, 604)->save($thumbnail, 'jpeg', 80);
                    $images[] = $thumbnail;
                } else {
                    unlink($thumbnail);
                }
            }

            $uploads = $dba->query('SELECT uploads FROM users WHERE id = '.$broadcast['by_id'])->fetchArray();
			$stream = $dba->query('SELECT * FROM videos WHERE video_id = "'.$name.'"')->fetchArray();

            $dba->query('UPDATE users SET uploads = '.($uploads += $metadata['size']).' WHERE id = '.$broadcast['by_id']);
            $dba->query('UPDATE broadcasts SET live = 2 WHERE video_id = "'.$name.'"');

			$dba->query('INSERT INTO queue (video_id, video_width, processing) VALUES ('.$stream['id'].','.$video_width.',0)');
            $dba->query('UPDATE videos SET duration = "'.$metadata['duration'].'", thumbnail = "'.$stream['thumbnail'].'", thumbnail_1 = "'.$images[0].'", thumbnail_2 = "'.$images[1].'", thumbnail_3 = "'.$images[2].'", size = "'.$metadata['size'].'", path = "'.$path.'" WHERE video_id = "'.$name.'"');
            require('./process-queue.php');
		} else {
			$video = $dba->query('SELECT * FROM videos WHERE video_id = "'.$name.'"')->fetchArray();
			Specific::DeleteVideo($video['id']);
			if(file_exists($path)){
				unlink($path);
			}
		}
	}
}
?>