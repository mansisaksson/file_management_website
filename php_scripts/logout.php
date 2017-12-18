<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_PHP_DIR . 'globals.php';

$session = Session::getInstance();
if (!isset($session)) {
    echo "No User Logged In";
    return;
}

$session->destroy();
header("Location: ".RP_MAIN_DIR."index.php");
?>
