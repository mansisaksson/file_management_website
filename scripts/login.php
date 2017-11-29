<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR . 'helper_functions.php';

//TODO: Validation
createNewUserSession($_POST['username']);
header("Location: ".RP_MAIN_DIR."index.php?content=users_overview.php");

function createNewUserSession($username)
{
    $conn = HelperFunctions::createConnectionToDB();
    
    // Update our session
    $stmt = $conn->prepare("SELECT id FROM ".SQL::USERS_TABLE." WHERE user_name = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    
    $id = $stmt->get_result();
    $stmt->close();
    $conn->close();
    
    if (!isset($id)){
        echo ("Could not retrive user data from database");
        return false;
    }
    
    $session = Session::createNewSession();
    $session->SetUserName($username);
    $session->SetUserID($id);
    return true;
}
?>