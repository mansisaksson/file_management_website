<?php 
class OneTimeURL
{
    public $URLID = "";
    public $FileID = "";
    public $URLOwner = "";
    public $UseCount = "";
    public $UseLimit = "";
    
    function __construct(
        string $urlID,
        string $fileID,
        string $urlOwner,
        int $useCount,
        int $useLimit)
    {
        $this->urlID = $urlID;
        $this->fileID = $fileID;
        $this->URLOwner = $urlOwner;
        $this->useCount= $useCount;
        $this->useLimit = $useLimit;
    }
    
    public function saveURLToDB(): bool
    {
        // TODO
        return true;//Database::addOneTimeURL($this);
    }
    
    public function deleteURL(): bool
    {
        // TODO
        return true;//Database::removeOneTimeURL($this->urlID);
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
            $row["file_id"],
            $row["url_owner"],
            $row["use_count"],
            $row["use_limit"]);
    }
    
    public static function findURLs($url_owner): array
    {
        $urls = array();
        
        $conn = HelperFunctions::createConnectionToDB();
        if (!isset($conn)) {
            return $urls;
        }
        
        $stmt = $conn->prepare("SELECT * FROM ".SQL::ONETIME_URL_TABLE."WHERE url_owner = ?");

        if (!$stmt) {
            error_msg($conn->error);
            return $urls;
        }
        
        $stmt->bind_param('s', $url_owner);
        
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
                $row["file_id"],
                $row["url_owner"],
                $row["use_count"],
                $row["use_limit"]);
            
            $files[$count] = $file;
            $count++;
        }
        
        return $files;
    }
}
?>