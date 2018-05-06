<?php
require_once "header.php";

$fileWebsite = true;
if ($fileWebsite)
{
    require_once FP_FMSITE_DIR."index.php";
}
else {
    require_once FP_PTSITE_DIR."index.php";
}
?>