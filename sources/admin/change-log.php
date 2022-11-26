<?php 
if ($TEMP['#loggedin'] === false || Specific::Admin() === false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$TEMP['#page']        = 'change-log';
$TEMP['#title']       = $TEMP['#word']['change_log'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	  = Specific::Url('admin/change-log');
$TEMP['#content']     = Specific::Maket("admin/change-log");
?>