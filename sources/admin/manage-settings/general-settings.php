<?php 
if ($TEMP['#loggedin'] === false || Specific::Admin() == false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$TEMP['#binary_found'] = false;
if(is_executable("{$TEMP['#settings']['binary_path']}/ffmpeg") && is_executable("{$TEMP['#settings']['binary_path']}/ffprobe")){
	$TEMP['#binary_found']  = true;
}
$TEMP['#enabled'] = true;
if (!is_callable('exec') && false === stripos(ini_get('disable_functions'), 'exec')) {
    $TEMP['#enabled'] = false;
}

$TEMP['#page']         = 'manage-settings';
$TEMP['#title']        = $TEMP['#word']['settings_'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description']  = $TEMP['#settings']['description'];
$TEMP['#keyword']      = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	   = Specific::Url('admin/manage-settings/general-settings');
$TEMP['#content']      = Specific::Maket("admin/manage-settings/general-settings");
?>