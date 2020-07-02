<?php
include_once 'AquaponicsDB.php';

$db = new AquaponicsDB();
$sDate = $_GET['startdate'];
$eDate = $_GET['enddate'];
$arrReadings = $db->get_history($sDate, $eDate);     // from AquaponicsDB.php
if ($arrReadings > 0) {
    echo json_encode($arrReadings, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode("Fail");
}

//THIS ONE I USED FOR DRAWING THE LINE GRAPH
