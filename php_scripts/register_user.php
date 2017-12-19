<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_PHP_DIR.'globals.php';

//Get form information
$username = $_POST['username'];
$password = $_POST['password'];
$password_conf = $_POST['confirm_password'];
$registration_key = $_POST['registration_key'];

if ($registration_key !== "t@aU3UEE2b3b3&8Z") {
    die ("Invalid Registration Key");
}

// Check password validity
if ($password !== $password_conf){
    die ("Password missmatch");
}

// Create the user
$user = User::createNewUser(uniqid(), $username, $password);
if (!isset($user)) {
    die ("Failed to create user ".$username);
}

if (HelperFunctions::createNewUserSession($user)) {
    header("Location: ".RP_MAIN_DIR."index.php?content=user_overview.php");
}
else {
    die ("Failed to create user session");
}
//HelperFunctions::goToRetPage();
?>