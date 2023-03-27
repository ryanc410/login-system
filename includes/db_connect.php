<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'DATABASE_USER_NAME');
define('DB_PASSWORD', 'DATABASE_USER_PASSWORD');
define('DB_NAME', 'DATABASE_NAME');

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>
