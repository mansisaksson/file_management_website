<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR.'globals.php';

$fileToUpload = "fileToUpload";

if(isset($_POST["submit"]) === false) {
    die ("No File specified");
}

$conn = HelperFunctions::createConnectionToFileTable();

if (isset($conn))
{
    $target_file = uniqid();
    
    if (tryUploadFile($fileToUpload, $target_file))
    {
        //TODO: This is dangerous, you could potentially have a dangerous filename/description, need to sanitize this
        /* //create a prepared statement
            if ($stmt = $mysqli->prepare("SELECT District FROM City WHERE Name=?")) {
            
                //bind parameters for markers
                $stmt->bind_param("s", $city);
            
                //execute query
                $stmt->execute();
            
                //bind result variables
                $stmt->bind_result($district);
            
                //fetch value
                $stmt->fetch();
            
                printf("%s is in district %s\n", $city, $district);
            
                close statement
                $stmt->close();
            } */
        $sql = "INSERT INTO ".Globals::SQL_FILE_TABLE." (id, file_name, description, download_count, download_limit)"
            ."VALUES ('".$target_file."'
            , '".basename($_FILES[$fileToUpload]["name"])."'
            , 'DEFAULT_DESCRIPTION'
            , '0'
            , '-1')";
        
        if ($conn->query($sql) !== TRUE)
            die("Failed to insert file into db: " . $conn->error);
        
        $conn->close();
        HelperFunctions::GoToRetPage();
    }
}

function tryUploadFile($fileToUpload, $target_file)
{
    // Check if file already exists
    if (file_exists(FP_UPLOADS_DIR . $target_file)) {
        echo "Sorry, file already exists." . "<br>";
        return false;
    }
    
    if ($_FILES["fileToUpload"]["size"] <= 0) {
        echo "Not a valid File" . "<br>";
        return false;
    }
    
    if (move_uploaded_file($_FILES[$fileToUpload]["tmp_name"], FP_UPLOADS_DIR.$target_file)) {
        echo "The file ". basename($_FILES[$fileToUpload]["name"]). " has been uploaded." . "<br>";
    }
    else {
        echo "Sorry, there was an error uploading your file." . "<br>";
        return false;
    }
    
    return true;
}
?>