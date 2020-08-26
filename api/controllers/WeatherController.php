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
            case "POST":
                echo "You use today post method";
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
            case "POST":
                echo "You use two post method";
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
            case "POST":
                break;
        }
    }
}