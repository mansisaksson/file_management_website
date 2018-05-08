<?php
require_once dirname(__DIR__) . '/../header.php';
require_once FP_PHP_DIR . 'Core/Globals.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$fileID = "";
if (isset($_GET["fileID"])) {
    $fileID = $_GET["fileID"];
}

if (isset($_POST["fileID"])) {
    $fileID = $_POST["fileID"];
}

if ($fileID === "") {
    exit_script("No File specified", false);
}

$requireAuth = true;
/*
 * Is this a one-time url?
 */
$url = OneTimeURL::getURL($fileID);
if (isset($url)) {
    $requireAuth = false;
    $fileID = $url->FileID;
    /* 
     * TODO: it might be nice to allow for passwords on one time urls, 
     * if so we might need to redirect to a page that collects password information
     */
    $url->UseCount += 1;
    if ($url->UseCount > $url->UseLimit) {
        // TODO: Do we want to remove the URL here or allow for the user to renew it?
        exit_script("This URL is no longer valid", false);
    }
    $url->saveURLToDB();
}

// Retrevie file information
$userFile = UserFile::getFile($fileID);
if (!isset($userFile)){
    exit_script("Could Not Find File in Database", false);
}

// Does the file actually exist on disk?
if (!file_exists($userFile->getPath())) {
    exit_script("Could not find file: ".$userFile->getPath(), false);
}

if ($requireAuth) // If this is a one-time URL then we consider the file to be public
{
    /* Check if file is public
     * If not, check if user is owner
     */
    if ($userFile->IsPublic !== true) {
        if (!HelperFunctions::isUserLoggedIn($userFile->FileOwner)) {
            exit_script("Insufficent Permissions", false);
        }
    }
    
    if ($userFile->IsPasswordProdected()) {
        if (!isset($_POST["password"])) {
            $redirect =  RP_MAIN_DIR."download_password.php?".$_SERVER['QUERY_STRING'];
            header("Location: ".$redirect);
            return;
        }
        
        $password = $_POST["password"];
        if (!$userFile->ValidatePassword($password)) {
            exit_script("Invalid Password", false);
        }
    }
}

$userFile->DownloadCount += 1;
$userFile->saveFileToDB();

// Download file
$fd = fopen($userFile->getPath(), "rb");
if ($fd) 
{
    $fsize = filesize($userFile->getPath());
    $contentType = mime_content_type($userFile->getPath());
    header("Content-type: ".$contentType);
    header("Content-Disposition: attachment; filename=\"".$userFile->getFullName()."\"");
    header("Content-length: $fsize");
    header("Cache-control: private"); //use this to open files directly
    while(!feof($fd)) 
    {
        $buffer = fread($fd, 1*(1024*1024));
        echo $buffer;
        ob_flush();
        flush();    //These two flush commands seem to have helped with performance
    }
}

fclose($fd);
exit_script("File Transfer complete");
?>