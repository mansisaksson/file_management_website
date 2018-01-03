<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_PHP_DIR . 'Core/HelperFunctions.php';

// Check user permissions
if (!HelperFunctions::isUserLoggedIn()) {
    echo "Insufficient permissions";
    return;
}
?>

<form enctype="multipart/form-data" method="post" name="fileForm">
    <input type="file" name="fileToUpload" /> <br><br>
    <label>File Password:</label><br>
    <input type="password" name="password" autocomplete="new-password" /> <br>
    <label>Confirm Password:</label><br>
    <input type="password" name="confirm_password" autocomplete="new-password" /> <br>
    
    <input type="checkbox" name="isPublic" value="0">
    <label>Public</label> <br><br>
</form>

<progress value="0" max="100"></progress> <br>
<input type="button" id=uploadButton value="Upload" /> 

<p id="php_return"></p>

<script type="text/javascript" src="<?php echo RP_JS_DIR; ?>upload_file.js"></script>
