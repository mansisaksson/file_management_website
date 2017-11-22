<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR.'globals.php';

if (isset($_GET["fileID"]))
{
    $fileID = $_GET["fileID"];
    
    // Create connection
    $conn = HelperFunctions::createConnectionToFileTable();
    if (isset($conn))
    {
        if (!file_exists(FP_UPLOADS_DIR.$fileID)) {
            die("That file does not exist");
        }
        
        $sql = "SELECT * FROM ".Globals::SQL_FILE_TABLE." WHERE id='".$fileID."'";
        $result = $conn->query($sql);
        
        if ($result !== false && $result->num_rows > 0) 
        {
            while($row = $result->fetch_assoc()) 
            {
                $file_url = FP_UPLOADS_DIR.$fileID;//$_SERVER['SERVER_NAME'].$dir_filesDir."/".$fileID;
                if (file_exists($file_url))
                {
                    header("Content-Type: application/octet-stream");
                    header("Content-Transfer-Encoding: Binary");
                    header("Content-disposition: attachment; filename=\"".$row["file_name"]."\"");
                    readfile($file_url);
                }
                else 
                    die ("Could not find ".$file_url);
            }
        } 
        else {
            echo "0 results";
        }
        $conn->close();
    }
}
else
    die("No File specified");
?>