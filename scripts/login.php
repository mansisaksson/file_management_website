<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR . 'helper_functions.php';

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
if (HelperFunctions::createNewUserSession($username)) {
    header("Location: ".RP_MAIN_DIR."index.php?content=user_overview.php");
}
else {
    echo "Failed to create user session";
    return;
}
?>