import config from "./config.js";

var weekWeatherDataset;
var currentIndex;
var refreshing = false;

$(document).ready(function(){
    for( let cityName of config.TW_CityName ) {
        $("#city").append(`<option>${cityName}</option>`);
    }

    /* 當城市查詢頁面框改變時，更新當前天氣，並更新地區的下拉式選單。 */
    $("#city").change(function(){
        $("#TW_CityImg").prop("src","image/city/"+config.TW_CityImg[$(this).val()]);
        $("#cityName").text($(this).val());
        refreshWeatherData( config.weekWeatherUrl, $(this).val());
        getRainAvgData( updateRainAvgUI, config.rainAvgDataUrl, $(this).val() );
    });

    $("#city").trigger("change");
    refreshStationData( config.stationDataUrl );
    
    $("#rainBtn").click(function(){
        getRainData( updateRainUI, config.rainDataUrl, $("#city").val() );
        $('#rainModal').modal('show')
    });

    $('#rainModal').on('show.bs.modal', function (e) {
        $("#rainModalLabel > span").text($("#city").val());
    })
});

function refreshStationData( resourceUrl ) {
    $.ajax({
        url: resourceUrl,
        type: "PUT",
        beforeSend: function() {
            refreshing = true;
            $("#rainBtn").prop("disabled",true);
            $("#rainBtn").text("更新中...");
        }
    }).done( function( msg ) {
        refreshRainData( config.rainDataUrl );
    }).fail(function(){
        alert("更新站台資料失敗");
    });
}

function refreshRainData( resourceUrl ) {
    $.ajax({
        url: resourceUrl,
        type: "PUT",
    }).done( function( msg ) {
        refreshing = false;
        $("#rainBtn").prop("disabled", false);
        $("#rainBtn").text("查看各觀測站降雨資料");
        getRainAvgData( updateRainAvgUI, config.rainAvgDataUrl, $("#city").val() );
        getRainData( updateRainUI, config.rainDataUrl, $("#city").val() );
    }).fail(function(){
        alert("更新雨量資料失敗");
    });
}

function getRainAvgData( callback = null, resourceUrl, cityName ) {
    if( !refreshing ) {
        $.ajax({
            url: resourceUrl + cityName,
            type: "GET"
        }).done( function( data ) {
            callback( data[0] );
        }).fail(function(){
            alert("載入失敗");
        });
    }
}

function updateRainAvgUI( data ) {
    let str_1hr = ( parseFloat(data["avg_1hr"])>=0 ? data["avg_1hr"]+" mm" : "沒有資料" );
    $("#avg_1hr").text( str_1hr );
    let str_24hr = ( parseFloat(data["avg_24hr"])>=0 ? data["avg_24hr"]+" mm" : "沒有資料" );
    $("#avg_24hr").text( str_24hr );
}

function getRainData( callback = null, resourceUrl, cityName ) {
    $.ajax({
        url: resourceUrl + cityName,
        type: "GET"
    }).done( function( dataset ) {
        callback( dataset );
    }).fail(function(){
        alert("載入失敗");
    });
}

function updateRainUI( dataset ) {
    $("#rainTableBody").empty();
    dataset.forEach( ( data ) => {
        data["rain_1hr"] = ( parseFloat(data["rain_1hr"])>= 0 ? data["rain_1hr"]+" mm" : "沒有資料" );
        data["rain_24hr"] = ( parseFloat(data["rain_24hr"])>= 0 ? data["rain_24hr"]+" mm" : "沒有資料" );

        let row = $("<tr></tr>").append(`<td>${data["stationId"]}</td>`)
                                .append(`<td>${data["stationName"]}</td>`)
                                .append(`<td>${data["stationAltitude"]}</td>`)
                                .append(`<td>${data["longitude"]}</td>`)
                                .append(`<td>${data["latitude"]}</td>`)
                                .append(`<td>${data["stationAddress"]}</td>`)
                                .append(`<td class="text-right">${data["rain_1hr"]}</td>`)
                                .append(`<td class="text-right">${data["rain_24hr"]}</td>`);
        row.appendTo( $("#rainTableBody") );
    });
}

function refreshWeatherData( resourceUrl, cityName ) {
    $.ajax({
        url: resourceUrl + cityName,
        type: "PUT"
    }).done( function( msg ) {
        getWeatherData( updateTodayWeatherUI, config.todayWeatherUrl, cityName );
        getWeatherData( updateTwodayWeatherUI, config.twodayWeatherUrl, cityName );
        getWeatherData( updateWeekWeatherUI, config.weekWeatherUrl, cityName );
    }).fail(function(){
        alert("載入失敗");
    });
}

function getWeatherData( callback = null, resourceUrl ,cityName ) {
    $.ajax({
        url: resourceUrl + cityName,
        type: "GET"
    }).done( function( dataset ) {
        callback( dataset );
        return true;
    }).fail(function(){
        alert("載入失敗");
        return false;
    });
}

function updateTodayWeatherUI( dataset ) {
    let data = dataset[0];
    $("#today_Img").prop( "src", `image/weather/${config.weatherClassImg[data['weatherClass']]}.png` );
    $("#today_maxT").text( data["maxT"] );
    $("#today_minT").text( data["minT"] );
    $("#today_weatherCond").text( data["weatherCond"] );
    $("#today_comfortIdx").text( comfortIdxStmt(data["comfortIdx"]) );
    $("#today_rainProb").text( data["rainProb"] );
    $("#today_wind").text( data["wind"] );
}

