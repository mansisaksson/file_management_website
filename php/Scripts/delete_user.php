<?php 
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR . 'Core/Globals.php';

if (!isset($_POST["user_id"])) {
    exit_script("No User Specified", 500);
}

if (!isset($_POST["password"])) {
    exit_script("No Password Specified", 500);
}

$userID = $_POST["user_id"];
$password = $_POST["password"];

$user = User::getUser($userID);
if (!isset($user)) {
    exit_script("Could not find user", 500);
}

if (!HelperFunctions::isUserLoggedIn($user->UserID)) {
    exit_script("Insufficient permissions", 401);
}

if (!$user->ValidatePassword($password)) {
    exit_script("Invalid Password", 401);
}

$files = UserFile::findFiles("", $user->UserID);
if (!$user->deleteUser()) {
    exit_script("Failed to remove user", 500);
}

foreach ($files as &$file) {
    $file->deleteFile();
}

Session::getInstance()->destroy();
?>