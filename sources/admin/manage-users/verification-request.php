<?php 
if ($TEMP['#loggedin'] === false || $TEMP['#owner_global'] === false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$verification_requests = $dba->query('SELECT * FROM requests ORDER BY id DESC LIMIT ? OFFSET ?', $TEMP['#settings']['data_load_limit'], 1)->fetchAll();
$TEMP['#total_pages'] = $dba->totalPages;

if (!empty($verification_requests)) {
	foreach ($verification_requests as $value) {
		$TEMP['#status'] = $value['status'];
	    $TEMP['!id'] = $value['id'];
        $TEMP['!data'] = Specific::Data($value['by_id']);
        $TEMP['!time'] = Specific::DateFormat($value['time']);
        $TEMP['!status'] = $TEMP['#status'] == 0 ? $TEMP['#word']['pending'] : $TEMP['#status'] == 1 ? $TEMP['#word']['verified'] : $TEMP['#word']['rejected'];
		$TEMP['verification_list'] .= Specific::Maket('admin/manage-users/includes/verification-list');
	}
	Specific::DestroyMaket();
} else {
	$TEMP['verification_list'] .= Specific::Maket('not-found/requests');
}


$TEMP['#page']         = 'manage-users';
$TEMP['#title']        = $TEMP['#word']['verification_requests'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description']  = $TEMP['#settings']['description'];
$TEMP['#keyword']      = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	   = Specific::Url('admin/manage-users/verification-request');

$TEMP['#content'] = Specific::Maket("admin/manage-users/verification-request");
?>