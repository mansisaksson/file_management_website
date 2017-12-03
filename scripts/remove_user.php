<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR . 'globals.php';

$session = Session::getInstance();
if ($session->UserName() === null) {
    echo ("No User Logged In");
    return;
}

$userID = $session->UserID();
if (!Database::removeUser($userID)) {
    echo "Failed to remove user!";
    return;
}

$session->destroy();
echo "User Removed";

// TODO: Remove user files etc...
?>