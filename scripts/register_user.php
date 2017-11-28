<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR.'helper_functions.php';

session_start();

$conn = HelperFunctions::createConnectionToDB();
if (!isset($conn)){
    die ("Faild to establish connection to Database");
}

//Get form information
// TODO: validate this information
$username = $conn->escape_string($_POST['username']);
$password = $conn->escape_string($_POST['password']);
$password_conf = $conn->escape_string($_POST['confirm_password']);

// Check password validity
if ($password !== $password_conf){
    die ("Password missmatch");
}

//Check so that the username is not already taken
$stmt = $conn->prepare("SELECT * FROM ".SQL::USERS_TABLE." WHERE user_name = ?");
if ($stmt === false){
    die ("Faild to open user table: ". $conn->error);
}
$stmt->bind_param('s', $username);
$stmt->execute();

$result = $stmt->get_result();
$stmt->close();
if (isset($result) && $result->num_rows !== 0) {
    die ("User already exists");
}

// Insert into DB
$userID = uniqid();
$hashed_password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12)); // use this -> password_verify($password, $hash)
$stmt = $conn->prepare("INSERT INTO ".SQL::USERS_TABLE." VALUES(?, ?, ?)");
$stmt->bind_param('sss', $userID, $username, $hashed_password);
if (!$stmt->execute()){
    die ("faild to add user");
}
$stmt->close();
$conn->close();

HelperFunctions::updateUserSession($username);
HelperFunctions::goToRetPage();
?>