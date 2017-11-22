<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR . 'helper_functions.php';

class Globals
{
    // MySQL
    const SQL_SERVERNAME = "localhost";
    const SQL_USERNAME = "root";
    const SQL_PASSWORD = "";
    
    const SQL_FILE_DATABASE = "filemanagmentdb";
    const SQL_FILE_TABLE = "files";
}
?>
