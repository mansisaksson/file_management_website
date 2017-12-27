<?php 
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR . 'Core/Globals.php';

if (!isset($_POST["user_id"])) {
    fatal_error("No User Specified", 500);
    return;
}

if (!isset($_POST["password"])) {
    fatal_error("No Password Specified", 500);
    return;
}

$userID = $_POST["user_id"];
$password = $_POST["password"];

$user = User::getUser($userID);
if (!isset($user)) {
    fatal_error("Could not find user", 500);
    return;
}

if (!HelperFunctions::isUserLoggedIn($user->UserID)) {
    fatal_error("Insufficient permissions", 401);
    return;
}

if (!$user->ValidatePassword($password)) {
    fatal_error("Invalid Password", 401);
    return;
}

$files = UserFile::findFiles("", $user->UserID);
if (!$user->deleteUser()) {
    fatal_error("Failed to remove user", 500);
    return;
}

foreach ($files as &$file) {
    $file->deleteFile();
}

Session::getInstance()->destroy();
?>