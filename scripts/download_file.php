<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR.'globals.php';

if (!isset($_GET["fileID"])) {
    die("No File specified");
}

$fileID = $_GET["fileID"];
if (!file_exists(FP_UPLOADS_DIR.$fileID)) {
    die("That file does not exist");
}

$conn = HelperFunctions::createConnectionToDB();
if (!isset($conn)) {
    die ("Failed to establish connection to database");
}

$sql_query = "SELECT * FROM ".SQL::FILE_TABLE." WHERE id=?";
$stmt = $conn->prepare($sql_query);
if (!$stmt) {
    die ("Invalid SQL statement". $conn->error);
}

$stmt->bind_param("s", $fileID);
if (!$stmt->execute()) {
    die("Failed to extract file from database". $conn->error);
}

$result = $stmt->get_result();
$stmt->close();
$conn->close();

if ($result === false || $result->num_rows <= 0) {
    die ("0 results");
}

while($row = $result->fetch_assoc()) 
{
    $fullname = FP_UPLOADS_DIR.$fileID;
    if (!file_exists($fullname)) {
        echo "Could not find file: ".$fullname;
        continue;
    }
           
    $fd = fopen($fullname, "rb");
    if ($fd) 
    {
        $fsize = filesize($fullname);
        $path_parts = pathinfo($fullname);
        $ext = strtolower($path_parts["extension"]);
        switch ($ext)
        {
            case "pdf":
                header("Content-type: application/pdf");
                break;
            case "zip":
                header("Content-type: application/zip");
                break;
            default:
                header("Content-type: application/octet-stream");
                break;
        }
        
        header("Content-Disposition: attachment; filename=\"".$row["file_name"]."\"");
        header("Content-length: $fsize");
        header("Cache-control: private"); //use this to open files directly
        while(!feof($fd)) 
        {
            $buffer = fread($fd, 1*(1024*1024));
            echo $buffer;
            ob_flush();
            flush();    //These two flush commands seem to have helped with performance
        }
    }

    fclose($fd);
}
?>