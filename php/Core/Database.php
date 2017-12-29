<?php
require_once dirname(__DIR__) . '/../header.php';
require_once FP_PHP_DIR . 'Core/User.php';
require_once FP_PHP_DIR . 'Core/UserFile.php';
require_once FP_PHP_DIR . 'Core/OneTimeURL.php';

class Database
{         
    static function addUser(User $user): bool
    {
        $conn = HelperFunctions::createConnectionToDB();
        if (!isset($conn)) {
            return false;
        }
        
        Database::createUserTable($conn, false); // Make sure table exists
        
        $stmt = $conn->prepare("REPLACE INTO ".SQL::USERS_TABLE.
            "(user_id, user_name, password)".
            " VALUES(?, ?, ?)");
        
        if (!$stmt) {
            error_msg("Bad SQL Syntax: ". $conn->error);
            return false;
        }
        
        $stmt->bind_param('sss', $user->UserID, $user->UserName, $user->HashedUserPassword);
        
        if (!$stmt->execute()){
            error_msg("Faild to add user: ".$conn->error);
            return false;
        }
        
        $stmt->close();
        $conn->close();
        
        return true;
    }
    
    static function removeUser($userID): bool
    {
        $conn = HelperFunctions::createConnectionToDB();
        if (!isset($conn)) {
            return false;
        }
        
        // Add to user file table
        $query = "DELETE FROM ".SQL::USERS_TABLE." WHERE user_id = ?";
        
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            error_msg("Bad SQL Syntax: ". $conn->error);
            return false;
        }
        
        $stmt->bind_param("s", $userID);
        
        if (!$stmt->execute()) {
            error_msg("Failed to remove user: ". $conn->error);
            return false;
        }
        $stmt->close();
        $conn->close();
        
        return true;
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
            error_msg("Bad SQL Syntax: ". $conn->error);
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
            error_msg("Failed to add file: ". $conn->error);
            return false;
        }
        $stmt->close();
        $conn->close();
        
        return true;
    }
    
    static function removeFile(string $fileID): bool
    {
        $conn = HelperFunctions::createConnectionToDB();
        if (!isset($conn)) {
            return false;
        }
        
        // Add to user file table
        $query = "DELETE FROM ".SQL::FILE_TABLE." WHERE file_id = ?";
        
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            error_msg("Bad SQL Syntax: ". $conn->error);
            return false;
        }
        
        $stmt->bind_param("s", $fileID);
        
        if (!$stmt->execute()) {
            error_msg("Failed to remove file: ". $conn->error);
            return false;
        }
        $stmt->close();
        $conn->close();
        
        return true;
    }
    
    static function addOneTimeURL(OneTimeURL $url): bool
    {
        $conn = HelperFunctions::createConnectionToDB();
        if (!isset($conn)) {
            return false;
        }
        
        Database::createOneTimeURLTable($conn, false);
        
        $query = "REPLACE INTO ".SQL::ONETIME_URL_TABLE
            ." (url_id, url_name, url_owner, file_id, url_password, use_count, use_limit)"
            ." VALUES (?, ?, ?, ?, ?, ?, ?)";
            
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            error_msg("Bad SQL Syntax: ". $conn->error);
            return false;
        }
        
        $stmt->bind_param("sssssii",
            $url->URLID,
            $url->URLName,
            $url->URLOwner,
            $url->FileID,
            $url->HashedURLPassword,
            $url->UseCount,
            $url->UseLimit);
        
        if (!$stmt->execute()) {
            error_msg("Failed to add URL: ". $conn->error);
            return false;
        }
        $stmt->close();
        $conn->close();
        
        return true;
    }
    
    static function removeOneTimeURL(string $urlID): bool
    {
        $conn = HelperFunctions::createConnectionToDB();
        if (!isset($conn)) {
            return false;
        }
        
        $query = "DELETE FROM ".SQL::ONETIME_URL_TABLE." WHERE url_id = ?";
        
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            error_msg("Bad SQL Syntax: ". $conn->error);
            return false;
        }
        
        $stmt->bind_param("s", $urlID);
        
        if (!$stmt->execute()) {
            error_msg("Failed to remove URL: ". $conn->error);
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
        
        $sql = "CREATE TABLE IF NOT EXISTS ".SQL::USERS_TABLE." (
            user_id VARCHAR(256) PRIMARY KEY,
            user_name VARCHAR(256) NOT NULL,
            password VARCHAR(256) NOT NULL
            )";
        
        if (!$conn->query($sql)) {
            error_msg("Error creating user table " . $conn->error);
            return false;
        }
        
        return true;
    }
    
    static function createFileTable($conn, bool $clearExistingTable): bool
    {
        if ($clearExistingTable) {
            $conn->query("DROP TABLE ".SQL::FILE_TABLE);
        }
        
        $sql = "CREATE TABLE IF NOT EXISTS ".SQL::FILE_TABLE." (
            file_id VARCHAR(256) NOT NULL,
            file_owner VARCHAR(256) NOT NULL,
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
    
    static function createOneTimeURLTable($conn, bool $clearExistingTable): bool
    {
        if ($clearExistingTable) {
            $conn->query("DROP TABLE ".SQL::ONETIME_URL_TABLE);
        }
        
        $sql = "CREATE TABLE IF NOT EXISTS ".SQL::ONETIME_URL_TABLE." (
            url_id VARCHAR(256) NOT NULL,
            url_name VARCHAR(256) NOT NULL,
            url_owner VARCHAR(256) NOT NULL,
            file_id VARCHAR(256) NOT NULL,
            url_password VARCHAR(256) NOT NULL,
            use_count INT(6) DEFAULT 0,
            use_limit INT(6) DEFAULT 1,
            PRIMARY KEY (url_id),
            FOREIGN KEY (url_owner) REFERENCES ".SQL::USERS_TABLE."(user_id),
            FOREIGN KEY (file_id) REFERENCES ".SQL::FILE_TABLE."(file_id)
            )";
        
        if (!$conn->query($sql)) {
            error_msg("Failed to create URL Table: " . $conn->error);
            return false;
        }
        
        return true;
    }
    
    static function createDatabase($conn, bool $clearExistingDatabase): bool
    {
        if ($clearExistingDatabase){
            $conn->query("DROP DATABASE ".SQL::DATABASE);
        }
        
        $sql = "CREATE DATABASE IF NOT EXISTS ".SQL::DATABASE;
        
        if (!$conn->query($sql)) {
            error_msg("Failed to create database: ". $conn->error);
            return false;
        }
        
        return true;
    }
}
?>