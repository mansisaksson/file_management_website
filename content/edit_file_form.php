<?php 
require_once dirname(__DIR__).'/header.php';
require_once FP_PHP_DIR.'Core/OneTimeURL.php';
require_once FP_PHP_DIR.'Core/UserFile.php';

if (!isset($_GET["fileID"])) {
    echo "No File Selected";
    return;
}

$fileID = $_GET["fileID"];
$userFile = UserFile::getFile($fileID);
if (!isset($userFile)) {
    exit_script("Invalid file id", 500);
}

// Check user permissions
if (!HelperFunctions::isUserLoggedIn($userFile->FileOwner)) {
    exit_script("Insufficient permissions", 401);
}
?>

<h3>Manage File [<?php echo $userFile->getFullName(); ?>]</h3>
<hr>

<form enctype="multipart/form-data" method="post" name="fileForm">
	<input type="hidden" id="file_id" name="file_id" value="<?php echo $fileID; ?>">
	File Name: <br>
	<input type="text" name="file_name" value="<?php echo $userFile->FileName; ?>"> <br><br>
	Description: <br>
	<textarea name="file_description" rows="4" cols="50"><?php echo $userFile->FileDescription; ?></textarea><br><br>
	
	<input type="checkbox" name="change_password" id="change_password" onclick="togglePasswordFormEnabled();">
    <label>Change Password</label> <br>
	
	New Password: <br>
	<input type="password" name="new_password" id="new_password" disabled> <br>
	Confirm Password: <br>
	<input type="password" name="password_confirm" id="password_confirm" disabled> <br><br>
	    
    <input type="checkbox" name="isPublic" <?php echo $userFile->IsPublic ? "checked" : ""; ?>>
    <label>Public</label> <br>
</form>

<input type="button" id="apply" value="Apply Changes" /> <br><br>
<input type="button" id="delete" value="Delete File" /> 

<p id="php_return"></p>

<script type="text/javascript" src="<?php echo RP_JS_DIR; ?>edit_file.js"></script>

<h3>One Time URLs</h3>
<hr>

<?php 
printOneTimeURLs($userFile);

?>
<style>
div#addURLContainer {
    border-style: solid;
    border-width: 2px;
    padding: 5px;
    margin-top: 10px;
}
h4#URLHeaderText {
    margin: 0px;
    margin-bottom: 10px;  
}
</style>

<div id="addURLContainer">
<h4 id ="URLHeaderText">Add New URL</h4>
<form enctype="multipart/form-data" method="post" name="addURLForm">
	<input type="hidden" name="file_id" value="<?php echo $fileID; ?>">
	URL Name: <br>
	<input type="text" name="url_name" value="DEFAULT NAME"> <br>
	Limit: <br>
    <input type="text" name="url_limit" value="1">
</form>
<input type="button" id="addURL" value="Add URL" />
</div>
<?php

function printOneTimeURLs(UserFile $file)
{
    $fileURLs = OneTimeURL::findURLs($file->FileOwner, $file->FileID);
    if (count($fileURLs) <= 0){
        echo "No URLs Found";
        return;
    }
    ?>
    <style>
        table#filesTable {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
        
        #filesTable th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 4px;
        }
        
        tr#header {
            font-size: 16px;
            font-weight: bold;
        }
        
        tr#content {
            font-size: 12px;
            font-weight: normal;
        }
        
        #filesTable tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>

    <table style="width:100%" id = "filesTable">
      <tr id = "header"> 
      	<th>URL ID</th>
      	<th>Use Name</th>
        <th>Use Count</th>
        <th>Use Limit</th>
        <th>Delete</th>
      </tr>
    <?php
    foreach ($fileURLs as &$url) 
    {
        ?><tr id = "content"><?php 
        echo "<th>" . $url->URLID . "</th>";
        echo "<th>" . $url->URLName . "</th>";
        echo "<th>" . $url->UseCount . "</th>";
        echo "<th>" . $url->UseLimit . "</th>";
        ?>
        <th>
        	<button type="submit" class="deleteURL" value="<?php echo $url->URLID; ?>">X</button>
        </th>
        </tr><?php
    }
    ?></table><?php 
}
