<?php
require('./includes/autoload.php');
$process_queue = $dba->query('SELECT * FROM queue LIMIT '.$TEMP['#settings']['max_queue'])->fetchAll();
if (count($process_queue) <= $TEMP['#settings']['max_queue'] && count($process_queue) > 0) {
    foreach ($process_queue as $value) {
        if ($value['processing'] == 0) {
            $video = $dba->query('SELECT * FROM videos WHERE id = '.$value['video_id'].' AND deleted = 0')->fetchArray();
            $dba->query('UPDATE queue SET processing = 1 WHERE video_id = '.$video['id']);
            $path         = $video['path'];
            $video_width  = $value['video_width'];
            Specific::ConvertVideo(array(
                'insert' => $video['id'],
                'video_id' => $video['video_id'],
                'path' => $path,
                'video_width' => $video_width
            ));
            require('./process-queue.php');
        }
    }
}