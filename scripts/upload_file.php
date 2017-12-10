<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR.'globals.php';

// Check user permissions
if (!HelperFunctions::hasAuthority()) {
    die ("Insufficient permissions");
}

$session = Session::getInstance();
if ($session->UserName() === null) {
    echo ("No User Logged In");
    return;
}

$fileToUpload = "fileToUpload";
if (isset($_FILES[$fileToUpload]) === false){
    die ("No file specifiled");
}

//Get form information
$password = $_POST['password'];
$password_conf = $_POST['confirm_password'];
$public = isset($_POST['isPublic']) ? 1 : 0;

// Check password validity
if ($password !== $password_conf){
    die ("Password missmatch");
}

$file_name = basename($_FILES[$fileToUpload]["name"]);
$target_file = uniqid();

if (tryUploadFile($fileToUpload, $target_file)) {   
    Database::addUserFile($session->UserID(), $target_file, $file_name, $password, $public);
}

function tryUploadFile($fileToUpload, $target_file)
{
    if (isset($_FILES[$fileToUpload]) === false) {
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