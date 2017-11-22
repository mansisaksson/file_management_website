<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR . 'helper_functions.php';
?>

<form action="scripts/create_database.php?return=<?php echo HelperFunctions::getReturnAddr() ?>" method="post" enctype="multipart/form-data">
	Regenerate Database:
	<input type="submit" class="button" value="Clear DB" name="GenerateDB">
</form>

<style>
table {
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

<table style="width:100%">
  <tr>
    <th>Download</th>
    <th>id</th>
    <th>Name</th>
    <th>Description</th>
    <th>Download Count</th>
    <th>Download Limit</th>
  </tr>
<?php
$conn = HelperFunctions::createConnectionToFileTable();
if (isset($conn))
{
    $sql = "SELECT * FROM ".Globals::SQL_FILE_TABLE;
    $result = $conn->query($sql);
    
    if ($result !== false && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) 
        {
            ?><tr>
            <th>
            <form action="<?php echo RP_SCRIPTS_DIR; ?>download_file.php">
            	<button type="submit" value="<?php echo $row["id"]; ?>" name="fileID">Download</button>
            </form>
            </th> <?php
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
}
?>

</table>