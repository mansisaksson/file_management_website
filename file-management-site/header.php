<?php // prepend.php - autoprepended at the top of your tree
require_once dirname(__DIR__).'/header.php';

$jsFolderName = 'js/';
$contentFolderName = 'content/';
$cssFolderName = 'CSS/';

define('FP_JS_DIR', FP_FMSITE_DIR . $jsFolderName);
define('RP_JS_DIR', RP_FMSITE_DIR . $jsFolderName);

define('FP_CONTENT_DIR', FP_FMSITE_DIR . $contentFolderName);
define('RP_CONTENT_DIR', RP_FMSITE_DIR . $contentFolderName);

define('FP_CSS_DIR', FP_FMSITE_DIR . $cssFolderName);
define('RP_CSS_DIR', RP_FMSITE_DIR . $cssFolderName);
?>
