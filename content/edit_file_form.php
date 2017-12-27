<?php 
require_once dirname(__DIR__).'/header.php';

if (!isset($_GET["fileID"])) {
    echo "No File Selected";
    return;
}

$fileID = $_GET["fileID"];
$userFile = UserFile::getFile($fileID);

// Check user permissions
if (!HelperFunctions::isUserLoggedIn($userFile->FileOwner)) {
    echo "Insufficient permissions";
    return;
}
?>

<form enctype="multipart/form-data" method="post" name="fileForm">
	<input type="hidden" id="file_id" name="file_id" value="<?php echo $fileID; ?>">
	File Name: <br>
	<input type="text" name="file_name" value="<?php echo $userFile->FileName; ?>"> <br><br>
	
	<textarea name="file_description" rows="4" cols="50"><?php echo $userFile->FileDescription; ?></textarea><br><br>
	
	<input type="checkbox" name="change_password" id="change_password" onclick="togglePasswordFormEnabled();">
    <label>Change Password</label> <br>
	
	New Password: <br>
	<input type="password" name="new_password" id="new_password" disabled> <br>
	Confirm Password: <br>
	<input type="password" name="password_confirm" id="password_confirm" disabled> <br><br>
	    
    <input type="checkbox" name="isPublic" <?php echo $userFile->IsPublic ? "checked" : ""; ?>>
    <label>Public</label> <br><br>
</form>

<input type="button" id="apply" value="Apply" /> <br><br>
<input type="button" id="delete" value="Delete File" /> 

<p id="editReturn"></p>

<script type="text/javascript" src="<?php echo RP_JS_DIR; ?>edit_file.js"></script>
