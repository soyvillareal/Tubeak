<?php 
if ($TEMP['#loggedin'] === false || Specific::Admin() == false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$TEMP['#page']         = 'manage-settings';
$TEMP['#title']        = $TEMP['#word']['website_settings'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description']  = $TEMP['#settings']['description'];
$TEMP['#keyword']      = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	  = Specific::Url('admin/manage-settings/site-settings');

$TEMP['last_sitemap']  = Specific::DateFormat($TEMP['#settings']['last_sitemap'], false);
$TEMP['icon_favicon']  = Specific::GetFile($TEMP['#settings']['icon_favicon']);
$TEMP['icon_logo'] 	   = Specific::GetFile($TEMP['#settings']['icon_logo']);
$TEMP['#content']      = Specific::Maket("admin/manage-settings/site-settings");
?>