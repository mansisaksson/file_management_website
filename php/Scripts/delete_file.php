<?php 
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR . 'Core/Globals.php';

if (!isset($_POST["file_id"])) {
    fatal_error("No File Specified", 500);
    return;
}

if (!isset($_POST["password"])) {
    fatal_error("No Password Specified", 500);
    return;
}

$fileID = $_POST["file_id"];
$password = $_POST["password"];

$file = UserFile::getFile($fileID);
if (!isset($file)) {
    fatal_error("Could not find file", 500);
    return;
}

if (!HelperFunctions::isUserLoggedIn($file->FileOwner)) {
    fatal_error("Insufficient permissions", 401);
    return;
}

$user = User::getUser($file->FileOwner);
if (!isset($user)) {
    fatal_error("Could not find file owner", 500);
    return;
}

if (!$user->ValidatePassword($password)) {
    fatal_error("Invalid Password", 401);
    return;
}

if (!$file->deleteFile()) {
    fatal_error("Failed to remove file", 500);
    return;
}
?>