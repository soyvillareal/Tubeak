<?php 
if ($TEMP['#loggedin'] === false || Specific::Admin() === false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$header_ad = Specific::GetAd('header_ad');
$sidebar_ad = Specific::GetAd('sidebar_ad');
$comments_ad = Specific::GetAd('comments_ad');

$TEMP['#header_ad_stat'] = $header_ad['active'];
$TEMP['#sidebar_ad_stat'] = $sidebar_ad['active'];
$TEMP['#comments_ad_stat'] = $comments_ad['active'];

$TEMP['#page']        = 'manage-ads';
$TEMP['#title']       = $TEMP['#word']['website_ads'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	  = Specific::Url('admin/manage-ads');


$TEMP['header_ad'] = $header_ad['content'];
$TEMP['sidebar_ad'] = $sidebar_ad['content'];
$TEMP['comments_ad'] = $comments_ad['content'];

$TEMP['#content'] = Specific::Maket("admin/manage-ads");
?>