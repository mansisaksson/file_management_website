<?php
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR . 'Core/HelperFunctions.php';

//Get form information
$username = $_POST['username'];
$password = $_POST['password'];

// Retreive hashed password/validate existance of user
$user = User::getUser($username, true);
if (!isset($user)) {
    echo "Invalid User";
    return;
}

// Validate password
if ($user->ValidatePassword($password) !== true) {
    echo "Invalid Password";
    return;
}

// Create user session
if (HelperFunctions::createNewUserSession($user)) {
    header("Location: ".RP_MAIN_DIR."index.php?content=file_overview.php");
}
else {
    echo "Failed to create user session";
    return;
}
?>