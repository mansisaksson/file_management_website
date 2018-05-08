<?php 
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR . 'Core/Globals.php';

$fileID = isset($_POST["file_id"]) ? $_POST["file_id"] : "";

$file = UserFile::getFile($fileID);
if (!isset($file)) {
    exit_script("Could not find file", false);
}

if (!HelperFunctions::isUserLoggedIn($file->FileOwner)) {
    exit_script("Insufficient permissions", false);
}

$newName = isset($_POST["file_name"]) ? $_POST["file_name"] : "";
if ($newName === "") { // Quck exit if name is not valid
    exit_script("File Name cannot be empty", false);
}

$newDesc = isset($_POST["file_description"]) ? $_POST["file_description"] : "";
$changePassword = isset($_POST["change_password"]);
$newPassword = isset($_POST["new_password"]) ? $_POST["new_password"] : "";
$newPassword_conf = isset($_POST["password_confirm"]) ? $_POST["password_confirm"] : "";
$newIsPublic = isset($_POST["isPublic"]);

// Update file
$file->FileName = $newName;
$file->FileDescription = $newDesc;
if ($changePassword === true) {
    if ($newPassword !== $newPassword_conf) {
        exit_script("Pasword Missmatch", false);
    }
    
    $file->setPassword($newPassword);
}
$file->IsPublic = $newIsPublic;

if ($file->saveFileToDB()) {
    exit_script("File updated successfully!");
}
?>