<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR . 'helper_functions.php';

//Get form information
$username = $_POST['username'];
$password = $_POST['password'];

// Retreive hashed password/validate existance of user
$userID = Database::getUserID($username);
if (!isset($userID)) {
    echo "Invalid User";
    return;
}

// Validate password
$hashed_password = Database::getUserPassword($userID);
if (!password_verify($password, $hashed_password)) {
    echo "Invalid Password";
    return;
}

// Create user session
if (createNewUserSession($username)) {
    header("Location: ".RP_MAIN_DIR."index.php?content=users_overview.php");
}
else {
    echo "Failed to create user session";
    return;
}

function createNewUserSession($username)
{
    $conn = HelperFunctions::createConnectionToDB();
    $esc_username = $conn->escape_string($username);
    
    // Update our session
    $stmt = $conn->prepare("SELECT id FROM ".SQL::USERS_TABLE." WHERE user_name = ?");
    $stmt->bind_param('s', $esc_username);
    $stmt->execute();
    
    $id = $stmt->get_result();
    $stmt->close();
    $conn->close();
    
    if (!isset($id)){
        echo ("Could not retrive user data from database");
        return false;
    }
    
    $session = Session::createNewSession();
    $session->SetUserName($esc_username);
    $session->SetUserID($id);
    return true;
}
?>