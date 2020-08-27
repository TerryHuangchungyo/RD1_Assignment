<?php
require_once "cores/config.php";

class UpdateTime {
    public function getData( $columnName, $cityName ) {
        try {
            $dbInfo = "mysql:host=".DB::dbhost.";dbname=".DB::dbname.";dbport=".DB::dbport.";";
            $dblink = new PDO( $dbInfo, DB::dbuser, DB::dbpass );
            $stmt = $dblink->prepare( "SELECT $columnName FROM weatherUpdateTime WHERE cityName = ?;");
            if($stmt->execute(array( $cityName ))) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result["weekWeather"];
            } else {
                return "";
            }
            $dblink = null;
        } catch ( PDOException $e ) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function updateData( $columnName, $cityName, $dataTime ) {
        try {
            $dbInfo = "mysql:host=".DB::dbhost.";dbname=".DB::dbname.";dbport=".DB::dbport.";";
            $dblink = new PDO( $dbInfo, DB::dbuser, DB::dbpass );
            $stmt = $dblink->prepare( "UPDATE weatherUpdateTime SET $columnName = ? WHERE cityName = ?;");
            if($stmt->execute(array( $dataTime, $cityName ))) {
                return true;
            } else {
                return false;
            }
            $dblink = null;
        } catch ( PDOException $e ) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
}