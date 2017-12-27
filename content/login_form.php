<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_PHP_DIR . 'Core/Globals.php';

if (HelperFunctions::isUserLoggedIn()) {
    header("Location: ".RP_MAIN_DIR."index.php?content=users_overview.php");
}
?>

<form action="<?php echo RP_PHP_DIR."Scripts/login.php?return=".HelperFunctions::getReturnAddr(); ?>" method="post" enctype="multipart/form-data">
	Username: <br>
	<input type="text" name="username"> <br>
	Password: <br>
	<input type="password" name="password"> <br>
	<input type="submit" name="submit"> <br>
</form>