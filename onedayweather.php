<?php
$auth = "CWB-F600B430-4562-4DF4-BE65-CCBC059A1AA7";
$dataid = "F-C0032-001";
$locationName = urlencode("臺中市");
$timeFrom=urlencode("2020-08-24T12:00:00");
$timeTo=urlencode("2020-08-24T18:00:00");


$url =<<<QRY
https://opendata.cwb.gov.tw/api/v1/rest/datastore/$dataid?Authorization=$auth&locationName=$locationName&timeFrom=$timeFrom&timeTo=$timeTo"
QRY;

$ch = curl_init();
curl_setopt( $ch, CURLOPT_URL, $url );
curl_setopt( $ch, CURLOPT_HEADER, false );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

$result = curl_exec( $ch );
$arr = (Array)json_decode( $result, true );
header("Content-type: text/html; charset=utf-8;");
echo "<br>";
if( $arr["success"] ) {
    echo "地區:".$arr["records"]["location"][0]["locationName"]."<br>";
    echo "天氣情況:".$arr["records"]["location"][0]["weatherElement"][0]["time"][0]["parameter"]["parameterName"]."<br>";
    echo "降雨機率:".$arr["records"]["location"][0]["weatherElement"][1]["time"][0]["parameter"]["parameterName"]."<br>";
    echo "最低溫度:".$arr["records"]["location"][0]["weatherElement"][2]["time"][0]["parameter"]["parameterName"]."<br>";
    echo "舒適度:".$arr["records"]["location"][0]["weatherElement"][3]["time"][0]["parameter"]["parameterName"]."<br>";
    echo "最高溫度:".$arr["records"]["location"][0]["weatherElement"][4]["time"][0]["parameter"]["parameterName"]."<br>";
}
curl_close( $ch );
?>