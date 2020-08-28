<?php
require_once "cores/config.php";
require_once "cores/controller.php";

class StationController extends Controller {
    public function info() {
        switch( $_SERVER["REQUEST_METHOD"] ) {
            case "PUT":
                $crawler = new StationCrawler();
                $result = $crawler->setUrl( OpenData::weekWeatherUrl )
                                ->setAuthCode( OpenData::auth )
                                ->setDatasetId( OpenData::stationDatasetId )
                                ->getData();
                $this->model("Station")->updateData( $result );

                $result = $crawler->setDatasetId( OpenData::noManStationDatasetId )
                                ->getData();
                $this->model("Station")->updateData($result);
                break;
        }
    }
}