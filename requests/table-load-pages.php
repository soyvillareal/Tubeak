<?php 
$deliver['status'] = 400;
$page = Specific::Filter($_POST['page_id']);
$typet = Specific::Filter($_POST['type']);
$types = array('sessions', 'languages', 'edit-language', 'admin-users', 'verification-request', 'admin-videos', 'admin-categories', 'admin-comments', 'admin-applied-locks', 'admin-reports');
$by_id = !empty($_POST['by_id']) ? Specific::Filter($_POST['by_id']) : $TEMP['#user']['id'];

if(!empty($page) && is_numeric($page) && isset($page) && $page > 0 && in_array($typet, $types)){
	$html = "";
	if ($typet == 'sessions'){
		$user_sessions = $dba->query('SELECT * FROM sessions WHERE by_id = '.$by_id.' ORDER BY id DESC LIMIT ? OFFSET ?', $TEMP['#settings']['data_load_limit'], $page)->fetchAll();
	    if (!empty($user_sessions)) {
	        foreach ($user_sessions as $value) {
	            $session = Specific::GetSessions($value);
	            $TEMP['!id'] = $value['id'];
	            $TEMP['!ip'] = $session['ip'];
	            $TEMP['!browser'] = $session['browser'];
	            $TEMP['!platform'] = $session['platform'];
	            $TEMP['!time'] = Specific::DateFormat($value['time']);
	            $html .= Specific::Maket("settings/security/includes/sessions");
	        }
	        Specific::DestroyMaket();
	    }
	} else if($typet == 'languages'){
		$languages = $dba->query('SELECT * FROM types WHERE type != "category" LIMIT ? OFFSET ?', $TEMP['#settings']['data_load_limit'], $page)->fetchAll();
		if (!empty($languages)) {
			foreach ($languages as $value) {
				$TEMP['!code'] = $value['type'];
				$TEMP['!language'] = $value['name'];
				$html .= Specific::Maket('admin/manage-languages/includes/languages-list');
			}
			Specific::DestroyMaket();
		}
	} else if($typet == 'edit-language'){
		$language = Specific::Filter($_POST['language']);
		$keyword = Specific::Filter($_POST['keyword']);
		$words = Specific::Words($language, 1, true, $page, $keyword);
		if (!empty($words)) {
			foreach ($words['sql'] as $value) {
			    if($value['word'] != 'others'){
                    $TEMP['!key'] = $value['word'];
                    $TEMP['!value'] = $value[$language];
                    $html .= Specific::Maket('admin/manage-languages/includes/words-list');
                }
			}
			Specific::DestroyMaket();
		}
	} else if($typet == 'admin-users'){
		$keyword = Specific::Filter($_POST['keyword']);
		$query = '';
		if(!empty($keyword)){
			$query = " WHERE id LIKE '%$keyword%' OR username LIKE '%$keyword%' OR email LIKE '%$keyword%' OR ip LIKE '%$keyword%'";
	    }
		$users = $dba->query('SELECT * FROM users'.$query.' ORDER BY id DESC LIMIT ? OFFSET ?', $TEMP['#settings']['data_load_limit'], $page)->fetchAll();
		if (!empty($users)) {
			foreach ($users as $value) {
				$user = Specific::Data($value['id']);
			    $TEMP['!data'] = $user;
			    $TEMP['!status'] = $user['status'] == 1 ? $TEMP['#word']['active'] : $TEMP['#word']['pending'];
				$html .= Specific::Maket('admin/manage-users/includes/users-list');
			}
			Specific::DestroyMaket();
		}
	} else if($typet == 'verification-request'){
		$verification_requests = $dba->query('SELECT * FROM requests ORDER BY id DESC LIMIT ? OFFSET ?', $TEMP['#settings']['data_load_limit'], $page)->fetchAll();
		foreach ($verification_requests as $value) {
			$TEMP['#status'] = $value['status'];

		    $TEMP['!id'] = $value['id'];
		    $TEMP['!data'] = Specific::Data($value['by_id']);
		    $TEMP['!time'] = Specific::DateFormat($value['time']);
	        $TEMP['!status'] = $TEMP['#status'] == 0 ? $TEMP['#word']['pending'] : $TEMP['#status'] == 1 ? $TEMP['#word']['verified'] : $TEMP['#word']['rejected'];
			$html .= Specific::Maket('admin/manage-users/includes/verification-list');
		}
		Specific::DestroyMaket();
	} else if($typet == 'admin-videos'){
		$query = '';
		$keyword = Specific::Filter($_POST['keyword']);
		$TEMP['#approve'] = Specific::Filter($_POST['approve']);
		$TEMP['#privacy'] = Specific::Filter($_POST['privacy']);
		$TEMP['#category'] = Specific::Filter($_POST['category']);

		if(!empty($TEMP['#approve']) && $TEMP['#approve'] != 'all'){
			if ($TEMP['#approve'] == 'pending') {
				$query = ' WHERE approved = 0';
			} elseif ($TEMP['#approve'] == 'approved') {
				$query = ' WHERE approved = 1';
			}
		}

		if(!empty($TEMP['#privacy']) && $TEMP['#privacy'] != 'all'){
			$where = ($TEMP['#settings']['approve_videos'] == 'on' && !empty($query)) ? ' AND' : ' WHERE';
			if ($TEMP['#privacy'] == 'public') {
				$query .= $where.' privacy = 0';
			} elseif ($TEMP['#privacy'] == 'private') {
				$query .= $where.' privacy = 1';
			} elseif ($TEMP['#privacy'] == 'unlisted') {
				$query .= $where.' privacy = 2';
			}
		}

		if(!empty($TEMP['#category']) && $TEMP['#category'] != 'all'){
			foreach($TEMP['#categories'] as $key => $value) {
		        if($key == $TEMP['#category']){
		        	$where = !empty($query) ? ' AND' : ' WHERE';
		        	$query .= $where.' category = "'.$key.'"';
		        }    
		    }
		}

		if(!empty($keyword)){
			$where = !empty($query) ? ' AND' : ' WHERE';
	        $query .= $where." title LIKE '%$keyword%' OR tags LIKE '%$keyword%' OR description LIKE '%$keyword%'";
	    }
	    $where = !empty($query) ? ' AND' : ' WHERE';
	    $videos = $dba->query('SELECT * FROM videos v '.$where.' deleted = 0 AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 1 OR (SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) ORDER BY id DESC LIMIT ? OFFSET ?', $TEMP['#settings']['data_load_limit'], $page)->fetchAll();

		if (!empty($videos)) {
			foreach ($videos as $value) {
				$video = Specific::Video($value);
				$TEMP['#video_approve'] = $video['approved'];

				$TEMP['!privacy'] = $TEMP['#word']['public'];
				if($video['privacy'] == 1){
					$TEMP['!privacy'] = $TEMP['#word']['private'];
				} else if($video['privacy'] == 2){
					$TEMP['!privacy'] = $TEMP['#word']['unlisted'];
				}
			    $TEMP['!id'] = $video['id'];
		        $TEMP['!title'] = $video['title'];
		        $TEMP['!url'] = $video['url'];
		        $TEMP['!category'] = $video['category_name'];
		        $TEMP['!data'] = Specific::Data($video['by_id']);
				$html .= Specific::Maket('admin/includes/videos-list');
			}
			Specific::DestroyMaket();
		}
	} else if($typet == 'admin-categories'){
		$categories = Specific::Categories(true, $page);
		if(!empty($categories)){
			foreach ($categories['sql'] as $key => $value) {
				$TEMP['!key'] = $key;
				$TEMP['!value'] = $value;
				$html .= Specific::Maket('admin/includes/categories-list');
			}
			Specific::DestroyMaket();
		}
	} else if($typet == 'admin-comments'){
		$keyword = Specific::Filter($_POST['keyword']);
		$query = '';
		if(!empty($keyword)){
			$query = " WHERE id LIKE '%$keyword%' OR text LIKE '%$keyword%'";
	    }

		$comments = $dba->query('SELECT * FROM comments'.$query.' ORDER BY id DESC LIMIT ? OFFSET ?', $TEMP['#settings']['data_load_limit'], $page)->fetchAll();
		if (!empty($comments)) {
			foreach ($comments as $value) {
	            $video = Specific::Video($value['video_id']);

	            $TEMP['!id'] = $value['id'];
	            $TEMP['!video_id'] = $video['video_id'];
	            $TEMP['!url'] = $video['url'];
	            $TEMP['!time'] = Specific::DateFormat($value['time']);
	            $TEMP['!text'] = Specific::GetComposeText($value['text']);

	            $html .= Specific::Maket('admin/includes/comments-list');
	        }
	        Specific::DestroyMaket();
		}
	} else if($typet == 'admin-applied-locks'){
		$keyword = Specific::Filter($_POST['keyword']);
		$query = '';
		if(!empty($keyword)){
			$query = " WHERE id LIKE '%$keyword%' OR value LIKE '%$keyword%'";
	    }
		$users = $dba->query('SELECT * FROM banned'.$query.' ORDER BY id DESC LIMIT ? OFFSET ?', $TEMP['#settings']['data_load_limit'], $page)->fetchAll();
		if (!empty($users)) {
			foreach ($users as $value) {
	            $TEMP['!id'] = $value['id'];
	            $TEMP['!value'] = $value['value'];
	            $TEMP['!time'] = Specific::DateFormat($value['time']);
				$html .= Specific::Maket('admin/manage-users/includes/applied-locks-list');
			}
			Specific::DestroyMaket();
		}
	} else if($typet == 'admin-reports'){
		$reports = $dba->query('SELECT * FROM reports ORDER BY id DESC LIMIT ? OFFSET ?', $TEMP['#settings']['data_load_limit'], $page)->fetchAll();
		if (!empty($reports)) {
		    foreach ($reports as $value) {
		        $user_data  = Specific::Data($value['by_id']);
		        $TEMP['#type'] = $value['type'];

		        $TEMP['!id'] = $value['id'];
		        $TEMP['!data'] = $user_data;
		        $TEMP['!type'] = $TEMP['#type'];
		        $TEMP['!time'] = Specific::DateFormat($value['time']);

		        if($value['type'] == 'video'){
					$video = Specific::Video($value['to_id']);
					$TEMP['!url'] = $video['url'];
		            $TEMP['!title'] = $video['title'];
		            $TEMP['!video_id'] = $video['video_id'];
		        } else {
		        	$user_rdata  = Specific::Data($value['to_id']);
		        	$TEMP['!url'] = Specific::Url('user/' . $user_rdata['user_id']);
		            $TEMP['!title'] = $user_rdata['username'];
		        }

		        $html .= Specific::Maket('admin/includes/reports-list');
		    }
		    Specific::DestroyMaket();
		}
	}
	$deliver['status'] = 200;
	$deliver['html'] = $html;
}
?>