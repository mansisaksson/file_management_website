<?php 
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR . 'Core/Globals.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$fileID = "";
if (isset($_GET["id"])) {
    $fileID = $_GET["id"];
}

if (isset($_POST["id"])) {
    $fileID = $_POST["id"];
}

if ($fileID === "") {
    exit_script("No File specified", false);
}

// Retrevie file information
$file = UserFile::getFile($fileID);
if (!isset($file)){
    exit_script("Could Not Find File in Database", false);
}

// Does the file actually exist on disk?
if (!file_exists($file->getPath())) {
    exit_script("Could not find file: ".$file->getPath(), false);
}

// Limit file-size
$fileSize = filesize($file->getPath());
if ($fileSize === E_WARNING) {
    exit_script("Error checking file-size", false);
}

if ($fileSize >= 50000000) { // 50mb
    exit_script("File is too large for preview", false);
}

// File Authentication
if (!HelperFunctions::isUserLoggedIn($file->FileOwner)) {
    exit_script("Insufficent Permissions", false);
}

$contentType = mime_content_type($file->getPath());
if ($contentType === "application/octet-stream") {
    exit_script("Cannot preview file", false);
}

if ($contentType === "application/zip") {
    exit_script("Cannot preview file", false);
}

header("Content-type: ".$contentType);
header('Content-Disposition: inline; filename="' . $file->getFullName() . '"');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($file->getPath()));
header('Accept-Ranges: bytes');

@readfile($file->getPath());

?>