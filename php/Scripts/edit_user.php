<?php 
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR . 'Core/Globals.php';

$userID = isset($_POST["user_id"]) ? $_POST["user_id"] : "";

$user = User::getUser($userID);
if (!isset($user)) {
    fatal_error("Could not find user", 500);
    return;
}

if (!HelperFunctions::isUserLoggedIn($user->UserID)) {
    fatal_error("Insufficient permissions", 401);
    return;
}

$newName = isset($_POST["user_name"]) ? $_POST["user_name"] : "";
$changePassword = isset($_POST["change_password"]);
$newPassword = isset($_POST["new_password"]) ? $_POST["new_password"] : "";
$newPassword_conf = isset($_POST["password_confirm"]) ? $_POST["password_confirm"] : "";

// Update file
if (!$user->setUserName($newName)) {
    fatal_error("Invalid User Name: ", 500);
    return;
}

if ($changePassword === true) {
    if ($newPassword !== $newPassword_conf) {
        fatal_error("Pasword Missmatch", 500);
        return;
    }
    
    $user->setPassword($newPassword);
}

if (!$user->saveUserToDB()) {
    fatal_error("Failed to save user changes", 500);
    return;
}

log_msg("User updated successfully!");
?>