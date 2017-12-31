<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_PHP_DIR . 'Core/HelperFunctions.php';

$user = Session::getUser();
if (!isset($user)) {
    echo "No User Logged In";
    return;
}

$searchQuerry = "";
if (isset($_POST['search_query'])) {
    $searchQuerry = $_POST['search_query'];
}

echo "<h2> Upload File </h2>";
echo "<hr>";
require_once FP_CONTENT_DIR . 'upload_file_form.php';
?>
<h2>Files</h2>
<hr>

<!-- TODO: make this into its own thing
<form action="<?php echo RP_MAIN_DIR."index.php"; ?>" method="get">
	<input type="hidden" name="content" value="edit_user_form.php">
	<button type="submit" value="<?php echo $user->UserID; ?>" name="user_id">Edit User</button>
</form>
-->   
<br>
<form action=<?php echo RP_MAIN_DIR."index.php?content=file_overview.php" ?> method="post" enctype="multipart/form-data">
    <input class="js-copytextarea" name="search_query" value=<?php echo $searchQuerry; ?>>
    <button type="submit">Search</button>
</form>
<?php
printUserFiles($user->UserID, $searchQuerry);

function printUserFiles($userID, $searchQuery)
{
    $files = UserFile::findFiles($searchQuery, $userID);
    if (count($files) <= 0){
        echo "No Files Found";
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
      	<th>Download</th>
      	<th>URL</th>
        <th>Name</th>
        <th>Type</th>
        <th>Description</th>
        <th>Password Protected</th>
        <th>Public</th>
        <th>Download Count</th>
        <th>Edit</th>
      </tr>
    <?php
    
    foreach ($files as &$file) 
    {
        ?>
        <tr id = "content">
    		<th>
    			<form action="<?php echo RP_PHP_DIR."Scripts/download_file.php"; ?>" method="get">
                	<button type="submit" value="<?php echo $file->FileID; ?>" name="fileID">Download</button>
                </form>
            </th>
            
            <th><input class="js-copytextarea" value =<?php echo HelperFunctions::getDownloadURL($file->FileID); ?>></th>
            
            <?php
            $hasPassword = $file->IsPasswordProdected() ? "true" : "false";
            $isPublic = $file->IsPublic === true ? "true" : "false";
            
            echo "<th>" . $file->FileName . "</th>";
            echo "<th>" . $file->FileType . "</th>";
            echo "<th>" . $file->FileDescription . "</th>";
            echo "<th>" . $hasPassword . "</th>";
            echo "<th>" . $isPublic . "</th>";
            echo "<th>" . $file->DownloadCount . "</th>";
            ?>
            <th>
    			<form action="<?php echo RP_MAIN_DIR."index.php"; ?>" method="get">
    				<input type="hidden" name="content" value="edit_file_form.php">
                	<button type="submit" value="<?php echo $file->FileID; ?>" name="fileID">Edit</button>
                </form>
            </th>
        </tr>
        <?php
        
    }
    ?></table><?php 
}
?>