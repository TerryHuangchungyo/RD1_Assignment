<?php
require_once "cores/config.php";

class Today {
    public function getData( $cityName ) {
        try {
            $currentDateTime = date("Y-m-d H:i:s",mktime(date("H")+8, date("i"), date("s"), date("m"), date("d"), date("Y")));
            $dbInfo = "mysql:host=".DB::dbhost.";dbname=".DB::dbname.";dbport=".DB::dbport.";";
            $dblink = new PDO( $dbInfo, DB::dbuser, DB::dbpass );
            $stmt = $dblink->prepare( "SELECT minT, maxT, weatherCond, weatherClass, comfortIdx, rainProb, wind FROM ".DB::weatherTbName." WHERE ? BETWEEN startTime AND endTime AND cityName = ?");
            if($stmt->execute(array( $currentDateTime, $cityName ))) {
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