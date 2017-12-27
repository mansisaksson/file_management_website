<?php
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR.'Core/Globals.php';

$sessionUser = Session::getUser();
if (!isset($sessionUser)) {
    die ("No User Logged In");
}

$fileToUpload = "fileToUpload";
if (isset($_FILES[$fileToUpload]) === false){
    die ("No file specifiled");
}

//Get form information
$password = $_POST['password'];
$password_conf = $_POST['confirm_password'];
$public = isset($_POST['isPublic']);

// Check password validity
if ($password !== $password_conf){
    die ("Password missmatch");
}

$file_name = basename($_FILES[$fileToUpload]["name"]);

$file = UserFile::createNewFile($sessionUser->UserID, $file_name, "DEFAULT_DESCRIPTION", $public, $password);
$target_file_name = $file->FileID;

if (tryUploadFile($fileToUpload, $target_file_name)) {
    if (!$file->saveFileToDB()) {
        unlink(FP_UPLOADS_DIR.$file->FileID); // Delete the file if we failed to add it to the database
    }
}

function tryUploadFile($fileToUpload, $target_file): bool
{
    if (!isset($_FILES[$fileToUpload])) {
        echo "Not a valid File" . "<br>";
        return false;
    }
    
    if ($_FILES[$fileToUpload]["size"] <= 0) {
        echo "Not a valid File" . "<br>";
        return false;
    }
    
    // Check if file already exists
    if (file_exists(FP_UPLOADS_DIR . $target_file)) {
        echo "Sorry, file already exists." . "<br>";
        return false;
    }
    
    if (move_uploaded_file($_FILES[$fileToUpload]["tmp_name"], FP_UPLOADS_DIR.$target_file)) {
        echo "The file ". basename($_FILES[$fileToUpload]["name"]). " has been uploaded." . "<br>";
    }
    else {
        echo "Sorry, there was an error uploading your file." . "<br>";
        return false;
    }
    
    return true;
}
?>