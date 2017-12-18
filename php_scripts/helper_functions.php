<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_PHP_DIR . 'globals.php';

class HelperFunctions
{
    public static function getDownloadURL($fileID)
    {
        return $_SERVER['SERVER_NAME'].RP_MAIN_DIR."download.php?fileID=".$fileID;
    }
    
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
            //header("Location: ".RP_MAIN_DIR."index.php");
        }
    }
    
    public static function createConnectionToDB(): ?mysqli
    {
        $conn = new mysqli(SQL::SERVERNAME, SQL::USERNAME, SQL::PASSWORD);
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
            return null;
        }
        
        // Open Database
        $sql = "USE ".SQL::DATABASE;
        if ($conn->query($sql) !== TRUE) {
            echo "Could not find file table: " . $conn->error;
            return null;
        }
        
        return $conn;
    }
    
    public static function getReturnAddr(): string
    {
        return basename($_SERVER['REQUEST_URI']);
    }
    
    public static function isUserLoggedIn($userID = ""): bool
    {
        $user = Session::getUser();
        if (isset($user) && ($userID === "" || $user->UserID === $userID)) {
            return true;
        }
        
        return false;
    }
    
    public static function createNewUserSession(User $user): bool
    {
        // Make sure user exists
        if ($user->isValidUser()) {
            echo "Tried to create session with invalid user";
            return false;
        }
        
        $session = Session::createNewSession();
        $session->SetUser($user);
        return true;
    }
};
?>