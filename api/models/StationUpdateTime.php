<?php
require_once "cores/config.php";

class StationUpdateTime {
    public function getData( $columnName ) {
        try {
            $dbInfo = "mysql:host=".DB::dbhost.";dbname=".DB::dbname.";dbport=".DB::dbport.";";
            $dblink = new PDO( $dbInfo, DB::dbuser, DB::dbpass );
            $stmt = $dblink->prepare( "SELECT $columnName FROM".DB::stationUpdateTbName."WHERE id = 0;");
            if($stmt->execute()) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result[$columnName];
            } else {
                return "";
            }
            $dblink = null;
        } catch ( PDOException $e ) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function updateData( $columnName, $dataTime ) {
        try {
            $dbInfo = "mysql:host=".DB::dbhost.";dbname=".DB::dbname.";dbport=".DB::dbport.";";
            $dblink = new PDO( $dbInfo, DB::dbuser, DB::dbpass );
            $stmt = $dblink->prepare( "UPDATE ".DB::stationUpdateTbName." SET $columnName = ? WHERE id = 0;");
            if($stmt->execute(array( $dataTime ))) {
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