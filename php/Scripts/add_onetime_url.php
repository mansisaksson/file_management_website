<?php 
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR.'Core/Globals.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$fileID = isset($_POST["file_id"]) ? $_POST["file_id"] : "ID_ERROR";
$urlName = isset($_POST["url_name"]) ? $_POST["url_name"] : "NAME_ERROR";
$urlLimit = isset($_POST["url_limit"]) ? $_POST["url_limit"] : 0;

$file = UserFile::getFile($fileID);
if (!isset($file)) {
    exit_script("Could not find File", false);
}

if (!HelperFunctions::isUserLoggedIn($file->FileOwner)) {
    exit_script("Insuffisient Permissions", false);
}

$url = OneTimeURL::createNewURL($urlName, $file, $urlLimit);
if (!isset($url)) {
    exit_script("Could not Create URL", false);
}

if (!$url->saveURLToDB()) {
    exit_script("Failed to save URL to DB", false);
}

exit_script("URL Added to database.");
?>