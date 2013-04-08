<?php
$dbName = 'onlinetest';
$host = 'localhost';
$username = 'root';
$password = '';

//Users Host Name
$userHostName = 'http://localhost/projects/onlinetest';

//Users Host Name
$adminHostName = 'http://localhost/projects/onlinetest/admin';

$connection = mysql_connect($host, $username, $password) or die('Could not connect to database');
mysql_select_db($dbName, $connection);

session_start();

?>