<?php
class AquaponicsDB extends PDO
{
    protected $connected;
    public function __construct($user = "", $passwd = "", $db = "")
    {
        $this->connected = TRUE;
        include "../Config/Mysql.php";
        try {
            //calls PDO __Construct -> database type, localhost, and user, pwd
            parent::__construct("mysql:dbname=$db;host=127.0.0.1", $user, $passwd);
        } catch (Exception $ex) {
            $this->connected = FALSE;
        }
    }

    /**
     * Get all (id, water_level, dis_oxygen, ph, time, system_id)
     * @return boolean
     */
    public function get_all()
    {
        $stm = $this->prepare("select * from readings where system_id = 1");
        $stm->execute();
        $res = $stm->fetchAll(PDO::FETCH_CLASS);    // returns array of objects
        log($res);
        if (count($res) > 0)
            return $res;
        else
            return FALSE;
    }
    /**
     * Get sensor values (water_level, dis_oxygen, ph, time)
     * @return boolean
     */
    public function get_values()
    {
        $stm = $this->prepare("SELECT time, water_level, temperature, ph, dis_oxygen FROM readings WHERE system_id = 1
        ORDER BY TIME DESC");
        $stm->execute();
        $res = $stm->fetchAll(PDO::FETCH_CLASS);    // returns array of objects
        if (count($res) > 0)
            return $res;
        else
            return FALSE;
    }
    /**
     * Get latest sensor values (time, water_level, temperature, ph, dis_oxygen)
     * @return boolean
     */
    public function get_latest()
    {
        $stm = $this->prepare("SELECT time, water_level, temperature, ph, dis_oxygen FROM readings WHERE system_id = 1
        ORDER BY TIME DESC LIMIT 1");
        $stm->execute();
        $res = $stm->fetchAll(PDO::FETCH_CLASS);    // returns array of objects
        if (count($res) > 0)
            return $res;
        else
            return FALSE;
    }
    /**
     * Get values between certain time and date
     * @return boolean
     */
    public function get_history($startDate, $endDate)
    {
        $stm = $this->prepare("SELECT * FROM readings WHERE system_id = 1 AND time BETWEEN '$startDate' AND '$endDate'");
        $stm->execute();
        $res = $stm->fetchAll(PDO::FETCH_CLASS);    // returns array of objects
        if (count($res) > 0)
            return $res;
        else
            return FALSE;
    }
}
