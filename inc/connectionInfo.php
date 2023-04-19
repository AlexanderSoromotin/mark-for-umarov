<?
include_once 'SxGeo.php';
// include_once '../db.php';

$user_agent = $_SERVER["HTTP_USER_AGENT"];
if (strpos($user_agent, "Firefox") !== false) $browser = "Firefox";
elseif (strpos($user_agent, "Opera") !== false) $browser = "Opera";
elseif (strpos($user_agent, "Chrome") !== false) $browser = "Chrome";
elseif (strpos($user_agent, "MSIE") !== false) $browser = "Internet Explorer";
elseif (strpos($user_agent, "Safari") !== false) $browser = "Safari";
else $browser = "Неизвестный";

function getIp() {
    $keys = [
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'REMOTE_ADDR'
    ];
    foreach ($keys as $key) {
        if (!empty($_SERVER[$key])) {
            $ip = trim(end(explode(',', $_SERVER[$key])));
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        } 
    }
}

$ip = getIp();

// IPWHOIS API
$user_location_data = json_decode(file_get_contents("http://ipwhois.app/json/" . $ip . '?lang=ru'), 1);
if ($user_location_data['success']) {
    $connectionInfo = array(
        "browser" => $browser,
        "ip" => $ip,
        "isp" => $user_location_data['isp'],
        "country" => $user_location_data['country'],
        "region" => $user_location_data['region'],
        "city" => $user_location_data['city'],
        "latitude" => $user_location_data['latitude'],
        "longitude" => $user_location_data['longitude']
    );
}

// $ip = '176.59.204.177';
// $ip = '93.80.42.110';
// $ip = '93.186.225.208';

// $SxGeo = new SxGeo('https://findcreek.com/inc/SxGeoCity.dat', SXGEO_BATCH | SXGEO_MEMORY);
// // https://www.pvsm.ru/php-2/10293// print_r($res);
// $location_data = $SxGeo->getCityFull($ip);
// var_dump($location_data);
// также можно использовать следующий код
// $SxGeo->getCity($ip);

// // широта
// $lat = $location_data['city']['lat'];
// // долгота
// $lon = $location_data['city']['lon'];
// // название города на русском языке
// $city_name_ru = $location_data['city']['name_ru'];
// // название города на английском языке
// $city_name_en = $location_data['city']['name_en'];
// // ISO-код страны
// $country = $location_data['country']['name_ru'];


// // $region = $SxGeo->getCityFull($ip);
// // название региона на русском языке
// $region_name_ru = $location_data['region']['name_ru'];

// $isp = file_get_contents("https://api.iplocation.net/?ip=" . $ip);
// $isp = json_decode($isp, 1);
// $isp = $isp['isp'];

// $connectionInfo = array(
//     "browser" => $browser,
//     "ip" => $ip,
//     "isp" => $isp,
//     "country" => $country,
//     "region" => $region_name_ru,
//     "city" => $city_name_ru,
//     "lat" => $lat,
//     "lon" => $lon
// );

// // var_dump($connectionInfo);




?>
