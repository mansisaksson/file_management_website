<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_PHP_DIR . 'helper_functions.php';

// Check user permissions
if (!HelperFunctions::isUserLoggedIn()) {
    echo ("Insufficient permissions");
    return;
}

$searchQuerry = "";
if (isset($_POST['search_query'])) {
    $searchQuerry = $_POST['search_query'];
}

$db_query = "tables=".SQL::GLOBAL_FILE_TABLE . "&return=".HelperFunctions::getReturnAddr();
?>
<form action="scripts/create_database.php?<?php echo $db_query ?>" method="post" enctype="multipart/form-data">
	<input type="submit" class="button" value="Clear Files" name="GenerateDB">
</form>
<br>

<form action=<?php echo RP_MAIN_DIR."index.php?content=files_overview.php" ?> method="post" enctype="multipart/form-data">
    <input class="js-copytextarea" name="search_query" value=<?php echo $searchQuerry; ?>>
    <button type="submit">Search</button>
</form>
<?php

printFiles($searchQuerry);

function printFiles($searchQuery)
{
    // TODO: Should not do direct acces to the DB here, better to create some form of helper function in UserFile.php
    $conn = HelperFunctions::createConnectionToDB();
    if (!isset($conn)) {
        echo "Failed to establish connection to DB";
        return;
    }
    
    $esc_query = "%".$conn->escape_string($searchQuery)."%";
    $stmt = $conn->prepare("SELECT * FROM ".SQL::GLOBAL_FILE_TABLE." WHERE file_name LIKE ?");
    if (!$stmt) {
        echo "Faild to locate file table";
        return;
    }
    $stmt->bind_param('s', $esc_query);
    if (!$stmt->execute()) {
        echo "File Seach Error: ".$conn->error."<br>";
        return;
    }
    
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    
    if ($result === false || $result->num_rows <= 0){
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
        <th>Owner</th>
      </tr>
    <?php
    
    while($row = $result->fetch_assoc()) 
    {
        $user = User::getUser($row["file_owner"]);
        ?>
        <tr>
		<th>
			<form action="<?php echo RP_PHP_DIR; ?>download_file.php">
            	<button type="submit" value="<?php echo $row["id"]; ?>" name="fileID">Download</button>
            </form>
        </th>
        
        <th>
       		<input class="js-copytextarea" value =<?php echo HelperFunctions::getDownloadURL($row["id"]); ?>>
        </th>
        <?php
        echo "<th>" . $row["file_name"] . "</th>";
        echo "<th>" . $user->UserName . "</th>";
        ?></tr><?php
    }
    ?></table><?php 
}
?>
