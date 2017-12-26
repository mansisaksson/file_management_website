<?php 
require_once dirname(__DIR__).'/header.php';

class UserFile
{
    public $FileOwner = "";
    public $FileID = "";
    public $FileName = "";
    public $FileType = "";
    public $FileDescription = "";
    public $IsPublic = false;
    public $DownloadCount = 0;
    
    public $HashedFilePassword = "";
    
    function __construct(
        string $fileOwner,
        string $fileID,
        string $fileName,
        string $fileType,
        string $fileDescription,
        bool $isPublic,
        int $downloadCount,
        string $hashedFilePassword)
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
    
    public function saveFileToDB(): bool
    {
        return Database::addUserFile($this);
    }
    
    public static function createNewFile(
        string $fileOwner,
        string $fileName,
        string $fileDescription,
        bool $isPublic,
        string $password): ?UserFile
    {
        $fileID = uniqid();
        $file_type = pathinfo($fileName, PATHINFO_EXTENSION);
        $file_name = pathinfo($fileName, PATHINFO_FILENAME);
        $file = new UserFile($fileOwner, $fileID, $file_name, $file_type, $fileDescription, $isPublic, 0, "");
        $file->setPassword($password);
        
        return $file;
    }
    
    public static function getFile($fileID)
    {
        $conn = HelperFunctions::createConnectionToDB();
        if (!isset($conn)) {
            return null;
        }
        
        $stmt = $conn->prepare("SELECT * FROM ".SQL::FILE_TABLE." WHERE file_id = ?");
        if (!$stmt) {
            error_msg($conn->error);
            return null;
        }
        
        $stmt->bind_param('s', $fileID);
        if (!$stmt->execute()) {
            error_msg("File Seach Error: ".$conn->error);
            return null;
        }
        
        $result = $stmt->get_result();
        $stmt->close();
        $conn->close();
        
        if ($result === false || $result->num_rows <= 0){
            return null;
        }
        
        $row = $result->fetch_assoc();
        return new UserFile(
            $row["file_owner"],
            $row["file_id"],
            $row["file_name"],
            $row["file_type"],
            $row["file_description"],
            $row["public"],
            $row["download_count"],
            $row["file_password"]);
    }
    
    public static function findFiles($searchQuery, $file_owner = ""): ? array
    {
        $conn = HelperFunctions::createConnectionToDB();
        if (!isset($conn)) {
            return null;
        }
        
        
        $query_owner = $file_owner !== "";
        $esc_query = "%".$conn->escape_string($searchQuery)."%";
        
        if ($query_owner) {
            $stmt = $conn->prepare("SELECT * FROM ".SQL::FILE_TABLE."
                                    WHERE file_owner = ? 
                                    AND file_name LIKE ?");
        }
        else {
            $stmt = $conn->prepare("SELECT * FROM ".SQL::FILE_TABLE."
                                    WHERE file_name LIKE ?");
        }
        
        if (!$stmt) {
            error_msg($conn->error);
            return null;
        }
        
        if ($query_owner) {
            $stmt->bind_param('ss', $file_owner, $esc_query);
        }
        else {
            $stmt->bind_param('s', $esc_query);
        }
        
        if (!$stmt->execute()) {
            error_msg("File Seach Error: ".$conn->error);
            return null;
        }
        
        $result = $stmt->get_result();
        $stmt->close();
        $conn->close();
        
        if ($result === false || $result->num_rows <= 0){
            return null;
        }
        
        $files = array($result->num_rows);
        $count = 0;
        while($row = $result->fetch_assoc())
        {
            $file = new UserFile(
                $row["file_owner"],
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