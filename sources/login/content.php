<?php
if ($TEMP['#loggedin'] === true) {
	header("Location: " . Specific::Url());
	exit();
}

$TEMP['#bubbles'] = Specific::Bubbles();

$TEMP['to']           = Specific::Filter($_GET['to']);
$TEMP['bubbles']      = implode(',', $TEMP['#bubbles']['rands']);

$TEMP['#page']        = 'login';
$TEMP['#title']       = $TEMP['#word']['login'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url']    = Specific::Url('login'.(!empty($_GET['to']) ? '?to='.urlencode($_GET['to']) : ''));

$TEMP['#content']     = Specific::Maket('login/content');
?>