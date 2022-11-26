<?php 
if ($TEMP['#loggedin'] === false || Specific::Admin() == false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$pages = Specific::GetPages();
$TEMP['#terms_active'] = $pages['active']['terms-of-use'];
$TEMP['#privacy_active'] = $pages['active']['privacy-of-use'];
$TEMP['#about_active'] = $pages['active']['about-us'];

$TEMP['#page']         = 'manage-pages';
$TEMP['#title']        = $TEMP['#word']['site_pages'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description']  = $TEMP['#settings']['description'];
$TEMP['#keyword']      = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	   = Specific::Url('admin/manage-pages');

$TEMP['terms'] = $pages['page']['terms-of-use'];
$TEMP['privacy'] = $pages['page']['privacy-policy'];
$TEMP['about'] = $pages['page']['about-us'];

$TEMP['#content'] = Specific::Maket("admin/manage-pages");
?>