<?php
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR.'Core/Globals.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

//Get form information
$username = $_POST['username'];
$password = $_POST['password'];
$password_conf = $_POST['confirm_password'];
$registration_key = $_POST['registration_key'];

if ($registration_key !== "t@aU3UEE2b3b3&8Z") {
    exit_script("Invalid Registration Key", 400);
}

// Check password validity
if ($password !== $password_conf) {
    exit_script("Password missmatch", 400);
}

// Create the user
$user = User::createNewUser(uniqid(), $username, $password);
if (!isset($user)) {
    exit_script("Failed to create user ".$username, 500);
}

if (HelperFunctions::createNewUserSession($user)) {
    exit_script("User Added Successfully");
}
else {
    exit_script("Failed to create user session");
}
?>