<?php
require_once dirname(__DIR__).'/header.php';
require_once FP_SCRIPTS_DIR . 'session.php';
require_once FP_SCRIPTS_DIR . 'helper_functions.php';

class SQL
{
    // MySQL
    const SERVERNAME = "localhost";
    const USERNAME = "root";
    const PASSWORD = "";
    
    const DATABASE = "mi_ws_db";
    const FILE_TABLE = "files";
    const USERS_TABLE = "users";
}
?>
