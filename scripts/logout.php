<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR . 'globals.php';

$session = Session::getInstance();
if ($session->UserName() !== null)
{
    $session->destroy();
    header("Location: ".RP_MAIN_DIR."index.php");
}
?>
