<?php
require_once "header.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Nothing here</title>
<link rel="stylesheet" type="text/css" href="./CSS/style_index.css"
	media="screen">
</head>

<body>
	<header id="top-bar">
		<div id="top-menu"> 
			<?php require "content/login_dropdown.html"; ?> 
		</div>
	</header>

	<header id="top-header">
		<!-- Nothing Here -->
	</header>

	<?php require_once FP_CONTENT_DIR."top_nav_menu.html"; ?>

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
    		    require FP_CONTENT_DIR."upload_file_form.php"; // TODO: actual front page
    		}
    		?>
    	</div>
	</section>

	<?php require "content/footer.html"; ?>
	
	<div id="background-image"></div>
	<div id="background-stripes"></div>
</body>
</html>