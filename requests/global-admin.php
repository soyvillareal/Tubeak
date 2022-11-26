<?php
if ($TEMP['#loggedin'] === false) {
    $deliver = array(
        'status' => 400,
        'error' => $TEMP['#word']['error']
    );
    echo json_encode($deliver);
    exit();
} else if ($TEMP['#owner_global'] === false) {
    $deliver = array(
        'status' => 400,
        'error' => 'Not admin'
    );
    echo json_encode($deliver);
    exit();
}

if($TEMP['#owner_global'] === true){
    if($one == 'get-report'){
        $report_id = Specific::Filter($_POST['report_id']);
        $type = Specific::Filter($_POST['report_type']);
        if(!empty($report_id)){
            $report = $dba->query('SELECT * FROM reports WHERE id = '.$report_id)->fetchArray();
            if (!empty($report) && in_array($type, array('video', 'user'))) {
                if ($report['option'] == 1){
                    $option = $TEMP['#word']['spam'];
                } else if ($report['option'] == 2){
                    $option = $TEMP['#word']['infringes_on_my_copyright'];
                } else if ($report['option'] == 3){
                    $option = $TEMP['#word']['no_video_play'];
                } else if ($report['option'] == 4){
                    $option = $TEMP['#word']['sexual_content'];
                } else if ($report['option'] == 5){
                    $option = $TEMP['#word']['identity_fraud'];
                } else if ($report['option'] == 6){
                    $option = $TEMP['#word']['invasion_privacy'];
                } else if ($report['option'] == 7){
                    $option = $TEMP['#word']['noting_reply'];
                }

                $TEMP['id'] = $report['id'];
                $TEMP['#type'] = $type;
                $TEMP['text'] = $option;
                $TEMP['time'] = Specific::DateString($report['time']);

                if($type == 'video'){
                    $TEMP['#video'] = Specific::Video($report['to_id']);
                    $TEMP['video_id'] = $TEMP['#video']['video_id'];
                    $TEMP['#adults_only'] = $TEMP['#video']['adults_only'];
                    $TEMP['#approved'] = $TEMP['#video']['approved'];
                } else {
                    $TEMP['#data'] = Specific::Data($report['to_id']);
                    $TEMP['data'] = $TEMP['#data'];
                }
                
                $deliver = array(
                    'status' => 200,
                    'html' => Specific::Maket('admin/includes/form-report')
                );
            }
        }
    } else if($one == 'send-report') {
        $deliver['status'] = 400;
        $report_id     = Specific::Filter($_POST['report-id']);
        $report_type = Specific::Filter($_POST['report_type']);
        $ban_ip = Specific::Filter($_POST['ban_ip']);
        $ban_user = Specific::Filter($_POST['ban_user']);
        $adults_only = Specific::Filter($_POST['adults_only']);
        $approved = Specific::Filter($_POST['approved']);
        $delete_video = Specific::Filter($_POST['delete-video']);
        $app_delban = array(0, 1);
        if (!empty($report_id) && is_numeric($report_id) && in_array($report_type, array('video', 'user')) && (($report_type == 'video' && in_array($approved, $app_delban) && in_array($delete_video, $app_delban) && in_array($adults_only, $app_delban)) || ($report_type == 'user' && in_array($ban_user, $app_delban)))) {
            $report = $dba->query('SELECT * FROM reports WHERE id = '.$report_id)->fetchArray();
            if($report_type == 'video'){
                if($delete_video == 0){
                    $dba->query('UPDATE videos SET adults_only = '.$adults_only.', approved = '.$approved.' WHERE id = '.$report['to_id']);
                    $dba->query('DELETE FROM reports WHERE id = '.$report_id);
                } else {
                    Specific::DeleteVideo($report['to_id']);
                }
            } else if($report_type == 'user'){
                $user_data = Specific::Data($report['to_id']);
                if($dba->query('SELECT COUNT(*) FROM banned WHERE value = "'.$ban_ip.'"')->fetchArray() == 0 && $ban_ip == 1){
                    $dba->query('INSERT INTO banned (value, time) VALUES ("'.$user_data['ip'].'",'.time().')');
                }
                if($dba->query('SELECT COUNT(*) FROM banned WHERE value = "'.$ban_user.'"')->fetchArray() == 0 && $ban_user == 1){
                    $dba->query('INSERT INTO banned (value, time) VALUES ("'.$user_data['username'].'",'.time().')');
                }
                $dba->query('DELETE FROM reports WHERE id = '.$report_id);
            }
            $deliver['status'] = 200;
        }
    } else if($one == 'delete-report'){
        $deliver['status'] = 400;
        $report_id     = Specific::Filter($_POST['report-id']);
        if (!empty($report_id) && is_numeric($report_id)){
            if($dba->query('DELETE FROM reports WHERE id = '.$report_id)->returnStatus()){
                $deliver['status'] = 200;
            }
        }
    } else if($one == 'delete-comment'){
        $id = Specific::Filter($_POST['id']);
        $by_id = $dba->query('SELECT by_id FROM comments WHERE id = '.$id)->fetchArray();
        $user_data = Specific::Data($by_id);
        if(($user_data['role'] == 0 || Specific::Admin()) && !empty($id)){
            if ($dba->query('DELETE FROM comments WHERE id = '.$id)->returnStatus()) {
                $dba->query('DELETE FROM reactions WHERE to_id = '.$id.' AND place = "commentary"');
                $replies = $dba->query('SELECT * FROM replies WHERE comment_id = '.$id)->fetchAll();
                $dba->query('DELETE FROM replies WHERE comment_id = '.$id);
                if (!empty($replies)) {
                    foreach ($replies as $value) {
                        $dba->query('DELETE FROM reactions WHERE to_id = '.$value['id'].' AND place = "reply"');
                    }
                }
                $deliver['status'] = 200;
            }
        }
    } else if($one == 'search-commens'){
        $keyword = Specific::Filter($_POST['keyword']);
        $html = '';
        $query = '';
        if(!empty($keyword)){
            $query = " WHERE id LIKE '%$keyword%' OR text LIKE '%$keyword%'";
        }
        $comments = $dba->query('SELECT * FROM comments'.$query.' ORDER BY id DESC LIMIT ? OFFSET ?', $TEMP['#settings']['data_load_limit'], 1)->fetchAll();
        $deliver['total_pages'] = $dba->totalPages;
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
            $deliver['status'] = 200;
        } else {
            if(!empty($keyword)){
                $TEMP['keyword'] = $keyword;
                $html .= Specific::Maket('not-found/result-for');
            } else {
                $html .= Specific::Maket('not-found/comments');
            }
        }
        $deliver['html'] = $html;
    } else if($one == 'search-videos'){
        $keyword = Specific::Filter($_POST['keyword']);
        $TEMP['#approve'] = Specific::Filter($_POST['approve']);
        $TEMP['#privacy'] = Specific::Filter($_POST['privacy']);
        $TEMP['#category'] = Specific::Filter($_POST['category']);
        $html = '';
        $query = '';

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

        $videos = $dba->query('SELECT * FROM videos v '.$query.$where.' deleted = 0 AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 1 OR (SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) ORDER BY id DESC LIMIT ? OFFSET ?', $TEMP['#settings']['data_load_limit'], 1)->fetchAll();
        $deliver['total_pages'] = $dba->totalPages;

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
            $deliver['status'] = 200;
        } else {
            if(!empty($keyword)){
                $TEMP['keyword'] = $keyword;
                $html .= Specific::Maket('not-found/result-for');
            } else {
                $html .= Specific::Maket('not-found/videos');
            }
        }
        $deliver['html'] = $html;
    } else if($one == 'delete-video'){
        $video_id = Specific::Filter($_POST['id']);
        $by_id = $dba->query('SELECT by_id FROM videos WHERE id = '.$video_id)->fetchArray();
        $user_data = Specific::Data($by_id);
        if(($user_data['role'] == 0 || Specific::Admin()) && !empty($video_id)) {
            $delete = Specific::DeleteVideo($video_id);
            if ($delete) {
                $deliver['status'] = 200;
            }
        }
    } else if($one == 'approve-disapprove'){
        $approve = Specific::Filter($_POST['approve']);
        $video_id = Specific::Filter($_POST['video_id']);
        if (!empty($video_id) && !empty($approve)) {
            $approve = $approve == 'approve' ? 1 : 0;
            if($dba->query('UPDATE videos SET approved = '.$approve.' WHERE id = '.$video_id)->returnStatus()){
                $deliver = array('status' => 200);
            }
        }
    } else if ($one == 'accept-verification') {
        if (!empty($_POST['id']) && is_numeric($_POST['id'])) {
            $request_id    = Specific::Filter($_POST['id']);
            $by_id = $dba->query('SELECT by_id FROM requests WHERE id = '.$request_id)->fetchArray();
            if($dba->query('UPDATE users SET verified = 1 WHERE id = '.$by_id)->returnStatus() && $dba->query('UPDATE requests SET status = 1 WHERE id = '.$request_id)->returnStatus()){
                Specific::SendNotification(array(
                    'from_id' => $TEMP['#user']['id'],
                    'to_id' => $by_id,
                    'type' => "'verification_accepted'",
                    'notify_key' => "'settings'",
                    'time' => time()
                ));
                $deliver['status'] = 200;
            }
        }
    } else if ($one == 'reject-verification') {
        if (!empty($_POST['id']) && is_numeric($_POST['id'])) {
            $request_id    = Specific::Filter($_POST['id']);
            if($dba->query('UPDATE requests SET status = 2 WHERE id = '.$request_id)->returnStatus()){
                Specific::SendNotification(array(
                    'from_id' => $TEMP['#user']['id'],
                    'to_id' => $dba->query('SELECT by_id FROM requests WHERE id = '.$request_id)->fetchArray(),
                    'type' => "'verification_rejected'",
                    'notify_key' => "'settings'",
                    'time' => time()
                ));
                $deliver['status'] = 200;
            }
        }
    } else if($one == 'search-applied-locks'){
        $keyword = Specific::Filter($_POST['keyword']);
        $html = '';
        $query = '';
        if(!empty($keyword)){
            $query = " WHERE id LIKE '%$keyword%' OR value LIKE '%$keyword%'";
        }
        $users = $dba->query('SELECT * FROM banned'.$query.' ORDER BY id DESC LIMIT ? OFFSET ?', $TEMP['#settings']['data_load_limit'], 1)->fetchAll();
        $deliver['total_pages'] = $dba->totalPages;
        if (!empty($users)) {
            foreach ($users as $value) {
                $TEMP['!id'] = $value['id'];
                $TEMP['!value'] = $value['value'];
                $TEMP['!time'] = Specific::DateFormat($value['time']);
                $html .= Specific::Maket('admin/manage-users/includes/applied-locks-list');
            }
            Specific::DestroyMaket();
            $deliver['status'] = 200;
        } else {
            if(!empty($keyword)){
                $TEMP['keyword'] = $keyword;
                $html .= Specific::Maket('not-found/result-for');
            } else {
                $html .= Specific::Maket('not-found/applied-locks');
            }
        }
        $deliver['html'] = $html;
    } else if ($one == 'remove-ban') {
        $deliver['status'] = 400;
        $ban_id = Specific::Filter($_POST['id']);
        if (!empty($ban_id) && is_numeric($ban_id)){
            if($dba->query('DELETE FROM banned WHERE id = '.$ban_id)->returnStatus()){
                $deliver['status'] = 200;
            }
        }
    } else if ($one == 'add-ban') {
        $deliver['status'] = 400;
        $value = Specific::Filter($_POST['value']);
        if(empty($value)){
            $error = $TEMP['#word']['there_problems_with_some_fields'];
        }else if($dba->query('SELECT COUNT(*) FROM banned WHERE value = "'.$value.'"')->fetchArray() > 0){
            $error = $TEMP['#word']['this_lock_has_already_been_applied'];
        }
        if (!empty($value) && empty($error)){
            if ($dba->query('INSERT INTO banned (value, time) VALUES ("'.$value.'",'.time().')')->returnStatus()) {
                $deliver['status'] = 200;
            }
        } else {
            $deliver = array(
                'status' => 400,
                'error' => $error
            );
        }
    } else if($one == 'delete-user') {
        $by_id = Specific::Filter($_POST['id']);
        if (!empty($by_id) && Specific::Admin()) {
            $delete = Specific::DeleteUser($by_id);
            if ($delete) {
                $deliver['status'] = 200;
            }
        }
    } else if($one == 'search-users'){
        $keyword = Specific::Filter($_POST['keyword']);
        $html = '';
        $query = '';
        if(!empty($keyword)){
            $query .= " WHERE id LIKE '%$keyword%' OR username LIKE '%$keyword%' OR email LIKE '%$keyword%' OR ip LIKE '%$keyword%'";
        }
        $users = $dba->query('SELECT * FROM users'.$query.' ORDER BY id DESC LIMIT ? OFFSET ?', $TEMP['#settings']['data_load_limit'], 1)->fetchAll();
        $deliver['total_pages'] = $dba->totalPages;
        if (!empty($users)) {
            foreach ($users as $value) {
                $user = Specific::Data($value['id']);
                $TEMP['!data'] = $user;
                $TEMP['!status'] = $user['status'] == 1 ? $TEMP['#word']['active'] : $TEMP['#word']['pending'];
                $html .= Specific::Maket('admin/manage-users/includes/users-list');
            }
            Specific::DestroyMaket();
            $deliver['status'] = 200;
        } else {
            if(!empty($keyword)){
                $TEMP['keyword'] = $keyword;
                $html .= Specific::Maket('not-found/result-for');
            } else {
                $html .= Specific::Maket('not-found/users');
            }
        }
        $deliver['html'] = $html;
    } else if($one == 'get-info-user'){
        $by_id = Specific::Filter($_POST['by_id']);
        if(!empty($by_id)){
            $TEMP['#data'] = Specific::Data($by_id);
            $TEMP['#status'] = $TEMP['#data']['status'];
            $TEMP['#role'] = $TEMP['#data']['role'];
            $TEMP['#verification'] = $TEMP['#data']['verified'];
            $TEMP['#ban_ip'] = $dba->query('SELECT COUNT(*) FROM banned WHERE value = "'.$TEMP['#data']['ip'].'"')->fetchArray();
            $TEMP['#ban_user'] = $dba->query('SELECT COUNT(*) FROM banned WHERE value = "'.$TEMP['#data']['username'].'"')->fetchArray();

            if (!empty($TEMP['#data'])) {
                $TEMP['data'] = $TEMP['#data'];
                $deliver = array(
                    'status' => 200,
                    'html' => Specific::Maket('admin/manage-users/includes/form-info-user')
                );
            }
        }
    } else if($one == 'info-user'){
        $by_id = Specific::Filter($_POST['by_id']);
        $upload_limit = Specific::Filter($_POST['upload_limit']);
        $status = Specific::Filter($_POST['status']);
        $type = Specific::Filter($_POST['type']);
        $verification = Specific::Filter($_POST['verification']);

        $ban_ip = Specific::Filter($_POST['ban_ip']);
        $ban_user = Specific::Filter($_POST['ban_user']);
        $radio_arr = array(0, 1);
        $upload_arr = array(0, 8000000,16000000,32000000,64000000,128000000,256000000,512000000,1000000000,5000000000);
        if(!empty($by_id)){
            $user_data = Specific::Data($by_id);
            if(($user_data['role'] == 0 || Specific::Admin()) && in_array($upload_limit, $upload_arr) && in_array($status, $radio_arr) && in_array($type, array(0, 1, 2)) && in_array($verification, $radio_arr) && in_array($ban_ip, $radio_arr) && in_array($ban_user, $radio_arr)){
                if(!Specific::Admin()){
                    $type = $user_data['role'];
                }
                $dba->query('UPDATE users SET status = '.$status.', role = '.$type.', verified = '.$verification.', upload_limit = '.$upload_limit.' WHERE id = '.$by_id);

                if($dba->query('SELECT COUNT(*) FROM banned WHERE value = "'.$ban_ip.'"')->fetchArray() == 0 && $ban_ip == 1){
                    $dba->query('INSERT INTO banned (value, time) VALUES ("'.$user_data['ip'].'",'.time().')');
                } else {
                    $dba->query('DELETE FROM banned WHERE value = "'.$user_data['ip'].'"');
                }
                if($dba->query('SELECT COUNT(*) FROM banned WHERE value = "'.$ban_user.'"')->fetchArray() == 0 && $ban_user == 1){
                    $dba->query('INSERT INTO banned (value, time) VALUES ("'.$user_data['username'].'",'.time().')');
                } else {
                    $dba->query('DELETE FROM banned WHERE value = "'.$user_data['username'].'"');
                }
                $deliver = array(
                    'status' => 200
                );
            }
        }
    }
}

