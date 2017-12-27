<?php
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR . 'Core/Globals.php';

$user = Session::getUser();
if (!isset($user)) {
    echo ("No User Logged In");
    return;
}

if (!Database::removeUser($user->UserID)) {
    echo "Failed to remove user!";
    return;
}

$session->destroy();
echo "User Removed";

// TODO: Remove user files etc...
?>