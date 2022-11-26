<?php
if (!isset($_POST['ids'])) {
 	$deliver['status'] = 400;
} else {
	$deliver['status'] = 400;
	$ids = Specific::Filter($_POST['ids']);
	$html = '';
	$by_id = 0;
	if (!empty($_POST['by_id'])) {
		$by_id = Specific::Filter($_POST['by_id']);
	}
	if($one == 'home'){
		$videos = $dba->query('SELECT * FROM videos v WHERE converted = 1 AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 1 OR (SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND id NOT IN ('.$ids.') AND privacy = 0 AND deleted = 0 AND by_id NOT IN ('.$TEMP['#blocked_users'].') ORDER BY id DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();
		if (!empty($videos)) {
		    foreach ($videos as $value) {
		        $video = Specific::Video($value);

		        $TEMP['!live'] = $video['live'];
			    $TEMP['!get_progress'] = Specific::GetProgress($video['id']);
		        $TEMP['!id'] = $video['id'];
			    $TEMP['!title'] = $video['title'];
			    $TEMP['!views'] = Specific::Number($video['views']);
			    $TEMP['!data'] = $video['data'];
			    $TEMP['!thumbnail'] = $video['thumbnail'];
			    $TEMP['!url'] = $video['url'];
			    $TEMP['!time'] = $video['time_string'];
			    $TEMP['!progress_value'] = $TEMP['!get_progress']['percent_loaded'];
			    $TEMP['!duration'] = $video['duration'];
			    $TEMP['!video_id'] = $video['video_id'];
			    $TEMP['!animation'] = $video['animation'];

		        $html .= Specific::Maket('home/includes/list');
		    }
		    Specific::DestroyMaket();
		}
	} else if (in_array($one, array_keys($TEMP['#categories']))) {
	    $videos = $dba->query('SELECT * FROM videos v WHERE converted = 1 AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 1 OR (SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND category = "'.$one.'" AND privacy = 0 AND deleted = 0 AND by_id NOT IN ('.$TEMP['#blocked_users'].') AND id NOT IN ('.$ids.') ORDER BY id DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();
		if (!empty($videos)) {
		    foreach ($videos as $value) {
		        $video = Specific::Video($value);

		        $TEMP['!live'] = $video['live'];  
		        $TEMP['!get_progress'] = Specific::GetProgress($video['id']);
		        $TEMP['!id'] = $video['id'];
			    $TEMP['!title'] = $video['title'];
			    $TEMP['!views'] = Specific::Number($video['views']);
			    $TEMP['!data'] = $video['data'];
			    $TEMP['!thumbnail'] = $video['thumbnail'];
			    $TEMP['!url'] = $video['url'];
			    $TEMP['!time'] = $video['time_string'];
			    $TEMP['!progress_value'] = $TEMP['!get_progress']['percent_loaded'];
			    $TEMP['!duration'] = $video['duration'];
			    $TEMP['!video_id'] = $video['video_id'];
			    $TEMP['!animation'] = $video['animation'];
		        $html .= Specific::Maket('categories/includes/list');
		    }
		    Specific::DestroyMaket();
		}
	} else if ($one == 'trending') {
		$end = strtotime('-1 minute', strtotime(date("h:i:s A")));
    	$start = strtotime('-24 hour', strtotime(date("h:i:s A")));
	    $videos = $dba->query('SELECT video_id, COUNT(*) AS count FROM views v WHERE `time` >= '.$start.' AND `time` <= '.$end.'  AND (SELECT id FROM videos b WHERE converted = 1 AND id = v.video_id AND ((SELECT live FROM broadcasts WHERE video_id = b.video_id) = 1 OR (SELECT live FROM broadcasts WHERE video_id = b.video_id) = 3 OR broadcast = 0) AND privacy = 0 AND deleted = 0 AND by_id NOT IN ('.$TEMP['#blocked_users'].') AND id NOT IN ('.$ids.')) = video_id GROUP BY video_id ORDER BY count DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();

		if (!empty($videos)) {
		    foreach ($videos as $value) {
		        $video = Specific::Video($value['video_id']);

		        $TEMP['!live'] = $video['live'];
		        $TEMP['!get_progress'] = Specific::GetProgress($video['id']);
		        $TEMP['!id'] = $video['id'];
		        $TEMP['!title'] = $video['title'];
		        $TEMP['!views'] = Specific::Number($video['views']);
		        $TEMP['!data'] = $video['data'];
		        $TEMP['!thumbnail'] = $video['thumbnail'];
		        $TEMP['!url'] = $video['url'];
		        $TEMP['!time'] = $video['time_string'];
		        $TEMP['!progress_value'] = $TEMP['!get_progress']['percent_loaded'];
		        $TEMP['!duration'] = $video['duration'];
		        $TEMP['!video_id'] = $video['video_id'];
		        $TEMP['!animation'] = $video['animation'];

		        $html .= Specific::Maket('trending/includes/list');
		    }
		    Specific::DestroyMaket();
		}
	} else if ($one == 'subscriptions') {
		$subscriptions = $dba->query('SELECT by_id FROM subscriptions WHERE subscriber_id = '.$TEMP['#user']['id'])->fetchAll(FALSE);
		if (!empty($subscriptions)) {
			$videos = $dba->query('SELECT * FROM videos v WHERE converted = 1 AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 1 OR (SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND id NOT IN ('.$ids.') AND deleted = 0 AND by_id IN ('.implode(',', $subscriptions).') AND privacy = 0 ORDER BY `id` DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();
		}
		if (!empty($videos)) {
		    foreach ($videos as $value) {
		        $video = Specific::Video($value);

		        $TEMP['!live'] = $video['live'];
		        $TEMP['!get_progress'] = Specific::GetProgress($video['id']);
		        $TEMP['!id'] = $video['id'];
		        $TEMP['!title'] = $video['title'];
		        $TEMP['!views'] = Specific::Number($video['views']);
		        $TEMP['!data'] = $video['data'];
		        $TEMP['!thumbnail'] = $video['thumbnail'];
		        $TEMP['!url'] = $video['url'];
		        $TEMP['!time'] = $video['time_string'];
		        $TEMP['!progress_value'] = $TEMP['!get_progress']['percent_loaded'];
		        $TEMP['!duration'] = $video['duration'];
		        $TEMP['!video_id'] = $video['video_id'];
		        $TEMP['!animation'] = $video['animation'];

		        $html .= Specific::Maket('subscriptions/includes/list');
		    }
		    Specific::DestroyMaket();
		}
	} else if($one == 'blocked-users') {
		$users = $dba->query('SELECT * FROM blocked WHERE by_id = '.$by_id.' AND to_id NOT IN ('.$ids.') ORDER BY to_id ASC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();
	    if(!empty($users)){
	        foreach ($users as $value) {
	            $id = $value['to_id'];
	            $TEMP['!id'] = $id;
	            $TEMP['!data'] = Specific::Data($id);
            	$TEMP['!text'] = $TEMP['#word']['unblock'];
            	$TEMP['!time'] = Specific::DateString($value['time']);
	            $html .= Specific::Maket("settings/blocked-users/includes/users-list");
	        }
	        Specific::DestroyMaket();
	    }
	} else if ($one == 'video-producer') {
		$videos = array();
		$types = array('views', 'likes','dislikes','comments');
		if (!empty($_POST['video-producer-type']) && in_array($_POST['video-producer-type'], $types) && !empty($_POST['video-producer-ids'])) {
			if ($_POST['video-producer-type'] == 'views') {
        		$videos = $dba->query('SELECT * FROM videos v WHERE by_id = '.$TEMP['#user']['id'].' AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND id NOT IN ('.$ids.') AND deleted = 0 ORDER BY views DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();
    		} else if ($_POST['video-producer-type'] == 'likes') {
				$videos = $dba->query('SELECT * FROM videos v WHERE by_id = '.$TEMP['#user']['id'].' AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND deleted = 0 AND id NOT IN ('.$ids.') ORDER BY likes DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();	
			}else if ($_POST['video-producer-type'] == 'dislikes') {
				$videos = $dba->query('SELECT * FROM videos v WHERE by_id = '.$TEMP['#user']['id'].' AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND deleted = 0 AND id NOT IN ('.$ids.') ORDER BY dislikes DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();
			}else if ($_POST['video-producer-type'] == 'comments') {
				$comments = $dba->query('SELECT video_id, COUNT(*) AS comments FROM comments c WHERE (SELECT id FROM videos WHERE by_id = '.$TEMP['#user']['id'].' AND id = c.video_id AND deleted = 0) = video_id AND video_id NOT IN ('.$ids.') GROUP BY video_id ORDER BY comments DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();
				if(!empty($comments)){
					foreach ($comments as $value) {
			    		$videos[] = $dba->query('SELECT * FROM videos WHERE id = '.$value['video_id'])->fetchArray();
			    	}
				} else {
					$videos = $dba->query('SELECT * FROM videos v WHERE by_id = '.$TEMP['#user']['id'].' AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND deleted = 0 AND id NOT IN ('.$ids.') ORDER BY id DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();
				}	
			}
		} else {
			$videos = $dba->query('SELECT * FROM videos v WHERE by_id = '.$TEMP['#user']['id'].' AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND deleted = 0 AND id NOT IN ('.$ids.') ORDER BY id DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();
		}
		if (!empty($videos)) {
		    foreach ($videos as $value) {
		        $video = Specific::Video($value);
		        $comments_count = $dba->query('SELECT COUNT(*) FROM comments WHERE video_id = '.$video['id'])->fetchArray();

		        $TEMP['!live'] = $video['live'];
		        $TEMP['!id'] = $video['id'];
				$TEMP['!data'] = $video['data'];
				$TEMP['!thumbnail'] = $video['thumbnail'];
				$TEMP['!url'] = $video['url'];
				$TEMP['!title'] = $video['title'];
				$TEMP['!description'] = Specific::GetUncomposeText($video['description'], 130);
				$TEMP['!views'] = Specific::Number($video['views']);
				$TEMP['!time'] = $video['time_string'];
				$TEMP['!duration'] = $video['duration'];
				$TEMP['!video_id'] = $video['video_id'];
				$TEMP['!likes'] = Specific::Number($video['likes']);
				$TEMP['!dislikes'] = Specific::Number($video['dislikes']);
				$TEMP['!comments'] = Specific::Number($comments_count);
		        $html .= Specific::Maket('channel/video-producer/includes/list');
		    }
		    Specific::DestroyMaket();
		}
	} else if ($one == 'history') {
		$videos = $dba->query('SELECT * FROM history WHERE by_id = '.$TEMP['#user']['id'].' AND id NOT IN ('.$ids.') AND video_id NOT IN ('.Specific::BlockedVideos().') ORDER BY id DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();
		
		if (!empty($videos)) {
		    foreach ($videos as $value) {
		    	$video = Specific::Video($value['video_id']);

		    	$TEMP['!live'] = $video['live'];
		    	$TEMP['!get_progress'] = Specific::GetProgress($video['id']);
		        $TEMP['!id'] = $value['id'];
			    $TEMP['!title'] = $video['title'];
			    $TEMP['!description'] = Specific::GetUncomposeText($video['description'], 130);
			    $TEMP['!views'] = Specific::Number($video['views']);
			    $TEMP['!data'] = $video['data'];
			    $TEMP['!thumbnail'] = $video['thumbnail'];
			    $TEMP['!url'] = $video['url'];
			    $TEMP['!time'] = $video['time_string'];
			    $TEMP['!progress_value'] = $TEMP['!get_progress']['percent_loaded'];
			    $TEMP['!duration'] = $video['duration'];
			    $TEMP['!video_id'] = $video['video_id'];
			    $TEMP['!animation'] = $video['animation'];

		        $html .= Specific::Maket('history/includes/list');
		    }
		    Specific::DestroyMaket();
		}
	} else if ($one == 'user-videos') {
		$videos = $dba->query('SELECT * FROM videos v WHERE by_id = '.$by_id.' AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 1 OR (SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND id NOT IN ('.$ids.') AND deleted = 0 ORDER BY id DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();

		if (!empty($videos)){
		    foreach ($videos as $value) {
		        $video = Specific::Video($value);

		        $TEMP['!live'] = $video['live'];
		        $TEMP['!get_progress'] = Specific::GetProgress($video['id']);
		        $TEMP['!id'] = $video['id'];
		        $TEMP['!title'] = $video['title'];
		        $TEMP['!views'] = Specific::Number($video['views']);
		        $TEMP['!thumbnail'] = $video['thumbnail'];
		        $TEMP['!url'] = $video['url'];
		        $TEMP['!time'] = $video['time_string'];
		        $TEMP['!progress_value'] = $TEMP['!get_progress']['percent_loaded'];
		        $TEMP['!duration'] = $video['duration'];
		        $TEMP['!video_id'] = $video['video_id'];
		        $TEMP['!animation'] = $video['animation'];

		        $html .= Specific::Maket('user/videos/includes/list');
		    }
		    Specific::DestroyMaket();
		}
	} else if ($one == 'user-playlist') {
		$query = '';
		$user_data   = Specific::Data($by_id);
		$TEMP['#is_owner'] = Specific::IsOwner($user_data['id'], 2);
		if($TEMP['#is_owner'] == false){
			$query = ' AND privacy = 1 AND by_id NOT IN ('.$TEMP['#blocked_users'].')';
		}

	    $lists = $dba->query('SELECT * FROM lists l WHERE by_id = '.$user_data['id'].$query.' AND id NOT IN ('.$ids.') AND (SELECT COUNT(*) FROM playlists WHERE l.list_id = list_id) > 0 LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();
		if(!empty($lists)) {
		    foreach ($lists as $value) {
		        $lists = $dba->query('SELECT *, COUNT(*) as count FROM playlists WHERE list_id = "'.$value['list_id'].'" ORDER BY video_id ASC LIMIT 1')->fetchArray();
			    $video = Specific::Video($lists['video_id']);

			    $TEMP['!id'] = $value['id'];
			    $TEMP['!title'] = $value['title'];  

			    $TEMP['!count'] = $lists['count'];
			    $TEMP['!thumbnail'] = $video['thumbnail'];
			    $TEMP['!url'] = Specific::WatchSlug($video['video_id'], 'playlist', $value['list_id']);
			    $TEMP['!video_id'] = $video['video_id'];

			    $html .= Specific::Maket('user/playlists/includes/playlists'); 
		    }
		    Specific::DestroyMaket();
		}
	} else if ($one == 'user-liked-videos') {
		$videos = $dba->query('SELECT to_id FROM reactions WHERE by_id = '.$by_id.' AND type = 1 AND place = "video" AND to_id NOT IN ('.$ids.') ORDER BY id DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();
		if (!empty($videos)) {
		    foreach ($videos as $value) {
		        $video = Specific::Video($value['to_id']);

		        $TEMP['!live'] = $video['live'];
		        $TEMP['!get_progress'] = Specific::GetProgress($video['id']);
		        $TEMP['!id'] = $video['id'];
		        $TEMP['!title'] = $video['title'];
		        $TEMP['!views'] = Specific::Number($video['views']);
		        $TEMP['!data'] = $video['data'];
		        $TEMP['!thumbnail'] = $video['thumbnail'];
		        $TEMP['!url'] = $video['url'];
		        $TEMP['!time'] = $video['time_string'];
		        $TEMP['!progress_value'] = $TEMP['!get_progress']['percent_loaded'];
		        $TEMP['!duration'] = $video['duration'];
		        $TEMP['!video_id'] = $video['video_id'];
		        $TEMP['!animation'] = $video['animation'];

		        $html .= Specific::Maket('user/liked-videos/includes/list');
		    }
		    Specific::DestroyMaket();
		}
	} else if ($one == 'video-comment') {
		$comments = $dba->query('SELECT * FROM comments c WHERE (SELECT id FROM videos WHERE by_id = '.$TEMP['#user']['id'].' AND id = c.video_id AND deleted = 0) = video_id AND by_id NOT IN ('.$TEMP['#blocked_users'].') AND c.id NOT IN ('.$ids.') ORDER BY time DESC LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();
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

			$TEMP['!id'] =  $value['id'];
			$TEMP['!text'] =  Specific::GetComposeText($value['text']);
			$TEMP['!time'] =  Specific::DateString($value['time']);
			$TEMP['!data'] =  $user_data;
			$TEMP['!comment_url'] =  $video['url'].'&cl='.$value['id'];

			$TEMP['!norm_likes'] = $likes;
            $TEMP['!norm_dislikes'] = $dislikes;
            $TEMP['!likes'] = $comm_likes;
            $TEMP['!dislikes'] = $comm_dislikes;
		    $TEMP['!liked' ] = $like_active_class;
		    $TEMP['!disliked' ] = $dislike_active_class;
            $TEMP['!isliked'] = !empty($like_active_class) ? 'true' : 'false';
            $TEMP['!isdisliked'] = !empty($dislike_active_class) ? 'true' : 'false';

			$TEMP['!comment'] =  Specific::Maket('channel/comments/includes/comments');

			$TEMP['!id'] = $video['id'];
			$TEMP['!title'] = $video['title'];
			$TEMP['!url'] = $video['url'];
			$TEMP['!thumbnail'] = $video['thumbnail'];
			$TEMP['!description'] = $video['compose_description'];
			$TEMP['!duration'] = $video['duration'];
			$TEMP['!video_id'] = $video['video_id'];
			$TEMP['!views'] = $video['views'];
			$TEMP['!likes'] = $video['likes'];
			$TEMP['!dislikes'] = $video['dislikes'];
			$TEMP['!comment_id'] = $value['id'];

			$html .= Specific::Maket("channel/comments/includes/list");

		}
		Specific::DestroyMaket();
	} else if ($one == 'subscribers') {
	    $id = Specific::Filter($_POST['id']);
	    if(!empty($id) && is_numeric($id)){
	        $subscribers = $dba->query('SELECT * FROM subscriptions WHERE subscriber_id = '.$TEMP['#user']['id'].' AND id < '.$id.' AND by_id NOT IN ('.$ids.') AND by_id NOT IN ('.$TEMP['#blocked_users'].') ORDER BY id DESC LIMIT 6')->fetchAll();
	        if (!empty($subscribers)) {
	            foreach ($subscribers as $value) {
	                $sub = Specific::Data($value['by_id']);
	                if (!empty($sub)) {
	                	$TEMP['!point_active'] = $dba->query('SELECT COUNT(*) FROM broadcasts WHERE live = 1 AND by_id = '.$sub['id'])->fetchArray() > 0 ? ' background-red' : '';
	                	$TEMP['!data'] = $sub;
                		$TEMP['!active_class'] = $_POST['profile_id'] == $sub['id'] && $_POST['hasActive'] == 0 ? ' active' : '';

                		$html .= Specific::Maket('header/subscribers');
	                }
	            }
	            Specific::DestroyMaket();
	        }
	    }
	}else if($one == 'search-videos'){
		$query = '';
		$deliver['status'] = 400;
		$keyword 	   = Specific::Filter($_POST['keyword']);
		$last_id       = (!empty($_POST['last_id']) && is_numeric($_POST['last_id'])) ? $_POST['last_id'] : 0;
		$users_ids = Specific::Filter($_POST['users_ids']);

		if(!empty($last_id) && !empty($keyword)){
			$keyword = Specific::Filter($_POST['keyword']);
			$category = Specific::Filter($_POST['category']);
			$date = Specific::Filter($_POST['date']);
			$features = Specific::Filter($_POST['features']);
			$order = Specific::Filter($_POST['order']);
			if (isset($category) && !empty($category)) {
			    $category_sql = " AND category = '".$category."' ";
			}
			if (isset($date) && !empty($date)) {
			    if ($date == 'last_hour') {
			        $time = time()-(60*60);
			        $date_sql = " AND time >= ".$time." ";
			    }
			    elseif ($date == 'today') {
			        $time = time()-(60*60*24);
			        $date_sql = " AND time >= ".$time." ";
			    }
			    elseif ($date == 'this_week') {
			        $time = time()-(60*60*24*7);
			        $date_sql = " AND time >= ".$time." ";
			    }
			    elseif ($date == 'this_month') {
			        $time = time()-(60*60*24*30);
			        $date_sql = " AND time >= ".$time." ";
			    }
			    elseif ($date == 'this_year') {
			        $time = time()-(60*60*24*365);
			        $date_sql = " AND time >= ".$time." ";
			    }
			}
			if (isset($features) && !empty($features)) {
			    if ($features == 'hd_feature') {
			        $features_sql = " AND 720p = 1";
			    }else if($features == '4k_feature'){
			        $features_sql = " AND 2160p = 1";
			    }
			}
			if (isset($order) && !empty($order)) {
			    if ($order == 'date_upload') {
			        $order_sql = " ORDER BY time DESC";
			    }else if($order == 'count_views'){
			        $order_sql = " ORDER BY views DESC";
			    }
			}else{
			    $order_sql = " AND id > ".$last_id." ORDER BY id ASC";
			}
			if(!empty($ids)){
				$query = 'AND id NOT IN ('.$ids.')';
			}
			$videos = $dba->query('SELECT * FROM videos v WHERE converted = 1 AND (title LIKE "%'.$keyword.'%" OR tags LIKE "%'.$keyword.'%" OR description LIKE "%'.$keyword.'%") '.$query.' AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 1 OR (SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND privacy = 0 AND deleted = 0 '.$category_sql.$date_sql.$features_sql.$order_sql.' LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();
			if (!empty($videos)) {
				if(!empty($users_ids)){
					$query = 'AND id NOT IN ('.$users_ids.')';
				}
				$users = $dba->query("SELECT * FROM users WHERE (`username` LIKE '%$keyword%') ".$query." ORDER BY id ASC")->fetchArray();
				if (!empty($users)) {
				    $user = Specific::Data($users['id']);
				    $subs = $dba->query('SELECT COUNT(*) FROM subscriptions WHERE by_id = '.$user['id'])->fetchArray();
		    		$count = $dba->query('SELECT COUNT(*) FROM videos WHERE converted = 1 AND by_id = '. $user['id'].' AND deleted = 0')->fetchArray();

				    $TEMP['!subscribe_button'] = Specific::SubscribeButton($user['id']);
				    $TEMP['!id'] = $user['id'];
			        $TEMP['!data'] = $user;
		       		$TEMP['!subs'] = $subs == 0 ? $TEMP['#word']['empty_subscribers'] : $subs . ' ' . $TEMP['#word']['subscribers'];
		        	$TEMP['!count'] = $count;

				    $html .= Specific::Maket('search/includes/user-list');   
				}
			    foreach ($videos as $value) {
			    	$video = Specific::Video($value);
			       	$progress = Specific::GetProgress($video['id']);

			       	$TEMP['!live'] = $video['live'];
	        		$TEMP['!get_progress'] = $progress;
				    $TEMP['!id'] = $video['id'];
				    $TEMP['!data'] = $video['data'];
			        $TEMP['!thumbnail'] = $video['thumbnail'];
			        $TEMP['!url'] = $video['url'];
			        $TEMP['!title'] = $video['title'];
				    $TEMP['!description'] = Specific::GetUncomposeText($video['description'], 130);
				    $TEMP['!views'] = Specific::Number($video['views']);
			        $TEMP['!time'] = $video['time_string'];
			        $TEMP['!progress_value'] = $TEMP['!get_progress']['percent_loaded'];
				    $TEMP['!duration'] = $video['duration'];
				    $TEMP['!video_id'] = $video['video_id'];
				    $TEMP['!animation'] = $video['animation'];

				    $html .= Specific::Maket('search/includes/list');
			    }
			    Specific::DestroyMaket();
			} else {
				if(!empty($users_ids)){
					$query = 'AND id NOT IN ('.$users_ids.')';
				}
				$users = $dba->query("SELECT * FROM users WHERE (`username` LIKE '%$keyword%') $query ORDER BY id DESC")->fetchAll();
			    if(!empty($users)){
			        foreach ($users as $value) {
			            $user = Specific::Data($value['id']);
			            $subs = $dba->query('SELECT COUNT(*) FROM subscriptions WHERE by_id = '.$user['id'])->fetchArray();
			            $count = $dba->query('SELECT COUNT(*) FROM videos WHERE converted = 1 AND by_id = '.$user['id'].' AND deleted = 0')->fetchArray();

			            $TEMP['!subscribe_button'] = Specific::SubscribeButton($user['id']);
			            $TEMP['!id'] = $user['id'];
			            $TEMP['!data'] = $user;
			            $TEMP['!subs'] = $subs == 0 ? $TEMP['#word']['empty_subscribers'] : $subs . ' ' . $TEMP['#word']['subscribers'];
			            $TEMP['!count'] = $count;

			            $html .= Specific::Maket('search/includes/user-list'); 
			        }
			        Specific::DestroyMaket();
			    } else {
					$deliver['status'] = 400;
			    }
			}
		}
	} else if($one == 'related-videos'){
		$deliver['status'] = 400;
		if (!empty($_POST['video_id']) && is_numeric($_POST['video_id'])) {
			$video_id   = Specific::Filter($_POST['video_id']);
			$video_data = $dba->query('SELECT * FROM videos WHERE converted = 1 AND id = '.$video_id.' AND deleted = 0 AND by_id NOT IN ('.$TEMP['#blocked_users'].')')->fetchArray();
			if (!empty($video_data)) {
				$limit_random = $TEMP['#settings']['data_load_limit'] / 2;

				$random_videos = $dba->query('SELECT id FROM videos v WHERE converted = 1 AND MATCH(title) AGAINST ("'.$video_data['title'].'") AND id <> '.$video_id.' AND by_id NOT IN ('.$TEMP['#blocked_users'].') AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 1 OR (SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND privacy = 0 AND deleted = 0 AND id NOT IN ('.$ids.') ORDER BY rand() LIMIT '.$limit_random)->fetchAll(FALSE);
				$related_videos = $dba->query('SELECT id FROM videos v WHERE converted = 1 AND MATCH(title) AGAINST ("'.$video_data['title'].'") AND id <> '.$video_id.' AND by_id NOT IN ('.$TEMP['#blocked_users'].') AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 1 OR (SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND privacy = 0 AND category = "'.$video_data['category'].'" AND deleted = 0 AND id NOT IN ('.$ids.') LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll(FALSE);

				if(!empty($random_videos)){
				    foreach ($related_videos as $key => $value) { 
				        foreach ($random_videos as $k => $val) {
					        if($key == rand(0, count($related_videos)) && !in_array($val, $related_videos)){
					            $related_videos[$key] = $val;
					            unset($random_videos[$k]);
				            }
				        }
				    }
				}
				if(count($related_videos) == 0){
					$related_videos = $dba->query('SELECT * FROM videos v WHERE converted = 1 AND title NOT LIKE "%'.$TEMP['#title'].'%" AND id <> '.$video_id.' AND by_id NOT IN ('.$TEMP['#blocked_users'].') AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 1 OR (SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND privacy = 0 AND deleted = 0 AND id NOT IN ('.$ids.') ORDER BY rand() LIMIT '.$TEMP['#settings']['data_load_limit'])->fetchAll();
				}

				if (count($related_videos) > 0) {
					foreach ($related_videos as $value) {
					    $video  = Specific::Video($value);
						$user_data    = Specific::Data($value['by_id']);
			    		$TEMP['#is_verified']  = ($user_data['verified'] == 1) ? true : false;

			    		$TEMP['!live'] = $video['live'];
			    		$TEMP['!get_progress'] = Specific::GetProgress($value['id']);
					    $TEMP['!id'] = $video['id'];
					    $TEMP['!title'] = $video['title'];
						$TEMP['!url'] = $video['url'];
					    $TEMP['!thumbnail'] = $video['thumbnail'];
				        $TEMP['!data'] = $video['data'];
				        $TEMP['!views'] = $video['views'];
				        $TEMP['!time'] = $video['time_format'];
						$TEMP['!duration'] = $video['duration'];
					    $TEMP['!progress_value'] = $TEMP['!get_progress']['percent_loaded'];
					    $TEMP['!video_id'] = $video['video_id'];
					    $TEMP['!animation'] = $video['animation'];

						$html .= Specific::Maket('watch/includes/video-sidebar');
					}
					Specific::DestroyMaket();
				} else {
					$deliver['status'] = 400;
				}
			}
		}
	} else if($one == 'fetch-chat'){
		$deliver['status'] = 400;
		$html = '';
		$query = ' AND id NOT IN ('.$ids.')';
		$video_id = Specific::Filter($_POST['video_id']);
		$broadcast_time = Specific::Filter($_POST['broadcast-time']);
		$last_id = Specific::Filter($_POST['last_id']);
		$type = Specific::Filter($_POST['type']);
		if(!empty($last_id)){
			$query .= ' AND id > '.$last_id;
		}

		if($type == 'online'){
	        $html = Specific::BroadcastChat($video_id, 'html', 0, $query);
	    } else if($type == 'offline'){
	        $json = Specific::BroadcastChat($video_id, 'json', $broadcast_time, $query);
	    }

	    if(!empty($html) || !empty($json)){
	    	$deliver = array(
	    		'status' => 200,
	    		'html' => $html,
	    		'json' => json_decode(stripslashes($json))
	    	);
	    }
	}
	if(!empty($html)){
		$deliver = array(
			'status' => 200,
	    	'html' => $html
		);
    }
}
?>