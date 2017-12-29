<?php 
require_once dirname(__DIR__) . '/../header.php';

class SQL
{
    // MySQL
    const SERVERNAME = "localhost";
    const USERNAME = "root";
    const PASSWORD = "";
    
    const DATABASE = "mansisaksson_webpage_db";
    const FILE_TABLE = "files";
    const ONETIME_URL_TABLE = "onetime_urls";
    const USERS_TABLE = "users";
    const PERMISSIONS_TABLE = "permission_groups";
}
?>