<?php
include_once 'AquaponicsDB.php';

$db = new AquaponicsDB();
$arrReadings = $db->get_values();     // from AquaponicsDB.php
if ($arrReadings > 0) {
    $lastEl = array_values(array_slice($arrReadings, -1))[0];
    echo json_encode($lastEl, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode("Fail");
}

//THIS ONE IS USED FOR THE GAUGES, FOR NOW IT'S NOT GETTING ONLY THE LATEST, BUT ALL ORDERED BY TIME (not a good practice?),
//NEED TO MAKE A NEW METHOD IN DB CLASS FOR THIS TO GET ONLY ONE ROW OR GET THE LIVE DATA FROM RETRIEVELIVE.PHP
