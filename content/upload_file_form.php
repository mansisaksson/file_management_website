<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR.'helper_functions.php';

// Check user permissions
if (!HelperFunctions::hasAuthority()) {
    echo ("Insufficient permissions");
    return;
}
?>

<form enctype="multipart/form-data" method="post" name="fileForm">
    <input type="file" name="fileToUpload" /> <br><br>
    <label>File Password:</label><br>
    <input type="password" name="password" /> <br>
    <label>Confirm Password:</label><br>
    <input type="password" name="confirm_password" /> <br>
    
    <input type="checkbox" name="isPublic" value="0">
    <label>Public</label> <br><br>
</form>

<progress value="0" max="100"></progress> <br>
<input type="button" value="Upload" /> 

<p id="uploadReturn"></p>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script type="text/javascript" src="<?php RP_JS_DIR ?>js/upload_file.js"></script>
