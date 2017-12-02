<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR.'globals.php';

// Check user permissions
if (!HelperFunctions::hasAuthority()) {
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
if ($tablesID === SQL::FILE_TABLE || $tablesID === "all")
{
    $conn->query("DROP TABLE ".SQL::FILE_TABLE);
    $sql = "CREATE TABLE ".SQL::FILE_TABLE." (
        id VARCHAR(256) PRIMARY KEY,
        file_name VARCHAR(256) NOT NULL,
        description TEXT NOT NULL,
        download_count INT(6) DEFAULT 0,
        download_limit INT(6) DEFAULT -1
    )";
    
    if ($conn->query($sql) !== TRUE) {
        die("Error creating file table " . $conn->error);
    }
}

// Create User Table
if ($tablesID === SQL::USERS_TABLE || $tablesID === "all")
{
    $conn->query("DROP TABLE ".SQL::USERS_TABLE);
    $sql = "CREATE TABLE ".SQL::USERS_TABLE." (
        id VARCHAR(256) PRIMARY KEY,
        user_name VARCHAR(256) NOT NULL,
        password VARCHAR(256) NOT NULL
    )";
    
    if ($conn->query($sql) !== TRUE) {
        die("Error creating user table " . $conn->error);
    }
}

$conn->close();
HelperFunctions::goToRetPage();
?>
