<?php
$id = Specific::Filter($_GET['id']);
$ecode = Specific::Filter($_GET['code']);
$TEMP['#descode'] = Specific::Filter($_GET['insert']);
if ($TEMP['#loggedin'] || empty($ecode) || empty($id)) {
	header("Location: " . Specific::Url());
	exit();
}

$user = $dba->query('SELECT * FROM users WHERE user_id = "'.$id.'" AND token = "'.$ecode.'"')->fetchArray();
$page = empty($user) || $user['status'] == 1 || (strlen($TEMP['#descode']) != 6 && !empty($TEMP['#descode'])) ? 'invalid-auth' : 'authentication';

$TEMP['#bubbles'] = Specific::Bubbles();

$TEMP['title'] = $TEMP['#word']['check_your_email'];
$TEMP['type'] = 'email';
$TEMP['id'] = $id;
$TEMP['code'] = $ecode;
$TEMP['bubbles'] = implode(',', $TEMP['#bubbles']['rands']);
if(!empty($$_GET['insert'])){
	$TEMP['desone'] = substr($TEMP['#descode'], 0, 1);
	$TEMP['destwo'] = substr($TEMP['#descode'], 1, 1);
	$TEMP['desthree'] = substr($TEMP['#descode'], 2, 1);
	$TEMP['desfour'] = substr($TEMP['#descode'], 3, 1);
	$TEMP['desfive'] = substr($TEMP['#descode'], 4, 1);
	$TEMP['dessix'] = substr($TEMP['#descode'], 5, 1);
}

$TEMP['#page']        = 'login';
$TEMP['#title']       = $TEMP['#word']['check_your_email'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url']    = Specific::Url('verify-email/'.$ecode.'/'.$id);

$TEMP['#content'] = Specific::Maket("$page/content");
?>