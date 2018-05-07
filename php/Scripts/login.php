<?php
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR . 'Core/HelperFunctions.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

//Get form information
$username = $_POST['username'];
$password = $_POST['password'];

// Retreive hashed password/validate existance of user
$user = User::getUser($username, true);
if (!isset($user)) {
    exit_script("Invalid User", 400);
}

// Validate password
if ($user->ValidatePassword($password) !== true) {
    exit_script("Invalid Password", 401);
}

// Create user session
if (!HelperFunctions::createNewUserSession($user)) {
    exit_script("Failed to create user session", 500);
}

exit_script("Login Successfull");
?>