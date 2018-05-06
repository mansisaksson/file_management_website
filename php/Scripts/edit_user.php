<?php 
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR . 'Core/Globals.php';

$userID = isset($_POST["user_id"]) ? $_POST["user_id"] : "";

$user = User::getUser($userID);
if (!isset($user)) {
    exit_script("Could not find user", 500);
}

if (!HelperFunctions::isUserLoggedIn($user->UserID)) {
    exit_script("Insufficient permissions", 401);
}

$newName = isset($_POST["user_name"]) ? $_POST["user_name"] : "";
$changePassword = isset($_POST["change_password"]);
$newPassword = isset($_POST["new_password"]) ? $_POST["new_password"] : "";
$newPassword_conf = isset($_POST["password_confirm"]) ? $_POST["password_confirm"] : "";

// Update file
if (!$user->setUserName($newName)) {
    exit_script("Invalid User Name: ", 500);
}

if ($changePassword === true) {
    if ($newPassword !== $newPassword_conf) {
        exit_script("Pasword Missmatch", 500);
    }
    
    $user->setPassword($newPassword);
}

if (!$user->saveUserToDB()) {
    exit_script("Failed to save user changes", 500);
}

Session::setUser($user);

exit_script("User updated successfully!");
?>