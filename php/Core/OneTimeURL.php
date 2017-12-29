<?php
require_once dirname(__DIR__) . '/../header.php';
require_once FP_PHP_DIR . 'Core/Database.php';

class OneTimeURL
{
    public $URLID = "";
    public $URLName = "";
    public $URLOwner = "";
    public $FileID = "";
    public $HashedURLPassword = "";
    public $UseCount = "";
    public $UseLimit = "";
    
    function __construct(
        string $urlID,
        string $urlName,
        string $urlOwner,
        string $fileID,
        string $hashedPassword,
        int $useCount,
        int $useLimit)
    {
        $this->URLID = $urlID;
        $this->URLName = $urlName;
        $this->URLOwner = $urlOwner;
        $this->FileID = $fileID;
        $this->HashedURLPassword = $hashedPassword;
        $this->UseCount= $useCount;
        $this->UseLimit = $useLimit;
    }
    
    public function saveURLToDB(): bool
    {
        return Database::addOneTimeURL($this);
    }
    
    public function deleteURL(): bool
    {
        return Database::removeOneTimeURL($this->URLID);
    }
    
    public function setPassword($password)
    {
        if ($password !== "") {
            $this->HashedURLPassword = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));
        }
        else {
            $this->HashedURLPassword = "";
        }
    }
    
    public static function createNewURL(string $urlName, UserFile $file, int $limit): ?OneTimeURL
    {
        $fileID = uniqid();
        $url = new OneTimeURL($fileID, $urlName, $file->FileOwner, $file->FileID, "", 0, $limit);
        //$url->setPassword() // TODO: do password on this
        return $url;
    }
    
    public static function getURL($urlID)
    {
        $conn = HelperFunctions::createConnectionToDB();
        if (!isset($conn)) {
            return null;
        }
        
        $stmt = $conn->prepare("SELECT * FROM ".SQL::ONETIME_URL_TABLE." WHERE url_id = ?");
        if (!$stmt) {
            error_msg($conn->error);
            return null;
        }
        
        $stmt->bind_param('s', $urlID);
        if (!$stmt->execute()) {
            error_msg("Could not find URL: ".$conn->error);
            return null;
        }
        
        $result = $stmt->get_result();
        $stmt->close();
        $conn->close();
        
        if ($result === false || $result->num_rows <= 0){
            return null;
        }
        
        $row = $result->fetch_assoc();
        return new OneTimeURL(
            $row["url_id"],
            $row["url_name"],
            $row["url_owner"],
            $row["file_id"],
            $row["url_password"],
            $row["use_count"],
            $row["use_limit"]);
    }
    
    public static function findURLs($url_owner, $file_id = ""): array
    {
        $urls = array();
        
        $conn = HelperFunctions::createConnectionToDB();
        if (!isset($conn)) {
            return $urls;
        }
        
        $query_file_id = ($file_id !== "");
        if ($query_file_id) {
            $stmt = $conn->prepare("SELECT * FROM ".SQL::ONETIME_URL_TABLE.
                " WHERE url_owner = ?".
                " AND file_id = ?"
                );
        }
        else {
            $stmt = $conn->prepare("SELECT * FROM ".SQL::ONETIME_URL_TABLE.
                "WHERE url_owner = ?"
                );
        }

        if (!$stmt) {
            error_msg("Bad SQL Syntax: ".$conn->error);
            return $urls;
        }
        
        if ($query_file_id) {
            $stmt->bind_param('ss', $url_owner, $file_id);
        }
        else {
            $stmt->bind_param('s', $url_owner);
        }
        
        if (!$stmt->execute()) {
            error_msg("URL Seach Error: ".$conn->error);
            return $urls;
        }
        
        $result = $stmt->get_result();
        $stmt->close();
        $conn->close();
        
        if ($result === false || $result->num_rows <= 0){
            return $urls;
        }
        
        // TODO: Can you pre-allocate the required memory instead of pushing every time?
        $count = 0;
        while($row = $result->fetch_assoc())
        {
            $file = new OneTimeURL(
                $row["url_id"],
                $row["url_name"],
                $row["url_owner"],
                $row["file_id"],
                $row["url_password"],
                $row["use_count"],
                $row["use_limit"]);
            
            $files[$count] = $file;
            $count++;
        }
        
        return $files;
    }
}
?>