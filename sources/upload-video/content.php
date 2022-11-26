<?php 
if ($TEMP['#loggedin'] === false) {
	header("Location: " . Specific::Url('login'));
	exit();
}
if ($TEMP['#settings']['upload_videos'] == 'off') {
	header("Location: " . Specific::Url('404'));
	exit();
}
$content = 'content';
if (!Specific::Admin() && (($TEMP['#user']['upload_limit'] != 0 && $TEMP['#user']['uploads'] >= $TEMP['#user']['upload_limit']) || $TEMP['#user']['uploads'] >= $TEMP['#settings']['upload_limit'])) {
	$content = "limit_reached";
}

$TEMP['max_upload_size'] = $TEMP['#word']['file_is_too_large_the_maximum'] . ' ' . Specific::BytesFormat($TEMP['#settings']['max_upload_size']);

$TEMP['#page']        = 'upload-video';
$TEMP['#title']       = $TEMP['#word']['upload'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url']    = Specific::Url('upload-video');

$TEMP['#content']     = Specific::Maket("upload-video/$content");
?>