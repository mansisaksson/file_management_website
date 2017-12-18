<?php 
require_once dirname(__DIR__).'/header.php';
require_once FP_PHP_DIR . 'globals.php';

$fileID = $_POST["file_id"];

$file = UserFile::getFile($fileID);
if (!isset($file)) {
    echo "Could not find file"."<br>";
    return;
}

if (!HelperFunctions::isUserLoggedIn($file->FileOwner)) {
    echo "Insufficient permissions"."<br>";
    return;
}

$newName = isset($_POST["file_name"]) ? $_POST["file_name"] : "";
if ($newName === "") { // Quck exit if name is not valid
    die("File Name cannot be empty"."<br>");
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
        die("Pasword Missmatch"."<br>");
    }
    
    $file->setPassword($newPassword);
}
$file->IsPublic = $newIsPublic;

if ($file->saveFileToDB()) {
    echo "File updated successfully!"."<br>";
}
?>