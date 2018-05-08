<?php
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR . 'Core/Globals.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$session = Session::getInstance();
if (!isset($session)) {
    exit_script("No User Logged In", false);
}

$session->destroy();
exit_script("Logout Successfull");
?>
