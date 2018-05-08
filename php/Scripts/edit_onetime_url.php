<?php 
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR.'Core/Globals.php';

$urlID = isset($_GET["url_id"]) ? $_GET["url_id"] : "";
$urlName = isset($_GET["url_name"]) ? $_GET["url_name"] : "NAME_ERROR";
$urlLimit = isset($_GET["url_limit"]) ? $_GET["url_limit"] : 0;

$url = OneTimeURL::getURL($urlID);
if (!isset($urlID)) {
    exit_script("Could Not Find URL", false);
}

if (!HelperFunctions::isUserLoggedIn($url->URLOwner)) {
    exit_script("Insuffisient Permissions", false);
}

$url->URLName = $urlName;
$url->UseLimit = $urlLimit;

if (!$url->saveURLToDB()) {
    exit_script("Failed to save changes to URL", false);
}

exit_script("URL Edited Successfully!");
?>