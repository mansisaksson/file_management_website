<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_PHP_DIR . 'Core/Globals.php';

if (HelperFunctions::isUserLoggedIn()) {
    header("Location: ".RP_MAIN_DIR."index.php?content=users_overview.php");
}
?>

<form method="post" enctype="multipart/form-data" id="user_info_form">
	Username: <br>
	<input type="text" name="username"> <br>
	Password: <br>
	<input type="password" name="password"> <br>
</form>
<button id="loginButton">Login</button>
<p id="php_return"></p>

<script type="text/javascript" src="<?php echo RP_JS_DIR; ?>login.js"></script>
