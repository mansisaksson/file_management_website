<?php 
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR . 'Core/Globals.php';

$fileID = "";
if (isset($_GET["id"])) {
    $fileID = $_GET["id"];
}

if (isset($_POST["id"])) {
    $fileID = $_POST["id"];
}

if ($fileID === "") {
    exit_script("No File specified", 400);
}

// Retrevie file information
$file = UserFile::getFile($fileID);
if (!isset($file)){
    exit_script("Could Not Find File in Database", 400);
}

// Does the file actually exist on disk?
if (!file_exists($file->getPath())) {
    exit_script("Could not find file: ".$file->getPath(), 400);
}

// Limit file-size
$fileSize = filesize($file->getPath());
if ($fileSize === E_WARNING) {
    exit_script("Error checking file-size", 500);
}

if ($fileSize >= 50000000) { // 50mb
    exit_script("File is too large for preview", 400);
}

// File Authentication
if (!HelperFunctions::isUserLoggedIn($file->FileOwner)) {
    exit_script("Insufficent Permissions", 401);
}

$contentType = mime_content_type($file->getPath());
if ($contentType === "application/octet-stream") {
    exit_script("Cannot preview file", 500);
}

if ($contentType === "application/zip") {
    exit_script("Cannot preview file", 500);
}

header("Content-type: ".$contentType);
header('Content-Disposition: inline; filename="' . $file->getFullName() . '"');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($file->getPath()));
header('Accept-Ranges: bytes');

@readfile($file->getPath());

?>