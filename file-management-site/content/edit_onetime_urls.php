<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_PHP_DIR.'Core/OneTimeURL.php';

if (!isset($_GET["fileID"])) {
    echo "No File Selected";
    return;
}

$fileID = $_GET["fileID"];
?>
<script type="text/javascript" src="<?php echo RP_JS_DIR; ?>edit_url.js"></script>

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
	<input type="text" id="url_name" name="url_name" value=""> <br>
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
      	<th>Download URL</th>
      	<th>Use Name</th>
        <th>Use Count</th>
        <th>Use Limit</th>
        <th></th>
      </tr>
    <?php
    foreach ($fileURLs as &$url) 
    {
        ?>
        <tr id = "content">
        <th><input class="js-copytextarea" value =<?php echo HelperFunctions::getDownloadURL($url->URLID); ?>></th>
        <?php 
        echo "<th id=\"name_". $url->URLID ."\" name=\"".$url->URLName."\">" . $url->URLName . "</th>";
        echo "<th>" . $url->UseCount . "</th>";
        echo "<th>" . $url->UseLimit . "</th>";
        ?>
        <th>
        	<button type="submit" class="deleteURL" value="<?php echo $url->URLID; ?>">X</button>
        	<button type="submit" class="renewURL" value="<?php echo $url->URLID; ?>">Renew</button>
        </th>
        </tr><?php
    }
    ?></table><?php 
}