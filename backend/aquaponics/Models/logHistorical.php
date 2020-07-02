<?php
include_once '../Config/dbconnect.php';
$tbl = "readings";
//Get URL parameters
$dateTime = $_GET["datetime"];
$ph = $_GET["ph"];          //pH
$do = $_GET["do"];          //Dissolved Oxygen Level
$temp = $_GET["temp"];      //Temperature
$level = $_GET["level"];    //Water Level
//Write to database
$conn = connect();
$insertquery = 'INSERT INTO '.$tbl.' VALUES ('.$level.','.$temp.','.$ph.','.$do.','.$dateTime.', 1)';
if (!$connection->query($insertquery))
{
    $s = "Failed to insert the data: ".$connection->error;
    error_log($s."\n",3,'../log/error_log.txt');
    $connection->close();
    die(time()." ".$s);
}
$connection->close();
die(time()." Historical data logged.");