<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR . 'helper_functions.php';

// Check user permissions
if (!HelperFunctions::hasAuthority()) {
    echo ("Insufficient permissions");
    return;
}

$db_query = "tables=".SQL::FILE_TABLE . "&return=".HelperFunctions::getReturnAddr();
?>
<form action="scripts/create_database.php?<?php echo $db_query ?>" method="post" enctype="multipart/form-data">
	<input type="submit" class="button" value="Clear Files" name="GenerateDB">
</form>

<?php
printFiles();

function printFiles()
{
    $conn = HelperFunctions::createConnectionToDB();
    if (!isset($conn)) {
        echo "Failed to establish connection to DB";
        return;
    }
    
    ?>
    <style>
        table#filesTable {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
        
        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        
        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>

    <table style="width:100%" id = "filesTable">
      <tr>
      	<th>Download</th>
        <th>id</th>
        <th>Name</th>
        <th>Description</th>
        <th>Download Count</th>
        <th>Download Limit</th>
      </tr>
    <?php
    
    $sql = "SELECT * FROM ".SQL::FILE_TABLE;
    $result = $conn->query($sql);
        
    if ($result !== false && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            ?><tr>
			<th>
            <form action="<?php echo RP_SCRIPTS_DIR; ?>download_file.php">
            	<button type="submit" value="<?php echo $row["id"]; ?>" name="fileID">Download</button>
            </form>
            </th><?php
            echo "<th>" . $row["id"] . "</th>";
            echo "<th>" . $row["file_name"] . "</th>";
            echo "<th>" . $row["description"] . "</th>";
            echo "<th>" . $row["download_count"] . "</th>";
            echo "<th>" . $row["download_limit"] . "</th>";
            ?></tr><?php
        }
    } 
    else {
        echo "0 results";
    }
    $conn->close();
    
    ?></table><?php 
}
?>