<?php 
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
        $user = User::getUser($this->userID);
        return isset($user);
    }
    
    public function setPassword($password)
    {
        if ($password !== "") {
            $this->HashedFilePassword = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));
        }
        else {
            $this->HashedFilePassword = "";
        }
    }
    
    public function ValidatePassword($password)
    {
        return password_verify($password, $this->HashedUserPassword);
    }
    
    public function saveUserToDB(): bool
    {
        return Database::addUser($this);
    }
    
    public static function createNewUser(
        string $userID,
        string $userName,
        string $password): ?User
    {
        $existingUser = User::getUser($userID);
        if (isset($existingUser)) {
            echo "Duplicate user IDs"."<br>";
            return null;
        }
        
        $existingUser = User::getUser($userName, true);
        if (isset($existingUser)) {
            echo "Username already exists"."<br>";
            return null;
        }
        
        $user = new User($userID, $userName, "");
        $user->setPassword($password);
        if (!$user->saveUserToDB()) {
            echo "Failed to add user to Database";
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
        
        $searchColumn = $useNameAsId ? "user_name" : "id";
        $stmt = $conn->prepare("SELECT * FROM ".SQL::USERS_TABLE." WHERE ".$searchColumn." = ?");
        if (!$stmt) {
            return null;
        }
        
        $stmt->bind_param('s', $UserID);
        if (!$stmt->execute()) {
            echo "Failed to get user info: ".$stmt->error."<br>";
            return null;
        }
        
        $result = $stmt->get_result();
        $stmt->close();
        $conn->close();
        
        if ($result === false || $result->num_rows <= 0){
            echo "Could not retrive user data from database. <br>";
            return null;
        }
        
        $row = $result->fetch_assoc();
        
        $id = $row["id"];
        $userName = $row["user_name"];
        $userPassword = $row["password"];
        
        return new User($id, $userName, $userPassword);
    }
}
?>