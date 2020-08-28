<?php
require_once "cores/config.php";
require_once "cores/crawler.php";

class RainCrawler extends Crawler {
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
        $rainDataset = Array();
        if(  $dataset && $arr["success"] === "true" ) {
            $formatData = [];
            try {
                $rainDataset = $arr["records"]["location"];
                foreach( $rainDataset as $rainData ) {
                    $row = [];
                    $row["stationId"] = $rainData["stationId"];
                    $row["rain_1hr"] = $rainData["weatherElement"][1]["elementValue"];
                    $row["rain_24hr"] = $rainData["weatherElement"][6]["elementValue"];
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