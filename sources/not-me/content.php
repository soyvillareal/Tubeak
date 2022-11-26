<?php 
$id = Specific::Filter($_GET['id']);
$ecode = Specific::Filter($_GET['code']);
if ($TEMP['#loggedin'] === true || empty($id) || empty($ecode)) {
	header("Location: " . Specific::Url());
	exit();
}

$user = $dba->query('SELECT * FROM users WHERE user_id = "'.$id.'" AND token = "'.$ecode.'"')->fetchArray();
$TEMP['#reports'] = $dba->query('SELECT COUNT(*) FROM reports WHERE type = "user" AND by_id = '.$user['id'].' AND to_id = '.$user['id'])->fetchArray();

$page = empty($user) ? 'invalid-auth' : 'not-me';

$TEMP['#bubbles'] = Specific::Bubbles();

$TEMP['by_id']        = $id;
$TEMP['token']        = $ecode;
$TEMP['email']        = $user['email'];
$TEMP['id']           = $user['id'];
$TEMP['bubbles']      = implode(',', $TEMP['#bubbles']['rands']);

$TEMP['#page']        = 'login';
$TEMP['#title']       = $TEMP['#word']['didnt_create_this_account'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url']    = $TEMP['#settings']['site_url'].'/not-me/'.$ecode.'/'.$id;

$TEMP['#content']     = Specific::Maket("$page/content");
?>