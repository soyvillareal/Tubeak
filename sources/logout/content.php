<?php
if (!empty($_SESSION['session_id'])) {
    $dba->query('DELETE FROM sessions WHERE session_id = "'.Specific::Filter($_SESSION['session_id']).'"');
}
if (isset($_COOKIE['session_id'])) {
    $dba->query('DELETE FROM sessions WHERE session_id = "'.Specific::Filter($_COOKIE['session_id']).'"');
    setcookie('session_id', null, -1,'/');
} 
session_destroy();
header("Location: ".Specific::Url());
exit();
?>