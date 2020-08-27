<?php
require_once "cores/config.php";
require_once "cores/controller.php";

class WeatherController extends Controller {
    public function today( $cityName = null ) {
        switch( $_SERVER["REQUEST_METHOD"] ) {
            case "GET":
                if( $cityName === null ) {
                    header( "HTTP/1.1 404 Not Found" );
                    exit;
                }

                $data = $this->model("Today")->getData( $cityName );
                $this->view( "JsonAPI", $data );
                break;
        }
    }

    public function twoday( $cityName ) {
        switch( $_SERVER["REQUEST_METHOD"] ) {
            case "GET":
                if( $cityName === null ) {
                    header( "HTTP/1.1 404 Not Found" );
                    exit;
                }
                $data = $this->model("Twoday")->getData( $cityName );
                $this->view( "JsonAPI", $data );
                break;
        }
    }

    public function week( $cityName = null ) {
        if( $cityName === null ) {
            header( "HTTP/1.1 404 Not Found" );
            exit;
        }

        switch( $_SERVER["REQUEST_METHOD"] ) {
            case "GET":
                if( $cityName === null ) {
                    header( "HTTP/1.1 404 Not Found" );
                    exit;
                }
                $data = $this->model("Week")->getData( $cityName );
                $this->view( "JsonAPI", $data );
                break;
            case "PUT":
                if( $cityName === null ) {
                    header( "HTTP/1.1 404 Not Found" );
                    exit;
                }
                $pastUpdateTime = $this->model("UpdateTime")->getData( "weekWeather", $cityName );
                sscanf($pastUpdateTime, "%d-%d-%d %d:%d:%d", $y, $m,$d, $h, $i, $s );
                $pastUpdateTimeStamp = mktime( $h, $i, $s, $m, $d, $y );
                $currentTimeStamp = mktime(date("H")+8, date("i"), date("s"), date("m"), date("d"), date("Y"));
                $currentTime = date("Y-m-d H:i:s", $currentTimeStamp );
                // echo ($currentTimeStamp - $pastUpdateTimeStamp); use for debug
                if( ($currentTimeStamp - $pastUpdateTimeStamp) > 3600 ) {
                    $this->model("Week")->updateData( $cityName );
                    $this->model("UpdateTime")->updateData( "weekWeather", $cityName, $currentTime );
                }
                break;
        }
    }
}