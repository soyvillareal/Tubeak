<?php 
if ($TEMP['#loggedin'] === false || Specific::Admin() == false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$languages = $dba->query('SELECT * FROM types WHERE type != "category" LIMIT ? OFFSET ?', $TEMP['#settings']['data_load_limit'], 1)->fetchAll();
$TEMP['#total_pages'] = $dba->totalPages;

foreach ($languages as $value) {
	$TEMP['!code'] = $value['type'];
	$TEMP['!language'] = $value['name'];
	$TEMP['languages_list'] .= Specific::Maket('admin/manage-languages/includes/languages-list');
}
Specific::DestroyMaket();

$TEMP['#page']         = 'manage-languages';
$TEMP['#title']        = $TEMP['#word']['language_settings'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description']  = $TEMP['#settings']['description'];
$TEMP['#keyword']      = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	   = Specific::Url('admin/manage-languages/general-languages');

$TEMP['#content'] = Specific::Maket("admin/manage-languages/general-languages");
?>