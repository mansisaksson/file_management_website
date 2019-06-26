<?php // prepend.php - autoprepended at the top of your tree
$serverRootFolder = "/";
$phpScriptsFolderName = 'php/';
$uploadsFolderName = 'uploads/';

$fmSiteFolderName = 'file-management-site/';
$ptSiteFolderName = 'portfolio-site/';

// *** Begin Main Dirs
define('FP_MAIN_DIR', dirname(__FILE__) . '/');
define('RP_MAIN_DIR', $serverRootFolder);

define('FP_PHP_DIR', FP_MAIN_DIR . $phpScriptsFolderName);
define('RP_PHP_DIR', RP_MAIN_DIR . $phpScriptsFolderName);

define('FP_UPLOADS_DIR', FP_MAIN_DIR . $uploadsFolderName);
define('RP_UPLOADS_DIR', RP_MAIN_DIR . $uploadsFolderName);
// *** End Main Dirs

// *** Begin Sites
define('FP_FMSITE_DIR', FP_MAIN_DIR . $fmSiteFolderName);
define('RP_FMSITE_DIR', RP_MAIN_DIR . $fmSiteFolderName);

define('FP_PTSITE_DIR', FP_MAIN_DIR . $ptSiteFolderName);
define('RP_PTSITE_DIR', RP_MAIN_DIR . $ptSiteFolderName);
// *** End Sites

define('ERROR_ENABLED', true);
if (ERROR_ENABLED)
{
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    error_reporting(-1);
}

ob_start();

?>
