<?php 
require_once dirname(__DIR__).'/header.php';

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
        if (!$stmt) {
            return null;
        }
            
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
        
        Database::createUserTable($conn, false); // Make sure table exists
        
        // Create the user
        $esc_id = $conn->escape_string($id);
        $esc_username = $conn->escape_string($username);
        $hashed_password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));
        
        $stmt = $conn->prepare("INSERT INTO ".SQL::USERS_TABLE." VALUES(?, ?, ?)");
        $stmt->bind_param('sss', $esc_id, $esc_username, $hashed_password);
        if (!$stmt->execute()){
            echo "Faild to add user: ".$stmt->error." <br>";
            return false;
        }
        
        Database::createUserFileTable($conn, $esc_id, false);
        $stmt->close();
        $conn->close();
        
        return true;
    }
    
    static function removeUser($id)
    {
        $conn = HelperFunctions::createConnectionToDB();
        if (!isset($conn)) {
            return false;
        }
        
        $esc_id = $conn->escape_string($id);
        
        $stmt = $conn->prepare("DELETE FROM ".SQL::USERS_TABLE." WHERE id = ?");
        $stmt->bind_param('s', $esc_id);
        if (!$stmt->execute()){
            echo "Faild to remove user: ".$stmt->error." <br>";
            return false;
        }
        $stmt->close();
        
        $sql = "DROP TABLE user_files_".$esc_id;
        if ($conn->query($sql) !== TRUE) {
            echo "Error removing file table " . $conn->error;
            return false; // TODO: We should remove the user from the user Table here
        }
        
        $conn->close();
        
        return true;
    }
    
    static function addUserFile($userID, $fileID, $file_name, $password, $public)
    {
        $conn = HelperFunctions::createConnectionToDB();
        if (!isset($conn)) {
            return false;
        }
        
        $file_type = pathinfo($file_name, PATHINFO_EXTENSION);
        $esc_file_name = $conn->escape_string(pathinfo($file_name, PATHINFO_FILENAME));
        
        $hashed_password = "";
        if ($password !== "")
            $hashed_password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));
        
        Database::createGlobalFileTable($conn, false);
        Database::createUserFileTable($conn, $userID, false);
        
        // Add to global file table
        $query = "INSERT INTO ".SQL::GLOBAL_FILE_TABLE
            ." (id, file_owner, file_name)"
            ." VALUES (?, ?, ?)";
            
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            die($conn->error);
        }
        
        $stmt->bind_param("sss", $fileID, $userID, $esc_file_name);
        if (!$stmt->execute()) {
            die($conn->error);
        }
        $stmt->close();
        
        // Add to user file table
        $query = "INSERT INTO ".SQL::USER_FILES_TABLE.$userID
            ." (file_id, file_name, file_type, file_description, file_password, public, download_count)"
            ." VALUES (?, ?, ?, 'DEFAULT_DESCRIPTION', ?, ?, '0')";
            
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            die($conn->error."<br>");
        }
        
        $stmt->bind_param("ssssi", $fileID, $esc_file_name, $file_type, $hashed_password, $public);
        if (!$stmt->execute()) {
            die($conn->error."<br>");
        }
        $stmt->close();
        $conn->close();
    }
    
    static function createUserTable($conn, $clearExistingTable)
    {
        if ($clearExistingTable){
            $conn->query("DROP TABLE ".SQL::USERS_TABLE);
        }
        
        $sql = "CREATE TABLE ".SQL::USERS_TABLE." (
            id VARCHAR(256) PRIMARY KEY,
            user_name VARCHAR(256) NOT NULL,
            password VARCHAR(256) NOT NULL
            )";
        
        if ($conn->query($sql) === false) {
            echo "Error creating user table " . $conn->error;
            return false;
        }
        
        return true;
    }
    
    static function createUserFileTable($conn, $userID, $clearExistingTable)
    {
        if ($clearExistingTable){
            $conn->query("DROP TABLE ".SQL::USER_FILES_TABLE.$userID);
        }
        
        $sql = "CREATE TABLE ".SQL::USER_FILES_TABLE.$userID." (
            file_id VARCHAR(256) PRIMARY KEY,
            file_name VARCHAR(256) NOT NULL,
            file_type VARCHAR(256) NOT NULL,
            file_description TEXT NOT NULL,
            file_password VARCHAR(256),
            public TINYINT(1),
            download_count INT(6) DEFAULT 0
            )";
        
        if ($conn->query($sql) === false) {
            echo $conn->error."<br>";
            return false;
        }
        
        return true;
    }
    
    static function createGlobalFileTable($conn, $clearExistingTable)
    {
        if ($clearExistingTable){
            $conn->query("DROP TABLE ".SQL::GLOBAL_FILE_TABLE);
        }
        
        $sql = "CREATE TABLE ".SQL::GLOBAL_FILE_TABLE." (
            id VARCHAR(256) PRIMARY KEY,
            file_owner VARCHAR(256) NOT NULL,
            file_name VARCHAR(256) NOT NULL
            )";
        
        if ($conn->query($sql) === false) {
            echo $conn->error."<br>";
            return false;
        }
        
        return true;
    }
}
?>