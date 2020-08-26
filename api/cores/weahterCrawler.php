<?php
class WeatherCrawler {
    private $datasetId;
    private $cityName;
    private $fromTime;
    private $toTime;

    public function setDatasetId( $datasetId ) {
        $this->datasetId = $datasetId;
    }
    
    public function setCityName( $cityName ) {
        $this->cityName = $cityName;
    }

    public function setFromTime( $fromTime ) {
        $this->fromTime = $fromTime;
    }

    public function setToTime( $toTime ) {
        $this->toTime = $toTime;
    }

    public function getData() {
        return [];
    }

    public function dataTransform( $data ) {
        return [];
    }
}