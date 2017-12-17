<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR . 'helper_functions.php';

$session = Session::getInstance();
if ($session->UserName() === null) {
    echo ("No User Logged In");
    return;
}

echo "User Name: ".$session->UserName()."<br>";
echo "User ID: ".$session->UserID()."<br>";
echo "<br>";

?>
<form action="" method="post" enctype="multipart/form-data">
	<input type="submit" class="button" value="Delete User" name="delete_user">
</form>

<?php
echo "My Files <br>";
printUserFiles($session->UserID(), "");

function printUserFiles($userID, $searchQuery)
{
    $files = UserFile::getUserFiles($userID, $searchQuery);
    if (!isset($files)){
        echo "No Files Found";
        return;
    }
    ?>
    <style>
        table#filesTable {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
        
        #filesTable th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 4px;
        }
        
        tr#header {
            font-size: 16px;
            font-weight: bold;
        }
        
        tr#content {
            font-size: 12px;
            font-weight: normal;
        }
        
        #filesTable tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>

    <table style="width:100%" id = "filesTable">
      <tr id = "header"> 
      	<th>Download</th>
      	<th>URL</th>
        <th>Name</th>
        <th>Type</th>
        <!-- <th>Description</th> -->
        <th>Password Protected</th>
        <th>Public</th>
        <th>Download Count</th>
      </tr>
    <?php
    
    foreach ($files as &$file) 
    {
        ?>
        <tr id = "content">
    		<th>
    			<form action="<?php echo RP_SCRIPTS_DIR; ?>download_file.php">
                	<button type="submit" value="<?php echo $file->FileID; ?>" name="fileID">Download</button>
                </form>
            </th>
            
            <th>
           		<input class="js-copytextarea" value =<?php echo HelperFunctions::getDownloadURL($file->FileID); ?>>
            </th>
            <?php
            $hasPassword = $file->IsPasswordProdected() ? "true" : "false";
            $isPublic = $file->IsPublic === true ? "true" : "false";
            
            echo "<th>" . $file->FileName . "</th>";
            echo "<th>" . $file->FileType . "</th>";
            //echo "<th>" . $row["file_description"] . "</th>";
            echo "<th>" . $hasPassword . "</th>";
            echo "<th>" . $isPublic . "</th>";
            echo "<th>" . $file->DownloadCount . "</th>";
        ?></tr><?php
    }
    ?></table><?php 
}
?>