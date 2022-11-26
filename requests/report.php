<?php 
if ($TEMP['#loggedin'] === false && $_POST['option'] != 8) {
    $deliver = array(
        'status' => 400,
        'error' => $TEMP['#word']['error']
    );
    echo json_encode($deliver);
    exit();
}

$deliver['status'] = 400;
$t_sql = 'videos';
$type = 'video';
$query = ' AND deleted = 0';
$des_user = false;
if($one == 'user'){
	$t_sql = 'users';
	$type = 'user';
	$query = '';
}

if (!empty($_POST['id']) && is_numeric($_POST['id'])) {
	$report_id    = Specific::Filter($_POST['id']);
	$option    = Specific::Filter($_POST['option']);
	$this_id     = $option == 8 ? $report_id : $TEMP['#user']['id'];
	if ($dba->query('SELECT COUNT(*) FROM '.$t_sql.' WHERE id = '.$report_id.$query)->fetchArray() > 0) {
		$reports = $dba->query('SELECT COUNT(*) FROM reports WHERE to_id = '.$report_id.' AND by_id = '.$this_id)->fetchArray();
		if(!empty($_POST['option'])){
			$insert = $dba->query('INSERT INTO reports (by_id,to_id,type,option,time) VALUES ('.$this_id.','.$report_id.',"'.$type.'",'.$option.','.time().')')->insertId();
			if($option == 8){
				$des_user = $dba->query('UPDATE users SET status = 2 WHERE id = '.$this_id)->returnStatus();
			}
			if ($insert || $des_user) {
				$deliver['status'] = 200;	
			}
		}
	}
}
?>
