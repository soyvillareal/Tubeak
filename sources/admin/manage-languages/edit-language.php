<?php 
if ($TEMP['#loggedin'] === false || Specific::Admin() == false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$language = Specific::Filter($_GET['edit']);

if(empty($language) || !in_array($language, Specific::Languages())){
	header("Location: " . Specific::Url('admin'));
    exit();
}

$words = Specific::Words($language, 1, true);
$TEMP['#total_pages'] = $words['total_pages'];
if (!empty($words)) {
	foreach ($words['sql'] as $value) {
		if($value['word'] != 'others'){
			$TEMP['!key'] = $value['word'];
			$TEMP['!value'] = $value[$language];
			$TEMP['words_list'] .= Specific::Maket('admin/manage-languages/includes/words-list');
		}
	}
	Specific::DestroyMaket();
} else {
	$TEMP['words_list'] = Specific::Maket('not-found/words');
}

$TEMP['#page']         = 'manage-languages';
$TEMP['#title']        = $TEMP['#word']['edit_language'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description']  = $TEMP['#settings']['description'];
$TEMP['#keyword']      = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	   = Specific::Url('admin/manage-languages/edit-language');

$TEMP['language'] 		= $language;
$TEMP['#content']       = Specific::Maket("admin/manage-languages/edit-language");
?>