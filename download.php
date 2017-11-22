<?php
require_once 'header.php';

$redirect =  RP_MAIN_DIR.basename(FP_SCRIPTS_DIR)."/download_file.php?".$_SERVER['QUERY_STRING'];
header("Location: ".$redirect);
?>