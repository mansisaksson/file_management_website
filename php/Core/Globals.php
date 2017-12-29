<?php
require_once dirname(__DIR__) . '/../header.php';
require_once FP_PHP_DIR . 'Core/Session.php';
require_once FP_PHP_DIR . 'Core/HelperFunctions.php';
require_once FP_PHP_DIR . 'Core/Database.php';
require_once FP_PHP_DIR . 'Core/MySQL.php';

?>
<style>
    p#error_msg {
        color: #ff7c7c;
        margin: 0px;
        background-color: #000000;
        float: left;
    }
    p#warning_msg {
        color: #fffa00;
        margin: 0px;
        background-color: #000000;
        float: left;
    }
     p#log_msg { 
/*          color: #ffffff;  */
/*          background-color: #000000;  */
         margin: 0px; 
         float: left; 
     } 
</style>
<?php 

function exit_script(string $msg = "", int $errorCode = 200)
{
    if ($msg !== "") {
        $id = ($errorCode >= 200 && $errorCode < 300) ? "log_msg" : "error_msg";
        echo "<p id=".$id.">". "Fatal Error: ".$msg. "</p><br>";
    }
    
    http_response_code($errorCode);
    exit();
}

function error_msg($msg)
{
    if (ERROR_ENABLED) {
        echo "<p id=error_msg>". $msg."</p><br>";
    }
}

function warning_msg($msg)
{
    if (ERROR_ENABLED) {
        echo "<p id=warning_msg>". $msg."</p><br>";
    }
}

function log_msg($msg)
{
    //if (ERROR_ENABLED) {
        echo "<p id=log_msg>". $msg."</p><br>";
    //}
}
?>
