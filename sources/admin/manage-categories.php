<?php 
if ($TEMP['#loggedin'] === false || Specific::Admin() == false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$categories = Specific::Categories(true, 1);
$TEMP['#total_pages'] = $categories['total_pages'];
if(!empty($categories)){
	foreach ($categories['sql'] as $key => $value) {
		$TEMP['!key'] = $key;
		$TEMP['!value'] = $value;
		$TEMP['categories_list'] .= Specific::Maket('admin/includes/categories-list');
	}
	Specific::DestroyMaket();
} else {
	$TEMP['categories_list'] .= Specific::Maket('not-found/categories');
}

$TEMP['#page']        = 'manage-categories';
$TEMP['#title']       = $TEMP['#word']['language_settings'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	  = Specific::Url('admin/manage-categories');
$TEMP['#content']     = Specific::Maket("admin/manage-categories");
?>