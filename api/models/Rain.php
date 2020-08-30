<?php
require_once "crawlers/RainCrawler.php";
class Rain {
    public function updateData() {
        $crawler = new RainCrawler();
        $dataset = $crawler->setUrl( OpenData::weekWeatherUrl )
                        ->setAuthCode( OpenData::auth )
                        ->setDatasetId( OpenData::rainDatasetId )
                        ->getData();
        
        try {

            $dbStr = "mysql:host=".DB::dbhost.";dbname=".DB::dbname.";dbport=".DB::dbport.";";
            $dblink = new PDO( $dbStr, DB::dbuser, DB::dbpass);
            $dblink->query("TRUNCATE TABLE ".DB::rainTbName);
            $insertPreStmt = "INSERT INTO ".DB::rainTbName." 
            ( stationId, rain_1hr, rain_24hr ) VALUES (:stationId, :rain_1hr, :rain_24hr )";

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

    public function getData( $cityName ) {
        try {

            $dbStr = "mysql:host=".DB::dbhost.";dbname=".DB::dbname.";dbport=".DB::dbport.";";
            $dblink = new PDO( $dbStr, DB::dbuser, DB::dbpass);
            $preStmt = "SELECT s.stationId, stationName, stationAltitude, longitude, latitude, cityName, stationAddress, rain_1hr, rain_24hr 
            FROM ".DB::stationTbName." s JOIN ".DB::rainTbName." r ON s.stationId = r.stationId WHERE cityName = :cityName;";

 
            $stmt = $dblink->prepare($preStmt);
            $stmt->bindParam(":cityName",$cityName);

            if($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                return null;
            }
            $dblink = null;

        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function getAvg( $cityName ) {
        try {

            $dbStr = "mysql:host=".DB::dbhost.";dbname=".DB::dbname.";dbport=".DB::dbport.";";
            $dblink = new PDO( $dbStr, DB::dbuser, DB::dbpass);
            $avg1hr_Stmt = "SELECT ROUND(AVG(result.rain_1hr),2) as avg_1hr
            FROM (SELECT rain_1hr FROM ".DB::stationTbName." s JOIN ".DB::rainTbName." r ON s.stationId = r.stationId WHERE cityName = :cityName ) as result
            WHERE rain_1hr >= 0";

            $avg24hr_Stmt = "SELECT ROUND(AVG(result.rain_24hr),2) as avg_24hr
            FROM (SELECT rain_24hr FROM ".DB::stationTbName." s JOIN ".DB::rainTbName." r ON s.stationId = r.stationId WHERE cityName = :cityName ) as result
            WHERE rain_24hr >= 0";

            $result["avg_1hr"] = -1;
            $result["avg_24hr"] = -1;
            $stmt = $dblink->prepare($avg1hr_Stmt);
            $stmt->bindParam(":cityName",$cityName);
            if($stmt->execute()) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if( $row["avg_1hr"] != NULL ) {
                    $result["avg_1hr"] = $row["avg_1hr"];
                }
            } 



            $stmt = $dblink->prepare($avg24hr_Stmt);
            $stmt->bindParam(":cityName",$cityName);
            if($stmt->execute()) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if( $row["avg_24hr"] != NULL ) {
                    $result["avg_24hr"] = $row["avg_24hr"];
                }
            }
            
            $dblink = null;
            return [$result];
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
}