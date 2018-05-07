<?php
require_once dirname(__DIR__) . '/../header.php';
require_once FP_PHP_DIR . 'Core/Session.php';
require_once FP_PHP_DIR . 'Core/HelperFunctions.php';
require_once FP_PHP_DIR . 'Core/Database.php';
require_once FP_PHP_DIR . 'Core/MySQL.php';

class GenericResponse
{
    public $message = "";
    public $serverOutput = "";

    function __construct() {
    }
}

class StaticResponse
{
    private static $serverResponse;
    
    public static function SetMessage(String $msg) {
        StaticResponse::EnsureValidResponse();
        self::$serverResponse->message = $msg;
    }

    public static function SetServerOutput(String $serverOutput) {
        StaticResponse::EnsureValidResponse();
        self::$serverResponse->serverOutput = $serverOutput;
    }

    public static function GetMessage() {
        StaticResponse::EnsureValidResponse();
        return self::$serverResponse;
    }

    private static function EnsureValidResponse() {
        if (!isset(self::$serverResponse)) {
            self::$serverResponse = new GenericResponse();
        }
    }
}


function exit_script(string $msg = "", int $errorCode = 200)
{
    http_response_code($errorCode);

    // Clean the output to ensure that we're returning valid Json
    // However we still want to return the errors produced by the internal php code
    StaticResponse::SetServerOutput(ob_get_contents());
    ob_clean();
    
    StaticResponse::SetMessage($msg);

    echo json_encode(StaticResponse::GetMessage());
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
