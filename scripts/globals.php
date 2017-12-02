<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR . 'session.php';
require_once FP_SCRIPTS_DIR . 'helper_functions.php';

class SQL
{
    // MySQL
    const SERVERNAME = "localhost";
    const USERNAME = "root";
    const PASSWORD = "";
    
    const DATABASE = "mi_ws_db";
    const FILE_TABLE = "files";
    const USERS_TABLE = "users";
    const PERMISSIONS_TABLE = "permission_groups";

}

class Database
{
    static function getUserID($username)
    {
        $conn = HelperFunctions::createConnectionToDB();
        if (!isset($conn)) {
            return null;
        }
        
        // Update our session
        $esc_username = $conn->escape_string($username);
        $stmt = $conn->prepare("SELECT id FROM ".SQL::USERS_TABLE." WHERE user_name = ?");
        $stmt->bind_param('s', $esc_username);
        if (!$stmt->execute()) {
            echo "Failed to get user ID: ".$stmt->error."<br>";
            return;
        }
        
        $result = $stmt->get_result();
        $stmt->close();
        $conn->close();
        
        if ($result === false || $result->num_rows <= 0){
            echo "Could not retrive user data from database. <br>";
            return null;
        }
        
        $id = $result->fetch_row()[0];
        return (string)$id;
    }
    
    static function getUserPassword($id)
    {
        $conn = HelperFunctions::createConnectionToDB();
        if (!isset($conn)) {
            return null;
        }
        
        // Update our session
        $esc_id = $conn->escape_string((string)$id);
        $stmt = $conn->prepare("SELECT password FROM ".SQL::USERS_TABLE." WHERE id = ?");
        $stmt->bind_param('s', $esc_id);
        if (!$stmt->execute()) {
            echo "Failed to get user password: ".$stmt->error."<br>";
            return;
        }
        
        $result = $stmt->get_result();
        $stmt->close();
        $conn->close();
        
        if ($result === false || $result->num_rows <= 0){
            echo "Could not retrive user data from database. <br>";
            return null;
        }
        
        $hashed_password = $result->fetch_row()[0];
        return (string)$hashed_password;
    }
    
    static function getUserName($id)
    {
        $conn = HelperFunctions::createConnectionToDB();
        if (!isset($conn)) {
            return null;
        }
        
        // Update our session
        $esc_id = $conn->escape_string($id);
        $stmt = $conn->prepare("SELECT user_name FROM ".SQL::USERS_TABLE." WHERE id = ?");
        $stmt->bind_param('s', $esc_id);
        if (!$stmt->execute()) {
            echo "Failed to get username: ".$stmt->error."<br>";
            return;
        }
        
        $result = $stmt->get_result();
        $stmt->close();
        $conn->close();
        
        if ($result === false || $result->num_rows <= 0){
            echo "Could not retrive user data from database. <br>";
            return null;
        }
        
        $hashed_name = $result->fetch_row()[0];
        return (string)$hashed_name;
    }
    
    static function createNewUser($id, $username, $password)
    {
        $conn = HelperFunctions::createConnectionToDB();
        if (!isset($conn)) {
            return false;
        }
        
        $esc_id = $conn->escape_string($id);
        $esc_username = $conn->escape_string($username);
        $hashed_password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));
        
        $stmt = $conn->prepare("INSERT INTO ".SQL::USERS_TABLE." VALUES(?, ?, ?)");
        $stmt->bind_param('sss', $esc_id, $esc_username, $hashed_password);
        if (!$stmt->execute()){
            echo "Faild to add user: ".$stmt->error." <br>";
            return false;
        }
        $stmt->close();
        $conn->close();
        
        return true;
    }
}
?>
