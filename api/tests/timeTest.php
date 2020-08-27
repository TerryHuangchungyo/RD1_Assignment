<?php
$dateStr2 = "2020-08-28 14:22:25";
$timestamp1 = mktime(date("H")+8, date("i"), date("s"), date("m"), date("d"), date("Y"));
sscanf($dateStr2, "%d-%d-%d %d:%d:%d", $y, $m,$d, $h, $i, $s );
$timestamp2 = mktime( $h, $i, $s, $m, $d, $y );
echo $timestamp1."<br>";
echo $timestamp2."<br>";
$diff = ( $timestamp2 - $timestamp1);
echo $diff;
?>