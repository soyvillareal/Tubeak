<?php 
if ($TEMP['#loggedin'] === false || $TEMP['#owner_global'] === false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$reports = $dba->query('SELECT * FROM reports ORDER BY id DESC LIMIT ? OFFSET ?', $TEMP['#settings']['data_load_limit'], 1)->fetchAll();
$TEMP['#total_pages'] = $dba->totalPages;

if (!empty($reports)) {
    foreach ($reports as $value) {
        $user_data  = Specific::Data($value['by_id']);
        $TEMP['#type'] = $value['type'];   
        $TEMP['!id'] = $value['id'];
        $TEMP['!data'] = $user_data;
        $TEMP['!type'] = $TEMP['#type'];
        $TEMP['!time'] = Specific::DateFormat($value['time']);
        if($value['type'] == 'video'){
            $video = Specific::Video($value['to_id']);
            $TEMP['!url'] = $video['url'];
            $TEMP['!title'] = $video['title'];
            $TEMP['!video_id'] = $video['video_id'];
        } else {
            $user_rdata  = Specific::Data($value['to_id']);
            $TEMP['!url'] = Specific::Url('user/' . $user_rdata['by_id']);
            $TEMP['!title'] = $user_rdata['username'];
        }
        $TEMP['reports_list'] .= Specific::Maket('admin/includes/reports-list');
    }
    Specific::DestroyMaket();
} else {
    $TEMP['reports_list'] .= Specific::Maket('not-found/reports');
}

$TEMP['#page']        = 'manage-repors';
$TEMP['#title']       = $TEMP['#word']['general_reports'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url']    = Specific::Url('admin/manage-repors');
$TEMP['#content']     = Specific::Maket("admin/manage-repors");
?>