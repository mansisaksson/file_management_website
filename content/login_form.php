<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_PHP_DIR . 'globals.php';

if (HelperFunctions::isUserLoggedIn()) {
    header("Location: ".RP_MAIN_DIR."index.php?content=users_overview.php");
}
?>

<form action="<?php echo RP_PHP_DIR; ?>login.php?return=<?php echo HelperFunctions::getReturnAddr(); ?>" method="post" enctype="multipart/form-data">
	Username: <br>
	<input type="text" name="username"> <br>
	Password: <br>
	<input type="password" name="password"> <br>
	<input type="submit" name="submit"> <br>
</form>