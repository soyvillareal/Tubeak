<?php
if (empty($_POST['by_id']) || $TEMP['#loggedin'] === false) {
    $deliver = array(
        'status' => 400,
        'error' => $TEMP['#word']['error']
    );
    echo json_encode($deliver);
    exit();
}

if ($one == 'general') {
    $dates  = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
    $emptys = array();
    $gen = 'male';
    $deliver['status'] = 400;

    $by_id = Specific::Filter($_POST['by_id']);
    $user_data = Specific::Data($by_id);
    $age_changed = $user_data['age_changed'];
    $d = $user_data['birthday'];
    $m = $user_data['birthday_month'];
    $y = $user_data['birthday_year'];

    $day = Specific::Filter($_POST['day']);
    $month = Specific::Filter($_POST['month']);
    $year = Specific::Filter($_POST['year']);

    $username = Specific::Filter($_POST['username']);
    $username_changed = Specific::Filter($_POST['username_changed']);
    $email = Specific::Filter($_POST['settings-email']);
    $gender = Specific::Filter($_POST['gender']);

    
    if (empty($username) && time() > $user_data['user_changed']) {
        $emptys[] = 'username';
    }
    if (empty($email)) {
        $emptys[] = 'settings-email';
    }
    if($day != $d || $month != $m || $year != $y && $age_changed < 1){
        if(empty($day)){
            $emptys[] = 'day';
        }
        if(empty($month)){
            $emptys[] = 'month';
        }
        if(empty($year)){
            $emptys[] = 'year';
        }
    }
    if(empty($emptys)){
        if ($day != $d || $month != $m || $year != $y && $age_changed < 1) {
            $d = $day;
            $m = $month;
            $y = $year;
            if(!Specific::Admin()){
                $age_changed = $age_changed + 1;
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
        }
        if (!empty($gender)) {
            if (in_array($gender, array(1, 2))) {
                $gen = $gender;
            }
        }
        if ($email != $user_data['email']) {
            if ($dba->query('SELECT COUNT(*) FROM users WHERE email = "'.$email.'"')->fetchArray() > 0) {
                $errors[] = array('error' => $TEMP['#word']['email_exists'], 'el' => 'settings-email');
            }
        }
        if (time() < $user_data['user_changed'] && !empty($username) && $username_changed == "false") {
            $errors[] = array('error' => $TEMP['#word']['you_still_dont_meet_equirements'], 'el' => 'username');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = array('error' => $TEMP['#word']['email_invalid_characters'], 'el' => 'settings-email');
        }
            
        if (!isset($errors)) {
            $update_data = '';
            $redirect_verify = false;
            $user_changed = false;
            $date_birthday = DateTime::createFromFormat('d-m-Y H:i:s', "$d-$m-$y 00:00:00");
            
            if ($TEMP['#settings']['validate_email'] == 'on' && !empty($email) && $user_data['email'] != $email) {
                $code = rand(111111, 999999);
                $token = md5($code);
                $dba->query('UPDATE users SET token = "'.$token.'" WHERE id = '.$user_data['id']);

                $TEMP['token'] = $token;
                $TEMP['code'] = $code;
                $TEMP['id'] = $user_data['user_id'];
                $TEMP['username'] = $user_data['username'];
                $TEMP['text'] = $TEMP['#word']['check_your_new_email'];
                $TEMP['footer'] = $TEMP['#word']['just_ignore_this_message'];
                $TEMP['type'] = 'change';

                $send_email = Specific::SendEmail(array(
                    'from_email' => $TEMP['#settings']['smtp_username'],
                    'from_name' => $TEMP['#settings']['name'],
                    'to_email' => $user_data['email'],
                    'to_name' => $user_data['name'],
                    'subject' => $TEMP['#word']['verify_your_account'],
                    'charSet' => 'UTF-8',
                    'message_body' => Specific::Maket('emails/includes/verify-email'),
                    'is_html' => true
                ));
                if ($send_email) {
                    $redirect_verify = true;
                    $deliver['ecode'] = $token;
                    $update_data = ', change_email = "'.$email.'"';
                }
            }else{
                $update_data = ', email = "'.$email.'"';
            }
            

            if(!empty($username) && $username != $user_data['username'] && $username_changed == "false"){
                $time_changed = strtotime("+2 month, 12:00am", time());
                $update_data .= ', username = "'.$username.'", user_changed = '.$time_changed;
                $user_changed = true;
                $deliver['text'] = $TEMP['#word']['you_can_update_your_username_again_in'].Specific::DateString($time_changed, false);
            }

            if (Specific::IsOwner($_POST['by_id'])) {
                $update = $dba->query('UPDATE users SET gender = '.$gen.', age_changed = '.$age_changed.', date_birthday = '.$date_birthday->getTimestamp().', country = '.Specific::Filter($_POST['country']).', first_name = "'.Specific::Filter($_POST['first_name']).'", last_name = "'.Specific::Filter($_POST['last_name']).'", about = "'.Specific::Filter($_POST['about']).'", facebook = "'.Specific::Filter($_POST['facebook']).'", mail_contact = "'.Specific::Filter($_POST['mail_contact']).'", twitter = "'.Specific::Filter($_POST['twitter']).'", instagram = "'.Specific::Filter($_POST['instagram']).'"'.$update_data.' WHERE id = '.$by_id)->returnStatus();
                if ($update){
                    $deliver['status'] = 200;
                    $deliver['message'] = $TEMP['#word']['setting_updated'];
                    $deliver['redirect_verify'] = $redirect_verify;
                    $deliver['user_changed'] = $user_changed;
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
} else if ($one == 'change-password') {
    $emptys = array();
    $user_data = Specific::Data($_POST['by_id']);
    if (empty($_POST['current-password'])) {
        $emptys[] = 'current-password';
    }
    if (empty($_POST['password'])) {
        $emptys[] = 'password';
    }
    if (empty($_POST['re-password'])) {
        $emptys[] = 're-password';
    }

    if (empty($emptys)) {
        if ($user_data['password'] != sha1($_POST['current-password'])) {
            $errors[] = array('error' => $TEMP['#word']['current_password_dont_match'], 'el' => 'current-password');
        }
        if (strlen($_POST['password']) < 4) {
            $errors[] = array('error' => $TEMP['#word']['password_is_short'], 'el' => 'password');
        }
        if ($_POST['password'] != $_POST['re-password']) {
            $errors[] = array('error' => $TEMP['#word']['new_password_dont_match'], 'el' => 're-password');
        }
        if (!isset($errors)) {
            if (Specific::IsOwner($_POST['by_id'])) {
                if ($dba->query('UPDATE users SET password = "'.sha1($_POST['password']).'" WHERE id = '.Specific::Filter($_POST['by_id']))->returnStatus()) {
                    $deliver = array(
                        'status' => 200,
                        'message' => $TEMP['#word']['setting_updated']
                    );
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
} else if ($one == 'delete') {
    $user_data = Specific::Data($_POST['by_id']);
    if($TEMP['#settings']['delete_account'] == 'on' && !empty($user_data) && Specific::IsOwner($_POST['by_id'])){  
        $current_password = Specific::Filter($_POST['current-password']);  
        if(!empty($current_password)){
            if ($user_data['password'] == sha1($current_password)) {
                if (Specific::DeleteUser($user_data['id'])) {
                    $deliver = array(
                        'status' => 200,
                        'message' => $TEMP['#word']['your_account_was_deleted'],
                        'url' => Specific::Url()
                    );
                }
            } else {
                $deliver = array(
                    'status' => 400,
                    'err' => $TEMP['#word']['incorrect_password']
                );
            }
        } else {
            $deliver = array(
                'status' => 400,
                'empty' => $TEMP['#word']['this_field_is_empty']
            );
        }
    } else {
        $deliver = array(
            'status' => 400,
            'error' => $TEMP['#word']['error']
        );
    }
        
} else if ($one == 'request-verification') {
    if ($dba->query('SELECT COUNT(*) FROM requests WHERE status = 0 AND by_id = '.$TEMP['#user']['id'])->fetchArray() == 0) {
        $TEMP['username'] = $TEMP['#user']['username'];
        $send_email = Specific::SendEmail(array(
            'from_email' => $TEMP['#settings']['smtp_username'],
            'from_name' => $TEMP['#settings']['name'],
            'to_email' => $TEMP['#user']['email'],
            'to_name' => $TEMP['#user']['name'],
            'subject' => $TEMP['#word']['verification_request_was_received'],
            'charSet' => 'UTF-8',
            'message_body' => Specific::Maket('emails/includes/verify-account'),
            'is_html' => true
        ));
        if($send_email){
            if ($dba->query('INSERT INTO requests (by_id, `time`) VALUES ('.$TEMP['#user']['id'].','.time().')')->returnStatus()) {
                $deliver['status']  = 200;
                $deliver['message'] = $TEMP['#word']['verif_request_sent'];
            } else {
                $deliver['status']  = 500;
                $deliver['message'] = $TEMP['#word']['unknown_error'];
            }
        }

    } else{
        $deliver['status']  = 400;
        $deliver['message'] = $TEMP['#word']['submit_verif_request_error'];
    }
} else if ($one == 'authentication') {
    if(in_array($_POST['authentication'], array(0, 1))){
        $by_id = $TEMP['#user']['id'];
        if (!empty($_POST['by_id']) && is_numeric($_POST['by_id']) && $_POST['by_id'] > 0) {
            if (Specific::Admin()) {
                $by_id = Specific::Filter($_POST['by_id']);
            }
        }
        if($dba->query('UPDATE users SET authentication = '.Specific::Filter($_POST['authentication']).' WHERE id = '.$by_id)->returnStatus()){
            $deliver = array(
                'status' => 200,
                'message' => $TEMP['#word']['setting_updated']
            );
        }
    }  
} else if ($one == 'block-user') {
    $deliver['status'] = 400;
    if(!empty($_POST['by_id']) && is_numeric($_POST['by_id'])){
        $by_id = Specific::Filter($_POST['by_id']);
        $user_data = Specific::Data($by_id);
        if (!empty($user_data) && $user_data['role'] == 0) {
            if ($dba->query('SELECT COUNT(*) FROM blocked WHERE by_id = '.$TEMP['#user']['id'].' AND to_id = '.$by_id)->fetchArray() > 0) {
                $dba->query('DELETE FROM blocked WHERE by_id = '.$TEMP['#user']['id'].' AND to_id = '.$by_id);
                $deliver['message'] = $TEMP['#word']['block'];
            } else {
                $dba->query('INSERT INTO blocked (by_id, to_id, `time`) VALUES ('.$TEMP['#user']['id'].','.$by_id.','.time().')');
                $deliver['message'] = $TEMP['#word']['unblock'];
            }
            $deliver['status'] = 200;
        } else {
            $deliver = array(
                'status' => 400,
                'error' => $TEMP['#word']['error']
            );
        }
    } else {
        $deliver = array(
            'status' => 400,
            'error' => $TEMP['#word']['there_problems_with_some_fields']
        );
    }
} else if($one == 'reset-live-key') {
    $deliver['status'] = 200;
    $by_id = Specific::Filter($_POST['by_id']);
    if(!empty($by_id) && ($by_id == $TEMP['#user']['id'] || $TEMP['#owner_global'] === true)){
        $live_key = 'live_'.Specific::RandomKey(24, 30);
        if($dba->query('SELECT COUNT(*) FROM users WHERE live_key = "'.$live_key.'"')->fetchArray() > 0){
            $live_key = 'live_'.Specific::RandomKey(24, 30);
        }
        if($dba->query('UPDATE users SET live_key = "'.$live_key.'" WHERE id = '.$by_id)->returnStatus()){
            $deliver = array(
                'status' => 200,
                'message' => $TEMP['#word']['your_transmission_key_was_successfully_changed'],
                'live_key' => $live_key
            );
        }
    }
}
?>