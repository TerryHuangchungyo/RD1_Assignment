<?php
require_once "models/UpdateTime.php";

$model = new UpdateTime;
var_dump($model->getData( 'weekWeather', "臺北市" ));
$currentDateTime = date("Y-m-d H:i:s",mktime(date("H")+8, date("i"), date("s"), date("m"), date("d"), date("Y")));
var_dump($currentDateTime);
$model->updateData( 'weekWeather', "臺北市", $currentDateTime );
?>