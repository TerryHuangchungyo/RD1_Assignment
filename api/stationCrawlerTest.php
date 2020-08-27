<?php
require_once "cores/config.php";
require_once "crawlers/StationCrawler.php";

$crawler = new StationCrawler();

$crawler->setUrl( OpenData::weekWeatherUrl )
        ->setAuthCode( OpenData::auth )
        ->setDatasetId( OpenData::stationDatasetId )
        ->getData();

$crawler->setDatasetId( OpenData::noManStationDatasetId )
        ->getData();