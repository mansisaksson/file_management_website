<?php
require_once "header.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Nothing here</title>
<link rel="stylesheet" type="text/css" href="./CSS/style_index.css" media="screen">
<script data-main="js/main" src="js/lib/require.js"></script>
</head>

<body>
	<?php require FP_CONTENT_DIR."scrolling_header.php"; ?> 

	<header id="top-header">
		<!-- Nothing Here -->
	</header>

	<?php require FP_CONTENT_DIR."top_nav_menu.php"; ?>

	<section id="container">
		<div id="content">
    		<?php
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

	<?php require FP_CONTENT_DIR."footer.html"; ?>
	
	<div id="background-stripes"></div>
</body>
</html>