<?php
$auth = "CWB-F600B430-4562-4DF4-BE65-CCBC059A1AA7";
$dataid = "F-C0032-001";
$locationName = urlencode("臺中市");
$timeFrom=urlencode("2020-08-24T12:00:00");
$timeTo=urlencode("2020-08-24T18:00:00");


$url =<<<QRY
https://opendata.cwb.gov.tw/api/v1/rest/datastore/$dataid?Authorization=$auth&locationName=$locationName&timeFrom=$timeFrom&timeTo=$timeTo"
QRY;
echo $url;

$ch = curl_init();
curl_setopt( $ch, CURLOPT_URL, $url );
curl_setopt( $ch, CURLOPT_HEADER, false );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

$result = curl_exec( $ch );
$arr = (Array)json_decode( $result, true );
header("Content-type: text/html; charset=utf-8;");
echo "<br>";

var_dump($arr);
curl_close( $ch );
?>

