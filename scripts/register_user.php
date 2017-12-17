<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR.'globals.php';

//Get form information
$username = $_POST['username'];
$password = $_POST['password'];
$password_conf = $_POST['confirm_password'];
$registration_key = $_POST['registration_key'];

if ($registration_key !== "t@aU3UEE2b3b3&8Z") {
    echo "Invalid Registration Key";
    return;
}

// Check password validity
if ($password !== $password_conf){
    die ("Password missmatch");
}

//Check so that the username is not already taken
$user = Database::getUser($username, true);
if (isset($userID)){
    die ("Username already taken");
}

// Insert into DB
if (!Database::addUser(uniqid(), $username, $password)) {
    die ("Failed to create user ".$username);
}

if (HelperFunctions::createNewUserSession($username)) {
    header("Location: ".RP_MAIN_DIR."index.php?content=user_overview.php");
}
else {
    echo "Failed to create user session";
    return;
}
//HelperFunctions::goToRetPage();
?>