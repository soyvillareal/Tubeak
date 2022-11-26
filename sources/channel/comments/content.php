<?php 
if ($TEMP['#loggedin'] === false) {
	header("Location: " . Specific::Url('login'));
	exit();
}

$today_start = strtotime(date('M')." ".date('d').", ".date('Y')." 12:00am");
$today_end = strtotime(date('M')." ".date('d').", ".date('Y')." 11:59pm");

$this_month_start = strtotime("1 ".date('M')." ".date('Y')." 12:00am");
$this_month_end = strtotime(cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'))." ".date('M')." ".date('Y')." 11:59pm");

$this_year_start = strtotime("1 January ".date('Y')." 12:00am");
$this_year_end = strtotime("31 December ".date('Y')." 11:59pm");

$comments = $dba->query('SELECT * FROM comments c WHERE (SELECT id FROM videos WHERE by_id = '.$TEMP['#user']['id'].' AND id = c.video_id AND deleted = 0) = video_id AND by_id NOT IN ('.$TEMP['#blocked_users'].') ORDER BY id DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();

$today_comments_count = $dba->query('SELECT COUNT(*) FROM comments c WHERE (SELECT id FROM videos WHERE by_id = '.$TEMP['#user']['id'].' AND id = c.video_id AND deleted = 0) = video_id AND by_id NOT IN ('.$TEMP['#blocked_users'].') AND c.time >= '.$today_start.' AND c.time <= '.$today_end)->fetchArray();

$month_comments_count = $dba->query('SELECT COUNT(*) FROM comments c WHERE (SELECT id FROM videos WHERE by_id = '.$TEMP['#user']['id'].' AND id = c.video_id AND deleted = 0) = video_id AND by_id NOT IN ('.$TEMP['#blocked_users'].') AND c.time >= '.$this_month_start.' AND c.time <= '.$this_month_end)->fetchArray();

$year_comments_count = $dba->query('SELECT COUNT(*) FROM comments c WHERE (SELECT id FROM videos WHERE by_id = '.$TEMP['#user']['id'].' AND id = c.video_id AND deleted = 0) = video_id AND by_id NOT IN ('.$TEMP['#blocked_users'].') AND c.time >= '.$this_year_start.' AND c.time <= '.$this_year_end)->fetchArray();

if (!empty($comments)) {
	foreach ($comments as $value) {
	    $user_data = Specific::Data($value['by_id']);
		$video = Specific::Video($value['video_id']);

	    $like_active_class   = ($dba->query('SELECT COUNT(*) FROM reactions WHERE type = 1 AND to_id = '.$value['id'].' AND place = "commentary" AND by_id = '.$TEMP['#user']['id'])->fetchArray() > 0) ? ' active' : '';

	    $dislike_active_class = ($dba->query('SELECT COUNT(*) FROM reactions WHERE type = 2 AND to_id = '.$value['id'].' AND place = "commentary" AND by_id = '.$TEMP['#user']['id'])->fetchArray() > 0) ? ' active' : '';

	    $likes = $dba->query('SELECT COUNT(*) FROM reactions WHERE type = 1 AND to_id = '.$value['id'].' AND place = "commentary"')->fetchArray();

        $dislikes = $dba->query('SELECT COUNT(*) FROM reactions WHERE type = 2 AND to_id = '.$value['id'].' AND place = "commentary"')->fetchArray();

        $comm_likes = '';
        if($likes >= 1000000){
            $comm_likes = Specific::Number($likes);
        } else if($likes > 0) {
            $comm_likes = number_format($likes);
        }

        $comm_dislikes = '';
        if($dislikes >= 1000000){
            $comm_dislikes = Specific::Number($dislikes);
        } else if($dislikes > 0){
            $comm_dislikes = number_format($dislikes);
        }

		$TEMP['!owner_username']   = $video['by_id'] == $value['by_id'] ? ' owner-username' : '';
		$TEMP['#comment'] = $value;

		$TEMP['!id'] = $value['id'];
		$TEMP['!text'] = Specific::GetComposeText($value['text']);
		$TEMP['!time'] = Specific::DateString($value['time']);
		$TEMP['!data'] = $user_data;
		$TEMP['!comment_url'] = $video['url'].'&cl='.$value['id'];
		$TEMP['!norm_likes'] = $likes;
        $TEMP['!norm_dislikes'] = $dislikes;
        $TEMP['!likes'] = $comm_likes;
        $TEMP['!dislikes'] = $comm_dislikes;
		$TEMP['!liked' ] = $like_active_class;
	    $TEMP['!disliked' ] = $dislike_active_class;
        $TEMP['!isliked'] = !empty($like_active_class) ? 'true' : 'false';
        $TEMP['!isdisliked'] = !empty($dislike_active_class) ? 'true' : 'false';
		$TEMP['!comment'] = Specific::Maket('channel/comments/includes/comments');

		$TEMP['!title'] = $video['title'];
		$TEMP['!url'] = $video['url'];
		$TEMP['!thumbnail'] = $video['thumbnail'];
		$TEMP['!description'] = Specific::GetUncomposeText($video['description'], 130);
		$TEMP['!duration'] = $video['duration'];
		$TEMP['!views'] = $video['views'];
		$TEMP['!likes'] = $video['likes'];
		$TEMP['!dislikes'] = $video['dislikes'];
		$TEMP['!comment_id'] = $value['id'];

		$TEMP['comments'] .= Specific::Maket("channel/comments/includes/list");
	}
	Specific::DestroyMaket();
} else {
	$TEMP['comments'] = Specific::Maket("not-found/comments");
}

$TEMP['total_comments_today'] = $today_comments_count;
$TEMP['total_comments_month'] = $month_comments_count;
$TEMP['total_comments_year'] =  $year_comments_count;

$TEMP['#comments'] = count($comments);
$TEMP['#page'] = 'comments';
$TEMP['#title'] = $TEMP['#word']['comments'] . ' - ' . $TEMP['#settings']['title'];
$TEMP['#description'] = $TEMP['#settings']['description'];
$TEMP['#keyword'] = $TEMP['#settings']['keyword'];
$TEMP['#load_url'] = Specific::Url('channel?page=comments');

$TEMP['second_page'] = Specific::Maket("channel/comments/content");
$TEMP['#content'] = Specific::Maket("channel/content");
?>