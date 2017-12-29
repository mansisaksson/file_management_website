<?php 
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR . 'Core/Globals.php';

if (!isset($_POST["file_id"])) {
    exit_script("No File Specified", 500);
}

if (!isset($_POST["password"])) {
    exit_script("No Password Specified", 500);
}

$fileID = $_POST["file_id"];
$password = $_POST["password"];

$file = UserFile::getFile($fileID);
if (!isset($file)) {
    exit_script("Could not find file", 500);
}

if (!HelperFunctions::isUserLoggedIn($file->FileOwner)) {
    exit_script("Insufficient permissions", 401);
}

$user = User::getUser($file->FileOwner);
if (!isset($user)) {
    exit_script("Could not find file owner", 500);
}

if (!$user->ValidatePassword($password)) {
    exit_script("Invalid Password", 401);
}

if (!$file->deleteFile()) {
    exit_script("Failed to remove file", 500);
}
?>