export const todayWeatherUrl = "./api/weather/today/";
export const twodayWeatherUrl = "./api/weather/twoday/";
export const weekWeatherUrl = "./api/weather/week/";
export const rainDataUrl = "./api/weather/rain/";
export const rainAvgDataUrl = "./api/weather/rainAvg/";
export const stationDataUrl = "./api/Station/info";

export const TW_CityName = [
    '臺北市', '新北市', '基隆市',
    '桃園市', '新竹縣', '新竹市',
    '苗栗縣', '臺中市', '南投縣',
    '彰化縣', '雲林縣', '嘉義縣',
    '嘉義市', '臺南市', '高雄市',
    '屏東縣', '宜蘭縣', '花蓮縣',
    '臺東縣', '澎湖縣', '金門縣', '連江縣'
];

export const TW_CityImg = {
    '彰化縣':"Changhua.jpg",
    '嘉義縣':"Chiayi.jpg",
    '嘉義市':"ChiayiCity.jpg",
    '新竹縣':"Hsinchu.jpg",
    '新竹市':"HsinchuCity.jpg",
    '花蓮縣':"Hualian.jpg",
    '高雄市':"Kaoshiung.jpg",
    '基隆市':"Keelung.jpg",
    '金門縣':"Kinmen.jpg",
    '連江縣':"Lienchiang.jpg",
    '苗栗縣':"Miaoli.jpg",
    '南投縣':"Nantou.jpg",
    '新北市':"NewTaipei.jpg",
    '澎湖縣':"Penghu.jpg",
    '屏東縣':"Pingtun.jpg",
    '臺中市':"Taichung.jpg",
    '臺東縣':"Taidong.jpg",
    '臺南市':"Tainan.jpg",
    '臺北市':"Taipei.jpg",
    '桃園市':"Taoyuan.jpg",
    '宜蘭縣':"Yilan.jpg",
    '雲林縣':"Yunlin.jpg"
};

export const weatherClassImg = [
    1, 1, 3, 4, 4, 7, 7, 7, 19,
    18, 18, 24, 24, 24, 18, 21, 22,
    22, 22, 6, 24, 22, 22, 23, 5, 5, 
    5, 5, 15, 18, 18, 24, 18, 21, 22, 
    22, 22, 15, 7, 15, 14, 15, 30
];

export default { weekWeatherUrl ,todayWeatherUrl, twodayWeatherUrl,
     TW_CityName, TW_CityImg, weatherClassImg,
     rainDataUrl, rainAvgDataUrl, stationDataUrl };