<?php
require_once dirname(__DIR__).'/file-management-site/header.php';
require_once FP_PHP_DIR . 'Core/Globals.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Mans Website</title>
<link rel="stylesheet" type="text/css" href="<?php echo RP_CSS_DIR; ?>style_index.css" media="screen">
<script data-main="<?php echo RP_JS_DIR; ?>main" src="<?php echo RP_JS_DIR; ?>lib/require.js"></script>
</head>

<style>
h3#user_name {
    color: #fff;
    padding: 10px;
    font-size: 150%;
}
</style>

<body>
	<?php require FP_CONTENT_DIR."scrolling_header.php"; ?> 

	<header id="top-header">
		<h3 id=user_name>
    		<?php
    		$user = Session::getUser();
    		if (isset($user)) {
    		    echo "User: ".$user->UserName."<br>";
    		    echo "<br>";
    		}
    		?>
		</h3>
	</header>

	<?php require FP_CONTENT_DIR."top_nav_menu.php"; ?>

	<section id="container">
		<div id="content">
    		<?php
			//phpinfo();
    		$contentID = "content";
    		if (isset($_GET[$contentID]))
    		{
    		    $content = $_GET[$contentID]; 
    		    $filename = FP_CONTENT_DIR.$content;
    		    if (file_exists($filename)) {
    		        require $filename; 
    		    }
    		    else {
    		        echo "Could not find that";
    		    }
    		}
    		else
    		{
    		    ?>
    		    <p>Best webbsite in da world</p>
    		    <?php
    		    //require FP_CONTENT_DIR."upload_file_form.php"; // TODO: actual front page
    		}
    		?>
    	</div>
	</section>

	<?php require FP_CONTENT_DIR."footer.php"; ?>
	
	<div id="background-stripes"></div>
</body>
</html>