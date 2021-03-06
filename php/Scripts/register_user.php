<?php
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR.'Core/Globals.php';

//Get form information
$username = $_POST['username'];
$password = $_POST['password'];
$password_conf = $_POST['confirm_password'];
$registration_key = $_POST['registration_key'];

$key = file_get_contents(FP_FMSITE_DIR.'/registration.key');

if ($registration_key !== $key) {
    exit_script("Invalid Registration Key", false);
}

// Check password validity
if ($password !== $password_conf) {
    exit_script("Password missmatch", false);
}

// Create the user
$user = User::createNewUser(uniqid(), $username, $password);
if (!isset($user)) {
    exit_script("Failed to create user ".$username, false);
}

if (HelperFunctions::createNewUserSession($user)) {
    exit_script("User Added Successfully");
}
else {
    exit_script("Failed to create user session", false);
}
?>