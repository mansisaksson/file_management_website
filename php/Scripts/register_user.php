<?php
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR.'Core/Globals.php';

//Get form information
$username = $_POST['username'];
$password = $_POST['password'];
$password_conf = $_POST['confirm_password'];
$registration_key = $_POST['registration_key'];

if ($registration_key !== "t@aU3UEE2b3b3&8Z") {
    error_msg("Invalid Registration Key");
    return;
}

// Check password validity
if ($password !== $password_conf) {
    echo "Password missmatch";
    return;
}

// Create the user
$user = User::createNewUser(uniqid(), $username, $password);
if (!isset($user)) {
    error_msg("Failed to create user ".$username);
    return;
}

if (HelperFunctions::createNewUserSession($user)) {
    header("Location: ".RP_MAIN_DIR."index.php?content=file_overview.php");
}
else {
    error_msg("Failed to create user session");
    return;
}
?>