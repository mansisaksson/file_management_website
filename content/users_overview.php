<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR . 'helper_functions.php';
?>

<?php 
$db_query = "tables=".SQL::USERS_TABLE. "&return=".HelperFunctions::getReturnAddr();

$session = Session::getInstance();
if ($session->UserName() !== null)
{
    echo "Current User: ".$session->UserName()."<br>";
}
?>
<form action="scripts/create_database.php?<?php echo $db_query ?>" method="post" enctype="multipart/form-data">
	<input type="submit" class="button" value="Clear Users" name="GenerateDB">
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
    <th>id</th>
    <th>Name</th>
    <th>Password</th>
  </tr>
<?php
$conn = HelperFunctions::createConnectionToDB();
if (isset($conn))
{
    $sql = "SELECT * FROM ".SQL::USERS_TABLE;
    $result = $conn->query($sql);
    
    if ($result !== false && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) 
        {
            ?><tr><?php
            echo "<th>" . $row["id"] . "</th>";
            echo "<th>" . $row["user_name"] . "</th>";
            echo "<th>" . $row["password"] . "</th>";
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