if(Specific::Admin() === true){
    if($one == 'settings'){
    	$deliver['status'] = 400;
    	$types = array('on', 'off', 'normal', 'yes', 'no', '0', '1');
    	$inputs_num = array('max_queue', 'verification_subscribers_cap', 'smtp_port');
    	$show_comm = array('16', '24', '32', '40', '48');
    	$uploads = array(0, 8000000,16000000,32000000,64000000,128000000,256000000,512000000,1000000000,5000000000);
    	$radios = explode(',', Specific::Filter($_POST['radios']));
    	$errors = array();
    	if(!empty($_POST)){
    		foreach ($_POST as $key => $value) {
    			$insert = Specific::Filter($value);
    			if((in_array($key, $inputs_num) && ($insert < 0 || !is_numeric($insert))) || ($key != 'server_type' && $key != 'smtp_encryption' && $key != 'type_carrousel' && (in_array($key, $radios) && !in_array($insert, $types))) || ($key == 'type_carrousel' && !in_array($insert, array('all', 'videos', 'broadcast'))) || (($key == 'comments_load_limit' || $key == 'data_load_limit') && (!in_array($insert, $show_comm) || !is_numeric($insert))) || ($key == 'upload_limit' && (!is_numeric($insert) || !in_array($insert, $uploads))) || ($key == 'max_upload_size' && (!is_numeric($insert) || !in_array($insert, $uploads))) || ($key == 'language' && !in_array($insert, $TEMP['#languages'])) || ($key == 'server_type' && !in_array($insert, array('smtp', 'mail'))) || ($key == 'smtp_encryption' && !in_array($insert, array('tls', 'ssl')))){
                    $errors[] = false;
                }
    			if($key != 'token' && !in_array(false, $errors)){
                    $dba->query('UPDATE settings SET value = "'.$insert.'" WHERE name = "'.$key.'"');
    			}
    		}
            $deliver['status'] = 200;
            $deliver['message'] = $TEMP['#word']['setting_updated'];
    		if(in_array(false, $errors)){
                $deliver['err'] = false;
    		}
    	}
    } else if($one == 'media-site'){
        $deliver['status'] = 400;
        $redirect = false;
        $update = false;
        $key = key($_FILES);
        if(in_array($key, array('icon_favicon', 'icon_logo')) && !empty($_FILES[$key])){
            $width = $key == 'icon_favicon' ? 32 : 80;
            $height = $key == 'icon_favicon' ? 32 : 24;
            if(!empty($_FILES[$key]['tmp_name'])){  
                $favicon_now = $dba->query('SELECT * FROM settings WHERE name = "'.$key.'"')->fetchArray();
                if(file_exists($favicon_now['value'])){
                    unlink($favicon_now['value']);
                    $redirect = true;
                }
                $file_info = array(
                    'file' => $_FILES[$key]['tmp_name'],
                    'size' => $_FILES[$key]['size'],
                    'name' => $_FILES[$key]['name'],
                    'type' => $_FILES[$key]['type'],
                    'from' => $key,
                    'crop' => array('width' => $width, 'height' => $height)
                );
                $file_data = Specific::UploadImage($file_info);
                if (!empty($file_data)) {
                    $insert = $file_data;
                }
            }
            if($dba->query('UPDATE settings SET value = "'.$insert.'" WHERE name = "'.$key.'"')->returnStatus()){
                $deliver = array(
                    'status' => 200,
                    'message' => $TEMP['#word']['setting_updated'],
                    'redirect' => $redirect
                );
            }
        }
    } else if ($one == 'test-email') {
        $send_email = Specific::SendEmail(array(
            'from_email' => $TEMP['#settings']['smtp_username'],
            'from_name' => $TEMP['#settings']['name'],
            'to_email' => $TEMP['#settings']['smtp_username'],
            'to_name' => $TEMP['#user']['name'],
            'subject' => 'Test Message From ' . $TEMP['#settings']['name'],
            'charSet' => 'utf-8',
            'message_body' => $TEMP['#word']['receive_message_can_see_tray_because_everything_working_very_well']
        ));
        if ($send_email) {
            $deliver['status'] = 200;
        } else {
            $deliver = array(
                'status' => 400,
                'error' => $TEMP['#word']['information_provided_correct_email_could_information_correct']
            );
        }
    } else if($one == 'add-language') {
        $deliver['status']  = 400;
        $language = Specific::Filter($_POST['language']);
        $type = Specific::Filter($_POST['type']);
    	if(!empty($language) && !empty($type) && strpos($language, " ") === false && strpos($type, " ") === false && strlen($type) == 2){
            $type = strtolower($type);
    	    if (!in_array($type, $TEMP['#languages'])) {
                $deliver['status']  = 404;
    	        if ($dba->query('ALTER TABLE words ADD '.$type.' TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL')->returnStatus() && $dba->query('INSERT INTO types (name, type, time) VALUES ("'.$language.'","'.$type.'",'.time().')')->returnStatus()) {
    	            $deliver['status'] = 200;
    	        }
    	    }
    	}
    } else if($one == 'add-word'){
    	$word  = Specific::Filter($_POST['word']);
        if ($dba->query('SELECT COUNT(*) FROM words WHERE word = "'.$word.'"')->fetchArray() == 0) {
            if ($dba->query('INSERT INTO words (word) VALUES ("'.$word.'")')->returnStatus()) {
                $deliver['status'] = 200;
            }
        } else {
            $deliver['status']  = 400;
        }
    } else if($one == 'delete-language') {
        $language = Specific::Filter($_POST['language']);
        if (!empty($language) && in_array($language, $TEMP['#languages'])) {
            if ($dba->query('ALTER TABLE words DROP COLUMN '.$language)->returnStatus() && $dba->query('DELETE FROM types WHERE type = "'.$language.'"')->returnStatus()) {
                $deliver['status'] = 200;
            }
        }
    } else if($one == 'edit-word') {
        $word = Specific::Filter($_POST['word']);
        $word_name = Specific::Filter($_POST['word_name']);
        $language = Specific::Filter($_POST['language']);
        if(!empty($word) && !empty($word_name) && !empty($language)){
    	    if ($dba->query('UPDATE words SET '.$language.' = "'.$word_name.'" WHERE word = "'.$word.'"')->returnStatus()) {
    	        $deliver['status'] = 200;
    	    }
        } 
    } else if($one == 'delete-word'){
    	$word = Specific::Filter($_POST['word']);
    	if(!empty($word) && $word != 'others'){
    		if ($dba->query('DELETE FROM words WHERE word = "'.$word.'"')->returnStatus()) {
    	        $deliver['status'] = 200;
    	    }
    	}
    } else if($one == 'search-words'){
    	$keyword = Specific::Filter($_POST['keyword']);
    	$language = Specific::Filter($_POST['language']);
    	$html = '';
    	if(!empty($language)){
    		$words = Specific::Words($language, 1, true, 1, $keyword);
            $deliver['total_pages'] = $words['total_pages'];
    		if (!empty($words['sql'])) {
    			foreach ($words['sql'] as $value) {
                    if($value['word'] != 'others'){
                        $TEMP['!key'] = $value['word'];
                        $TEMP['!value'] = $value[$language];
                        $html .= Specific::Maket('admin/manage-languages/includes/words-list');
                    }
    			}
                Specific::DestroyMaket();
    			$deliver['status'] = 200;
    		} else {
                if(!empty($keyword)){
                $TEMP['keyword'] = $keyword;
                    $html .= Specific::Maket('not-found/result-for');
                } else {
                    $html .= Specific::Maket('not-found/words');
                }
            }
            $deliver['html'] = $html;
    	}
    } else if($one == 'add-category'){
        $values = Specific::Filter($_POST['values']);
        if(!empty($values)){
            $insert_categories = '';
            $insert_values = '';
            $pos = 0;
            $categories = explode(',', $values);
            $category = str_replace(' ', '_', strtolower($categories[0]));
            foreach ($TEMP['#languages'] as $value) {
                $insert_categories .= ", $value";
                $insert_values .= ", '{$categories[$pos++]}'";
            }
            if (!empty($insert_categories) && !empty($insert_values)) {
                if($dba->query('INSERT INTO words (word'.$insert_categories.') VALUES ("'.$category.'"'.$insert_values.')')->returnStatus() && $dba->query('INSERT INTO types (name, type, time) VALUES ("'.$category.'", "category",'.time().')')->returnStatus()){
                    $deliver['status'] = 200;
                }
                
            }
        }
    } else if($one == 'edit-category'){
        $category = Specific::Filter($_POST['category']);
        $values = Specific::Filter($_POST['values']);
        if(!empty($category) && !empty($values)){
            $update_data = '';
            $pos = 0;
            foreach ($TEMP['#languages'] as $value) {
                $set = ($pos == 0) ? ' SET' : ',';
                $val = explode(',', $values)[$pos++];
                $update_data .= "$set $value = '$val'";
            }
            if (!empty($update_data)) {
                if($dba->query('UPDATE words'.$update_data.' WHERE word = "'.$category.'"')->returnStatus()){
                    $deliver['status'] = 200;
                }
            }
        }
    } else if($one == 'delete-category'){
        $category = Specific::Filter($_POST['category']);
        if (!empty($category) && $category != 'others') {
            if($dba->query('DELETE FROM types WHERE name = "'.$category.'" AND type = "category"')->returnStatus() && $dba->query('DELETE FROM words WHERE word = "'.$category.'"')->returnStatus()){     
                $dba->query('UPDATE videos SET category = "others" WHERE category = "'.$category.'"');
                $deliver['status'] = 200;
            };
        }
    } else if($one == 'get-categories'){
        $category = Specific::Filter($_POST['category']);
        $TEMP['#type'] = Specific::Filter($_POST['type']);
        $edit_category = "";
        if(($TEMP['#type'] == 'edit' && !empty($category)) || $TEMP['#type'] == 'add'){
            $category = $dba->query('SELECT * FROM words WHERE word = "'.$category.'"')->fetchArray();
            if (($TEMP['#type'] == 'edit' && !empty($category)) || $TEMP['#type'] == 'add') {
                foreach ($TEMP['#languages'] as $value) {
                    $TEMP['!id'] = $value;
                    $TEMP['!title'] = ucfirst($value);
                    $TEMP['!cat_value'] = $category[$value];
                    $edit_category .= Specific::Maket('admin/includes/form-category');
                }
                Specific::DestroyMaket();
                $deliver = array(
                    'status' => 200,
                    'html' => $edit_category
                );
            }
        }
    } else if ($one == 'update-website-ad') {
        $deliver['status'] = 400;
        $inputs = array('header_ad', 'sidebar_ad', 'comments_ad');
        if(!empty($_POST)){
            foreach ($_POST as $key => $value) {
                if($key != 'token' && in_array($key, $inputs)){
                    $dba->query("UPDATE settings SET value = ? WHERE name = '".$key."'", json_encode(array('content' => $value, 'active' => Specific::Filter($_POST[$key.'_stat']))));
                }
            }
        }
        $deliver['status'] = 200;
    } else if($one == 'update-pages'){
        $deliver['status'] = 400;
        if(!empty($_POST)){
            foreach ($_POST as $key => $value) {
                if ($key != 'token' && in_array($key, array('terms-of-use', 'privacy-policy', 'about-us'))) {
                    $dba->query('UPDATE pages SET text = ?, active = ? WHERE type = "'.$key.'"', $value, Specific::Filter($_POST[$key.'-stat']));
                }
            }
        }
        $deliver['status'] = 200;
    } else if ($one == 'update-sitemap'){
        Specific::Sitemap(true);
    }
}
?>