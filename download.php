<?php
require_once 'header.php';

$redirect =  RP_PHP_DIR."Scripts/download_file.php?".$_SERVER['QUERY_STRING'];
header("Location: ".$redirect);
?>