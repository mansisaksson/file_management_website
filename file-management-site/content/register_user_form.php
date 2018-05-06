<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_PHP_DIR . 'Core/HelperFunctions.php';
?>

<form action="<?php echo RP_PHP_DIR."Scripts/register_user.php?return=".HelperFunctions::getReturnAddr(); ?>" method="post" enctype="multipart/form-data">
	Username: <br>
	<input type="text" name="username"> <br>
	Password: <br>
	<input type="password" name="password"> <br>
	Confirm Password: <br>
	<input type="password" name="confirm_password"> <br>
	Registration Key: <br>
	<input type="text" name="registration_key"> <br>
	<input type="submit" name="submit"> <br>
</form>