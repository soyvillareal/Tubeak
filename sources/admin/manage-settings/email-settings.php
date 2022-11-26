<?php 
if ($TEMP['#loggedin'] === false || Specific::Admin() == false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$TEMP['#page']         = 'manage-settings';
$TEMP['#title']        = $TEMP['#word']['configure_your_mail_server'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description']  = $TEMP['#settings']['description'];
$TEMP['#keyword']      = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	  = Specific::Url('admin/manage-settings/email-settings');
$TEMP['#content']      = Specific::Maket("admin/manage-settings/email-settings");
?>