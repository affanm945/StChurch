<?php

function OpenCon()
{

///define('DB_HOST', 'localhost');        // Your database host, usually "localhost"
///define('DB_NAME', 'db_oxon');          // Your database name (same as created in cPanel)
///define('DB_USER', 'keg9jw7857vj');     // Your database username (set during cPanel database creation)
//define('DB_PASS', '5evenEmi@2023');    // Your database password (set during cPanel database creation)


$dbhost = "localhost";
$dbport = 3307;  // MySQL port (changed from default 3306)
$dbuser = "root";
$dbname = "my_church_db";
$dbpass = "";

$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname, $dbport) or die("Connect failed: %s\n". $conn -> error);
return $conn;
}

function CloseCon($conn)
{
$conn -> close();
}

?>