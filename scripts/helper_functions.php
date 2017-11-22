<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR . 'globals.php';

class HelperFunctions
{
    public static function goToRetPage()
    {
        $returnID = "return";
        if (isset($_GET[$returnID]))
        {
            $returnPage = $_GET[$returnID];
            header("Location: ".RP_MAIN_DIR.$returnPage);
        }
        else
        {
            header("Location: ".RP_MAIN_DIR."index.php");
        }
    }
    
    public static function createConnectionToFileTable()
    {
        $conn = new mysqli(Globals::SQL_SERVERNAME, Globals::SQL_USERNAME, Globals::SQL_PASSWORD);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            return null;
        }
        
        // Open Database
        $sql = "USE ".Globals::SQL_FILE_DATABASE;
        if ($conn->query($sql) !== TRUE) {
            die("Could not find file table: " . $conn->error);
        }
        
        return $conn;
    }
    
    public static function getReturnAddr()
    {
        return basename($_SERVER['REQUEST_URI']);
    }
};
?>