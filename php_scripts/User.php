<?php 
class User
{
    public $UserName = "";
    public $UserID = "";
    
    private $HashedUserPassword = "";
    
    function __construct($userID, $userName, $hashedUserPassword)
    {
        $this->UserName = $userName;
        $this->UserID = $userID;
        $this->HashedUserPassword = $hashedUserPassword;
    }
    
    public function ValidatePassword($password)
    {
        return password_verify($password, $this->HashedUserPassword);
    }
    
    static function getUser($UserID, $useNameAsId = false)
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