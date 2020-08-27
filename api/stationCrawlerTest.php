<?php
require_once "cores/config.php";
require_once "crawlers/StationCrawler.php";

$crawler = new StationCrawler();

$result = $crawler->setUrl( OpenData::weekWeatherUrl )
        ->setAuthCode( OpenData::auth )
        ->setDatasetId( OpenData::stationDatasetId )
        ->getData();

$count = count($result);


$result = $crawler->setDatasetId( OpenData::noManStationDatasetId )
        ->getData();
$count += count($result);
echo $count;