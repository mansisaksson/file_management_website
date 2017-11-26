<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR.'helper_functions.php';
?>

<form enctype="multipart/form-data" id = "fileForm">
    <input type="file" name="fileToUpload" id="fileToUpload" />
    <input type="button" value="Upload" />
</form>
<progress value="0" max="100"></progress>

<p id="uploadReturn"></p>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script type="text/javascript" src="<?php RP_JS_DIR ?>js/upload_file.js"></script>
