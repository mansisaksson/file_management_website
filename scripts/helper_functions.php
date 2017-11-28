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
            //header("Location: ".RP_MAIN_DIR."index.php");
        }
    }
    
    public static function createConnectionToDB()
    {
        $conn = new mysqli(SQL::SERVERNAME, SQL::USERNAME, SQL::PASSWORD);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            return null;
        }
        
        // Open Database
        $sql = "USE ".SQL::DATABASE;
        if ($conn->query($sql) !== TRUE) {
            die("Could not find file table: " . $conn->error);
        }
        
        return $conn;
    }
    
    public static function getReturnAddr()
    {
        return basename($_SERVER['REQUEST_URI']);
    }
    
    public static function updateUserSession($username)
    {
        $conn = HelperFunctions::createConnectionToDB();
        
        // Update our session
        $stmt = $conn->prepare("SELECT id FROM ".SQL::USERS_TABLE." WHERE user_name = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        
        $id = $stmt->get_result();
        $stmt->close();
        $conn->close();
        
        if (!isset($id)){
            echo ("Could not retrive user data from database");
            return false;
        }
        
        $_SESSION[Session::USER_NAME] = $username;
        $_SESSION[Session::USER_ID] = $id;
        return true;
    }
};
?>