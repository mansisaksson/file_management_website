<?php
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR . 'Core/Globals.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$user = Session::getUser();
if (!isset($user)) {
    exit_script("No User Logged In", false);
}

if (!Database::removeUser($user->UserID)) {
    exit_script("Failed to remove user!", false);
}

$session->destroy();
exit_script("User Removed");
?>