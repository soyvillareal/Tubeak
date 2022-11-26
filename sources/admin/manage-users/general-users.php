<?php 
if ($TEMP['#loggedin'] === false || $TEMP['#owner_global'] === false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$users = $dba->query('SELECT * FROM users ORDER BY id DESC LIMIT ? OFFSET ?', $TEMP['#settings']['data_load_limit'], 1)->fetchAll();
$TEMP['#total_pages'] = $dba->totalPages;

if (!empty($users)) {
	foreach ($users as $value) {
		$user = Specific::Data($value['id']);
	    $TEMP['!data'] = $user;
	    $TEMP['!status'] = $user['status'] == 1 ? $TEMP['#word']['active'] : $TEMP['#word']['pending'];
		$TEMP['users_list'] .= Specific::Maket('admin/manage-users/includes/users-list');
	}
	Specific::DestroyMaket();
} else {
    $TEMP['users_list'] .= Specific::Maket('not-found/users');
}


$TEMP['#page']         = 'manage-users';
$TEMP['#title']        = $TEMP['#word']['manage_users'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description']  = $TEMP['#settings']['description'];
$TEMP['#keyword']      = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	   = Specific::Url('admin/manage-users/general-users');

$TEMP['#content']      = Specific::Maket("admin/manage-users/general-users");
?>