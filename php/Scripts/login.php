<?php
require_once dirname(__DIR__).'/../header.php';
require_once FP_PHP_DIR . 'Core/HelperFunctions.php';

class LoginResponse
{
    public $success = false;
    public $errorMessage = "";

    function __construct(bool $success, String $errorMessage = "")
    {
        $this->success = $success;
        $this->errorMessage = $errorMessage;
    }
}

//Get form information
$username = $_POST['username'];
$password = $_POST['password'];

// Retreive hashed password/validate existance of user
$user = User::getUser($username, true);
if (!isset($user)) {
    echo json_encode(new LoginResponse(false, "Invalid User"));
    return;
}

// Validate password
if ($user->ValidatePassword($password) !== true) {
    echo json_encode(new LoginResponse(true));
    return;
}

// Create user session
if (HelperFunctions::createNewUserSession($user)) {
    echo json_encode(new LoginResponse(true));
    return;
}
else {
    echo json_encode(new LoginResponse(false, "Failed to create user session"));
    return;
}
?>