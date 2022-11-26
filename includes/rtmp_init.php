<?php
require_once('autoload.php');
error_log("NGINX ON Publish POST: ".json_encode($_POST));
error_log("NGINX ON Publish GET: ".json_encode($_GET));
$url = $_POST['tcurl'];
if (empty($url)) {
    $url = $_POST['swfurl'];
}
$parts = parse_url($url);
error_log(print_r($parts, true));
parse_str($parts["query"], $_GET);
error_log(print_r($_GET, true));
?>