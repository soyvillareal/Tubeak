<?php
require_once('./includes/autoload.php');

if (!defined('BASEPATH')){
  header("Location: " . Specific::Url('404'));
  exit();
}
Specific::Sitemap();
?>