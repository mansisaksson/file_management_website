<?php
require_once dirname(__DIR__) . '/../header.php';
require_once FP_PHP_DIR . 'Core/Session.php';
require_once FP_PHP_DIR . 'Core/HelperFunctions.php';
require_once FP_PHP_DIR . 'Core/Database.php';
require_once FP_PHP_DIR . 'Core/MySQL.php';
require_once FP_PHP_DIR . 'Core/Patterns.php';

class ServerResponse extends Singleton
{
    private $content = array();

    public function __set($key, $value)
    {
        $this->content[$key] = $value;
    }

    public function __get($value)
    {
        return $this->content[$value];
    }

    public function getJson(): string {
        return json_encode($this->content);
    }
}

function exit_script(string $msg = "", bool $success = true, $payload = null)
{
    /*
    * We no longer handle HTTP level errors due to this reason:
    * "I would say it is better to be explicit about the separation of protocols. Let the HTTP server and the web browser do their own thing, and let the app do its own thing."
    */
    //http_response_code($errorCode);

    /* 
     * Clean the output to ensure that we're returning valid Json,
     * however we still want to return the errors produced by the internal php code 
     */
    ServerResponse::Instance()->serverOutput = ob_get_contents();

    if (ob_get_contents()) {
        ob_clean();
    }

    ob_end_flush();
    
    ServerResponse::Instance()->success = $success;
    ServerResponse::Instance()->message = $msg;

    if ($payload !== null) {
        ServerResponse::Instance()->payload = $payload;
    }

    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    
    echo ServerResponse::Instance()->getJson();
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
