<?php 
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR . 'Core/Globals.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (!isset($_POST["user_id"])) {
    exit_script("No User Specified", false);
}

if (!isset($_POST["password"])) {
    exit_script("No Password Specified", false);
}

$userID = $_POST["user_id"];
$password = $_POST["password"];

$user = User::getUser($userID);
if (!isset($user)) {
    exit_script("Could not find user", false);
}

if (!HelperFunctions::isUserLoggedIn($user->UserID)) {
    exit_script("Insufficient permissions", false);
}

if (!$user->ValidatePassword($password)) {
    exit_script("Invalid Password", false);
}

$files = UserFile::findFiles("", $user->UserID);
if (!$user->deleteUser()) {
    exit_script("Failed to remove user", false);
}

foreach ($files as &$file) {
    $file->deleteFile();
}

Session::getInstance()->destroy();
?>