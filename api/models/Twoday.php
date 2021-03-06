<?php
require_once "cores/config.php";

class Twoday {
    public function getData( $cityName ) {
        try {
            $currentDateTime = date("Y-m-d H:i:s",mktime(date("H")+8, date("i"), date("s"), date("m"), date("d"), date("Y")));
            $dbInfo = "mysql:host=".DB::dbhost.";dbname=".DB::dbname.";dbport=".DB::dbport.";";
            $dblink = new PDO( $dbInfo, DB::dbuser, DB::dbpass );
            $stmt = $dblink->prepare( "SELECT startTime, endTime, minT, maxT, weatherCond, weatherClass, comfortIdx, rainProb, wind FROM ".DB::weatherTbName.
            " WHERE DATE(startTime) BETWEEN DATE_ADD(CURRENT_DATE(), INTERVAL 1 DAY) AND DATE_ADD(CURRENT_DATE(), INTERVAL 2 DAY) AND cityName = ? ORDER BY startTime");
            if($stmt->execute(array( $cityName ))) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return null;
            }
            $dblink = null;
        } catch ( PDOException $e ) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
}