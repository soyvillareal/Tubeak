<?php
use Gregwar\Image\Image;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Specific {

	public static function PostCreate($deliver = array()) {
	    ob_end_clean();
	    header("Content-Encoding: none");
	    header("Connection: close");
	    ignore_user_abort(); // optional
	    ob_start();
	    if (!empty($deliver)) {
	        header('Content-Type: application/json');
	        echo json_encode($deliver);
	    }
	    $size = ob_get_length();
	    header("Content-Length: $size");
	    ob_end_flush(); // Strange behaviour, will not work
	    flush(); // Unless both are called!
	   	session_write_close();
	    if (is_callable('fastcgi_finish_request')) {
	        fastcgi_finish_request();
	    }
	    // Do processing here
	}

	public static function NotifySubscribers($id) {
	    global $TEMP, $dba;
	    if ($TEMP['#loggedin'] === false) {
	        return false;
	    }
	    $video = $dba->query('SELECT by_id,video_id FROM videos WHERE id = '.$id)->fetchArray();
	    $subscribers = $dba->query('SELECT * FROM subscriptions WHERE by_id = '.$video['by_id'])->fetchAll();
	    if(!empty($subscribers)){
	        foreach ($subscribers as $value) {
	            $query_rows[] = "('{$video['by_id']}', '{$value['subscriber_id']}', 'video_uploaded', '{$video['video_id']}', '".time()."')";
	        }
	        if ($dba->query("INSERT INTO notifications (from_id, to_id, type, notify_key, `time`) VALUES ".implode(',', $query_rows))->insertId()) {
	            return true;
	        }
	    } else {
	        return false;
	    }
	}

	public static function GetAd($type, $admin = true) {
	    global $TEMP;
	    $data = array();
	    $json_ad = json_decode($TEMP['#settings'][$type], true);
	    $data['content'] = $json_ad['content'];
	    $data['active'] = $json_ad['active'];
	    return $data;
	}

	public static function GetPages() {
	    global $dba;
	    $data  = array();
	    $pages = $dba->query('SELECT * FROM pages')->fetchAll();
	    foreach ($pages as $value) {
	        $data['page'][$value['type']] = htmlspecialchars_decode($value['text']);
	        $data['active'][$value['type']] = $value['active'];
	    }
	    return $data;
	}

	public static function GetFile($file, $type = 1){
	    global $TEMP;
	    if (empty($file)) {
	        return '';
	    }
	    $theme = '';
	    if($type == 2){
	        $theme = 'themes/'.$TEMP['#settings']['theme'].'/';
	    }
	    return self::Url($theme.$file);
	}

	public static function GetIcon($media, $type){
	    global $TEMP;
	    $icon = self::Url("themes/".$TEMP['#settings']['theme']."/images/icon-$type.png");
	    if(!empty($media)){
	        $icon = self::GetFile($media);
	    }
	    return $icon;
	}

	public static function Admin() {
	    global $TEMP;
	    return $TEMP['#loggedin'] === false ? false : $TEMP['#user']['role'] == 1 ? true : false;
	}

	public static function Moderator() {
	    global $TEMP;
	    return $TEMP['#loggedin'] === false ? false : $TEMP['#user']['role'] == 2 ? true : false;
	}

	public static function ResizeCover($data = array()){
	    if(empty($data)){
	        return false;
	    }
	    $tb = new ThumbAndCrop();
	    $tb->openImg($data['original']);
	    $newHeight = $tb->getRightHeight($data['width']);
	    $tb->creaThumb($data['width'], $newHeight);
	    $tb->setThumbAsOriginal();
	    $tb->cropThumb($data['width'], $data['height'], 0, abs($data['top']));
	    $tb->saveThumb($data['cover']);
	    $tb->resetOriginal();
	    $tb->closeImg();

	    return true;
	}

	public static function CreateDirImage(){
		if (!file_exists('uploads/images/' . date('Y'))) {
	        mkdir('uploads/images/' . date('Y'), 0777, true);
	    }
	    if (!file_exists('uploads/images/' . date('Y') . '/' . date('m'))) {
	        mkdir('uploads/images/' . date('Y') . '/' . date('m'), 0777, true);
	    }
	    return 'uploads/images/' . date('Y') . '/' . date('m');
	}

	public static function UploadImage($data = array()){
	    global $TEMP;
	    $filepath = self::CreateDirImage();
	    if (empty($data)) {
	        return false;
	    }
	    if (isset($data['file']) && !empty($data['file'])) {
	        $data['file'] = $data['file'];
	    }
	    $ext    = pathinfo($data['name'], PATHINFO_EXTENSION);
	    if (!in_array($ext, array('png','jpg','jpeg', 'gif')) || !in_array($data['type'], array('image/png', 'image/jpeg', 'image/gif'))) {
	        return array(
	            'error' => $TEMP['#word']['file_not_supported']
	        );
	    }
	    $file = $filepath . '/' . sha1(time()) . '_' . date('d') . '_' . self::RandomKey() . '_' . $data['from'];
	    $filename    = "$file.$ext";
	    if (move_uploaded_file($data['file'], $filename)) {
	        if($data['from'] == 'cover'){
	            $full_cover = $file.'_full.'.$ext;
	            Image::open($filename)->save($full_cover);
	        }
	        if (!empty($data['crop'])) {
	            Image::open($filename)->zoomCrop($data['crop']['width'], $data['crop']['height'])->save($filename, $ext, 60);
	        }
	        Image::open($filename)->save($filename, $ext, 80);
	        return $filename;
	    }
	}

	public static function CreateDirVideo($ext){
		if (!file_exists('uploads/videos/' . date('Y'))) {
	        mkdir('uploads/videos/' . date('Y'), 0777, true);
	    }
	    if (!file_exists('uploads/videos/' . date('Y') . '/' . date('m'))) {
	        mkdir('uploads/videos/' . date('Y') . '/' . date('m'), 0777, true);
	    }

	    return 'uploads/videos/' . date('Y') . '/' . date('m') . '/' . sha1(time()) . '_' . date('d') . '_' . self::RandomKey() . '_video.' . $ext;
	}

	public static function UploadVideo($data = array()) {
	    global $TEMP;
	    if (empty($data)) {
	        return false;
	    }
	    if (isset($data['file']) && !empty($data['file'])) {
	        $data['file'] = $data['file'];
	    }
	    if (isset($data['name']) && !empty($data['name'])) {
	        $data['name'] = self::Filter($data['name']);
	    }

	    $ext = pathinfo($data['name'], PATHINFO_EXTENSION);
	    if (!in_array($ext, array('avi','mov','MOV','3gp','mpeg', 'mp4','flv','mks','webm','ogg','mk3d','mkv','wmv')) || !in_array($data['type'], array('video/mp4', 'video/mov', 'video/3gp', 'video/3gpp', 'video/mpeg', 'video/flv', 'video/x-flv', 'video/avi', 'video/webm', 'video/msvideo', 'video/x-msvideo', 'video/x-ms-wmv', 'video/x-matroska', 'video/quicktime'))) {
	        return array(
	            'error' => $TEMP['#word']['file_not_supported']
	        );
	    }
	    $filename = self::CreateDirVideo($ext);;
	    if (move_uploaded_file($data['file'], $filename)) {
	        return array(
	        	'filename' => $filename,
	        	'name' => $data['name']
	        );
	    }
	}

	public static function ConvertVideo($data = array()){
		global $TEMP, $dba;

		$scales = array(
			426 => 240,
			640 => 360,
			854 => 480,
			1280 => 720,
			1920 => 1080,
			2560 => 1440,
			3840 => 2160
		);
		$filepath = explode('.', $data['path'])[0];
		if(strpos($filepath, 'uploads/videos') === false){
            $filepath = self::CreateDirVideo($filepath[1]);
            $filepath =  explode('.', $filepath)[0];
        }
		$fileLog = 'uploads/ffmpeg/ffmpeg-'.$data['video_id'].'.txt';
		if (!file_exists('uploads/ffmpeg')) {
            mkdir('uploads/ffmpeg', 0777, true);
        }
        $download_key = sha1(time());
        $video_an = $metadata['seconds'] / 2;
        $animation_time = 5; // 5 Seconds of animation

        if ($TEMP['#settings']['animation_video'] == 'on') {
            $animation_video = substr($filepath, 0,strpos($filepath, '_video') - 10).sha1(time()). "_animation.gif";
            exec("{$TEMP['#settings']['binary_path']}/ffmpeg -ss $video_an -t $animation_time -async 1 -y -i {$data['path']} -vf scale=480:-1,fps=8,setpts=0.6*PTS ".$animation_video);
            $dba->query('UPDATE videos SET animation = "'.$animation_video.'" WHERE id = '.$data['insert']);
        }

        if($TEMP['#settings']['storyboard'] == 'on'){
            exec("{$TEMP['#settings']['binary_path']}/ffmpeg -i {$data['path']} -r 1 -s 160x90 -f image2 ".$filepath."_thumb-%02d.jpg");
            $filePrefix     =   $filepath . "_thumb-";
            $fileSuffix     =   '.jpg';
            $thumbWidth     =   160;
            $thumbHeight    =   90;
            $imageFiles     =   array();
            $index          =   0;
            $spriteFile     =   $filepath . '_sprite-'.$index.'.png';
            $vttFile        =   $filepath . '_sprite.vtt';
            $dst_x          =   0;
            $dst_y          =   0;
            $newcount       =   1;

            foreach (glob($filePrefix.'*'.$fileSuffix) as $value) {
                $imageFiles[] = $value;
            }

            natsort($imageFiles);
            $spriteWidth =  $thumbWidth*5;
            $spriteHeight   =   $thumbHeight*5;

            $png = imagecreatetruecolor($spriteWidth,$spriteHeight);
            $handle =   fopen($vttFile,'wb+');
            fwrite($handle,'WEBVTT'."\n");

            foreach($imageFiles as $value) {
                $counter        =   str_replace($filePrefix,'',str_replace($fileSuffix, '', $value));
                $imageSrc = imagecreatefromjpeg($value);
                imagecopyresized ($png, $imageSrc, $dst_x , $dst_y , 0, 0, $thumbWidth, $thumbHeight, $thumbWidth,$thumbHeight);

                $varTCstart =   gmdate("H:i:s", $counter-1).'.000';
                $varTCend   =   gmdate("H:i:s", $counter).'.000';
                $varSprite  =   $spriteFile.'?xywh='.$dst_x.','.$dst_y.','.$thumbWidth.','.$thumbHeight;
                fwrite($handle,$varTCstart.' --> '.$varTCend."\n".$varSprite."\n\n");
                if ($counter % 5 == 0) {
                    $dst_x=0;
                    $dst_y+=$thumbHeight;
                }else{
                    $dst_x+=$thumbWidth;
                }
                if($newcount < 25){
                    imagepng($png,$spriteFile);
                } else if($newcount == 25){
                    imagepng($png,$spriteFile);
                    $newcount =   0;
                    $index   +=   1;
                    $dst_x    =   0;
                    $dst_y    =   0;
                    $spriteFile =   $filepath . '_sprite-'.$index.'.png';
                    $png = imagecreatetruecolor($spriteWidth,$spriteHeight);
                }
                $newcount++;
            }
            foreach (glob($filePrefix.'*'.$fileSuffix) as $value) {
                @unlink($value);
            }
            fclose($handle);
        }

        exec("{$TEMP['#settings']['binary_path']}/ffmpeg -y -i {$data['path']} -vcodec libx264 -hls_list_size 0 -preset {$TEMP['#settings']['convert_speed']} -filter:v scale=256:-2 -crf 26 {$filepath}_144p_load.m3u8 1>$fileLog 2>&1");
       	$dba->query('UPDATE videos SET converted = 1, 144p = 1, `path` = "'.$filepath.'_144p_load.m3u8" WHERE id = '.$data['insert']);         
        if($TEMP['#settings']['download_videos'] == 'on'){
            exec("{$TEMP['#settings']['binary_path']}/ffmpeg -y -i {$data['path']} -vcodec libx264 -preset {$TEMP['#settings']['convert_speed']} -filter:v scale=256:-2 -crf 26 {$filepath}_{$download_key}_144p_unload.mp4 2>&1");
            $dba->query('UPDATE videos SET download_key = "'.$download_key.'" WHERE id = '.$data['insert']);
        }
		foreach ($scales as $key => $value) {
			if ($data['video_width'] >= $key) {
	            exec("{$TEMP['#settings']['binary_path']}/ffmpeg -y -i {$data['path']} -vcodec libx264 -hls_list_size 0 -preset {$TEMP['#settings']['convert_speed']} -filter:v scale={$key}:-2 -crf 26 {$filepath}_{$value}p_load.m3u8");
	            $dba->query('UPDATE videos SET '.$value.'p = 1 WHERE id = '.$data['insert']);
	            if($TEMP['#settings']['download_videos'] == 'on'){
	            	exec("{$TEMP['#settings']['binary_path']}/ffmpeg -y -i {$data['path']} -vcodec libx264 -preset {$TEMP['#settings']['convert_speed']} -filter:v scale={$key}:-2 -crf 26 {$filepath}_{$download_key}_{$value}p_unload.mp4 2>&1");
	            }
	        }
		}
		if ($TEMP['#settings']['max_queue'] > 0) {
            $dba->query('DELETE FROM queue WHERE video_id = '.$data['insert']);
        }
        if($dba->query('SELECT COUNT(*) FROM broadcasts WHERE video_id = "'.$data['video_id'].'" AND live = 2')->fetchArray() > 0){
            $dba->query('UPDATE broadcasts SET live = 3 WHERE video_id = "'.$data['video_id'].'"');
        }
        if (file_exists($data['path'])) {
            @unlink($data['path']);
        }
        if(file_exists($fileLog)){
            @unlink($fileLog);
        }
        self::NotifySubscribers($data['insert']);
	}

	public static function Settings() {
	    global $dba;
	    $data  = array();
	    $settings = $dba->query('SELECT * FROM settings')->fetchAll();
	    foreach ($settings as $value) {
	        $data[$value['name']] = $value['value'];
	    }
	    return $data;
	}

	public static function AdultsOnly($by_id = 0) {
	    global $TEMP;
	    if ($TEMP['#loggedin'] === false) {
	        return false;
	    }
	    if ($TEMP['#user']['age'] < 18) {
	        return false;
	    }
	    return true;
	}

	public static function SubscribeButton($by_id = 0) {
	    global $TEMP, $dba;
	    if (empty($by_id)) {
	        return false;
	    }
	    if($by_id != $TEMP['#user']['id']){
		    $subs = "";
		    $subscriptions = $dba->query('SELECT COUNT(*) FROM subscriptions WHERE by_id = '.$by_id)->fetchArray();
		    if($subscriptions != 0){
		    	$subs = self::Number($subscriptions);
		    }
		    $is_subscribed_text  = $TEMP['#word']['subscribe'];
		    $is_subscribed_button = 'subscribe';
		    if ($TEMP['#loggedin'] === true) {
		        $is_blocked_user = !in_array($by_id, self::BlockedUsers(2)) == false ? ' disabled' : '';
		        if ($dba->query('SELECT COUNT(*) FROM subscriptions WHERE by_id = '.$by_id.' AND subscriber_id = '.$TEMP['#user']['id'])->fetchArray() > 0) {
		            $is_subscribed_text = $TEMP['#word']['subscribed'];
		            $is_subscribed_button = 'subscribed';
		        }
		    }
	    	$button = '<button class="background-trans btn-'.$is_subscribed_button.' vertical-center font-uppercase font-bold color-blue background-hover border-all border-blue subscribe-id-'.$by_id.'" id="bt-subscribe" data-id="'.$by_id.'" data-subs="'.$subs.'" data-type="'.$is_subscribed_button.'"'.$is_blocked_user.'>'.$is_subscribed_text.' <span class="margin-r5">'.$subs.'</span></button>';
	    } else {
	    	$button = '<a class="background-blue padding-10 font-medium font-bold font-uppercase color-white border-radius-4" href="'.self::Url('channel'.($TEMP['#page'] == 'watch' ? '?page=video-statistics&id='.$TEMP['video_id'] : '')).'" target="_self">'.$TEMP['#word']['statistics'].'</a>';
	    }
	    return $button;
	}

	public static function GetProgress($video_id = 0, $by_id = 0){
	    global $TEMP, $dba;
	    if($TEMP['#loggedin'] === false || $video_id == 0){
	        return false;
	    }
	    return $dba->query('SELECT * FROM progress WHERE by_id = '.($by_id == 0 ? $TEMP['#user']['id'] : $by_id).' AND video_id = '.$video_id)->fetchArray();
	}

	public static function BlockedUsers($type = 1) {
	    global $TEMP, $dba;

	    if ($TEMP['#loggedin'] === false || $TEMP['#settings']['blocked_users'] == 'off') {
	        return 0;
	    }
	    $data = 0;

	    $blocked_to = $dba->query('SELECT to_id FROM blocked WHERE by_id = '.$TEMP['#user']['id'].' AND to_id != '.$TEMP['#user']['id'])->fetchAll(FALSE);
	    $blocked_me = $dba->query('SELECT by_id FROM blocked WHERE by_id != '.$TEMP['#user']['id'].' AND to_id = '.$TEMP['#user']['id'])->fetchAll(FALSE);

	    if (!empty($blocked_to) || !empty($blocked_me)) {
	        $data = array_merge($blocked_to, $blocked_me);
	        if($type == 1) {
	        	$data = implode(',', $data);
	        }
	    }
	    return $data;
	}

	public static function BlockedVideos(){
	    global $TEMP, $dba;

	    if ($TEMP['#loggedin'] === false){
	        return 0;
	    }
	    $data = 0;
	    $query = $dba->query('SELECT id FROM videos WHERE by_id IN ('.$TEMP['#blocked_users'].') AND deleted = 0')->fetchAll();
	    if(!empty($query)){
	        $data = array();
	        foreach ($query as $value) {
	            $data[] = $value['id'];
	        }
	        $data = implode(',', $data);
	    }
	    return $data;
	}

	public static function Bubbles($data = array()){
	    global $dba;
	    $data = array();
	    $users = $dba->query("SELECT MAX(id) FROM users")->fetchArray();
	    if(!empty($users)){
	    	$count = 10;
		    $data = array();
		    for ($a = 0; $a < $count; $a++) {
		        $rand = rand(1, $users);
		        if (!in_array($rand, $data['rands'])) {
		            $bubble = self::Data($rand);
		            if(!empty($bubble)){
		                $data['avatar'][] = $bubble['avatar'];
		                $data['rands'][] = $rand;
		            } else {
		                $a = $a-1;
		            }
		        }
		    }
	    }
	    return $data;  
	}

	public static function Data($by_id = 0, $type = 1) {
	    global $TEMP, $dba;

	    if($type == 1){
	        $user = $dba->query('SELECT * FROM users WHERE id = "'.$by_id.'"')->fetchArray();
	    } else if($type == 2){
	        $user = $dba->query('SELECT * FROM users WHERE user_id = "'.$by_id.'"')->fetchArray();
	    } else if($type == 3){
	        $session_id = !empty($_SESSION['session_id']) ? $_SESSION['session_id'] : $_COOKIE['session_id'];
	        $by_id = $dba->query('SELECT by_id FROM sessions WHERE session_id = "'.$session_id.'"')->fetchArray();
	        $user = $dba->query('SELECT * FROM users WHERE id = '.$by_id)->fetchArray();
	    }
	    
	    if (empty($user)) {
	        return false;
	    }
	    $watermark = json_decode($user['watermark'], true);
	    $date_birthday = explode("-", date('d-m-Y', $user['date_birthday']));

	    $user['birthday'] = $date_birthday[0];
	    $user['birthday_month'] = $date_birthday[1];
	    $user['birthday_year'] = $date_birthday[2];

	    $user['age'] = date("md") < $date_birthday[1].$date_birthday[0] ? date("Y")-$date_birthday[2]-1 : date("Y")-$date_birthday[2];

	    $user['ex_watermark'] = $watermark['url'];
	    $user['watermark'] = self::GetFile($watermark['url']);
	    $user['watermark_start'] = $watermark['time'];

	    $user['ex_avatar'] = $user['avatar'];
	    
	    $type = 1;
	    if($user['avatar'] == 'images/default-avatar.jpg' || $user['avatar'] == 'images/default-favatar.jpg'){
	        $type = 2;
	    }
	    $user['avatar'] = self::GetFile($user['avatar'], $type);

	    $user['ex_cover']  = $user['cover'];
	    $cover_changed = $user['cover_changed'];
	    $type = 1;
	    if($user['cover'] == 'images/default-cover.jpg'){
	        $type = 2;
	    }
	    $user['cover']  = self::GetFile($user['cover'], $type) . ($cover_changed != 0 ? "?t=$cover_changed" : "");

	    $user['url']    = self::Url('user/' . $user['user_id']);
	    $user['about_uncompose'] = self::GetUncomposeText($user['about'], 0, true);
	    $user['date_time'] = self::DateFormat($user['time']);
	    $user['time'] = self::DateString($user['time']);

	    $ext  = array_pop(explode('.', $user['ex_cover']));
	    $dir  = reset(explode('.', $user['ex_cover']));
	    $user['full_cover_media'] = self::GetFile($dir . '_full.' . $ext);
	    $user['full_cover'] = $dir . '_full.' . $ext;

	    $user['name'] = $user['first_name'];
	    if(empty($user['first_name'])){
	        $user['name'] = $user['username'];
	    }

	    $user['country_name']  = $TEMP['#countries'][$user['country']];
	    $gender = $TEMP['#word']['male'];
	    if($user['gender'] == 2){
	        $gender = $TEMP['#word']['female'];
	    }
	    $user['gender_text'] = $gender;

	    $user['user_v']   = '<div class="cont-user-publisher margin-r10 vertical-center"><a class="color-grey" href="'. $user['url'] .'" target="_self">' . $user['username'] . '</a></div>';
	    if ($user['verified'] == 1 && $TEMP['#settings']['verification_badge'] == 'on') {
	        $user['user_v'] = '<div class="cont-user-publisher margin-r10 vertical-center"><a class="color-grey tabbable" class="color-grey" href="'. $user['url'] .'" target="_self">' . $user['username'] . '</a>' . '<div class="vertical-center" data-title="'. $TEMP['#word']['verified_account'] .'"><svg class="verified" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M 18.719 9.891 C 18.719 9.648 18.641 9.448 18.483 9.291 L 17.3 8.12 C 17.133 7.956 16.94 7.872 16.713 7.872 C 16.488 7.872 16.292 7.956 16.128 8.12 L 10.814 13.419 L 7.872 10.476 C 7.708 10.311 7.512 10.23 7.287 10.23 C 7.06 10.23 6.867 10.311 6.7 10.476 L 5.517 11.648 C 5.359 11.806 5.281 12.003 5.281 12.248 C 5.281 12.481 5.359 12.678 5.517 12.833 L 10.229 17.547 C 10.393 17.711 10.589 17.794 10.814 17.794 C 11.049 17.794 11.249 17.711 11.414 17.547 L 18.483 10.476 C 18.641 10.32 18.719 10.124 18.719 9.891 Z M 22 12 C 22 13.814 21.553 15.487 20.659 17.02 C 19.766 18.552 18.552 19.764 17.019 20.659 C 15.487 21.553 13.813 22 12 22 C 10.187 22 8.513 21.553 6.981 20.659 C 5.448 19.764 4.234 18.552 3.341 17.02 C 2.447 15.487 2 13.814 2 12 C 2 10.186 2.447 8.513 3.341 6.98 C 4.234 5.448 5.448 4.236 6.981 3.341 C 8.513 2.447 10.187 2 12 2 C 13.813 2 15.487 2.447 17.019 3.341 C 18.552 4.236 19.766 5.448 20.659 6.98 C 21.553 8.513 22 10.186 22 12 Z"/></svg></div></div>';
	    }
	    return $user;
	}

	public static function Video($video_id, $short_id = false) {
	    global $TEMP, $dba;
	    if (empty($video_id)) {
	        return false;
	    }
	    if (!is_array($video_id)) {
	    	$query = ' AND id = '.$video_id;
	    	if(!is_numeric($video_id)){
	    		if($short_id == false){
	    			$query = ' AND video_id = "'.$video_id.'"';
	    		} else {
	    			$query = ' AND short_id = "'.$video_id.'"';
	    		}
	    	}
	    	$video = $dba->query('SELECT * FROM videos WHERE by_id NOT IN ('.$TEMP['#blocked_users'].')'.$query)->fetchArray();
	    } else {
	        $video = $video_id;
	    }
	    if (!empty($video)) {
	        $video['ex_thumbnail'] = $video['thumbnail'];
	        $video['ex_thumbnail_1'] = $video['thumbnail_1'];
	        $video['ex_thumbnail_2'] = $video['thumbnail_2'];
	        $video['ex_thumbnail_3'] = $video['thumbnail_3'];
	        $video['ex_thumbnail_draft'] = $video['thumbnail_draft'];
	        
	        $video['thumbnail']       = self::GetFile($video['thumbnail']); 
	        $video['thumbnail_1']     = self::GetFile($video['thumbnail_1']); 
	        $video['thumbnail_2']     = self::GetFile($video['thumbnail_2']);
	        $video['thumbnail_3']     = self::GetFile($video['thumbnail_3']);
	        $video['thumbnail_draft'] = self::GetFile($video['thumbnail_draft']);

	        $video['path']  		  	  = self::GetFile($video['path']);
	        $video['title']               = self::Censor($video['title']);
	        $video['url']                 = self::WatchSlug($video['video_id']);
	        $video['compose_description'] = self::GetComposeText($video['description']);
	        $video['data']                = self::Data($video['by_id']);
	        $video['is_liked']            = 0;
	        $video['is_disliked']         = 0;
	        $video['is_owner']            = false;
	        $video['live']				  = ($dba->query('SELECT COUNT(*) FROM broadcasts WHERE video_id = "'.$video['video_id'].'" AND live != 3')->fetchArray() == 0 || $video['broadcast'] == 0);
	        if ($TEMP['#loggedin'] === true) {
	            $video['is_liked'] = $dba->query('SELECT COUNT(*) FROM reactions WHERE to_id = '.$video['id'].' AND type = 1 AND place = "video" AND by_id = '.$TEMP['#user']['id'])->fetchArray();
	            $video['is_disliked'] = $dba->query('SELECT COUNT(*) FROM reactions WHERE to_id = '.$video['id'].' AND type = 2 AND place = "video" AND by_id = '.$TEMP['#user']['id'])->fetchArray();

	            if ($video['data']['id'] == $TEMP['#user']['id'] || self::Admin()) {
	                $video['is_owner'] = true;
	            }
	        }
	        $video['time_format'] = self::DateFormat($video['time']);
	        $video['time_string'] = self::DateString($video['time']);
	        $video['category_name'] = $TEMP['#categories'][$video['category']];
	        $video['animation'] = self::GetFile($video['animation']);
	        return $video;
	    }
	    return array();
	}

	public static function DeleteVideo($id = 0) {
	    global $TEMP, $dba;
	    if (empty($id)) {
	        return false;
	    }

	    $video = $dba->query('SELECT * FROM videos WHERE id = '.$id.' AND deleted = 0')->fetchArray();
	    if(!empty($video)){
		    if ($video['thumbnail'] != 'uploads/images/thumbnail.jpg') {
			    if (file_exists($video['thumbnail'])) {
			        @unlink($video['thumbnail']);
			    }
			    if(file_exists($video['thumbnail_1'])){
			        @unlink($video['thumbnail_1']);
			    }
			    if(file_exists($video['thumbnail_2'])){
			        @unlink($video['thumbnail_2']);
			    }
			    if(file_exists($video['thumbnail_3'])){
			        @unlink($video['thumbnail_3']);
			    }
			    if(file_exists($video['thumbnail_draft'])){
			       	@unlink($video['thumbnail_draft']);
			    }
		    }

		    $video_path = explode('_video', $video['path']);
		    $download_key = $video['download_key'];
		    if ($video['converted'] == 0 && $TEMP['#settings']['max_queue'] > 0) {
                $dba->query('DELETE FROM queue WHERE video_id = '.$video['id']);
                @unlink('uploads/ffmpeg/ffmpeg-'.$video['video_id'].'.txt');
            }
		    if (!empty($video_path)) {
		        $dir = $video_path[0].'_video_*';     
		        $files = glob($dir);
		        foreach($files as $file){
		            @unlink($file);
		        }
		        if (!empty($download_key)) {
		            $dirDown = $video_path[0].'_video_'.$download_key.'_*';     
		            $filesDown = glob($dirDown);
		            foreach($filesDown as $fileDown){
		                @unlink($fileDown);
		            }
		        }
		        if (!empty($video['animation'])) {
		            @unlink($video['animation']);
		        }
		    }

		    $user = $dba->query('SELECT * FROM users WHERE id = '. $video['by_id'])->fetchArray();
		    $dba->query('UPDATE users SET uploads = '.($user['uploads'] - $video['size']).' WHERE id = '.$video['by_id']);

		    $comments = $dba->query('SELECT * FROM comments WHERE video_id = '.$id)->fetchAll();
		    foreach ($comments as $value) {
		        $dba->query('DELETE FROM reactions WHERE to_id = '.$value['id'].' AND place = "commentary"');
		        $replies = $dba->query('SELECT * FROM replies WHERE comment_id = '.$value['id'])->fetchAll();
		        $dba->query('DELETE FROM replies WHERE comment_id = '.$value['id']);
		        $dba->query('DELETE FROM notifications WHERE notify_key = "'.$video['video_id'].'&cl='.$value['id'].'"');
		        foreach ($replies as $value) {
		            $dba->query('DELETE FROM reactions WHERE to_id = '.$value['id'].' AND place = "reply"');
		            $dba->query('DELETE FROM notifications WHERE notify_key = "'.$video['video_id'].'&rl='.$value['id'].'"');
		        }
		    }

		    if($video['broadcast'] == 1){
		    	$dba->query('DELETE FROM broadcasts WHERE video_id = "'.$video['video_id'].'"');
		    	$dba->query('DELETE FROM broadcasts_chat WHERE video_id = '.$video['id']);
		    }

		    $dba->query('DELETE FROM reports WHERE to_id = '.$id);
		    $dba->query('DELETE FROM history WHERE video_id = '.$id);
		    $dba->query('DELETE FROM reactions WHERE place = "video" AND to_id = '.$id);
		    $dba->query('DELETE FROM watch_later WHERE video_id = '.$id);
		    $dba->query('DELETE FROM playlists WHERE video_id = '.$id);
		    $dba->query('DELETE FROM views WHERE video_id = '.$id);
		    $dba->query('DELETE FROM progress WHERE video_id = '.$id);
		    $dba->query('DELETE FROM notifications WHERE notify_key = "'.$video['video_id'].'"');

		    if ($dba->query('UPDATE videos SET `path` = ?, download_key = ?, animation = ?, thumbnail = ?, thumbnail_draft = ?, thumbnail_1 = ?, thumbnail_2 = ?, thumbnail_3 = ?, 144p = ?, 240p = ?, 360p = ?, 480p = ?, 720p = ?, 1080p = ?, 1440p = ?, 2160p = ?, deleted = ? WHERE id = '.$id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 1)->returnStatus()) {
		        return true;
		    }
		}
	    return false;
	}

	public static function DeleteUser($id = 0) {
	    global $TEMP, $dba;
	    if (empty($id)) {
	        return false;
	    }
	    if ($TEMP['#user']['id'] != $id && self::Admin() == false) {
	       	return false;
	    }
	    $videos = $dba->query('SELECT id FROM videos WHERE by_id = '.$id.' AND deleted = 0')->fetchArray();
	    foreach ($videos as $value) {
	        self::DeleteVideo($value['id']);
	    }
	    $data = self::Data($id);
	    if ($data['avatar'] != 'images/default-avatar.jpg') {
	        @unlink($data['avatar']);
	    }
	    if ($data['cover'] != 'images/default-cover.jpg') {
	        @unlink($data['cover']);
	    }
	    $dba->query('DELETE FROM blocked WHERE to_id = '.$id);
	    $dba->query('DELETE FROM notifications WHERE from_id = '.$id);
	    $dba->query('DELETE FROM replies WHERE by_id = '.$id);
	    $dba->query('DELETE FROM comments WHERE by_id = '.$id);
	    $dba->query('DELETE FROM broadcasts WHERE by_id = '.$id);
	    $dba->query('DELETE FROM broadcasts_chat WHERE by_id = '.$id);
	    $dba->query('DELETE FROM sessions WHERE by_id = '.$id);
	    $dba->query('DELETE FROM playlists WHERE by_id = '.$id);
	    $dba->query('DELETE FROM watch_later WHERE by_id = '.$id);
	    $dba->query('DELETE FROM history WHERE by_id = '.$id);
	    $dba->query('DELETE FROM reports WHERE by_id = '.$id);
	    $dba->query('DELETE FROM reports WHERE to_id = '.$id);
	    if($dba->query('DELETE FROM users WHERE id = '.$id)->returnStatus()){
	        return true;
	    }
	}

	public static function BroadcastChat($video_id, $type = 'html', $broadcast_time = 30, $query = ''){
		global $dba, $TEMP;
	    if($type == 'html'){
	    	$data = '';
	    	$broadcast_text = $dba->query('SELECT * FROM broadcasts_chat WHERE video_id = '.$video_id.$query.' ORDER BY id ASC LIMIT ? OFFSET ?', 10, 'reverse')->fetchAll();
	    } else if($type == 'json'){
			$data = array();
	    	$broadcast_text = $dba->query('SELECT * FROM broadcasts_chat WHERE video_id = '.$video_id.$query.' AND broadcast_time < '.$broadcast_time.' ORDER BY id ASC')->fetchAll();
	    }
	    if(!empty($broadcast_text)){
		    foreach ($broadcast_text as $value) {
		    	$TEMP['!owner_username'] = $value['by_id'] == $dba->query('SELECT by_id FROM videos WHERE id = '.$video_id)->fetchArray() ? ' owner-username' : '';
		        $TEMP['!data'] = self::Data($value['by_id']);
		        $TEMP['!id']   = $value['id'];
		        $TEMP['!text'] = self::Censor($value['text']);
		        if($type == 'html'){
		            $data .= self::Maket('live-broadcast/includes/broadcast-text');
		        } else if($type == 'json'){
		            $html_data = array('id' => $value['id'], 'html' => self::Maket('live-broadcast/includes/broadcast-text'));
		            $data[$value['broadcast_time']][] = $html_data;
		        }
		    }
		    self::DestroyMaket();
		    $data = $type == 'html' ? $data : addslashes(json_encode($data));
		}
	    return $data;
	}

	public static function SendEmail($data = array()) {
	    global $TEMP;

	    $mail = new PHPMailer();
	    $subject = self::Filter($data['subject']);
	    if(empty($data['is_html']) || !isset($data['is_html'])){
	    	$data['is_html'] = false;
	    }
	    if ($TEMP['#settings']['server_type'] == 'smtp') {
	        $mail->isSMTP();
	        $mail->Host        = $TEMP['#settings']['smtp_host'];
	        $mail->SMTPAuth    = true;
	        $mail->Username    = $TEMP['#settings']['smtp_username'];
	        $mail->Password    = $TEMP['#settings']['smtp_password'];
	        $mail->SMTPSecure  = $TEMP['#settings']['smtp_encryption'];
	        $mail->Port        = $TEMP['#settings']['smtp_port'];
	        $mail->SMTPOptions = array(
	            'ssl' => array(
	                'verify_peer' => false,
	                'verify_peer_name' => false,
	                'allow_self_signed' => true
	            )
	        );
	    } else {
	        $mail->IsMail();
	    }

	    $content = $data['message_body'];
	    if($data['is_html'] == true){
	    	$TEMP['title'] = $subject;
		    $TEMP['body'] = $content;
		    $content = self::Maket('emails/content');
	    }
	    $mail->IsHTML($data['is_html']);
	    if(!empty($data['reply_to'])){
	    	$mail->addReplyTo($data['reply_to'], $data['from_name']);
	    }
	    $mail->setFrom(self::Filter($data['from_email']), $data['from_name']);
	    $mail->addAddress(self::Filter($data['to_email']), $data['to_name']);
	    $mail->Subject = $subject;
	    $mail->CharSet = $data['charSet'];
	    $mail->MsgHTML($content);
	    if ($mail->send()) {
	        return true;
	    }
	    return false;
	}

	public static function WatchSlug($video_id, $type = 'watch', $list_id = 0){
	    $url = self::Url('watch?v=' . $video_id);
	    if($type == 'playlist'){
	        $url = self::Url('watch?v=' . $video_id . '&list=' . $list_id);
	    }
	    return $url;
	}

	public static function Number($views) {
	    $thousand = 1000;
	    $ten_thousand = 10000;
	    $hundred_thousand = 100000;
	    $one_million = 1000000;
	    $ten_million = 10000000;
	    $one_hundred_million = 100000000;
	    $billion = 1000000000;
	    $ten_thousand_millions = 10000000000;
	    
	    $views_sec = substr($views, 1, 1);
	    $views_third = substr($views, 2, 1);
	    $views_format = number_format($views,2,",",".");
	    $suffix = ' M';
	    if($views < $one_million){
	    	$suffix = ' K';
	    }
	    if($views >= $ten_thousand_millions){
	        $pos = 2;
	        $suffix = ' B';
	    } else if(($views >= $billion) && ($views < $ten_thousand_millions)){
	        $pos = 4;
	        $views_format = $views;
	    } else if(($views >= $one_hundred_million) && ($views < $billion) || ($views >= $hundred_thousand) && ($views < $one_million)){
	        $pos = 3;
	    } else if(($views >= $ten_million) && ($views < $one_hundred_million) || ($views >= $ten_thousand) && ($views < $hundred_thousand)){
	        if($views_third != 0){
	            $pos = 4;
	        }else {
	            $pos = 2;
	        }
	    } else if(($views >= $one_million) && ($views < $ten_million) || ($views >= $thousand) && ($views < $ten_thousand)){   
	        if ($views_sec != 0) {
	            $pos = 3;
	        }else {
	            $pos = 1;
	        }
	    } else {
	        return $views;
	    }
	    return substr($views_format, 0, $pos).$suffix;  
	}

	public static function IsBanned($banned = ''){
	    global $TEMP, $dba;
	    if(!empty($banned)){
	    	if($TEMP['#loggeding'] == true && $dba->query('SELECT COUNT(*) FROM users WHERE id = '.$TEMP['#user']['id'].' AND (role = 1 OR role = 2)')->fetchArray() > 0){
	    		return false;
	    	}
	        return ($dba->query('SELECT COUNT(*) FROM banned WHERE value = "'.$banned.'"')->fetchArray() > 0);
	    }
	    return false;
	}

	public static function DurationToSeconds($duration) {
	    $time = explode(":", $duration);
	    if(count($time) == 2){
	        $duration = ($time[0]*60)+$time[1];
	    }else{
	        $duration = ($time[0]*3600)+($time[1]*60)+$time[2];
	    }
	    return $duration;
	}

	public static function SecondsToDuration($seconds) {
	    $duration = '00:00';
	    $date = $seconds < 3600 ? "i:s" : "h:i:s";
	    if($seconds != 0){
	        $duration = date($date, $seconds);
	    }
	    return $duration;
	}

	public static function GetUncomposeText($text, $length = 0, $break = false, $decode = true) {
	    if(!empty($text)){
	        if (preg_match_all('/\[URL\](.*?)\[\/URL\]/i', $text, $urls)) {
	            foreach ($urls[1] as $value) {
	                $match_decode = $decode == true ? urldecode($value) : addslashes($value) ;
	                if (!preg_match("/http(|s)\:\/\//", $match_decode)) {
	                    $match_decode = 'http://' . $match_decode;
	                }
	                $text = str_replace('[URL]' . $value . '[/URL]', $match_decode, $text);
	            }
	        }
	        if(preg_match_all('/\[T\](.*?)\[\/T\]/i', $text, $durations)){
	            foreach ($durations[1] as $value) {
	                $text = str_replace('[T]' . $value . '[/T]', $value, $text);
	            }
	        }
	        if($length != 0){
	            $text = str_replace('"', "'", $text);
	            $text = str_replace('<br>', "", $text);
	            $text = str_replace("\n", "", $text);
	            $text = str_replace("\r", "", $text);
	            $text = mb_substr($text, 0, $length, "UTF-8").'...';     
	        } 
	        if($break == true){
	            $text = str_ireplace(array("<br />","<br>","<br/>"), "\r\n", $text);
	        }
	    }
	    return $text;
	}

	public static function GetComposeText($text){
	    if (preg_match_all('/\[URL\](.*?)\[\/URL\]/i', $text, $urls)) {
	        foreach ($urls[1] as $value) {
	            $match_decode     = urldecode($value);
	            $match_url = $match_decode;
	            if (strlen($match_decode) > 60) {
	                $match_decode = mb_substr($match_decode, 0, 60). '...';
	            }
	            if (!preg_match("/http(|s)\:\/\//", $match_decode)) {
	                $match_url = 'http://' . $match_url;
	            }
	            $text = str_replace('[URL]' . $value . '[/URL]', '<a href="' . strip_tags($match_url) . '" target="_blank" class="color-blue" rel="nofollow">' . $match_decode . '</a>', $text);
	        }
	    }
	    if (preg_match_all('/\[T\](.*?)\[\/T\]/i', $text, $durations)) {
	        foreach ($durations[1] as $value) {
	            $time = explode(":", $value);
	            if(count($time) == 2){
	                $current_time = ($time[0]*60)+$time[1];
	            }else{
	                $current_time = ($time[0]*3600)+($time[1]*60)+$time[2];
	            }

	            $text = str_replace('[T]' . $value . '[/T]', '<button class="btn-trans color-blue" id="time-goto" data-duration="'.$current_time.'">' . $value . '</button>', $text);
	        }
	    }
	    return self::Censor($text);
	}

	public static function ComposeText($text, $duration = -1){
	    $text = self::Filter($text);
	    if(preg_match_all('/(http\:\/\/|https\:\/\/|www\.)([^\ ]+)/i', $text, $urls)){
	        foreach ($urls[0] as $value) {
	            $url = strip_tags($value);
	            $text = str_replace($value, '[URL]' . urlencode($url) . '[/URL]', $text);
	        }
	    }
	    if($duration != -1 && preg_match_all('/[0-9]*:[0-9]*:[0-9]{2}/i', $text, $durations) || preg_match_all('/[0-9]*:[0-9]{2}/i', $text, $durations)){
	        foreach ($durations[0] as $value) {
	            if(self::DurationToSeconds($value) < self::DurationToSeconds($duration)) {
	                $text = str_replace($value, '[T]' . $value . '[/T]', $text);
	            }
	        }
	    }
	    return $text;
	}

	public static function SendNotification($data = array()){
	    global $TEMP, $dba;

	    $to_id = $data['to_id'];
	    $from_id = $data['from_id'];
	    $type  = $data['type'];

	    if ((!empty($to_id) && in_array($to_id, $TEMP['#blocked_users'])) || (empty($data) || !is_array($data)) || $TEMP['#user']['id'] == $to_id) {
	        return false;
	    }
	    $time = $dba->query("SELECT `time` FROM notifications WHERE from_id = $from_id AND to_id = $to_id AND type = $type")->fetchArray();
	    if(!empty($time)){
	    	$time = $time + 1800;
		    if($dba->query("SELECT COUNT(*) FROM notifications WHERE from_id = $from_id AND to_id = $to_id AND type = $type AND `time` < $time")->fetchArray() > 0){
		    	return false;
		    }
	    }
	    return $dba->query('INSERT INTO notifications ('.implode(',', array_keys($data)).') VALUES ('.implode(',', array_values($data)).')')->insertId();
	}

	public static function FfprobeData($filename = ''){
	    global $TEMP;

	    exec($TEMP['#settings']['binary_path']."/ffprobe -i {$filename} -v quiet -print_format json -show_format -show_streams -hide_banner", $output, $error);
	    if($error == 1){ 
	        return false;
	    } else {
	        $metadata = json_decode(implode($output));
	        $time = $metadata->format->duration;
	        $data = array(
	            'seconds' => $time,
	            'duration' => self::SecondsToDuration($time),
	            'width' => $metadata->streams[0]->width,
	            'height' => $metadata->streams[0]->height,
	            'size' => $metadata->format->size
	        );
	        if(pathinfo($filename, PATHINFO_EXTENSION) == 'flv'){
	        	$data['width'] = $metadata->streams[2]->width;
	        	$data['height'] = $metadata->streams[2]->height;
	        }
	    }
	    return $data;
	}

	public static function FfmpegProcessing($video_id){
	    $data = array('status' => 400, 'progress' => 0, 'reload' => 0);
	    $file = "uploads/ffmpeg/ffmpeg-$video_id.txt";
	    $content = @file_get_contents($file);
	    if($content){
	        preg_match("/Duration: (.*?), start:/", $content, $matches);
	        $rawDuration = $matches[1];
	        $ar = array_reverse(explode(":", $rawDuration));
	        $duration = floatval($ar[0]);
	        if (!empty($ar[1])) $duration += intval($ar[1]) * 60;
	        if (!empty($ar[2])) $duration += intval($ar[2]) * 60 * 60;
	        preg_match_all("/time=(.*?) bitrate/", $content, $matches);
	        $rawTime = array_pop($matches);
	        if (is_array($rawTime)){
	            $rawTime = array_pop($rawTime);
	        }
	        $ar = array_reverse(explode(":", $rawTime));
	        $time = floatval($ar[0]);
	        if (!empty($ar[1])) $time += intval($ar[1]) * 60;
	        if (!empty($ar[2])) $time += intval($ar[2]) * 60 * 60;
	        $data['status'] = 200;
	        $data['progress'] = round(($time/$duration) * 100);
	        if($data['progress'] == 100){
	            $data['reload'] = 1;
	            @unlink($file);
	        }
	    }
	    return $data;
	}

	public static function DataStream() {
		global $TEMP;
		$xml = simplexml_load_file(self::Url("stat.xsl"));
		$to_json = json_encode($xml);
		return json_decode($to_json, true);
	}

	public static function Url($params = '') {
	    global $site_url;
	    return $site_url . '/' . $params;
	}

	public static function GetSessions($value = array()){
	    $data = array();
	    $data['ip'] = 'Unknown';
	    $data['browser'] = 'Unknown';
	    $data['platform'] = 'Unknown';
	    if (!empty($value['details'])) {
	        $session = json_decode($value['details'], true);
	        $data['ip'] = $session['ip'];
	        $data['browser'] = $session['name'];
	        $data['platform'] = ucfirst($session['platform']);
	    }
	    return $data;
	}

	public static function RandomKey($minlength = 12, $maxlength = 20) {
		$length = mt_rand($minlength, $maxlength);
	    return substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"), 0, $length);
	}

	public static function TokenSession() {
	    $token = md5(self::RandomKey(60, 70));
	    if (!empty($_SESSION['session_id'])) {
	        return $_SESSION['session_id'];
	    }
	    $_SESSION['session_id'] = $token;
	    return $token;
	}

	public static function DateString($time, $is_string = true) {
	    global $TEMP;
	    if($is_string == true){
	        $diff = time() - $time;
	        if ($diff < 1) {
	            return $TEMP['#word']['now'];
	        }
	    } else {
	        $diff = $time - time();
	    }
	    $dates = array(
	        31536000 => array($TEMP['#word']['year'], $TEMP['#word']['years']),
	        2592000 => array($TEMP['#word']['month'], $TEMP['#word']['months']),
	        86400 => array($TEMP['#word']['day'], $TEMP['#word']['days']),
	        3600 => array($TEMP['#word']['hour'], $TEMP['#word']['hours']),
	        60 => array($TEMP['#word']['minute'], $TEMP['#word']['minutes']),
	        1 => array($TEMP['#word']['second'], $TEMP['#word']['seconds'])
	    );
	    foreach ($dates as $key => $value) {
	        $was = $diff/$key;
	        if ($was >= 1) {
	            $was_int = intval($was);
	            $string = $was_int > 1 ? $value[1] : $value[0];
	            return in_array($TEMP['#language'], array('es')) ? "{$TEMP['#word']['does']} $was_int $string" : "$was_int $string {$TEMP['#word']['does']}";
	        }
	    }
	}

	public static function DateFormat($ptime) {
	    global $TEMP; 
	    $date = date("j-m-Y", $ptime); 
	    $month = strtolower(strftime("%B", strtotime($date))); 
	    $month = $TEMP['#word'][$month];
	    $B = mb_substr($month, 0, 3, 'UTF-8');     
	    $dateFinaly = strftime("%e " . $B . ". %Y", strtotime($date));
	    return $dateFinaly;
	}

	public static function BytesFormat($bytes) {
	    $size = array(
	        '0' => '0MB',
	        '8000000' => '8MB',
	        '16000000' => '16MB',
	        '32000000' => '32MB',
	        '64000000' => '64MB',
	        '128000000' => '128MB',
	        '256000000' => '256MB',
	        '512000000' => '512MB',
	        '1000000000' => '1GB',
	        '5000000000' => '5GB'
	    );
	    return $size[$bytes];
	}

	public static function Words($language = 'en', $type = 0, $paginate = false, $page = 1, $keyword = ''){
	    global $TEMP, $dba;
	    $data   = array();
	    $query = '';
	    if(!empty($keyword)){
	        $query = " WHERE word LIKE '%$keyword%' OR `$language` LIKE '%$keyword%'";
	    }
	    if($paginate == true){
	        $data['sql'] = $dba->query('SELECT * FROM words'.$query.' LIMIT ? OFFSET ?', $TEMP['#settings']['data_load_limit'], $page)->fetchAll();
	        $data['total_pages'] = $dba->totalPages;
	    } else {
	        $sql = $dba->query('SELECT word,'.$language.' FROM words')->fetchAll();
	    }
	    if($type == 0){
	        foreach ($sql as $value) {
	            $data[$value['word']] = $value[$language];
	        }
	    }
	    return $data;
	}

	public static function Categories($paginate = false, $page = 1){
	    global $TEMP, $dba;
	    $data = array();
	    if($paginate == true){
	        $query = $dba->query('SELECT * FROM types WHERE type = "category" LIMIT ? OFFSET ?', $TEMP['#settings']['data_load_limit'], $page)->fetchAll();
	        $data['total_pages'] = $dba->totalPages;
	    } else {
	        $query = $dba->query('SELECT * FROM types WHERE type = "category"')->fetchAll();
	    }
	    foreach ($query as $value) {
	        $word = $value['name'];
	        if (!empty($TEMP['#word'][$word])) {
	            if($paginate == true){
	                $data['sql'][$word] = $TEMP['#word'][$word];
	            } else {
	                $data[$word] = $TEMP['#word'][$word];
	            }
	        }
	    }
	    return $data;
	}

	public static function IsOwner($by_id, $type = 1) {
	    global $TEMP;
	    if($type == 1){
	        if($TEMP['#user']['id'] == $by_id || self::Admin()){
	            return true;
	        }
	    } else {
	        if ($TEMP['#loggedin'] === true) {
	            if ($TEMP['#user']['id'] == $by_id) {
	                return true;
	            }
	        }
	    }
	    return false;
	}

	public static function BrowserDetails() {
	    $u_agent = $_SERVER['HTTP_USER_AGENT'];
	    $is_mobile = false;
	    $bname = 'Unknown';
	    $platform = 'Unknown';
	    $version = "";

	    // Is mobile platform?
	    if (preg_match("/(android|Android|ipad|iphone|IPhone|ipod)/i", $u_agent)) {
	        $is_mobile = true;
	    }

	    // First get the platform?
	    // First get the platform?
		if (preg_match('/linux/i', $u_agent)) {
		    $platform = 'Linux';
		} elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
		    $platform = 'Mac';
		} elseif (preg_match('/windows|win32/i', $u_agent)) {
		    $platform = 'Windows';
		} else if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', $u_agent)){
			$platform = 'Mobile';
		} else if(preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', $u_agent)){
			$platform = 'Tablet';
		}


	    // Next get the name of the useragent yes seperately and for good reason
	    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) {
	        $bname = 'Internet Explorer';
	        $ub = "MSIE";
	    } elseif(preg_match('/Firefox/i',$u_agent)) {
	        $bname = 'Mozilla Firefox';
	        $ub = "Firefox";
	    } elseif(preg_match('/Chrome/i',$u_agent)) {
	        $bname = 'Google Chrome';
	        $ub = "Chrome";
	    } elseif(preg_match('/Safari/i',$u_agent)) {
	        $bname = 'Apple Safari';
	        $ub = "Safari";
	    } elseif(preg_match('/Opera/i',$u_agent)) {
	        $bname = 'Opera';
	        $ub = "Opera";
	    } elseif(preg_match('/Netscape/i',$u_agent)) {
	        $bname = 'Netscape';
	        $ub = "Netscape";
	    }

	    // finally get the correct version number
	    $known = array('Version', $ub, 'other');
	    $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
	    if (!preg_match_all($pattern, $u_agent, $matches)) {
	        // we have no matching number just continue
	    }
	    // see how many we have
	    $i = count($matches['browser']);
	    if ($i != 1) {
	        //we will have two since we are not using 'other' argument yet
	        //see if version is before or after the name
	        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
	            $version= $matches['version'][0];
	        } else {
	            $version= $matches['version'][1];
	        }
	    } else {
	        $version= $matches['version'][0];
	    }

	    // check if we have a number
	    if ($version == null || $version == "") {
	        $version="?";
	    }
	    return array(
	        'validate' => array(
	            'is_mobile' => $is_mobile
	        ),
	        'details' => array(
	            'ip' => self::GetClientIp(),
	            'userAgent' => $u_agent,
	            'name' => $bname,
	            'version' => $version,
	            'platform'  => $platform,
	            'pattern' => $pattern
	        )
	    );
	}

	public static function Languages($query = 'type') {
	    global $dba;
	    return $dba->query('SELECT '.$query.' FROM types WHERE type != "category" ORDER BY id ASC')->fetchAll(FALSE);
	}

	public static function Language($lang = 'en'){
		global $TEMP, $dba;
		if ($TEMP['#loggedin'] == true) {
		    if (!empty($TEMP['#user']['language']) && in_array($TEMP['#user']['language'], $TEMP['#languages'])) {
		        $language = $TEMP['#user']['language'];
		    }
		}
		if (isset($lang) && !empty($lang)) {
		    $lang = strtolower($lang);
		    if (in_array($lang, $TEMP['#languages'])) {
		        $language = $lang;
		        if ($TEMP['#loggedin'] == true) {
		            $dba->query('UPDATE users SET language = "'.$lang.'" WHERE id = '.$TEMP['#user']['id']);
		        }
		    }
		}
		if (empty($language)) {
		    $language = $TEMP['#settings']['language'];
		}
		return $language;
	}

	public static function GetClientIp() {
	    foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'] as $value) {
	        if (array_key_exists($value, $_SERVER) ) {
	            foreach (array_map('trim', explode(',', $_SERVER[$value])) as $ip) {
	                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE || FILTER_FLAG_NO_RES_RANGE) !== false) {
	                    return $ip;
	                }
	            }
	        }
	    }
	    return '?';
	}

	public static function DestroyMaket(){
	    global $TEMP;
	    unset($TEMP['!data']);
	    foreach ($TEMP as $key => $value) {
	        if(substr($key, 0, 1) === '!'){
	            unset($TEMP[$key]);
	        }
	    }
	    return $TEMP;
	}

	public static function Maket($page){
	    global $TEMP;
	    $file = "./themes/".$TEMP['#settings']['theme']."/html/$page.html";
	    if(!file_exists($file)){
	    	exit("No found: $file");
	    }
	    ob_start();
	    require($file);
	    $html = ob_get_contents();
	    ob_end_clean();

	    $page = preg_replace_callback('/{\$word->(.+?)}/i', function($matches) use ($TEMP) {
	        return (isset($TEMP['#word'][$matches[1]])?addslashes($TEMP['#word'][$matches[1]]):"");
	    }, $html);
	    $page = preg_replace_callback('/{\$settings->(.+?)}/i', function($matches) use ($TEMP) {
	        return (isset($TEMP['#settings'][$matches[1]])?$TEMP['#settings'][$matches[1]]:"");
	    }, $page);
	    $page = preg_replace_callback('/{\$theme->\{(.+?)\}}/i', function($matches) use ($TEMP) {
	        return self::Url("themes/".$TEMP['#settings']['theme']."/".$matches[1]);
	    }, $page);
	    $page = preg_replace_callback('/{\$url->\{(.+?)\}}/i', function($matches) use ($TEMP) {
	        return self::Url($matches[1]!="home"?$matches[1]:"");
	    }, $page);
	    $page = preg_replace_callback('/{\$data->(.+?)}/i', function($matches) use ($TEMP) {
	        return (isset($TEMP['data'][$matches[1]])?$TEMP['data'][$matches[1]]:"");
	    }, $page);
	    $page = preg_replace_callback('/{\#([a-zA-Z0-9_]+)}/i', function($matches) use ($TEMP) {
	        $match = $TEMP["#".$matches[1]];
	        if(is_bool($match)){
	        	$match = json_encode($match);
	        }
	        return (isset($match)?$match:"");
	    }, $page);
	    $page = preg_replace_callback('/{\$([a-zA-Z0-9_]+)}/i', function($matches) use ($TEMP) {
	    	$match = $TEMP[$matches[1]];
	    	if(is_bool($match)){
	        	$match = json_encode($match);
	        }
	        return (isset($TEMP[$matches[1]])?$match:"");
	    }, $page);

	    if ($TEMP['#loggedin'] === true) {
	        $page = preg_replace_callback('/{\$me->(.+?)}/i', function($matches) use ($TEMP) {
	            return (isset($TEMP['#user'][$matches[1]])) ? $TEMP['#user'][$matches[1]] : '';
	        }, $page);
	    }
	    $page = preg_replace_callback('/{\!data->(.+?)}/i', function($matches) use ($TEMP) {
	        $match = $TEMP['!data'][$matches[1]];
	        return (isset($match)?$match:"");
	    }, $page);
	    $page = preg_replace_callback('/{\!([a-zA-Z0-9_]+)}/i', function($matches) use ($TEMP) {
	        $match = $TEMP["!".$matches[1]];
	    	if(is_bool($match)){
	        	$match = json_encode($match);
	        }
	        return (isset($match)?$match:"");
	    }, $page);
	    return $page;
	}

	public static function Logged() {
		global $dba;
	    if (isset($_SESSION['session_id']) && !empty($_SESSION['session_id'])) {
	        if ($dba->query('SELECT COUNT(*) FROM sessions WHERE session_id = "'.self::Filter($_SESSION['session_id']).'"')->fetchArray() > 0) {
	            return true;
	        }
	    } else if (isset($_COOKIE['session_id']) && !empty($_COOKIE['session_id'])) {
	        if ($dba->query('SELECT COUNT(*) FROM sessions WHERE session_id = "'.self::Filter($_COOKIE['session_id']).'"')->fetchArray() > 0) {
	            return true;
	        }
	    }
	    return false;
	}

	public static function Censor($input){
		global $TEMP, $dba;
	    if(!empty($input) && !empty($TEMP['#settings']['censor'])){
	        $censored = explode(',', $TEMP['#settings']['censor']);
	        foreach ($censored as $value) {
	            $value = trim($value);
	            $search[] = '/^'.$value.'$/i';
	        }
	        $input = preg_replace($search, '******', $input);
	    }
	    return $input;
	}

	public static function Filter($input){
	    global $dba;
	    if(!empty($input)){
	    	$input = mysqli_real_escape_string($dba->returnConnection(), $input);
		    $input = htmlspecialchars($input, ENT_QUOTES);
		    $input = str_replace('\r\n', " <br>", $input);
		    $input = str_replace('\n\r', " <br>", $input);
		    $input = str_replace('\r', " <br>", $input);
		    $input = str_replace('\n', " <br>", $input);
		    $input = stripslashes($input);
	    }
	    return $input;
	}

	public static function Sitemap($background = false){
		global $dba, $TEMP;
		$dbaLimit = 45000;
		$videos = $dba->query('SELECT COUNT(*) FROM videos WHERE privacy = 0 AND approved = 1 AND deleted = 0')->fetchArray();
		if(empty($videos)){
			return false;
		}
		$time = time();
		if($background == true){
			self::PostCreate(array(
				'status' => 200,
                'message' => $TEMP['#word']['sitemap_being_generated_may_take_few_minutes'],
                'time' => self::DateFormat($time)
			));
		}
		$limit = ceil($videos / $dbaLimit);
		$sitemap_x = '<?xml version="1.0" encoding="UTF-8"?>
		                <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		$sitemap_index = '<?xml version="1.0" encoding="UTF-8"?>
		                    <sitemapindex  xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" >';
		for ($i=1; $i <= $limit; $i++) {            
		  $sitemap_index .= "\n<sitemap>
		                          <loc>" . self::Url("/sitemap-$i.xml") . "</loc>
		                          <lastmod>" . date('c') . "</lastmod>
		                        </sitemap>";
		  $paginate = $dba->query('SELECT * FROM videos WHERE privacy = 0 AND approved = 1 AND deleted = 0 ORDER BY id ASC LIMIT ? OFFSET ?', $dbaLimit, $i)->fetchAll();
		  foreach ($paginate as $value) {
		    $video = self::Video($value);
		    $sitemap_x .= '<url>
		                    <loc>' . $video['url'] . '</loc>
		                    <lastmod>' . date('c', $video['time']). '</lastmod>
		                    <changefreq>monthly</changefreq>
		                    <priority>0.8</priority>
		                  </url>' . "\n";
		  }
		  $sitemap_x .= "\n</urlset>";
		  file_put_contents('sitemaps/sitemap-' . $i . '.xml', $sitemap_x);
		  $sitemap_x = '<?xml version="1.0" encoding="UTF-8"?>
		                  <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'; 
		}
		$sitemap_index .= '</sitemapindex>';
		$file_final = file_put_contents('sitemap-index.xml', $sitemap_index);
		$dba->query('UPDATE settings SET value = "'.$time.'" WHERE name = "last_sitemap"');
		return true;
	}
}
?>