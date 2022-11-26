<?php 
if ($TEMP['#loggedin'] === false || Specific::Admin() == false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$TEMP['#page']         = 'manage-languages';
$TEMP['#title']        = $TEMP['#word']['manage_languages'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description']  = $TEMP['#settings']['description'];
$TEMP['#keyword']      = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	  = Specific::Url('admin/manage-languages/add-word-language');
$TEMP['#content']      = Specific::Maket("admin/manage-languages/add-word-language");
?>