<?php
include_once 'Mysql.php';
$servername = "localhost";
function connect()
{
	global $servername;
	global $user;
	global $passwd;
	global $db;

    $connection = new mysqli($servername,$user,$passwd,$db);
    if ($connection->connect_error)
    {
        $s = "Failed to connect to the database: ".$connection->connect_error;
        error_log($s."\n",3,'../log/error_log.txt');
        die(time()." ".$s);
    }
    else
    {
        return $connection;
    }
}