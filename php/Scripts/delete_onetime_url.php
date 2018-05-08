<?php 
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR.'Core/Globals.php';

$urlID = isset($_POST["url_id"]) ? $_POST["url_id"] : "";

$url = OneTimeURL::getURL($urlID);
if (!isset($urlID)) {
    exit_script("Could Not Find URL", false);
}

if (!HelperFunctions::isUserLoggedIn($url->URLOwner)) {
    exit_script("Insuffisient Permissions", false);
}

if (!$url->deleteURL()) {
    exit_script("Failed to save changes to URL", false);
}

exit_script("URL Removed Successfully!");
?>