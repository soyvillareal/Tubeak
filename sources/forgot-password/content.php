<?php
if ($TEMP['#loggedin'] === true) {
	header("Location: " . Specific::Url());
	exit();
}

$TEMP['#bubbles'] = Specific::Bubbles();

$TEMP['bubbles']      = implode(',', $TEMP['#bubbles']['rands']);

$TEMP['#page']        = 'login';
$TEMP['#title']       = $TEMP['#word']['reset_your_password'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url']    = Specific::Url('forgot-password');

$TEMP['#content']     = Specific::Maket('forgot-password/content');
?>