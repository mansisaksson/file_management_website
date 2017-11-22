<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR.'globals.php';

// Create connection
$conn = new mysqli(Globals::SQL_SERVERNAME, Globals::SQL_USERNAME, Globals::SQL_PASSWORD);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "DROP DATABASE ".Globals::SQL_FILE_DATABASE;
if ($conn->query($sql) !== TRUE) {
    echo "Error deleing database: " . $conn->error;
}

// Create database
$sql = "CREATE DATABASE ".Globals::SQL_FILE_DATABASE;
if ($conn->query($sql) !== TRUE) {
    endScript("Error creating database: " . $conn->error, $conn);
}

// Open Database
$sql = "USE ".Globals::SQL_FILE_DATABASE;
if ($conn->query($sql) !== TRUE) {
    endScript("Error creating table: " . $conn->error, $conn);
}

// Create Tables
$sql = "CREATE TABLE ".Globals::SQL_FILE_TABLE." (
    id VARCHAR(256) PRIMARY KEY,
    file_name VARCHAR(256) NOT NULL,
    description TEXT NOT NULL,
    download_count INT(6) DEFAULT 0,
    download_limit INT(6) DEFAULT -1
)";

if ($conn->query($sql) === TRUE) {
    endScript("Error creating table: " . $conn->error, $conn);
}

endScript("", $conn);

function endScript($error, $sqlConnection)
{
    if ($error === "")
    {
        HelperFunctions::GoToRetPage();
    }
    else
    {
        echo $error;
    }
    
    $sqlConnection->close();
}
?>
