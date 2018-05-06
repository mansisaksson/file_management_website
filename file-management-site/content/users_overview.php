<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_PHP_DIR . 'Core/HelperFunctions.php';

return; // TODO: FIX THIS PAGE

$user = Session::getUser();
if (!isset($user)) {
    echo "No user logged in"."<br>";
    return;
}

echo "Current User: ".$user->UserName."<br>";

$db_query = "tables=".SQL::USERS_TABLE. "&return=".HelperFunctions::getReturnAddr();
?>
<form action="scripts/create_database.php?<?php echo $db_query ?>" method="post" enctype="multipart/form-data">
	<input type="submit" class="button" value="Clear Users" name="GenerateDB">
</form>
<?php
printUsers();

function printUsers()
{
    $conn = HelperFunctions::createConnectionToDB();
    if (!isset($conn)) {
        echo "Failed to establish connection to DB";
        return;
    }
    
    ?>
    <style>
        table#usersTable {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
        
        #usersTable td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        
        #usersTable tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>

    <table style="width:100%" id = usersTable>
      <tr>
        <th>id</th>
        <th>Name</th>
        <th>Password</th>
      </tr> 
    <?php
    
    $sql = "SELECT * FROM ".SQL::USERS_TABLE;
    $result = $conn->query($sql);
    
    if ($result !== false && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
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
    
    ?></table><?php 
}
?>