<?php
require_once "../cores/config.php";
require_once "../models/Station.php";
require_once "../crawlers/StationCrawler.php";
require_once "../controllers/StationController.php";

// $crawler = new StationCrawler();
// $model = new Station;

// $result = $crawler->setUrl( OpenData::weekWeatherUrl )
//         ->setAuthCode( OpenData::auth )
//         ->setDatasetId( OpenData::stationDatasetId )
//         ->getData();
// $model->updateData($result);

// $result = $crawler->setDatasetId( OpenData::noManStationDatasetId )
//         ->getData();
// $model->updateData($result);

$_SERVER["REQUEST_METHOD"] = "PUT";
$c = new StationController;
$c->info();