<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_PHP_DIR . 'session.php';
require_once FP_PHP_DIR . 'helper_functions.php';
require_once FP_PHP_DIR . 'Database.php';
require_once FP_PHP_DIR . 'MySQL.php';


function error_msg($msg)
{
    if (ERROR_ENABLED) {
        ?>
        <style>
            p#error_msg {
                color: #ff7c7c;
                margin: 0px;
                background-color: #000000;
                float: left;
            }
        </style>
        <?php 
        echo "<p id=error_msg>". $msg."</p><br>";
    }
}

function warning_msg($msg)
{
    if (ERROR_ENABLED) {
        ?>
        <style>
            p#warning_msg {
                color: #fffa00;
                margin: 0px;
                background-color: #000000;
                float: left;
            }
        </style>
        <?php 
        echo "<p id=warning_msg>". $msg."</p><br>";
    }
}

function log_msg($msg)
{
    if (ERROR_ENABLED) {
        ?>
        <style>
            p#log_msg {
                color: #ffffff;
                margin: 0px;
                background-color: #000000;
                float: left;
            }
        </style>
        <?php 
        echo "<p id=log_msg>". $msg."</p><br>";
    }
}
?>
