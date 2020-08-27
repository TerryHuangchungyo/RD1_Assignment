<?php
require_once "cores/config.php";
require_once "crawlers/WeekWeatherCrawler.php";

class Week {
    public function getData( $cityName ) {
        try {
            $currentDateTime = date("Y-m-d H:i:s",mktime(date("H")+8, date("i"), date("s"), date("m"), date("d"), date("Y")));
            $dbInfo = "mysql:host=".DB::dbhost.";dbname=".DB::dbname.";dbport=".DB::dbport.";";
            $dblink = new PDO( $dbInfo, DB::dbuser, DB::dbpass );
            $stmt = $dblink->prepare( "SELECT startTime, endTime, minT, maxT, weatherCond, weatherClass, comfortIdx, rainProb, wind FROM ".DB::weatherTbName.
            " WHERE DATE(startTime) BETWEEN DATE_ADD(CURRENT_DATE(), INTERVAL 1 DAY) AND DATE_ADD(CURRENT_DATE(), INTERVAL 6 DAY) AND cityName = ? ORDER BY startTime");
            if($stmt->execute(array( $cityName ))) {
                $dataset = [];
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $dataset[] = $row;
                }
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

    public function updateData( $cityName ) {
        $crawler = new WeekWeatherCrawler();

        $crawler->setUrl( OpenData::weekWeatherUrl );
        $crawler->setAuthCode( OpenData::auth );
        $crawler->setDatasetId( OpenData::weekDatasetId );
        $crawler->setCityName( $cityName );
        $tomorrow = date("Y-m-d\TH:i:s",mktime(0, 0, 0, date("m"), date("d"), date("Y")));
        $afterday = date("Y-m-d\TH:i:s",mktime(0, 0, 0, date("m"), date("d")+7, date("Y")));
        $crawler->setFromDate( $tomorrow );
        $crawler->setToDate( $afterday );
        $crawler->setSort( "time" );
        $dataset = $crawler->getData();
        if( $dataset ) {
            try {
                $dbStr = "mysql:host=".DB::dbhost.";dbname=".DB::dbname.";dbport=".DB::dbport.";";
                $dblink = new PDO( $dbStr, DB::dbuser, DB::dbpass);
                $dblink->query("DELETE FROM weather WHERE cityName =".$cityName );
                $preStmt = "INSERT INTO ".DB::weatherTbName." 
                (cityName, startTime, endTime, minT, maxT, weatherClass, weatherCond, comfortIdx, rainProb, wind) 
                VALUES (:cityName, :startTime, :endTime, :minT, :maxT, :weatherClass, :weatherCond, :comfortIdx, :rainProb, :wind)";
                
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

