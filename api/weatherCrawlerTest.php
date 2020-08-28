<?php
require_once "crawlers/WeekWeatherCrawler.php";
require_once "cores/config.php";

$crawler = new WeekWeatherCrawler();

$crawler->setUrl( OpenData::weekWeatherUrl );
$crawler->setAuthCode( OpenData::auth );
$crawler->setDatasetId( OpenData::weekDatasetId );
$crawler->setCityName("臺北市");
$tomorrow = date("Y-m-d\TH:i:s",mktime(0, 0, 0, date("m"), date("d"), date("Y")));
$afterday = date("Y-m-d\TH:i:s",mktime(0, 0, 0, date("m"), date("d")+7, date("Y")));
// $crawler->setFromDate( $tomorrow );
// $crawler->setToDate( $afterday );
$crawler->setSort( "time" );
$result = $crawler->getData();
// var_dump($result);

foreach( $result as $row ) {
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