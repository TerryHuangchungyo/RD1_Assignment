<?php
require_once "../crawlers/RainCrawler.php";
require_once "../models/Rain.php";
require_once "../controllers/WeatherController.php";
require_once "../cores/config.php";

$_SERVER["REQUEST_METHOD"] = "PUT";
$c = new WeatherController;
$c->rain();
// $crawler = new RainCrawler();

// $crawler->setUrl( OpenData::weekWeatherUrl );
// $crawler->setAuthCode( OpenData::auth );
// $crawler->setDatasetId( OpenData::rainDatasetId );
// $result = $crawler->getData();
?>