<?php
class DB
{
    const dbuser = "root";
    const dbpass = "root";
    const dbhost = "localhost";
    const dbport = 8889;
    const dbname = "RD1";
    const weatherTbName = "weather";
    const dropTbName = "droplet";
}

class OpenData {
    const weekWeatherUrl = "https://opendata.cwb.gov.tw/api/v1/rest/datastore/";
    const auth = "CWB-F600B430-4562-4DF4-BE65-CCBC059A1AA7";
    const weekDatasetId = "F-D0047-091";
}