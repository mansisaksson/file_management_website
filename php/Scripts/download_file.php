<?php
require_once dirname(__DIR__) . '/../header.php';
require_once FP_PHP_DIR . 'Core/Globals.php';

$fileID = "";
if (isset($_GET["fileID"])) {
    $fileID = $_GET["fileID"];
}

if (isset($_POST["fileID"])) {
    $fileID = $_POST["fileID"];
}

if ($fileID === "") {
    echo "No File specified";
    return;
}

$full_path = FP_UPLOADS_DIR.$fileID;

// Does the file actually exist on disk?
if (!file_exists($full_path)) {
    echo "Could not find file: ".$full_path."<br>";
    return;
}

// Retrevie file information
$userFile = UserFile::getFile($fileID);
if (!isset($userFile)){
    echo "Could Not Find File in Database"."<br>";
    return;
}

/* Check if file is public
*  If not, check if user is owner
*/
if ($userFile->IsPublic !== true) {
    if (!HelperFunctions::isUserLoggedIn($userFile->FileOwner)) {
        echo "Insufficent Permissions";
        return;
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
        echo "Invalid Password";
        return;
    }
}

$userFile->DownloadCount += 1;
$userFile->saveFileToDB();

// Download file
$fd = fopen($full_path, "rb");
if ($fd) 
{
    $fsize = filesize($full_path);
    switch ($userFile->FileType)
    {
        case "pdf":
            header("Content-type: application/pdf");
            break;
        case "zip":
            header("Content-type: application/zip");
            break;
        default:
            header("Content-type: application/octet-stream");
            break;
    }
    
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
?>