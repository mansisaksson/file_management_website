<?php
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR . 'Core/HelperFunctions.php';

//Get form information
$username = $_POST['username'];
$password = $_POST['password'];

// Retreive hashed password/validate existance of user
$user = User::getUser($username, true);
if (!isset($user)) {
    exit_script("Invalid User", false);
}

// Validate password
if ($user->ValidatePassword($password) !== true) {
    exit_script("Invalid Password", false);
}

// Create user session
if (!HelperFunctions::createNewUserSession($user)) {
    exit_script("Failed to create user session", false);
}

exit_script("Login Successfull");
?>