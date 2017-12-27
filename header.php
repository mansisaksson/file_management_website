<?php // prepend.php - autoprepended at the top of your tree
$serverRootFolder = "/mansisaksson_webbpage/";
$phpScriptsFolderName = 'php/';
$jsFolderName = 'js/';
$contentFolderName = 'content/';
$uploadsFolderName = 'uploads/';

define('FP_MAIN_DIR', dirname(__FILE__) . '/');
define('RP_MAIN_DIR', $serverRootFolder);

define('FP_PHP_DIR', FP_MAIN_DIR . $phpScriptsFolderName);
define('RP_PHP_DIR', RP_MAIN_DIR . $phpScriptsFolderName);

define('FP_JS_DIR', FP_MAIN_DIR . $jsFolderName);
define('RP_JS_DIR', RP_MAIN_DIR . $jsFolderName);

define('FP_CONTENT_DIR', FP_MAIN_DIR . $contentFolderName);
define('RP_CONTENT_DIR', RP_MAIN_DIR . $contentFolderName);

define('FP_UPLOADS_DIR', FP_MAIN_DIR . $uploadsFolderName);
define('RP_UPLOADS_DIR', RP_MAIN_DIR . $uploadsFolderName);

define('ERROR_ENABLED', true);
if (ERROR_ENABLED)
{
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    error_reporting(-1);
}
?>