function updateTwodayWeatherUI( dataset ) {
    for( let idx in dataset ) {
        let data = dataset[idx];
        let image = $("img.twoday-img").eq(idx);
        image.prop( "src", `image/weather/${config.weatherClassImg[data['weatherClass']]}.png` );
        let fieldset = $("div .twoday-info").eq(idx);
        fieldset.find("img").eq(idx).prop( "src", `image/weather/${config.weatherClassImg[data['weatherClass']]}.png` );
        fieldset.find('span.minT').text( data["minT"] );
        fieldset.find('span.maxT').text( data["maxT"] );
        fieldset.find('span.weatherCond').text( data["weatherCond"] );
        fieldset.find('span.comfortIdx').text( comfortIdxStmt(data["comfortIdx"]) );
        fieldset.find('span.rainProb').text( data["rainProb"] );
        fieldset.find('span.wind').text( data["wind"] );
    }
}

function updateWeekWeatherUI( dataset ) {
    tranDataFormat( dataset );
    $("#weatherBoxset").empty();
    if( Object.keys(weekWeatherDataset).length !== 0 ) {
        for( let date in weekWeatherDataset ) {
            let weatherBox = $("<div></div>").addClass("little-box")
                                            .addClass("bg-light")
                                            .addClass("p-2")
                                            .addClass("ml-2")
                                            .addClass("border")
                                            .addClass("rounded")
                                            .appendTo($("#weatherBoxset"));
            let month = parseInt(date.split("-")[1]);
            let day = parseInt(date.split("-")[2]);
            $("<h5></h5>").addClass("text-center").text(`${month}/${day}`).appendTo(weatherBox);
            $("<div></div>").appendTo( weatherBox );
            let minT = weekWeatherDataset[date][0]["minT"]; 
            let maxT = weekWeatherDataset[date][0]["maxT"];

            $("<p></p>").addClass("text-center")
            .html(`<span>${maxT}</span>°&nbsp;&nbsp;<span class="text-secondary">${minT}</span>°`)
            .appendTo( weatherBox );

            weatherBox.click( function () {
                $("#weatherBoxset").find(".little-box").eq(currentIndex).toggleClass("border-primary");
                currentIndex = $("#weatherBoxset").find(".little-box").index($(this));
                $(this).toggleClass("border-primary");

                let weekWeatherData = weekWeatherDataset[Object.keys(weekWeatherDataset)[currentIndex]];
                console.log( weekWeatherData );
                for( let idx in weekWeatherData ) {
                    let data = weekWeatherData[idx];
                    let image = $("img.week-img").eq(idx);
                    image.prop( "src", `image/weather/${config.weatherClassImg[data['weatherClass']]}.png` );
                    let fieldset = $("div .week-info").eq(idx);
                    fieldset.find('span.minT').text( data["minT"] );
                    fieldset.find('span.maxT').text( data["maxT"] );
                    fieldset.find('span.weatherCond').text( data["weatherCond"] );
                    fieldset.find('span.comfortIdx').text( comfortIdxStmt(data["comfortIdx"]) );
                    fieldset.find('span.rainProb').text( data["rainProb"] );
                    fieldset.find('span.wind').text( data["wind"] );
                }
            })
        }

        /* 獲取第一筆資料更新畫面 */
        let weekWeatherData = weekWeatherDataset[Object.keys(weekWeatherDataset)[0]];
        for( let idx in weekWeatherData ) {
            let data = weekWeatherData[idx];
            let image = $("img.week-img").eq(idx);
            image.prop( "src", `image/weather/${config.weatherClassImg[data['weatherClass']]}.png` );
            let fieldset = $("div .week-info").eq(idx);
            fieldset.find('span.minT').text( data["minT"] );
            fieldset.find('span.maxT').text( data["maxT"] );
            fieldset.find('span.weatherCond').text( data["weatherCond"] );
            fieldset.find('span.comfortIdx').text( comfortIdxStmt(data["comfortIdx"]) );
            fieldset.find('span.rainProb').text( data["rainProb"] );
            fieldset.find('span.wind').text( data["wind"] );
        }
        currentIndex = 0;
        $("#weatherBoxset").find(".little-box").eq(0).toggleClass("border-primary");
    }
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

function comfortIdxStmt( comfortIdx ) {
    if( comfortIdx < 10 ) {
        return "非常寒冷";
    } else if( 11 <= comfortIdx && comfortIdx <= 15 ) {
        return "寒冷";
    } else if( 16 <= comfortIdx && comfortIdx <= 19 ) {
        return "稍有寒意";
    } else if( 20 <= comfortIdx && comfortIdx <= 26 ) {
        return "舒適";
    } else if( 27 <= comfortIdx && comfortIdx <= 30 ) {
        return "悶熱";
    } else {
        return "易中暑";
    }
}

/* 轉換從api獲得的資料格式 */
function tranDataFormat( dataset ) {
    let newDataset = {};
    for( let data of dataset ) {
        let date = (data["startTime"].split(" "))[0];
        if( newDataset[date] !== undefined ) {
            newDataset[date].push(data);
        } else {
            newDataset[date] = [];
            newDataset[date].push(data);
        }
    }
    weekWeatherDataset = newDataset;
}

/* use for debug */
function echoData( dataset ) {
    console.log( dataset );
}