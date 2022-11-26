<?php 
header("HTTP/1.0 404 Not Found");

$TEMP['#page'] = '404';
$TEMP['#title'] = '404 - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword'] = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] = Specific::Url('404');

$TEMP['#content'] = Specific::Maket('404/content');
?>