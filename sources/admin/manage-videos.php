<?php 
if ($TEMP['#loggedin'] === false || $TEMP['#owner_global'] === false) {
	header("Location: " . Specific::Url('404'));
    exit();
}

$query = '';
$type_url = '';
$TEMP['#approve'] = Specific::Filter($_GET['approve']);
$TEMP['#privacy'] = Specific::Filter($_GET['privacy']);
$TEMP['#category'] = Specific::Filter($_GET['category']);

if($TEMP['#settings']['approve_videos'] == 'on' && !empty($TEMP['#approve']) && $TEMP['#approve'] != 'all'){
	if ($TEMP['#approve'] == 'pending') {
	    $type_url = '?approve=pending';
	    $query = ' WHERE approved = 0';
	} elseif ($TEMP['#approve'] == 'approved') {
	    $type_url = '?approve=approved';
	    $query = ' WHERE approved = 1';
	}
}

if(!empty($TEMP['#privacy']) && $TEMP['#privacy'] != 'all'){
	$approve = '?';
	$where = ' WHERE';
	if($TEMP['#settings']['approve_videos'] == 'on'){
		$approve = '?approve='.$TEMP['#approve'].'&';
		if(!empty($query)){
			$where = ' AND';
		}
	}
	if ($TEMP['#privacy'] == 'public') {
	    $type_url    = $approve.'privacy=public';
	    $query .= $where.' privacy = 0';
	} elseif ($TEMP['#privacy'] == 'private') {
	    $type_url    = $approve.'privacy=private';
	    $query .= $where.' privacy = 1';
	} elseif ($TEMP['#privacy'] == 'unlisted') {
	    $type_url    = $approve.'privacy=unlisted';
	    $query .= $where.' privacy = 2';
	}
}

if(!empty($TEMP['#category']) && $TEMP['#category'] != 'all'){
	$approve = $TEMP['#settings']['approve_videos'] == 'on' ? '?approve='.$TEMP['#approve'].'&' : '?';
	foreach($TEMP['#categories'] as $key => $value) {
        if($key == $TEMP['#category']){
        	$type_url    = $approve.'privacy='.$TEMP['#privacy'].'&category='.$key;
        	$where = !empty($query) ? ' AND' : ' WHERE';
        	$query .= $where.' category = "'.$key.'"';
        }    
    }
}
$where = !empty($query) ? ' AND' : ' WHERE';
$videos = $dba->query('SELECT * FROM videos v '.$query.$where.' deleted = 0 AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 1 OR (SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) ORDER BY id DESC LIMIT ? OFFSET ?', $TEMP['#settings']['data_load_limit'], 1)->fetchAll();
$TEMP['#total_pages'] = $dba->totalPages;

if (!empty($videos)) {
	foreach ($videos as $value) {
		$video = Specific::Video($value);
		$TEMP['#video_approve'] = $video['approved'];

		$TEMP['!privacy'] = $TEMP['#word']['public'];
		if($video['privacy'] == 1){
			$TEMP['!privacy'] = $TEMP['#word']['private'];
		} else if($video['privacy'] == 2){
			$TEMP['!privacy'] = $TEMP['#word']['unlisted'];
		}
	    $TEMP['!id'] = $video['id'];
        $TEMP['!title'] = $video['title'];
        $TEMP['!url'] = $video['url'];
        $TEMP['!category'] = $video['category_name'];
        $TEMP['!data'] = Specific::Data($video['by_id']);

		$TEMP['videos_list'] .= Specific::Maket('admin/includes/videos-list');
	}
	Specific::DestroyMaket();
} else {
	$TEMP['videos_list'] = Specific::Maket('not-found/videos');
}


$TEMP['#page']         = 'manage-videos';
$TEMP['#title']        = $TEMP['#word']['manage_videos'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description']  = $TEMP['#settings']['description'];
$TEMP['#keyword']      = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] 	  = Specific::Url('admin/manage-videos'.$type_url);

$TEMP['#content']      = Specific::Maket("admin/manage-videos");
?>