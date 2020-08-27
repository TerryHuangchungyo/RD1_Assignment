<?php
$auth = "CWB-F600B430-4562-4DF4-BE65-CCBC059A1AA7";
$dataid = "F-C0032-001";
$locationName = urlencode("臺中市");

$url =<<<QRY
https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-091?Authorization=$auth
QRY;

$ch = curl_init();
curl_setopt( $ch, CURLOPT_URL, $url );
curl_setopt( $ch, CURLOPT_HEADER, false );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

$result = curl_exec( $ch );
$arr = json_decode( $result, true );
header("Content-type: text/html; charset=utf-8;");

var_dump($arr);
curl_close( $ch );
?>