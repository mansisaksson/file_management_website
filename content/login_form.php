<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR . 'globals.php';

$session = Session::getInstance();
if ($session->UserName() !== null)
{
    echo "logged in!";
    header("Location: ".RP_MAIN_DIR."index.php?content=users_overview.php");
}
?>

<form action="scripts/login.php?return=<?php echo HelperFunctions::getReturnAddr(); ?>" method="post" enctype="multipart/form-data">
	Username: <br>
	<input type="text" name="username"> <br>
	Password: <br>
	<input type="password" name="password"> <br>
	<input type="submit" name="submit"> <br>
</form>