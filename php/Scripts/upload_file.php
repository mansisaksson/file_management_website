<?php
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR.'Core/Globals.php';

$sessionUser = Session::getUser();
if (!isset($sessionUser)) {
    exit_script("No User Logged In", 401);
}

$fileToUpload = "fileToUpload";
if (!isset($_FILES[$fileToUpload])){
    exit_script("Invalid File", 400);
}

//Get form information
$password = $_POST['password'];
$password_conf = $_POST['confirm_password'];
$public = isset($_POST['isPublic']);

// Check password validity
if ($password !== $password_conf){
    exit_script("Password missmatch", 401);
}

$file_name = basename($_FILES[$fileToUpload]["name"]);

$file = UserFile::createNewFile($sessionUser->UserID, $file_name, "DEFAULT_DESCRIPTION", $public, $password);
$target_file_name = $file->FileID;

$uploadResponse = "";
if (!tryUploadFile($fileToUpload, $target_file_name, $uploadResponse)) {
    exit_script($uploadResponse, 500);
}

if (!$file->saveFileToDB()) {
    unlink(FP_UPLOADS_DIR.$file->FileID); // Delete the file if we failed to add it to the database
    exit_script("Failed to save file to DB", 500);
}

exit_script($uploadResponse);

function tryUploadFile($fileToUpload, $target_file, &$responseMessage): bool
{
    if (!isset($_FILES[$fileToUpload])) {
        $responseMessage = "Not a valid File";
        return false;
    }
    
    if ($_FILES[$fileToUpload]["size"] <= 0) {
        $responseMessage = "The file is too small";
        return false;
    }
    
    // Check if file already exists
    if (file_exists(FP_UPLOADS_DIR . $target_file)) {
        $responseMessage = "Sorry, file already exists.";
        return false;
    }
    
    if (move_uploaded_file($_FILES[$fileToUpload]["tmp_name"], FP_UPLOADS_DIR.$target_file)) {
        $responseMessage = "The file ". basename($_FILES[$fileToUpload]["name"]). " has been uploaded.";
    }
    else {
        $responseMessage = "Sorry, there was an error uploading your file.";
        return false;
    }
    
    return true;
}
?>
