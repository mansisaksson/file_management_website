<?php
require_once dirname(__DIR__) . '/../header.php';
require_once FP_PHP_DIR . 'Core/Session.php';
require_once FP_PHP_DIR . 'Core/HelperFunctions.php';
require_once FP_PHP_DIR . 'Core/Database.php';
require_once FP_PHP_DIR . 'Core/MySQL.php';

class GenericResponse
{
    public $message = "";
    public $logs = array();

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

    public static function AddLog(String $Log) {
        StaticResponse::EnsureValidResponse();
        array_push(self::$serverResponse->logs, $Log);
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
    StaticResponse::SetMessage($msg);
    echo json_encode(StaticResponse::GetMessage());
    exit();
}

function error_msg($msg)
{
    if (ERROR_ENABLED) {
        StaticResponse::AddLog("<p id=error_msg>". $msg."</p><br>");
    }
}

function warning_msg($msg)
{
    if (ERROR_ENABLED) {
        StaticResponse::AddLog("<p id=warning_msg>". $msg."</p><br>");
    }
}

function log_msg($msg)
{
    //if (ERROR_ENABLED) {
        StaticResponse::AddLog("<p id=log_msg>". $msg."</p><br>");
    //}
}
?>
