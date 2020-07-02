<?php
//Get URL parameters
$dateTime = $_GET["datetime"];
$ph = $_GET["ph"];          //pH
$do = $_GET["do"];          //Dissolved Oxygen Level
$temp = $_GET["temp"];      //Temperature
$level = $_GET["level"];    //Water Level
$error = $_GET["error"];    //"false" = no errors; anything else = error
//Write to JSON file
$arr = array('dateTime' => $dateTime, 'message' => $error, 'pH' => $ph, 'do' => $do, 'temp' => $temp, 'level' => $level);
$file = fopen("../readings.json", "w");
if (!$file)
{
    $s = "Failed to write to readings.json";
    error_log($s."\n",3,'../log/error_log.txt');
    die(time()." ".$s);
}
else
{
    fwrite($file, json_encode($arr, JSON_UNESCAPED_UNICODE));
    fclose($file);
    die(time()." Live reading uploaded.");
}