<?php
include_once 'AquaponicsDB.php';
$frequency = $_GET["frequency"];

class avgForDay
{
    public $time;
    public $ph;
    public $dis_oxygen;
    public $temperature;
    public $water_level;
    public $countReadings;

    public function __construct($time, $pH, $do, $temp, $level)
    {
        $this->time = $time;
        $this->ph = $pH;
        $this->dis_oxygen = $do;
        $this->temperature = $temp;
        $this->water_level = $level;
        $this->countReadings = 1;
    }

    public function finalize()
    {
        $this->ph /= $this->countReadings;
        $this->dis_oxygen /= $this->countReadings;
        $this->temperature /= $this->countReadings;
        $this->water_level /= $this->countReadings;
    }
}
$db = new AquaponicsDB();
$sDate = $_GET['startdate'];
$eDate = $_GET['enddate'];
$arrReadings = $db->get_history($sDate, $eDate);     // from AquaponicsDB.php
$arrAvgReadings = array();                      // new array of avgForDay objects
$counterAll = -1;                               // counter from array retrieved from get_history()
$counter = -1;                                   // counter from $arrAvgReadings
do {
    $counterAll++;
    switch ($frequency) {
        case "day":
            $time = date("Y-m-d", strtotime($arrReadings[$counterAll]->{'time'}));
        break;

        case "week":
            $time = date("Y-W", strtotime($arrReadings[$counterAll]->{'time'}));
        break;

        case "month":
            $time = date("Y-m", strtotime($arrReadings[$counterAll]->{'time'}));
        break;
    }
    //$time = date("Y-m-d", strtotime($arrReadings[$counterAll]->{'time'}));
    $pH = $arrReadings[$counterAll]->{'ph'};
    $do = $arrReadings[$counterAll]->{'dis_oxygen'};
    $temp = $arrReadings[$counterAll]->{'temperature'};
    $level = $arrReadings[$counterAll]->{'water_level'};
    if ($counter == -1) {

        array_push($arrAvgReadings, new avgForDay($time, $pH, $do, $temp, $level));
        $counter++;
    } else {
        if ($time == date($arrAvgReadings[$counter]->time)) {
            $arrAvgReadings[$counter]->ph += $pH;
            $arrAvgReadings[$counter]->dis_oxygen += $do;
            $arrAvgReadings[$counter]->temperature += $temp;
            $arrAvgReadings[$counter]->water_level += $level;
            $arrAvgReadings[$counter]->countReadings++;
        } else {
            $arrAvgReadings[$counter]->finalize();
            $counter++;
            array_push($arrAvgReadings, new avgForDay($time, $pH, $do, $temp, $level));
        }
    }
} while ($counterAll < count($arrReadings) - 1);

echo json_encode($arrAvgReadings, JSON_UNESCAPED_UNICODE);