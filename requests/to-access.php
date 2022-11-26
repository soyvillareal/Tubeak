<?php
if ($TEMP['#loggedin'] === true && !in_array($one, array('verify-change-email', 'resend-change-email'))) {
	$deliver = array(
		'status' => 400,
		'error' => $TEMP['#word']['already_logged_in']
	);
    echo json_encode($deliver);
    exit();
} else if($one == 'login'){
	$deliver['status'] = 400;
	$error   = '';
	$emptys = array();
	$username = Specific::Filter($_POST['username']);
	$password = Specific::Filter($_POST['password']);
	if(empty($username)){
		$emptys[] = 'username';
	}
	if(empty($password)){
		$emptys[] = 'password';
	}
	if(empty($emptys)){
		if($dba->query('SELECT COUNT(*) FROM users WHERE username = "'.$username.'"')->fetchArray() == 0){
	        $error = array('error' => $TEMP['#word']['invalid_username'], 'el' => 'username');
	    } else if ($dba->query('SELECT COUNT(*) FROM users WHERE username = "'.$username.'" AND password = "'.sha1($password).'"')->fetchArray() == 0){
	       	$error = array('error' => $TEMP['#word']['invalid_password'], 'el' => 'password');
	    } else if(Specific::IsBanned($username)){
	    	$error = array('error' => $TEMP['#word']['this_account_is_suspended'], 'el' => 'username');
	    }
	    $to_access = $dba->query('SELECT * FROM users WHERE username = "'.$username.'" AND password = "'.sha1($password).'"')->fetchArray();
	    if (empty($error)) {
	        if ($to_access['status'] == 0) {
	           	$deliver = array(
	           		'status' => 401,
	           		'id' => $to_access['user_id'],
	           		'ecode' => $to_access['token'],
	           		'html' => $TEMP['#word']['account_is_not_active'] . ' <button class="btn-trans color-blue" id="resend-email">' . $TEMP['#word']['resend_email'] . '</button>'
	            );
	        } else if ($to_access['status'] == 2) {
	           	$deliver = array(
	           		'status' => 401,
	           		'html' => $TEMP['#word']['account_was_deactivated_owner_email_related'] . ' ' . $TEMP['#word']['if_you_need_more_help'] . ' <a class="color-blue" href="'.Specific::Url('contact').'" target="_self">' . $TEMP['#word']['contact_our_helpdesk'] . '</a>'
	            );
	        } else {
	            if ($to_access['authentication'] == 1 && $to_access['ip'] != Specific::GetClientIp()) {
	                $code = rand(111111, 999999);
	                $token = md5($code);
	                $dba->query('UPDATE users SET token = "'.$token.'" WHERE username = "'.$username.'"');

	                $TEMP['token'] = $token;
					$TEMP['code'] = $code;
					$TEMP['username'] = $username;
	                $send_email_data = array(
	                   	'from_email' => $TEMP['#settings']['smtp_username'],
	                    'from_name' => $TEMP['#settings']['name'],
	                    'to_email' => $to_access['email'],
	                    'to_name' => $username,
	                    'subject' => $TEMP['#word']['authentication'],
	                    'charSet' => 'UTF-8',
	                    'message_body' => Specific::Maket('emails/includes/authentication'),
	                    'is_html' => true
	                );
	                $send = Specific::SendEmail($send_email_data);
			       	if($send){
		                $deliver = array(
						    'status' => 401,
		            		'url' => "&one=authentication&code=$token&id=".$to_access['user_id']
						);
	                } else {
						$deliver = array(
						    'status' => 400,
						    'message' => $TEMP['#word']['error_occurred_to_send_mail']
						);
					}
	            } else {
	                $session_id = sha1(Specific::RandomKey()) . md5(time());
		            $session_details = json_encode(Specific::BrowserDetails()['details']);
		            $insert = $dba->query("INSERT INTO sessions (by_id, session_id, details, time) VALUES ({$to_access['id']},'$session_id','$session_details',".time().')')->insertId();

		            $_SESSION['session_id'] = $session_id;
		            setcookie("session_id", $session_id, time() + 315360000, "/");
		            $dba->query('UPDATE users SET ip = "'.Specific::GetClientIp().'" WHERE id = '.$to_access['id']);
		            $deliver = array(
		               	'status' => 200,
		               	'url' => !empty($_POST['to']) ? $_POST['to'] : Specific::Url()
		            );
	            }
	        }
      	} else {
	        $deliver = array(
		        'status' => 401,
	       		'err' => $error
		    );
	    }
	} else {
		$deliver = array(
			'status' => 400,
			'emptys' => $emptys
		);
	}
} else if($one == 'resend-code'){
	$id = Specific::Filter($_POST['id']);
	$code = Specific::Filter($_POST['code']);
	$user = $dba->query('SELECT * FROM users WHERE user_id = "'.$id.'" AND token = "'.$code.'"')->fetchArray();
	if (!empty($user)) {
	    $code = rand(111111, 999999);
	    $token = md5($code);
	    $dba->query('UPDATE users SET token = "'.$token.'" WHERE user_id = "'.$id.'"');
		
		$TEMP['id'] = $id;
		$TEMP['token'] =  $token;
		$TEMP['code'] = $code;
		$TEMP['username'] = $user['username'];
	    $send_email_data = array(
	        'from_email' => $TEMP['#settings']['smtp_username'],
	        'from_name' => $TEMP['#settings']['name'],
	        'to_email' => $user['email'],
	        'to_name' => $user['username'],
	        'subject' => $TEMP['#word']['authentication'],
	        'charSet' => 'UTF-8',
	        'message_body' => Specific::Maket('emails/includes/authentication'),
	        'is_html' => true
	    );
	    $send = Specific::SendEmail($send_email_data);
		if($send){
			$deliver = array(
				'status' => 200,
				'ecode' => $token,
		       	'message' => $TEMP['#word']['email_sent']
			);
		} else {
			$deliver = array(
				'status' => 400,
				'ecode' => $code,
		       	'message' => $TEMP['#word']['error_occurred_to_send_mail']
			);
		}
	} else {
		$deliver = array(
		    'status' => 204,
	   		'message' => $TEMP['#word']['error']
		);
	}
} else if($one == 'resend-email'){
	$code = Specific::Filter($_POST['code']);
	$id = Specific::Filter($_POST['id']);
	if(!empty($code) && !empty($id)){
		$user = $dba->query('SELECT * FROM users WHERE user_id = "'.$id.'" AND token = "'.$code.'"')->fetchArray();
		if(!empty($user)) {
			$code = rand(111111,999999);
			$token = sha1($code);
			$dba->query('UPDATE users SET token = "'.$token.'" WHERE user_id = "'.$id.'"');

			$TEMP['id'] = $id;
			$TEMP['token'] = $token;
			$TEMP['code'] = $code;
			$TEMP['username'] = $user['username'];
			$TEMP['text'] = $TEMP['#word']['verify_your_account'].' '.$TEMP['#word']['of'].' '.$TEMP['#settings']['title'];
			$TEMP['footer'] = '<a target="_blank" href="'.Specific::Url("not-me/$token/$id").'" style="color: #999; text-decoration: underline;">'.$TEMP['#word']['let_us_know'].'</a>.';
			$TEMP['type'] = 'verify';

			$send_email = Specific::SendEmail(array(
				'from_email' => $TEMP['#settings']['smtp_username'],
	            'from_name' => $TEMP['#settings']['name'],
				'to_email' => $user['email'],
				'to_name' => $user['username'],
				'subject' => $TEMP['#word']['verify_your_account'],
				'charSet' => 'UTF-8',
		        'message_body' => Specific::Maket('emails/includes/verify-email'),
				'is_html' => true
			));
			if($send_email){
				$deliver = array(
				    'status' => 200,
				    'ecode' => $token,
			   		'message' => $TEMP['#word']['email_sent']
				);
			}
		} else {
			$deliver = array(
			    'status' => 302,
		   		'url' => Specific::Url()
			);
		}
	} else {
		$deliver = array(
		    'status' => 400,
	   		'message' => $TEMP['#word']['error']
		);
	}
} else if($one == 'verify-code'){
	$deliver['status'] = 400;
	$id = Specific::Filter($_POST['id']);
	$ecode = Specific::Filter($_POST['ecode']);
	$user = $dba->query('SELECT * FROM users WHERE user_id = "'.$id.'" AND token = "'.md5($ecode).'"')->fetchArray();
	if ($TEMP['#settings']['authentication'] == 'on' && !empty($id) && !empty($ecode) && !empty($user)) {
	    $token = md5(rand(111111, 999999));
		$session_id          = sha1(Specific::RandomKey()) . md5(time());
	    $insert = $dba->query('INSERT INTO sessions (by_id, session_id, time) VALUES ('.$user['id'].',"'.$session_id.'",'.time().')')->insertId();
	    $_SESSION['session_id'] = $session_id;
	    setcookie("session_id", $session_id, time() + 315360000, "/");
	    $dba->query('UPDATE users SET ip = "'.Specific::GetClientIp().'", token = "'.$token.'" WHERE id = '.$user['id']);
	    $deliver = array(
		    'status' => 200,
		    'url' => !empty($_POST['to']) ? $_POST['to'] : Specific::Url()
		);
	} else {
		$deliver = array(
		    'status' => 400,
		    'message' => $TEMP['#word']['wrong_confirm_code']
		);
	}
} else if($one == 'forgot-password'){
	$deliver['status'] = 400;
	$error = '';
	$email = Specific::Filter($_POST['email']);
    if(!empty($email)) {
        $user = $dba->query('SELECT * FROM users WHERE email = "'.$email.'"')->fetchArray();
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = $TEMP['#word']['email_invalid_characters'];
        } else if(empty($user['id'])){
        	$error = $TEMP['#word']['email_not_exist'];
        }
        if ($user['status'] == 2) {
	        $deliver = array(
	         	'status' => 401,
	           	'html' => $TEMP['#word']['account_was_deactivated_owner_email_related'] . ' ' . $TEMP['#word']['if_you_need_more_help'] . ' <a class="color-blue" href="'.Specific::Url('contact').'" target="_self">' . $TEMP['#word']['contact_our_helpdesk'] . '</a>'
	        );
	    }else if (empty($error)) {
           	$user = Specific::Data($user['id']);
           	$code = time() + rand(111111,999999);
           	$token = sha1($code);
           	$dba->query('UPDATE users SET token = "'.$token.'" WHERE id = '.$user['id']);

           	$TEMP['token'] = $token;
			$TEMP['username'] = $user['username'];
           	$send_email_data = array(
           		'from_email' => $TEMP['#settings']['smtp_username'],
	            'from_name' => $TEMP['#settings']['name'],
           		'to_email' => $email,
           		'to_name' => $user['name'],
           		'subject' => $TEMP['#word']['reset_your_password'],
           		'charSet' => 'UTF-8',
           		'message_body' => Specific::Maket('emails/includes/reset-password'),
           		'is_html' => true
           	);
            $send = Specific::SendEmail($send_email_data);
            $status = $send ? 200 : 400;
            $message = $send ? $TEMP['#word']['email_sent'] : $TEMP['#word']['error_occurred_to_send_mail'];
            $deliver = array(
			    'status' => $status,
			    'message' => $message
			);
        } else {
        	$deliver = array(
			    'status' => 400,
			    'error' => $error
			);
        }
    }
} else if($one == 'reset-password'){
	$deliver['status'] = 400;
	$error   = '';
	$emptys = array();
	$code = Specific::Filter($_POST['code']);
	$password        = Specific::Filter($_POST['password']);
	$re_password      = Specific::Filter($_POST['re-password']);
	$by_id = $dba->query('SELECT id FROM users WHERE token = "'.$code.'"')->fetchArray();
	if(empty($password)){
		$emptys[] = 'password';
	}
	if(empty($password)){
		$emptys[] = 're-password';
	}
	if (empty($emptys)) {
	    if (empty($code) && empty($by_id)){
	    	$error = $TEMP['#word']['error'];
	    }else if ($password != $re_password) {
	        $error = $TEMP['#word']['password_not_match'];
	    } else if (strlen($password) < 4 || strlen($password) > 25) {
	        $error = $TEMP['#word']['password_is_short'];
	    }
	    if (empty($error)) {
	       	$token = sha1(time() + rand(111111,999999));
	       	if ($dba->query('UPDATE users SET password = "'.sha1($password).'", token = "'.$token.'" WHERE id = '.$by_id)->returnStatus()) {
		        $deliver = array(
				    'status' => 200,
					'url' => '&one=login'
				);
	        }
	    } else {
	    	$deliver = array(
			    'status' => 400,
			    'message' => $error
			);
	    }
	} else {
		$deliver = array(
		    'status' => 400,
		    'emptys' => $emptys
		);
	}
} else if($one == 'register'){
	$dates 			= array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
	$errors      	= array();
	$emptys     	= array();
	$deliver['status'] = 400;
	$username        = Specific::Filter($_POST['username']);
    $password        = Specific::Filter($_POST['password']);
    $re_password      = Specific::Filter($_POST['re-password']);
    $email           = Specific::Filter($_POST['email']);
    $gender          = Specific::Filter($_POST['gender']);
    $day          = Specific::Filter($_POST['day']);
    $month          = Specific::Filter($_POST['month']);
    $year          = Specific::Filter($_POST['year']);
	if (empty($username)){
		$emptys[] = 'username';
	}
	if (empty($email)){
		$emptys[] = 'email';
	}
	if (empty($password)){
		$emptys[] = 'password';
	}
	if (empty($re_password)){
		$emptys[] = 're-password';
	}
	if (empty($gender)){
		$emptys[] = 'gender';
	}
	if (empty($day)){
		$emptys[] = 'day';
	}
	if (empty($month)){
		$emptys[] = 'month';
	}
	if (empty($year)){
		$emptys[] = 'year';
	}
	if(empty($emptys)){
        if ($dba->query('SELECT COUNT(*) FROM users WHERE username = "'.$username.'"')->fetchArray() > 0) {
            $errors[] = array('error' => $TEMP['#word']['username_is_taken'], 'el' => 'username');
        }
        if (preg_match_all('~@(.*?)(.*)~', $email, $matches) && !empty($matches[2]) && !empty($matches[2][0]) && Specific::IsBanned($matches[2][0])) {
            $errors[] = array('error' => $TEMP['#word']['email_provider_banned'], 'el' => 'email');
        }
        if (strlen($username) < 4 || strlen($username) > 25) {
            $errors[] = array('error' => $TEMP['#word']['username_characters_length'], 'el' => 'username');
        }
        if (!preg_match('/^[\w]+$/', $username)) {
            $errors[] = array('error' => $TEMP['#word']['username_invalid_characters'], 'el' => 'username');
        }
        if ($dba->query('SELECT COUNT(*) FROM users WHERE email = "'.$email.'"')->fetchArray() > 0) {
            $errors[] = array('error' => $TEMP['#word']['email_exists'], 'el' => 'email');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = array('error' => $TEMP['#word']['email_invalid_characters'], 'el' => 'email');
        }
        if ($password != $re_password) {
            $errors[] = array('error' => $TEMP['#word']['password_not_match'], 'el' => 're-password');
        }
        if (strlen($password) < 4) {
            $errors[] = array('error' => $TEMP['#word']['password_is_short'], 'el' => 'password');
        }
        if (!in_array($gender, array(1, 2))) {
            $errors[] = array('error' => $TEMP['#word']['gender_is_invalid'], 'el' => 'gender');
        }
	 	if(strlen($day) > 2){
	    	$errors[] = array('error' => $TEMP['#word']['please_enter_valid_date'], 'el' => 'day');
	    }
	    if(!in_array($month, $dates)){
	    	$errors[] = array('error' => $TEMP['#word']['please_enter_valid_date'], 'el' => 'month');
	    } 
	    if(strlen($year) > 4 || strlen($year) < 4){
	    	$errors[] = array('error' => $TEMP['#word']['please_enter_valid_date'], 'el' => 'year');
	    }
	    if(!checkdate($month, $day, $year)){
	    	$errors[] = array('error' => $TEMP['#word']['please_enter_valid_date'], 'el' => 'day-month-year');
	    }
        if ($TEMP['#settings']['recaptcha'] == 'on') {
            if (!isset($_POST['recaptcha']) || empty($_POST['recaptcha'])) {
                $errors[] = array('error' => $TEMP['#word']['reCaptcha_error'], 'el' => 'g-recaptcha');
            }
        }
        if (empty($errors)) {
        	$date_birthday = DateTime::createFromFormat('d-m-Y H:i:s', "$day-$month-$year 00:00:00");
            $code = rand(111111,999999);
			$token = sha1($code);
			$id = Specific::RandomKey(12, 16);
			$live_key = 'live_'.Specific::RandomKey(24, 30);
			if($dba->query('SELECT COUNT(*) FROM users WHERE live_key = "'.$live_key.'"')->fetchArray() > 0){
				$live_key = 'live_'.Specific::RandomKey(24, 30);
			}
			$ip = Specific::GetClientIp();
			$password = sha1($password);
            $insert_array = array(
            	'user_id' => "'$id'",
                'username' => "'$username'",
                'user_changed' => strtotime("+2 month, 12:00am", time()),
                'password' => "'$password'",
                'email' => "'$email'",
                'ip' => "'$ip'",
                'gender' => "'$gender'",
                'status' => $TEMP['#settings']['validate_email'] == 'on' ? 0 : 1,
                'token' => "'$token'",
                'live_key' => "'$live_key'",
                'date_birthday' => $date_birthday->getTimestamp(),
                'time' => time()
            );
            if ($gender == 1) {
                $insert_array['avatar'] = "'images/default-avatar.jpg'";
            }else{
                $insert_array['avatar'] = "'images/default-favatar.jpg'";
            }
            $insert_array['language'] = "'{$TEMP['#settings']['language']}'";
            if (!empty($_SESSION['language'])) {
                if (in_array($_SESSION['language'], $TEMP['#languages'])) {
                    $insert_array['language'] = "'{$_SESSION['language']}'";
                }
            }
            $by_id = $dba->query('INSERT INTO users ('.implode(',', array_keys($insert_array)).') VALUES ('.implode(',', array_values($insert_array)).')')->insertId();
            if($by_id) {
	            if ($TEMP['#settings']['validate_email'] == 'on') {
					$TEMP['id'] = $id;
	            	$TEMP['token'] = $token;
					$TEMP['code'] = $code;
					$TEMP['username'] = $username;
					$TEMP['text'] = $TEMP['#word']['verify_your_account'].' '.$TEMP['#word']['of'].' '.$TEMP['#settings']['title'];
					$TEMP['footer'] = '<a target="_blank" href="'.Specific::Url("not-me/$token/$id").'" style="color: #999; text-decoration: underline;">'.$TEMP['#word']['let_us_know'].'</a>.';
					$TEMP['type'] = 'verify';

	                $send_email = Specific::SendEmail(array(
	                    'from_email' => $TEMP['#settings']['smtp_username'],
		                'from_name' => $TEMP['#settings']['name'],
	                    'to_email' => $email,
	                    'to_name' => $username,
	                    'subject' => $TEMP['#word']['verify_your_account'],
	                    'charSet' => 'UTF-8',
				        'message_body' => Specific::Maket('emails/includes/verify-email'),
	                    'is_html' => true
	                ));
	                if($send_email){
		                $deliver = array(
						    'status' => 200,
						    'message' => $TEMP['#word']['successfully_joined_desc']
						);
	               	} else {
						$deliver = array(
						    'status' => 400,
						    'message' => $TEMP['#word']['error_occurred_to_send_mail']
						);
					}
	            } else {
	                $session_id = sha1(Specific::RandomKey()) . md5(time());
			        $session_details = json_encode(Specific::BrowserDetails()['details']);
			        if($dba->query('INSERT INTO sessions (by_id, session_id, details, time) VALUES (?, ?, ?, ?)', $by_id, $session_id, $session_details, time())->insertId()){
			        	$_SESSION['session_id'] = $session_id;
		                setcookie("session_id", $session_id, time() + 315360000, "/");
		                $deliver = array(
							'status' => 200,
							'url' => Specific::Url()
						);
			        }
	            }
	        }
        } else {
        	$deliver = array(
				'status' => 400,
			    'err' => $errors
			);
        }
    } else {
        $deliver = array(
			'status' => 400,
		    'emptys' => $emptys
		);
    }
} else if ($one == 'verify-email'){
	$deliver['status'] = 400;
	$id = Specific::Filter($_POST['id']);
	$ecode = Specific::Filter($_POST['ecode']);
	$user = $dba->query('SELECT * FROM users WHERE user_id = "'.$id.'" AND token = "'.sha1($ecode).'"')->fetchArray();
	if(!empty($ecode) && !empty($id) && !empty($user)){
		if ($dba->query('UPDATE users SET status = 1, token = "'.sha1(rand(111111,999999)).'" WHERE id = '.$user['id'])->returnStatus()) {
			$session_id          = sha1(Specific::RandomKey()) . md5(time());
		    $insert = $dba->query('INSERT INTO sessions (by_id, session_id, time) VALUES ('.$user['id'].',"'.$session_id.'",'.time().')')->insertid();
		    $_SESSION['session_id'] = $session_id;
		    setcookie("session_id", $session_id, time() + 315360000, "/");
		}
		$deliver = array(
			'status' => 200,
			'message' => $TEMP['#word']['new_email_verified'],
			'url' => Specific::Url()
		);
	} else {
		$deliver = array(
			'status' => 400,
			'message' => $TEMP['#word']['error']
		);
	}
} else if($one == 'verify-change-email'){
	$deliver['status'] = 400;
	$id = Specific::Filter($_POST['id']);
	$ecode = Specific::Filter($_POST['ecode']);
	$user = $dba->query('SELECT * FROM users WHERE user_id = "'.$id.'" AND token = "'.md5($ecode).'"')->fetchArray();
	if (!empty($id) && !empty($ecode) && !empty($user) && Specific::IsOwner($user['id'])) {
		$code = rand(111111, 999999);
	    $token = md5($code);
	    if($dba->query('UPDATE users SET token = ?, email = ?, change_email = ? WHERE id = '.$user['id'], $token, $user['change_email'], NULL)->returnStatus()){
	    	$deliver = array(
			    'status' => 200,
			    'url' => 'settings'
			);
	    }
	} else {
		$deliver = array(
		    'status' => 400,
		    'message' => $TEMP['#word']['wrong_confirm_code']
		);
	}
} else if($one == 'resend-change-email'){
	$id = Specific::Filter($_POST['id']);
	$code = Specific::Filter($_POST['code']);
	$user = $dba->query('SELECT * FROM users WHERE user_id = "'.$id.'" AND token = "'.$code.'"')->fetchArray();
	if (!empty($user)) {
	    $code = rand(111111, 999999);
	    $token = md5($code);
	    $dba->query('UPDATE users SET token = "'.$token.'" WHERE user_id = "'.$id.'"');

	    $TEMP['id'] = $id;
		$TEMP['token'] = $token;
		$TEMP['code'] = $code;
		$TEMP['username'] = $user['username'];
		$TEMP['text'] = $TEMP['#word']['check_your_new_email'];
        $TEMP['footer'] = $TEMP['#word']['just_ignore_this_message'];
        $TEMP['type'] = 'change';

	    $send_email_data = array(
	        'from_email' => $TEMP['#settings']['smtp_username'],
	        'from_name' => $TEMP['#settings']['name'],
	        'to_email' => $user['email'],
	        'to_name' => $user['username'],
	        'subject' => $TEMP['#word']['verify_your_account'],
	        'charSet' => 'UTF-8',
	        'message_body' => Specific::Maket('emails/includes/verify-email'),
	        'is_html' => true
	    );
	    $send = Specific::SendEmail($send_email_data);
		$deliver = array(
			'status' => $send ? 200 : 400,
			'ecode' => $send ? $token : $code,
	       	'message' => $send ? $TEMP['#word']['email_sent'] : $TEMP['#word']['error_occurred_to_send_mail']
		);
	} else {
		$deliver = array(
		    'status' => 204,
	   		'message' => $TEMP['#word']['error']
		);
	}
} else if($one == 'bubbles'){
	$deliver['status'] = 400;
	$bubbles = Specific::Filter($_POST['bubbles']);
	if(!empty($bubbles)){
		$bubbles = Specific::Bubbles(array('rands' => explode(',', $bubbles)));
		$deliver = array(
			'status' => 200,
			'bubble' => $bubbles['avatar'],
			'bubbles' => $bubbles['rands']
		);
	}
}
?>