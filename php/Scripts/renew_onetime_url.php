<?php 
require_once dirname(__DIR__) . '/../header.php';
require_once FP_PHP_DIR . 'Core/OneTimeURL.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$urlID = isset($_POST["url_id"]) ? $_POST["url_id"] : "";

$url = OneTimeURL::getURL($urlID);
if (!isset($url)) {
    exit_script("Could not find URL", false);
}

$url->UseCount = 0;
if (!$url->saveURLToDB()) {
    exit_script("Failed to save URL to DB", false);
}

exit_script("URL Renewed!");
?>