<?php 
if ($TEMP['#loggedin'] === false || $TEMP['#owner_global'] === false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$comments = $dba->query('SELECT * FROM comments ORDER BY id DESC LIMIT ? OFFSET ?', $TEMP['#settings']['data_load_limit'], 1)->fetchAll();
$TEMP['#total_pages'] = $dba->totalPages;

if (!empty($comments)) {
	foreach ($comments as $value) {
		$video = Specific::Video($value['video_id']);
		$TEMP['!id'] = $value['id'];
		$TEMP['!video_id'] = $video['video_id'];
		$TEMP['!url'] = $video['url'];
		$TEMP['!time'] = Specific::DateFormat($value['time']);
		$TEMP['!text'] = Specific::GetComposeText($value['text']);
		$TEMP['comments_list'] .= Specific::Maket('admin/includes/comments-list');
	}
	Specific::DestroyMaket();
} else {
    $TEMP['comments_list'] .= Specific::Maket('not-found/comments');
}

$TEMP['#page']        = 'manage-commens';
$TEMP['#title']       = $TEMP['#word']['manage_comments'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword']     = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	  = Specific::Url('admin/manage-commens');
$TEMP['#content']     = Specific::Maket("admin/manage-commens");
?>