<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_PHP_DIR . 'session.php';
require_once FP_PHP_DIR . 'helper_functions.php';
require_once FP_PHP_DIR . 'Database.php';
require_once FP_PHP_DIR . 'MySQL.php';


function error_msg($error)
{
    if (ERROR_ENABLED) {
        echo $error."<br>";
    }
}
?>
