<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR . 'globals.php';

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
    
    public static function hasAuthority()
    {
        $session = Session::getInstance();
        if ($session->UserName() !== null)
        {
            return true;
        }
        
        return false;
    }
    
    function createNewUserSession($username)
    {
        $conn = HelperFunctions::createConnectionToDB();
        if (!isset($conn)) {
            return false;
        }
        
        // Update our session
        $esc_username = $conn->escape_string($username);
        $stmt = $conn->prepare("SELECT id FROM ".SQL::USERS_TABLE." WHERE user_name = ?");
        $stmt->bind_param('s', $esc_username);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $stmt->close();
        $conn->close();
        
        if ($result === false || $result->num_rows <= 0){
            echo ("Could not retrive user data from database. <br>");
            return false;
        }
        
        $id = $result->fetch_row()[0];
        $session = Session::createNewSession();
        $session->SetUserName($username);
        $session->SetUserID($id);
        return true;
    }
};
?>