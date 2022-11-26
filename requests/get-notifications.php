<?php 
if ($TEMP['#loggedin'] === false) {
	$deliver = array(
		'status' => 400,
		'error' => $TEMP['#word']['error']
	);
    echo json_encode($deliver);
    exit();
} else {
	$html = "";
	$type = Specific::Filter($_POST['type']);
	if ($type == 'new') {
		$live_notify = $dba->query('SELECT COUNT(*) FROM notifications WHERE seen = 0 AND to_id = '.$TEMP['#user']['id'])->fetchArray();
		if(!empty($live_notify)){
			$deliver['status'] = 200;
			$deliver['new'] = $live_notify > 9 ? '9+' : $live_notify;
		}
	} else if ($type == 'click'){
		$deliver['status'] = 304;
		$notifications = $dba->query('SELECT * FROM notifications WHERE to_id = '.$TEMP['#user']['id'].' AND from_id NOT IN ('.$TEMP['#blocked_users'].') ORDER BY id DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();
		if(count($notifications) > 0){
			foreach ($notifications as $value) {
				$TEMP['!notify'] = $value;
				$TEMP['!admin_types'] = array('verification_accepted', 'verification_rejected');
				$comments_types = array('commentary', 'reply');
				$words_notify = array('subscribed' => 'subscribed_', 'commentary' => 'commented_on_your_video', 'dislike' => 'disliked_comment', 'like' => 'liked_comment', 'reply' => 'replied_to_your_comment');

				$user_data = Specific::Data($value['from_id']);
				$type = $value['type'];
				if(in_array($type, array_keys($words_notify))){
					$title  = $TEMP['#word'][$words_notify[$type]];
				} else {
					$title  = $TEMP['#word'][$type];
				}
				$notify_key = $value['notify_key'];
				$url = Specific::Url($notify_key);
				$TEMP['!video'] = Specific::Video($notify_key);
				if(!empty($TEMP['!video']) && !in_array($value['type'], $comments_types)) {
					$url = Specific::WatchSlug($notify_key);
				}else if(!in_array($value['type'], $TEMP['!admin_types'])){
					if(in_array($value['type'], $comments_types)){
						$ex = explode('&', $notify_key);
						$url = Specific::WatchSlug($notify_key);
						$TEMP['!video'] = Specific::Video($ex[0]);
					}else{
						$url = Specific::Url('user/'.$notify_key);
					}
				}
				$TEMP['!id'] = $value['id'];
				$TEMP['!data'] = $user_data;
				$TEMP['!title'] = $title;
				$TEMP['!text'] = $TEMP['!video']['title'];
				$TEMP['!point_active'] = $value['seen'] == 0 ? ' background-red' : '';
				$TEMP['!url'] = $url;
				$TEMP['!thumbnail'] = $TEMP['!video']['thumbnail'];
				$TEMP['!admin_thumb'] = Specific::GetIcon($TEMP['#settings']['icon_favicon'], 'favicon');
				$TEMP['!time'] = Specific::DateString($value['time']);
				
				$html .= Specific::Maket('header/notifications');
			}
			Specific::DestroyMaket();
			$dba->query('UPDATE notifications SET seen = '.time().' WHERE to_id = '.$TEMP['#user']['id']);
			$deliver['status'] = 200;
			$deliver['html']   = $html;
		}
	}
}
?>