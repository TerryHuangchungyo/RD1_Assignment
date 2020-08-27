<?php
require_once "cores/crawler.php";

class WeekWeatherCrawler extends Crawler {
    private $url;
    private $authCode;
    private $datasetId;
    private $fromDate;
    private $toDate;
    private $cityName;
    private $sortCond;

    public function __construct() {
        $this->url = "";
        $this->authCode = "";
        $this->datasetId = "";
        $this->fromDate = "";
        $this->toDate = "";
        $this->cityName = "";
    }

    public function setUrl( $url ) {
        $this->url = $url;
        return $this;
    }
    
    public function setAuthCode( $auth ) {
        $this->authCode = $auth;
        return $this;
    }

    public function setDatasetId( $datasetId ) {
        $this->datasetId = $datasetId;
        return $this;
    }

    public function setFromDate( $fromDate ) {
        $this->fromDate = $fromDate;
        return $this;
    }

    public function setToDate( $toDate ) {
        $this->toDate = $toDate;
        return $this;
    }

    public function setCityName( $cityName ) {
        $this->cityName = $cityName;
        return $this;
    }

    public function setSort( $cond ) {
        $this->sortCond = $cond;
    }

    public function getData() {
        if( $this->url && $this->authCode ) {
            $resourceUrl = rtrim( $this->url, "/")."/";

            $options = [];
            ($this->authCode) ? $options["Authorization"] = ($this->authCode): "";
            ($this->fromDate) ? $options["timeFrom"] = urlencode(($this->fromDate)) : "";
            ($this->toDate) ? $options["timeTo"] =  urlencode(($this->toDate)) : "";
            ($this->cityName) ? $options["locationName"] = urlencode(($this->cityName)) : "";
            ($this->sortCond) ? $options["sort"] = urlencode(($this->sortCond)) : "";

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
            // var_dump( $result );
            return $this->transformData( $result );
        } else {
            return [];
        }
    }

    public function transformData( $result ) {
        $arr = json_decode( $result, true );
        $weatherData = Array();
        if(  $result && $arr["success"] === "true" ) {
            $formatData = [];
            try {
                foreach( $arr["records"]["locations"][0]["location"] as $location ) {
                    $weatherData = $location["weatherElement"];
                    $row = [];
                    for( $i = 0; $i < count($weatherData[0]["time"]); $i++ ) {
                        $row["cityName"] = $location["locationName"];
                        $row["startTime"]= $weatherData[0]["time"][$i]["startTime"];
                        $row["endTime"]= $weatherData[0]["time"][$i]["endTime"];
                        $row["minT"]= $weatherData[8]["time"][$i]["elementValue"][0]["value"];
                        $row["maxT"]= $weatherData[12]["time"][$i]["elementValue"][0]["value"];
                        $row["weatherClass"]= (int)$weatherData[6]["time"][$i]["elementValue"][1]["value"];
                        $row["weatherCond"]= $weatherData[6]["time"][$i]["elementValue"][0]["value"];
                        $row["comfortIdx"]= (int)$weatherData[7]["time"][$i]["elementValue"][0]["value"];
                        $row["rainProb"]= $weatherData[0]["time"][$i]["elementValue"][0]["value"] != " " ? $weatherData[0]["time"][$i]["elementValue"][0]["value"] :"0";
                        $row["wind"] = $weatherData[4]["time"][$i]["elementValue"][0]["value"];
                        $formatData[] = $row;
                    }
                }
                return $formatData;
            } catch( Exception $e ) {
                return [];
            }
            return [];
        }
    }
}