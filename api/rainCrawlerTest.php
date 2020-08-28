<?php
require_once "crawlers/RainCrawler.php";
require_once "models/Rain.php";
require_once "cores/config.php";

$model = new Rain;
$model->updateData();
// $crawler = new RainCrawler();

// $crawler->setUrl( OpenData::weekWeatherUrl );
// $crawler->setAuthCode( OpenData::auth );
// $crawler->setDatasetId( OpenData::rainDatasetId );
// $result = $crawler->getData();
?>