<?php 
require_once dirname(__DIR__).'/header.php';

if (!isset($_GET["user_id"])) {
    echo "No User Selected";
    return;
}

$userID = $_GET["user_id"];

// Check user permissions
if (!HelperFunctions::isUserLoggedIn($userID)) {
    echo "Insufficient permissions";
    return;
}


$user = User::getUser($userID);

?>

<form enctype="multipart/form-data" method="post" name="userForm">
	<input type="hidden" id="user_id" name="user_id" value="<?php echo $user->UserID; ?>">
	User Name: <br>
	<input type="text" name="user_name" value="<?php echo $user->UserName; ?>"> <br><br>
	
	<input type="checkbox" name="change_password" id="change_password" onclick="togglePasswordFormEnabled();">
    <label>Change Password</label> <br>
	
	New Password: <br>
	<input type="password" name="new_password" id="new_password" disabled> <br>
	Confirm Password: <br>
	<input type="password" name="password_confirm" id="password_confirm" disabled> <br><br>
</form>

<input type="button" id="apply" value="Apply" /> <br><br>
<input type="button" id="delete" value="Delete User" />

<p id="php_return"></p>

<script type="text/javascript" src="<?php echo RP_JS_DIR; ?>edit_user.js"></script>