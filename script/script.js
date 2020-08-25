import {TW_AreaName} from "./config.js";

$(document).ready(function(){
    for( let cityName of Object.keys(TW_AreaName) ) {
        $("#city").append(`<option>${cityName}</option>`);
    }

    /* 當城市查詢頁面框改變時，更新當前天氣，並更新地區的下拉式選單。 */
    $("#city").change(function(){
        $("#cityName").text($(this).val());
    });
});

function getTodayWeather() {

}

function objToQuery( obj ) {
    var str = [];
    for( let key in obj ) {
        let value = obj[key];
        if( typeof(value) != String ) {
            value = String( value );
        }
        str.push( encodeURI(key)+"="+encodeURI(value));
    }
    return str.join("&");
}

