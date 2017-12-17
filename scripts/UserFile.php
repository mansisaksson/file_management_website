<?php 
class UserFile
{
    public $FileOwner = "";
    public $FileID = "";
    public $FileName = "";
    public $FileType = "";
    public $FileDescription = "";
    public $IsPublic = false;
    public $DownloadCount = 0;
    
    private $HashedFilePassword = "";
    
    function __construct($fileOwner, $fileID, $fileName, $fileType, $fileDescription, $isPublic, $downloadCount, $hashedFilePassword)
    {
        $this->FileOwner = $fileOwner;
        $this->FileID = $fileID;
        $this->FileName = $fileName;
        $this->FileType = $fileType;
        $this->FileDescription = $fileDescription;
        $this->IsPublic = $isPublic;
        $this->DownloadCount = $downloadCount;
        $this->HashedFilePassword = $hashedFilePassword;
    }
    
    public function ValidatePassword($password)
    {
        return password_verify($password, $this->HashedFilePassword);
    }
    
    public function IsPasswordProdected()
    {
        return $this->HashedFilePassword !== "";
    }
    
    public function getFullName()
    {
        return $this->FileName.".".$this->FileType;
    }
    
    public static function getFile($fileID)
    {
        // *** Find File Owner
        $conn = HelperFunctions::createConnectionToDB();
        if (!isset($conn)) {
            echo "Failed to establish connection to database";
            return null;
        }
        
        $sql_query = "SELECT * FROM ".SQL::GLOBAL_FILE_TABLE." WHERE id = ?";
        $stmt = $conn->prepare($sql_query);
        if (!$stmt) {
            echo "Invalid SQL statement". $conn->error;
            return null;
        }
        
        $stmt->bind_param("s", $fileID);
        if (!$stmt->execute()) {
            echo "Failed to extract file from database". $conn->error;
            return null;
        }
        
        $result = $stmt->get_result();
        $stmt->close();
        
        if ($result === false || $result->num_rows <= 0) {
            echo "0 results";
            return null;
        }
        
        $row = $result->fetch_assoc();
        $fileOwner = $row["file_owner"];
        
        
        // *** Get the file information
        $stmt = $conn->prepare("SELECT * FROM ".SQL::USER_FILES_TABLE.$fileOwner." WHERE file_id = ?");
        if (!$stmt) {
            echo "Could not find user files. <br>";
            return null;
        }
        
        $stmt->bind_param('s', $fileID);
        if (!$stmt->execute()) {
            echo "File Seach Error: ".$stmt->error."<br>";
            return null;
        }
        
        $result = $stmt->get_result();
        $stmt->close();
        $conn->close();
        
        if ($result === false || $result->num_rows <= 0){
            echo "0 Results";
            return null;
        }
        
        $row = $result->fetch_assoc();
        return new UserFile(
            $fileOwner,
            $row["file_id"],
            $row["file_name"],
            $row["file_type"],
            $row["file_description"],
            $row["public"],
            $row["download_count"],
            $row["file_password"]);
    }
    
    public static function getUserFiles($userID, $searchQuery)
    {
        $conn = HelperFunctions::createConnectionToDB();
        if (!isset($conn)) {
            echo "Failed to establish connection to DB";
            return null;
        }
        
        $esc_query = "%".$conn->escape_string($searchQuery)."%";
        $stmt = $conn->prepare("SELECT * FROM ".SQL::USER_FILES_TABLE.$userID." WHERE file_name LIKE ?");
        if (!$stmt) {
            echo "Could not find user files. <br>";
            return null;
        }
        
        $stmt->bind_param('s', $esc_query);
        if (!$stmt->execute()) {
            echo "File Seach Error: ".$stmt->error."<br>";
            return null;
        }
        
        $result = $stmt->get_result();
        $stmt->close();
        $conn->close();
        
        if ($result === false || $result->num_rows <= 0){
            echo "0 Results";
            return null;
        }
        
        
        $files = array($result->num_rows);
        $count = 0;
        while($row = $result->fetch_assoc())
        {
            $file = new UserFile(
                $userID,
                $row["file_id"],
                $row["file_name"],
                $row["file_type"],
                $row["file_description"],
                $row["public"] === 1 ? true : false,
                $row["download_count"],
                $row["file_password"]);
            
            $files[$count] = $file;
            $count++;
        }
        
        return $files; 
    }
}
?>