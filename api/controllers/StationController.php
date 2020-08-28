<?php
require_once "cores/config.php";
require_once "cores/controller.php";

class StationController extends Controller {
    public function info() {
        switch( $_SERVER["REQUEST_METHOD"] ) {
            case "PUT":
                $pastUpdateTime = $this->model("StationUpdateTime")->getData( "station" );
                sscanf($pastUpdateTime, "%d-%d-%d %d:%d:%d", $y, $m,$d, $h, $i, $s );
                $pastUpdateTimeStamp = mktime( $h, $i, $s, $m, $d, $y );
                $currentTimeStamp = mktime(date("H")+8, date("i"), date("s"), date("m"), date("d"), date("Y"));
                $currentTime = date("Y-m-d H:i:s", $currentTimeStamp );
                // echo ($currentTimeStamp - $pastUpdateTimeStamp); use for debug
                if( ($currentTimeStamp - $pastUpdateTimeStamp) > 90*24*60*60 ) {
                    $this->model("Station")->updateData( OpenData::noManStationDatasetId );
                    $this->model("Station")->updateData( OpenData::stationDatasetId );
                    $this->model("StationUpdateTime")->updateData( "station", $currentTime );
                }
                break;
        }
    }
}