<?php
if ($TEMP['#loggedin'] === false) {
    $deliver = array(
        'status' => 400,
        'error' => $TEMP['#word']['error']
    );
    echo json_encode($deliver);
    exit();
} else if($one == 'new-list') {
    $deliver['status'] = 400;
    $title = Specific::Filter($_POST['title']);
    $description = Specific::Filter($_POST['desc']);
    $privacy = Specific::Filter($_POST['privacy']);
    $error = '';
    if(empty($title) || !in_array($privacy, array(0, 1))){
        $error = $TEMP['#word']['there_problems_with_some_fields'];
    } else if(strlen($title) > 35){
        $error = $TEMP['#word']['the_title_is_too_long'];
    } else if(strlen($description) > 500){
        $error = $TEMP['#word']['the_description_is_too_long'];
    }
    if (empty($error)) {
        if ($dba->query('INSERT INTO lists (list_id, by_id, title, description, privacy, `time`) VALUES ("'.Specific::RandomKey(12, 16).'",'.$TEMP['#user']['id'].',"'.$title.'","'.$description.'",'.$privacy.','.time().')')->insertId()) {
            $deliver['status'] = 200;
        }
    } else {
        $deliver = array(
            'status' => 400,
            'error' => $error
        );
    }
} else if($one == 'update-list'){
    $deliver['status'] = 400;
    $title = Specific::Filter($_POST['title']);
    $description = Specific::Filter($_POST['desc']);
    $privacy = Specific::Filter($_POST['privacy']);
    $list_id = Specific::Filter($_POST['id']);
    $error = '';
    if(empty($title) || empty($list_id) || !in_array($privacy, array(0, 1)) || !is_numeric($list_id) || $list_id == 0){
        $error = $TEMP['#word']['there_problems_with_some_fields'];
    } else if(strlen($title) > 35){
        $error = $TEMP['#word']['the_title_is_too_long'];
    } else if(strlen($description) > 500){
        $error = $TEMP['#word']['the_description_is_too_long'];
    }
    if (empty($error)) {
        $update = $dba->query('UPDATE lists SET title = "'.$title.'", description = "'.$description.'", privacy = '.$privacy.' WHERE id = "'.$list_id.'" AND by_id = '.$TEMP['#user']['id'])->returnStatus();
        if ($update) {
            $deliver['status'] = 200;
        }
    } else {
        $deliver = array(
            'status' => 400,
            'error' => $error
        );
    }
} else if($one == 'get-saved'){
    $deliver['status'] = 400;
    $video_id = Specific::Filter($_POST['id']);
    if(!empty($video_id) && is_numeric($video_id)){
        $TEMP['#watch_later'] = $dba->query('SELECT COUNT(*) FROM watch_later WHERE by_id = '.$TEMP['#user']['id'].' AND video_id = '.$video_id)->fetchArray();

        $playlists = $dba->query('SELECT * FROM lists WHERE by_id = '.$TEMP['#user']['id'])->fetchAll();
        foreach ($playlists as $value) {
            $TEMP['#privacy'] = $value['privacy'];
            $TEMP['#is_check'] = $dba->query('SELECT COUNT(*) FROM playlists WHERE by_id = '.$TEMP['#user']['id'].' AND list_id = "'.$value['list_id'].'" AND video_id = '.$video_id)->fetchArray();
            
            $TEMP['!list_id'] = $value['list_id'];
            $TEMP['!list_title'] = $value['title'];
            $TEMP['list'] .= Specific::Maket('watch/includes/list');
        }
        Specific::DestroyMaket();
        $deliver = array(
            'status' => 200,
            'html' => Specific::Maket('watch/includes/lists')
        );
    }
} else if($one == 'delete-list'){
    $deliver['status'] = 400;
    $list_id = Specific::Filter($_POST['id']);
    if (!empty($list_id) && is_numeric($list_id) && $list_id > 0) {
        $list = $dba->query('SELECT * FROM lists WHERE id = '.$list_id.' AND by_id = '.$TEMP['#user']['id'])->fetchArray();
        if (!empty($list)) {
            if($dba->query('DELETE FROM lists WHERE id = '.$list_id.' AND by_id = '.$TEMP['#user']['id'])->returnStatus() && $dba->query('DELETE FROM playlists WHERE list_id = "'.$list['list_id'].'" AND by_id = '.$TEMP['#user']['id'])->returnStatus()){
                $deliver = array(
                    'status' => 200,
                    'title' => $list['title']
                );
            }
        }
    }
} else if($one == 'delete-video-list'){
    $deliver['status'] = 400;
    $video_id = Specific::Filter($_POST['video_id']);
    $list_id = Specific::Filter($_POST['list_id']);
    if (!empty($video_id) && is_numeric($video_id) && $video_id > 0 && !empty($list_id)) {
        if($dba->query('DELETE FROM playlists WHERE list_id = "'.$list_id.'" AND by_id = '.$TEMP['#user']['id'].' AND video_id = '.$video_id)->returnStatus()){
            $deliver['status'] = 200;
        }
    }
} else if($one == 'delete-video-later'){
    $deliver['status'] = 400;
    $video_id = Specific::Filter($_POST['video_id']);
    if (!empty($video_id) && is_numeric($video_id) && $video_id > 0) {
        if($dba->query('DELETE FROM watch_later WHERE by_id = '.$TEMP['#user']['id'].' AND video_id = '.$video_id)->returnStatus()){
            $deliver['status'] = 200;
        }
    }
} else if($one == 'get-list'){
    $deliver['status'] = 400;
    $id = Specific::Filter($_POST['id']);
    if (!empty($id) && is_numeric($id) && $id > 0) {
        $list = $dba->query('SELECT * FROM lists WHERE by_id = '.$TEMP['#user']['id'].' AND id = '.$id)->fetchArray();
        if (!empty($list)) {
            $deliver = array(
                'status'  => 200,
                'id'      => $list['id'],
                'title'   => $list['title'],
                'desc'    => $list['description'],
                'public'  => $list['privacy'] == 1 ? ' selected' : '',
                'private' => $list['privacy'] == 0 ? ' selected' : ''
            );

        }
    }
} else if($one == 'toggle-list'){
    $deliver['status'] = 400;
    $video_id = Specific::Filter($_POST['id']);
    $list_id = Specific::Filter($_POST['list_id']);
    if (!empty($video_id) && !empty($list_id) && is_numeric($video_id)) {
        $list_title = $dba->query('SELECT title FROM lists WHERE list_id = "'.$list_id.'"')->fetchArray();
        if ($dba->query('SELECT COUNT(*) FROM playlists WHERE by_id = '.$TEMP['#user']['id'].' AND list_id = "'.$list_id.'" AND video_id = '.$video_id)->fetchArray() == 0) {
            if ($dba->query('INSERT INTO playlists (list_id, video_id, by_id) VALUES ("'.$list_id.'",'.$video_id.','.$TEMP['#user']['id'].')')->insertId()) {
                $deliver = array(
                    'status' => 200,
                    'message' => "{$TEMP['#word']['added_to']} $list_title"
                );
            }
        } else {
            if($dba->query('DELETE FROM playlists WHERE by_id = '.$TEMP['#user']['id'].' AND list_id = "'.$list_id.'" AND video_id = '.$video_id)->returnStatus()){
                $deliver = array(
                    'status' => 200,
                    'message' => "{$TEMP['#word']['removed_from']} $list_title"
                );
            }
        }
    }
} else if($one == 'toggle-later'){
    $deliver['status'] = 400;
    $video_id = Specific::Filter($_POST['id']);
    if (!empty($video_id) && is_numeric($video_id)) {
        if ($dba->query('SELECT COUNT(*) FROM watch_later WHERE by_id = '.$TEMP['#user']['id'].' AND video_id = '.$video_id)->fetchArray() == 0) {
            if ($dba->query('INSERT INTO watch_later (video_id, by_id, time) VALUES ('.$video_id.','.$TEMP['#user']['id'].','.time().')')->insertId()) {
                $deliver = array(
                    'status' => 200,
                    'message' => "{$TEMP['#word']['added_to']} {$TEMP['#word']['watch_later']}"
                );
            }
        } else {
            if($dba->query('DELETE FROM watch_later WHERE by_id = '.$TEMP['#user']['id'].' AND video_id = '.$video_id)->returnStatus()){
                $deliver = array(
                    'status' => 200,
                    'message' => "{$TEMP['#word']['removed_from']} {$TEMP['#word']['watch_later']}"
                );
            }
        }
    }
}
?>