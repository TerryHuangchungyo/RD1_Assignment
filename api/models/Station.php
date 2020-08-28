<?php
class Station {
    public function updateData( $stationDatasetId ) {
        if( $stationDatasetId ) {
            $crawler = new StationCrawler();
            $dataset = $crawler->setUrl( OpenData::weekWeatherUrl )
                            ->setAuthCode( OpenData::auth )
                            ->setDatasetId( $stationDatasetId )
                            ->getData();
            
            try {

                $dbStr = "mysql:host=".DB::dbhost.";dbname=".DB::dbname.";dbport=".DB::dbport.";";
                $dblink = new PDO( $dbStr, DB::dbuser, DB::dbpass);

                $insertPreStmt = "INSERT INTO ".DB::stationTbName." 
                ( stationId, stationName, stationAltitude, longitude, latitude, cityName, stationAddress ) 
                VALUES (:stationId, :stationName, :stationAltitude, :longitude, :latitude, :cityName, :stationAddress )";
                
                $updatePreStmt = "UPDATE ".DB::stationTbName." 
                 SET stationName = :stationName, stationAltitude = :stationAltitude , longitude = :longitude,
                latitude = :latitude, cityName = :cityName, stationAddress = :stationAddress WHERE stationId = :stationId";

                foreach( $dataset as $row ) {
                    $stmt = $dblink->prepare($insertPreStmt);
                    if( !$stmt->execute( $row ) ) {
                        $stmt = $dblink->prepare($updatePreStmt);
                        $stmt->execute( $row );
                    }
                }
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        }
    }
}