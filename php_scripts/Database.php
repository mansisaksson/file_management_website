<?php 
require_once dirname(__DIR__).'/header.php';
require_once FP_PHP_DIR . 'User.php';
require_once FP_PHP_DIR . 'UserFile.php';

class Database
{         
    static function addUser(User $user): bool
    {
        $conn = HelperFunctions::createConnectionToDB();
        if (!isset($conn)) {
            return false;
        }
        
        Database::createUserTable($conn, false); // Make sure table exists
        
        // Create the user
        $stmt = $conn->prepare("INSERT INTO ".SQL::USERS_TABLE." VALUES(?, ?, ?)");
        $stmt->bind_param('sss', $user->UserID, $user->UserName, $user->HashedUserPassword);
        if (!$stmt->execute()){
            echo "Faild to add user: ".$stmt->error." <br>";
            return false;
        }
        
        Database::createUserFileTable($conn, $esc_id, false);
        $stmt->close();
        $conn->close();
        
        return true;
    }
    
    static function removeUser($id): bool
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
    
    static function addUserFile(UserFile $file): bool
    {
        $conn = HelperFunctions::createConnectionToDB();
        if (!isset($conn)) {
            return false;
        }
               
        Database::createGlobalFileTable($conn, false);
        Database::createUserFileTable($conn, $file->FileOwner, false);
        
        // Add to global file table
        $query = "REPLACE INTO ".SQL::GLOBAL_FILE_TABLE
            ." (id, file_owner, file_name)"
            ." VALUES (?, ?, ?)";
            
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            echo $conn->error."<br>";
            return false;
        }
        
        $stmt->bind_param("sss", $file->FileID, $file->FileOwner, $file->FileName);
        if (!$stmt->execute()) {
            echo $conn->error."<br>";
            return false;
        }
        $stmt->close();
        
        // Add to user file table
        $query = "REPLACE INTO ".SQL::USER_FILES_TABLE.$file->FileOwner
            ." (file_id, file_name, file_type, file_description, file_password, public, download_count)"
            ." VALUES (?, ?, ?, ?, ?, ?, ?)";
            
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            echo $conn->error."<br>";
            return false;
        }
        
        $public = $file->IsPublic ? 1 : 0;
        $stmt->bind_param("sssssii", 
            $file->FileID,
            $file->FileName, 
            $file->FileType, 
            $file->FileDescription, 
            $file->HashedFilePassword, 
            $public, 
            $file->DownloadCount);
        
        if (!$stmt->execute()) {
            echo $conn->error."<br>";
            return false;
        }
        $stmt->close();
        $conn->close();
        
        return true;
    }
    
    static function createUserTable($conn, $clearExistingTable): bool
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
    
    static function createUserFileTable($conn, $userID, $clearExistingTable): bool
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
            //echo $conn->error."<br>";
            return false;
        }
        
        return true;
    }
    
    static function createGlobalFileTable($conn, $clearExistingTable): bool
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
            //echo $conn->error."<br>";
            return false;
        }
        
        return true;
    }
}
?>