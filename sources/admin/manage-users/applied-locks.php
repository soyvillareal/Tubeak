<?php 
if ($TEMP['#loggedin'] === false || $TEMP['#owner_global'] === false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$users = $dba->query('SELECT * FROM banned ORDER BY id DESC LIMIT ? OFFSET ?', $TEMP['#settings']['data_load_limit'], 1)->fetchAll();
$TEMP['#total_pages'] = $dba->totalPages;

if (!empty($users)) {
	foreach ($users as $value) {
	   	$TEMP['!id'] = $value['id'];
	    $TEMP['!value'] = $value['value'];
	    $TEMP['!time'] = Specific::DateFormat($value['time']);
		$TEMP['banned_list'] .= Specific::Maket('admin/manage-users/includes/applied-locks-list');
	}
	Specific::DestroyMaket();
} else {
    $TEMP['banned_list'] .= Specific::Maket('not-found/applied-locks');
}


$TEMP['#page']         = 'manage-users';
$TEMP['#title']        = $TEMP['#word']['applied_locks'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description']  = $TEMP['#settings']['description'];
$TEMP['#keyword']      = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	   = Specific::Url('admin/manage-users/applied-locks');

$TEMP['#content']      = Specific::Maket("admin/manage-users/applied-locks");
?>