<?php 
$TEMP['#page']        = "contact";
$TEMP['#title']       = $TEMP['#word']['contact_us'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	  = Specific::Url('contact');
$TEMP['#content']     = Specific::Maket('contact/content');
?>