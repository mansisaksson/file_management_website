<?php
require_once dirname(__DIR__) . '/../header.php';
require_once FP_PHP_DIR . 'Core/Database.php';

class User
{
    public $UserName = "";
    public $UserID = "";
    public $HashedUserPassword = "";
    
    function __construct(string $userID, string $userName, string $hashedUserPassword)
    {
        $this->UserName = $userName;
        $this->UserID = $userID;
        $this->HashedUserPassword = $hashedUserPassword;
    }
    
    public function isValidUser(): bool
    {
        $user = User::getUser($this->UserID);
        return isset($user);
    }
    
    public function setUserName($userName): bool
    {
        if ($userName === "") {
            log_msg("User Name cannot be empty");
            return false;
        }
        
        if (User::doesUserExist($userName)) {
            log_msg("Username Already exists");
            return false;
        }
        
        $this->UserName = $userName;
        return true;
    }
    
    public function setPassword($password): bool
    {
        if ($userName === "") {
            log_msg("Password cannot be empty");
            return false;
        }
        
        $this->HashedUserPassword = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));
        return true;
    }
    
    public function ValidatePassword($password)
    {
        return password_verify($password, $this->HashedUserPassword);
    }
    
    public function saveUserToDB(): bool
    {
        return Database::addUser($this);
    }
    
    public function deleteUser(): bool
    {
        return Database::removeUser($this->UserID);
    }
    
    public static function doesUserExist($userName)
    {
        //TODO: this is pretty slow, could do this a lot better
        $user = User::getUser($userName, true);
        return isset($user);
    }
    
    public static function createNewUser(
        string $userID,
        string $userName,
        string $password): ?User
    {
        if ($userName === "") {
            log_msg("Username cannot be empty");
            return null;
        }
        
        $existingUser = User::getUser($userID);
        if (isset($existingUser)) {
            error_msg("Duplicate user IDs");
            return null;
        }
        
        $existingUser = User::getUser($userName, true);
        if (isset($existingUser)) {
            error_msg("Username already exists");
            return null;
        }
        
        $user = new User($userID, $userName, "");
        $user->setPassword($password);
        if (!$user->saveUserToDB()) {
            error_msg("Failed to add user to Database");
            return null;
        }
        return $user;
    }
    
    public static function getUser($UserID, $useNameAsId = false): ?User
    {
        $conn = HelperFunctions::createConnectionToDB();
        if (!isset($conn)) {
            return null;
        }
        
        $searchColumn = $useNameAsId ? "user_name" : "user_id";
        $stmt = $conn->prepare("SELECT * FROM ".SQL::USERS_TABLE." WHERE ".$searchColumn." = ?");       
        if (!$stmt) {
            error_msg("Ivalid SQL statment: " . $conn->error);
            return null;
        }
        
        $stmt->bind_param('s', $UserID);
        if (!$stmt->execute()) {
            error_msg("Failed to get user info: " . $conn->error);
            return null;
        }
        
        $result = $stmt->get_result();
        $stmt->close();
        $conn->close();
        
        if ($result === false || $result->num_rows <= 0) {
            return null;
        }
        
        $row = $result->fetch_assoc();
        
        $id = $row["user_id"];
        $userName = $row["user_name"];
        $userPassword = $row["password"];
        
        return new User($id, $userName, $userPassword);
    }
}
?>