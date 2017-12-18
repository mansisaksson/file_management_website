<?php 
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR . 'globals.php';

// Check user permissions
if (!HelperFunctions::hasAuthority()) {
    echo "Insufficient permissions";
    return;
}

if (!isset($_GET["fileID"])) {
    echo "No File Selected";
    return;
}

$fileID = $_GET["fileID"];

$userFile = UserFile::getFile($fileID);
?>

<form action="<?php echo RP_SCRIPTS_DIR."edit_file.php"; ?>" method="post" enctype="multipart/form-data">
	File Name: <br>
	<input type="text" name="file_name" value="<?php echo $userFile->FileName ?>"> <br><br>
	
	<textarea rows="4" cols="50"><?php echo $userFile->FileDescription; ?></textarea><br><br>
	
	<input type="checkbox" name="change_password" id="change_password" onclick="togglePasswordFormEnabled();">
    <label>Change Password</label> <br>
	
	New Password: <br>
	<input type="password" name="new_password" id="new_password" disabled> <br>
	Confirm Password: <br>
	<input type="password" name="password_confirm" id="password_confirm" disabled> <br><br>
	    
    <input type="checkbox" name="isPublic" <?php echo $userFile->IsPublic ? "checked" : "" ?>>
    <label>Public</label> <br><br>
	
	<input type="submit" name="submit"> <br>
</form>

<script>
function togglePasswordFormEnabled()
{
	var enabled = document.getElementById('change_password').checked;
	var passwordInput = document.getElementById("new_password");
	var confPasswordInput = document.getElementById("password_confirm");

	passwordInput.disabled = !enabled;
	confPasswordInput.disabled = !enabled;
}
</script>
