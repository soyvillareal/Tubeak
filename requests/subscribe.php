<?php
if ($TEMP['#loggedin'] === false) {
    $deliver = array(
        'status' => 401,
        'error' => $TEMP['#word']['error']
    );
    echo json_encode($deliver);
    exit();
} else if ($one == 'channel') {
    $deliver['status'] = 400;
    if(!empty($_POST['by_id']) && $_POST['by_id'] != $TEMP['#user']['id']){
        $id = Specific::Filter($_POST['by_id']);
        if ($dba->query('SELECT COUNT(*) FROM subscriptions WHERE by_id = '.$id.' AND subscriber_id = '.$TEMP['#user']['id'])->fetchArray() > 0) {
            if ($dba->query('DELETE FROM subscriptions WHERE by_id = '.$id.' AND subscriber_id = '.$TEMP['#user']['id'])->returnStatus()) {
                $deliver['status'] = 304;
            }
            Specific::SendNotification(array(
                'to_id' => $id,
                'from_id' => $TEMP['#user']['id'],
                'type' => '"unsubscribed"',
                'notify_key' => "'{$TEMP['#user']['user_id']}'",
                'time' => time()
            ));
        }else{
            if ($dba->query('INSERT INTO subscriptions (by_id, subscriber_id, time) VALUES ('.$id.','.$TEMP['#user']['id'].','.time().')')->insertId()) {
                $deliver['status'] = 200;
                Specific::SendNotification(array(
                    'from_id' => $TEMP['#user']['id'],
                    'to_id' => $id,
                    'type' => '"subscribed"',
                    'notify_key' => "'{$TEMP['#user']['user_id']}'",
                    'time' => time()
                ));
            }
        }
    }
}
?>