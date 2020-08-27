<?php
require("cores/config.php");
set_error_handler('exceptions_error_handler');
$weatherDataId = OpenData::weekDatasetId;
$auth = OpenData::auth;

$url =<<<QRY
https://opendata.cwb.gov.tw/api/v1/rest/datastore/$weatherDataId?Authorization=$auth
QRY;

$ch = curl_init();
curl_setopt( $ch, CURLOPT_URL, $url );
curl_setopt( $ch, CURLOPT_HEADER, false );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

$result = curl_exec( $ch );
curl_close( $ch );

$arr = json_decode( $result, true );
header("Content-type: text/html; charset=utf-8;");
$weatherData = Array();
if(  $result && $arr["success"] === "true" ) {
    $formatData = [];
    try {
    foreach( $arr["records"]["locations"][0]["location"] as $location ) {
        $weatherData = $location["weatherElement"];
        $row = [];
        for( $i = 0; $i < 14; $i++ ) {
            $row["cityName"] = $location["locationName"];
            $row["startTime"]= $weatherData[0]["time"][$i]["startTime"];
            $row["endTime"]= $weatherData[0]["time"][$i]["endTime"];
            $row["minT"]= $weatherData[8]["time"][$i]["elementValue"][0]["value"];
            $row["maxT"]= $weatherData[12]["time"][$i]["elementValue"][0]["value"];
            $row["weatherClass"]= $weatherData[6]["time"][$i]["elementValue"][1]["value"];
            $row["weatherCond"]= $weatherData[6]["time"][$i]["elementValue"][0]["value"];
            $row["comfortIdx"]= $weatherData[7]["time"][$i]["elementValue"][0]["value"];
            $row["rainProb"]= $weatherData[0]["time"][$i]["elementValue"][0]["value"] != " " ? $weatherData[0]["time"][$i]["elementValue"][0]["value"] :"0";
            $row["wind"] = $weatherData[4]["time"][$i]["elementValue"][0]["value"];
            $formatData[] = $row;
        }
    }
    } catch( Exception $e ) {
        echo "Fatal error";
        exit;
    }

    try {
        $dbStr = "mysql:host=".DB::dbhost.";dbname=".DB::dbname.";dbport=".DB::dbport.";";
        $dblink = new PDO( $dbStr, DB::dbuser, DB::dbpass);
        $dblink->query("TRUNCATE TABLE weather");
        $preStmt = "INSERT INTO ".DB::weatherTbName." 
        (cityName, startTime, endTime, minT, maxT, weatherClass, weatherCond, comfortIdx, rainProb, wind) 
        VALUES (:cityName, :startTime, :endTime, :minT, :maxT, :weatherClass, :weatherCond, :comfortIdx, :rainProb, :wind)";
        
        foreach( $formatData as $row ) {
            echo $row["cityName"]." ";
            echo $row["startTime"]." ";
            echo $row["endTime"]." ";
            echo $row["minT"]." ";
            echo $row["maxT"]." ";
            echo $row["weatherClass"]." ";
            echo $row["weatherCond"]." ";
            echo $row["comfortIdx"]." ";
            echo $row["rainProb"]." ";
            echo $row["wind"]." ";
            echo "<br>";
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

function exceptions_error_handler($severity, $message, $filename, $lineno) {
    if (error_reporting() == 0) {
      return;
    }
    if (error_reporting() & $severity) {
      throw new ErrorException($message, 0, $severity, $filename, $lineno);
    }
}
?>