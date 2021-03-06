<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_PHP_DIR . 'Core/Globals.php';

?>
<link rel="stylesheet" type="text/css" href="<?php echo RP_CSS_DIR; ?>style_top_nav_menu.css" media="screen">
<script type="text/javascript" src="<?php echo RP_JS_DIR; ?>login.js"></script>

<nav id="top-nav">
	<ul id="menu">
		<?php
		$user = Session::getUser();
		if (isset($user))
		{
		    ?>
		    <li><a href="index.php?content=edit_user_form.php&user_id=<?php echo $user->UserID; ?>" class="no_drop">User</a></li>
			<li><a href="index.php?content=file_overview.php" class="no_drop">Files</a></li>
			<li><a href="index.php?content=files_overview.php" class="no_drop">All Files</a></li>
			<li><a href="#logoutButton" class="no_drop" >Logout</a></li>
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