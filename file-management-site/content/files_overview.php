<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_PHP_DIR . 'Core/HelperFunctions.php';

// Check user permissions
if (!HelperFunctions::isUserLoggedIn()) {
    echo ("Insufficient permissions");
    return;
}

$searchQuerry = "";
if (isset($_POST['search_query'])) {
    $searchQuerry = $_POST['search_query'];
}

?>
<form action=<?php echo RP_MAIN_DIR."index.php?content=files_overview.php" ?> method="post" enctype="multipart/form-data">
    <input class="js-copytextarea" name="search_query" value=<?php echo $searchQuerry; ?>>
    <button type="submit">Search</button>
</form>
<?php

printFiles($searchQuerry);

function printFiles($searchQuery)
{
    $files = UserFile::findFiles($searchQuery);
    if (count($files) <= 0) {
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
      <tr>
      	<th>Download</th>
      	<th>URL</th>
        <th>Name</th>
        <th>Type</th>
        <th>Description</th>
        <th>Password Protected</th>
        <th>Public</th>
        <th>Download Count</th>
      </tr>
    <?php
    
    foreach ($files as &$file)
    {
        // For now we do not list non public files
        if ($file->IsPublic || $file->FileOwner === Session::getUser()->UserID)
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
            </tr>
            <?php
        }        
    }
    
    ?></table><?php 
}
?>
