<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_PHP_DIR . 'Core/Globals.php';
?>

<link rel="stylesheet" type="text/css" href="./CSS/style_top_nav_menu.css" media="screen">

<nav id="top-nav">
	<ul id="menu">
		<?php
		$user = Session::getUser();
		if (isset($user))
		{
		    ?>
		    <li><a href="index.php?content=upload_file_form.php" class="no_drop">Upload File</a></li>
			<li><a href="index.php?content=files_overview.php" class="no_drop">All Files</a></li>
			<li><a href="index.php?content=user_overview.php" class="no_drop">User Overview</a></li>
			<li><a href="<?php echo RP_PHP_DIR."Scripts/logout.php"; ?>" class="no_drop">Logout</a></li>
		    <?php 
		}
		else
		{
		    $login_url = RP_MAIN_DIR."index.php?content=login_form.php";
		    $register_url = RP_MAIN_DIR."index.php?content=register_user_form.php";
		    ?>
		    <li><a href="<?php echo $register_url; ?>" class="no_drop">Register User</a></li>
		    <li><a href="<?php echo $login_url; ?>" class="no_drop">Login</a></li>
		    <?php 
		}
		?>
	</ul>
</nav>