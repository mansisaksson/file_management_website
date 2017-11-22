<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR.'helper_functions.php';
?>

<form action="<?php echo RP_SCRIPTS_DIR; ?>upload_file.php?return=<?php echo HelperFunctions::getReturnAddr(); ?>" method="post" enctype="multipart/form-data">
	Select image to upload:
	<input type="file" name="fileToUpload" id="fileToUpload">
	<input type="submit" value="Upload File" name="submit">
</form>