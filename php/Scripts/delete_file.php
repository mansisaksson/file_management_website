<?php 
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR . 'Core/Globals.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (!isset($_POST["file_id"])) {
    exit_script("No File Specified", false);
}

if (!isset($_POST["password"])) {
    exit_script("No Password Specified", false);
}

$fileID = $_POST["file_id"];
$password = $_POST["password"];

$file = UserFile::getFile($fileID);
if (!isset($file)) {
    exit_script("Could not find file", false);
}

if (!HelperFunctions::isUserLoggedIn($file->FileOwner)) {
    exit_script("Insufficient permissions", false);
}

$user = User::getUser($file->FileOwner);
if (!isset($user)) {
    exit_script("Could not find file owner", false);
}

if (!$user->ValidatePassword($password)) {
    exit_script("Invalid Password", false);
}

if (!$file->deleteFile()) {
    exit_script("Failed to remove file", false);
}
?>