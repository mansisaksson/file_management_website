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
    $conn = HelperFunctions::createConnectionToDB();
    if (!isset($conn)) {
        echo "Failed to establish connection to DB";
        return;
    }
    
    $esc_query = "%".$conn->escape_string($searchQuery)."%";
    $stmt = $conn->prepare("SELECT * FROM ".SQL::USER_FILES_TABLE.$userID." WHERE file_name LIKE ?");
    if (!$stmt) {
        echo "Could not find user files. <br>";
        return;
    }
    
    $stmt->bind_param('s', $esc_query);
    if (!$stmt->execute()) {
        echo "File Seach Error: ".$stmt->error."<br>";
        return;
    }
    
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    
    if ($result === false || $result->num_rows <= 0){
        echo "0 Results";
        return;
    }
    
    ?>
    <style>
        table#filesTable {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
        
        #filesTable td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        
        #filesTable tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>

    <table style="width:100%" id = "filesTable">
      <tr>
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
    
    while($row = $result->fetch_assoc()) 
    {
        $fileID = $row["file_id"];
        ?>
        <tr>
		<th>
			<form action="<?php echo RP_SCRIPTS_DIR; ?>download_file.php">
            	<button type="submit" value="<?php echo $fileID; ?>" name="fileID">Download</button>
            </form>
        </th>
        
        <th>
       		<input class="js-copytextarea" value =<?php echo HelperFunctions::getDownloadURL($fileID); ?>>
        </th>
        <?php
        $hasPassword = $row["file_password"] === "" ? "false" : "true";
        $isPublic = $row["public"] === 0 ? "false" : "true";
        
        echo "<th>" . $row["file_name"] . "</th>";
        echo "<th>" . $row["file_type"] . "</th>";
        //echo "<th>" . $row["file_description"] . "</th>";
        echo "<th>" . $hasPassword . "</th>";
        echo "<th>" . $isPublic . "</th>";
        echo "<th>" . $row["download_count"] . "</th>";
        ?></tr><?php
    }
    ?></table><?php 
}
?>