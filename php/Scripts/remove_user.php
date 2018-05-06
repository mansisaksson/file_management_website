<?php
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR . 'Core/Globals.php';

$user = Session::getUser();
if (!isset($user)) {
    exit_script("No User Logged In", 400);
}

if (!Database::removeUser($user->UserID)) {
    exit_script("Failed to remove user!", 500);
}

$session->destroy();
exit_script("User Removed", 500);
?>