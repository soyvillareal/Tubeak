<?php 
if (!isset($_POST['input'])) {
	$deliver = array('status' => 400, 'error' => $TEMP['#word']['error']);
    echo json_encode($deliver);
    exit();
}else {
	$error = '';
	$by_id = Specific::Filter($_POST['by_id']);
	$user_data = Specific::Data($by_id);
	$input = Specific::Filter($_POST['input']);
	$type = Specific::Filter($_POST['type']);
	$page = Specific::Filter($_POST['page']);
	$dates = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
	$re_password = $type == 're-password' ? Specific::Filter($_POST['password']) : Specific::Filter($_POST['re-password']);
	if(!empty($input) && !empty($type)){
		if ($dba->query('SELECT COUNT(*) FROM users WHERE username = "'.$input.'"')->fetchArray() > 0 && $type == 'username') {
	       	$error = $TEMP['#word']['username_is_taken'];
	    } else if (preg_match_all('~@(.*?)(.*)~', $input, $matches) && !empty($matches[2]) && !empty($matches[2][0]) && Specific::IsBanned($matches[2][0]) && ($type == 'email' || $type == 'settings-email')) {
	        $error = $TEMP['#word']['email_provider_banned'];
	    } else if ((strlen($input) < 4 || strlen($input) > 25) && $type == 'username') {
	        $error = $TEMP['#word']['username_characters_length'];
	    } else if (!preg_match('/^[\w]+$/', $input) && $type == 'username') {
	        $error = $TEMP['#word']['username_invalid_characters'];
	    } else if ($dba->query('SELECT COUNT(*) FROM users WHERE email = "'.$input.'"')->fetchArray() > 0 && ($type == 'email' || ($type == 'settings-email' && $user_data['email'] != $input))) {
	        $error = $TEMP['#word']['email_exists'];
	    } else if (!filter_var($input, FILTER_VALIDATE_EMAIL) && ($type == 'email' || $type == 'settings-email')) {
	        $error = $TEMP['#word']['email_invalid_characters'];
	    } else if($user_data['password'] != sha1($input) && $type == 'current-password') {
	    	$error = $TEMP['#word']['current_password_dont_match'];
	    } else if (strlen($input) < 4 && $type == 'password') {
	        $error = $TEMP['#word']['password_is_short'];
	    } else if ($input != $re_password && !empty($re_password) && ($type == 'password' || $type == 're-password')) {
	        $error = $TEMP['#word']['password_not_match'];
	    } else if((strlen($input) > 2 && $type == 'day') || (!in_array($input, $dates) && $type == 'month') || (strlen($input) > 4 && $type == 'year')){
	    	$error = $TEMP['#word']['please_enter_valid_date'];
	    }
	    $deliver = array(
			'status' => 200,
			'message' => (!empty($error) ? '*' : '').$error
		);
	} else {
		$deliver = array(
			'status' => 400,
			'message' => $TEMP['#word']['error']
		);
	}
}
?>