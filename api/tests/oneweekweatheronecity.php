<?php
require("../cores/config.php");
$weatherDataId = OpenData::weekDatasetId;
$auth = OpenData::auth;
$cityName = urlencode("臺中市");
$url =<<<QRY
https://opendata.cwb.gov.tw/api/v1/rest/datastore/$weatherDataId?Authorization=$auth&locationName=$cityName
QRY;

$ch = curl_init();
curl_setopt( $ch, CURLOPT_URL, $url );
curl_setopt( $ch, CURLOPT_HEADER, false );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

$result = curl_exec( $ch );
curl_close( $ch );

$arr = json_decode( $result, true );
header("Content-type: text/html; charset=utf-8;");
echo "<br>";
$weatherData = Array();
if( $result && $arr["success"] === "true" ) {
    $formatData = [];
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
    }
}
?>