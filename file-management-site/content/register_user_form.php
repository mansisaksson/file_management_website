<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_PHP_DIR . 'Core/HelperFunctions.php';
?>

<form method="post" enctype="multipart/form-data" id="user_info_form">
	Username: <br>
	<input type="text" name="username"> <br>
	Password: <br>
	<input type="password" name="password"> <br>
	Confirm Password: <br>
	<input type="password" name="confirm_password"> <br>
	Registration Key: <br>
	<input type="text" name="registration_key"> <br>
</form>
<button id="registerButton">Register</button>

<script type="text/javascript" src="<?php echo RP_JS_DIR; ?>register_user.js"></script>