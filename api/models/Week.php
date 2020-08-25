<?php
require_once "cores/config.php";

class Week {
    public function getData( $cityName ) {
        try {
            $currentDateTime = date("Y-m-d H:i:s",mktime(date("H")+8, date("i"), date("s"), date("m"), date("d"), date("Y")));
            $dbInfo = "mysql:host=".DB::dbhost.";dbname=".DB::dbname.";dbport=".DB::dbport.";";
            $dblink = new PDO( $dbInfo, DB::dbuser, DB::dbpass );
            $stmt = $dblink->prepare( "SELECT startTime, endTime, minT, maxT, weatherCond, weatherClass, comfortIdx, rainProb, wind FROM ".DB::weatherTbName." WHERE startTime BETWEEN DATE(?) AND DATEADD(DATE(?), 6 DAY ) AND cityName = ?");
            if($stmt->execute(array( $currentDateTime ,$currentDateTime, $cityName ))) {
                $dataset = [];
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $dataset[] = $row;
                    var_dump($row);
                }
                exit;
                return $dataset;
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