<?php
date_default_timezone_set("	Africa/Harare");
header("Cache-Control: no-cache");
header("Content-Type: text/event-stream");

function sendMessage($message)
{
    echo 'event: liveReceived\n';
    //echo 'data: '.json_encode($readingsInit, JSON_FORCE_OBJECT);
    echo 'data: '.$message;
    echo '\n\n';
    ob_end_flush();
    flush();
}

$jsonFileInit = file_get_contents('../readings.json');
//$readingsInit = json_decode($jsonFile, true);
sendMessage($message);

while (true)
{
    $jsonFile = file_get_contents('../readings.json');
    if ($jsonFile == $jsonFileInit)
    {
        $jsonFileInit = $jsonFile;
        sendMessage($jsonFile);
    }
    sleep(10); // rechecks file every 10 seconds
}