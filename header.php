<?php // prepend.php - autoprepended at the top of your tree
$scriptsFolderName = 'scripts/';
$jsFolderName = 'js/';
$contentFolderName = 'content/';
$uploadsFolderName = 'uploads/';

define('FP_MAIN_DIR', dirname(__FILE__) . '/');
define('RP_MAIN_DIR', '/mansisaksson_webbpage/'); //TODO: Could proberly use something like this dirname($_SERVER['REQUEST_URI'])

define('FP_SCRIPTS_DIR', FP_MAIN_DIR . $scriptsFolderName);
define('RP_SCRIPTS_DIR', RP_MAIN_DIR . $scriptsFolderName);

define('FP_JS_DIR', FP_MAIN_DIR . $jsFolderName);
define('RP_JS_DIR', RP_MAIN_DIR . $jsFolderName);

define('FP_CONTENT_DIR', FP_MAIN_DIR . $contentFolderName);
define('RP_CONTENT_DIR', RP_MAIN_DIR . $contentFolderName);

define('FP_UPLOADS_DIR', FP_MAIN_DIR . $uploadsFolderName);
define('RP_UPLOADS_DIR', RP_MAIN_DIR . $uploadsFolderName);
?>
