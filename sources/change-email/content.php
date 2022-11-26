<?php 
$id = Specific::Filter($_GET['id']);
$ecode = Specific::Filter($_GET['code']);
$TEMP['#descode'] = Specific::Filter($_GET['insert']);
if ($TEMP['#loggedin'] === false){
    header("Location: " . Specific::Url('login'));
    exit();
} else if (empty($id) || empty($ecode)) {
	header("Location: " . Specific::Url('settings'));
	exit();
} else if ($TEMP['#settings']['validate_email'] != 'on') {
    header("Location: " . Specific::Url('404'));
    exit();
}

$page = $dba->query('SELECT COUNT(*) FROM users WHERE user_id = "'.$id.'" AND token = "'.$ecode.'"')->fetchArray() == 0 || (strlen($TEMP['#descode']) != 6 && !empty($TEMP['#descode'])) ? 'invalid-auth' : 'authentication';

$TEMP['#bubbles'] = Specific::Bubbles();

$TEMP['title'] = $TEMP['#word']['check_your_new_email'];
$TEMP['type'] = 'change-email';
$TEMP['id'] = $id;
$TEMP['code'] = $ecode;
$TEMP['bubbles'] = implode(',', $TEMP['#bubbles']['rands']);
if(!empty($_GET['insert'])){
	$TEMP['desone'] = substr($TEMP['#descode'], 0, 1);
	$TEMP['destwo'] = substr($TEMP['#descode'], 1, 1);
	$TEMP['desthree'] = substr($TEMP['#descode'], 2, 1);
	$TEMP['desfour'] = substr($TEMP['#descode'], 3, 1);
	$TEMP['desfive'] = substr($TEMP['#descode'], 4, 1);
	$TEMP['dessix'] = substr($TEMP['#descode'], 5, 1);
}

$TEMP['#page']        = 'login';
$TEMP['#title']       = $TEMP['#word']['authentication'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url']    = Specific::Url('change-email/'.$ecode.'/'.$id);

$TEMP['#content']     = Specific::Maket("$page/content");
?>