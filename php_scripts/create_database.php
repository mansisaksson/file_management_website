<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_PHP_DIR.'globals.php';

// Check user permissions
if (!HelperFunctions::isUserLoggedIn()) {
    die ("Insufficient permissions");
}

if (!isset($_GET['tables'])){
    die ("No tables selected");
}

$tablesID = $_GET['tables'];

// Create connection
$conn = new mysqli(SQL::SERVERNAME, SQL::USERNAME, SQL::PASSWORD);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Open Database
$sql = "USE ".SQL::DATABASE;
if ($conn->query($sql) !== TRUE) {
    echo "Could not find database, creating... </br>";
 
    // Create database
    $sql = "CREATE DATABASE ".SQL::DATABASE;
    if ($conn->query($sql) !== TRUE) {
        die("Error creating database: " . $conn->error);
    }
    
    $conn->query("USE ".SQL::DATABASE);
}

// Create File Table
if ($tablesID === SQL::GLOBAL_FILE_TABLE || $tablesID === "all")
{
    $conn = HelperFunctions::createConnectionToDB();
    if (!isset($conn)) {
        return false;
    }
    
    Database::createGlobalFileTable($conn, true);
    $conn->close();
}

// Create User Table
if ($tablesID === SQL::USERS_TABLE || $tablesID === "all")
{
    $conn = HelperFunctions::createConnectionToDB();
    if (!isset($conn)) {
        return false;
    }
    
    Database::createUserTable($conn, true);
    $conn->close();
}

$conn->close();
HelperFunctions::goToRetPage();
?>
