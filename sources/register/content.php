<?php
$TEMP['#bubbles'] = Specific::Bubbles();

$TEMP['bubbles']      = implode(',', $TEMP['#bubbles']['rands']);

$TEMP['#page']        = 'register';
$TEMP['#title']       = $TEMP['#word']['register'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url']    = Specific::Url('register');

$TEMP['#content']     = Specific::Maket('register/content');
?>