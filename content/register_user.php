<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR . 'helper_functions.php';
?>

<form action="scripts/register_user.php?return=<?php echo HelperFunctions::getReturnAddr(); ?>" method="post" enctype="multipart/form-data">
	Username: <br>
	<input type="text" name="username"> <br>
	Password: <br>
	<input type="password" name="password"> <br>
	Confirm Password: <br>
	<input type="password" name="confirm_password"> <br>
	<input type="submit" name="submit"> <br>
</form>