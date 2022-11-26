<?php
if ($TEMP['#loggedin'] === true || empty($_GET['code'])) {
	header("Location: " . Specific::Url());
	exit();
}

$code = Specific::Filter($_GET['code']);
$page = $dba->query('SELECT COUNT(*) FROM users WHERE token = "'.$code.'"')->fetchArray() == 0 ? 'invalid-auth' : 'reset-password';

$TEMP['#bubbles'] = Specific::Bubbles();

$TEMP['code'] = $code;
$TEMP['bubbles'] = implode(',', $TEMP['#bubbles']['rands']);

$TEMP['#page']        = 'login';
$TEMP['#title']       = $TEMP['#word']['change_password'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];

$TEMP['#content']     = Specific::Maket("$page/content");
?>