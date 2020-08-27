<?php
class Station {
    public function updateData() {
        if( $dataset ) {
            try {

                $dbStr = "mysql:host=".DB::dbhost.";dbname=".DB::dbname.";dbport=".DB::dbport.";";
                $dblink = new PDO( $dbStr, DB::dbuser, DB::dbpass);
                $dblink->query("DELETE FROM weather WHERE cityName =".$cityName );

                $insertPreStmt = "INSERT INTO ".DB::weatherTbName." 
                ( stationId, stationName, stationAltitude, longitude, latitude, cityName, stationAddress, status ) 
                VALUES (:stationId, :stationName, :stationAltitude, :longitude, :latitude, :cityName, :stationAddress, :status )";
                
                $updatePreStmt = "INSERT INTO ".DB::weatherTbName." 
                ( stationId, stationName, stationAltitude, longitude, latitude, cityName, stationAddress, status ) 
                VALUES (:stationId, :stationName, :stationAltitude, :longitude, :latitude, :cityName, :stationAddress, :status )";

                foreach( $dataset as $row ) {
                    $stmt = $dblink->prepare($preStmt);
                    $stmt->bindParam(":cityName",$row["cityName"]);
                    $stmt->bindParam(":startTime",$row["startTime"]);
                    $stmt->bindParam(":endTime",$row["endTime"]);
                    $stmt->bindParam(":minT",$row["minT"]);
                    $stmt->bindParam(":maxT",$row["maxT"]);
                    $stmt->bindParam(":weatherClass",$row["weatherClass"]);
                    $stmt->bindParam(":weatherCond",$row["weatherCond"]);
                    $stmt->bindParam(":comfortIdx",$row["comfortIdx"]);
                    $stmt->bindParam(":rainProb", $row["rainProb"]);
                    $stmt->bindParam(":wind",$row["wind"]);
                    $stmt->execute();
                }
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        }
    }
}