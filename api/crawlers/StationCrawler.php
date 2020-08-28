<?php
require_once "cores/config.php";
require_once "cores/crawler.php";

class StationCrawler extends Crawler {
    private $url;
    private $authCode;
    private $datasetId;

    public function __construct() {
        $this->url = "";
        $this->authCode = "";
        $this->datasetId = "";
    }

    public function setUrl( $url ) {
        $this->url = $url;
        return $this;
    }

    public function setAuthCode( $authCode ) {
        $this->authCode = $authCode;
        return $this;
    }

    public function setDatasetId( $datasetId ) {
        $this->datasetId = $datasetId;
        return $this;
    }

    public function getData() {
        if( $this->url && $this->authCode ) {
            $resourceUrl = rtrim( $this->url, "/")."/";

            $options = [];
            ($this->authCode) ? $options["Authorization"] = ($this->authCode): "";

            $resourceUrl .= ($this->datasetId . "?");
            foreach( $options as $key => $value ) {
                $resourceUrl .= ( $key."=".$value."&" );
            }
            $resourceUrl = rtrim( $resourceUrl, "&" );

            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $resourceUrl );
            curl_setopt( $ch, CURLOPT_HEADER, false );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

            $result = curl_exec( $ch );
            curl_close( $ch );
            return $this->transformData( $result );
        }
    }

    public function transformData( $dataset ) {
        $arr = json_decode( $dataset, true );
        $stationDataset = Array();
        if(  $dataset && $arr["success"] === "true" ) {
            $formatData = [];
            try {
                $stationDataset = $arr["records"]["data"]["stationStatus"]["station"];
                foreach( $stationDataset as $station ) {
                    $row = [];
                    $row["stationId"] = $station["stationID"];
                    $row["stationName"] = $station["stationName"];
                    $row["stationAltitude"] = $station["stationAltitude"];
                    $row["longitude"] = $station["longitude"];
                    $row["latitude"] = $station["latitude"];
                    $row["cityName"] = $station["countyName"];
                    $row["stationAddress"] = $station["stationAddress"];
                    $formatData[] = $row;                
                }
                return $formatData;
            } catch( Exception $e ) {
                return [];
            }
            return [];
        }
    }
}