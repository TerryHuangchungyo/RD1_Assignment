<?php
header( "Content-type: application/json; charset=utf-8");

$transData = [];
foreach( $data as $row ) {
    $newrow = [];
    foreach( $row as $key => $value ) {
        $newrow[urlencode($key)] = urlencode($value);
    }
    $transData[] = $newrow;
}
echo urldecode(json_encode( $transData ));