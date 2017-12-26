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
            error_msg("Faild to add user: ".$conn->error);
            return false;
        }
        
        $stmt->close();
        $conn->close();
        
        return true;
    }
    
    static function removeUser($id): bool
    {
        // TODO
    }
    
    static function addUserFile(UserFile $file): bool
    {
        $conn = HelperFunctions::createConnectionToDB();
        if (!isset($conn)) {
            return false;
        }
               
        Database::createFileTable($conn, false);
               
        // Add to user file table
        $query = "REPLACE INTO ".SQL::FILE_TABLE
            ." (file_id, file_owner, file_name, file_type, file_description, file_password, public, download_count)"
            ." VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            error_msg("Failed to add file: ". $conn->error);
            return false;
        }
        
        $public = $file->IsPublic ? 1 : 0;
        $stmt->bind_param("ssssssii", 
            $file->FileID,
            $file->FileOwner,
            $file->FileName, 
            $file->FileType, 
            $file->FileDescription, 
            $file->HashedFilePassword, 
            $public, 
            $file->DownloadCount);
        
        if (!$stmt->execute()) {
            error_msg($conn->error);
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
            user_id VARCHAR(256) PRIMARY KEY,
            user_name VARCHAR(256) NOT NULL,
            password VARCHAR(256) NOT NULL
            )";
        
        if ($conn->query($sql) === false) {
            error_msg("Error creating user table " . $conn->error);
            return false;
        }
        
        return true;
    }
    
  
    static function createFileTable($conn, bool $clearExistingTable): bool
    {
        if ($clearExistingTable){
            $conn->query("DROP TABLE ".SQL::FILE_TABLE);
        }
        
        $sql = "CREATE TABLE ".SQL::FILE_TABLE." (
            file_id VARCHAR(256),
            file_owner VARCHAR(256),
            file_name VARCHAR(256) NOT NULL,
            file_type VARCHAR(256) NOT NULL,
            file_description TEXT NOT NULL,
            file_password VARCHAR(256),
            public TINYINT(1),
            download_count INT(6) DEFAULT 0,
            PRIMARY KEY (file_id),
            FOREIGN KEY (file_owner) REFERENCES ".SQL::USERS_TABLE."(user_id)
            )";
        
        if (!$conn->query($sql)) {
            error_msg("Failed to create File Table: " . $conn->error);
            return false;
        }
        
        return true;
    }
    
    static function createDatabase($conn, bool $clearExistingDatabase): bool
    {
        if ($clearExistingDatabase){
            $conn->query("DROP DATABASE ".SQL::DATABASE);
        }
        
        $sql = "CREATE DATABASE ".SQL::DATABASE;
        
        if ($conn->query($sql) === false) {
            error_msg("Failed to create database: ". $conn->error);
            return false;
        }
        
        return true;
    }
}
?>