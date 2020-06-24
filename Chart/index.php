<?php
function insert_covid_all($conn, $countries)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.covid19api.com/live/country/vietnam",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $query = "";
    $prefix = "INSERT INTO sys.covid_countries(COUNTRY_ID,CONFIRMED,DEATHS,RECOVERD,ACTIVE,DATE_TIME,DATE_YEAR,DATE_MONTH,DATE_DAY) VALUES ";

    foreach (json_decode($response, true) as $key => $value) {
        $id = $countries["$value[CountryCode]"]["ID"];

        $date = date_create($value["Date"]);

        $query =
            "$prefix($id,$value[Confirmed],$value[Deaths],$value[Recovered],$value[Active],TO_DATE('" . date_format($date, "Y-m-d H-i-s") . "', 'yyyy/mm/dd hh24:mi:ss')," . date_format($date, "Y") . "," . date_format($date, "m") . "," . date_format($date, "d") . ")";

        echo $query;
    }

}
function index_to_iso2($array)
{
    foreach ($array as $key => $value) {
        $array[$value["ISO2"]] = $value;
        unset($array[$key]);
    }
    return $array;
}

// $countries = select($conn);
$countries = [["ID" => 1,"ISO2" => "VN"],["ID" => 2,"ISO2" => "EN"]];

$countries = index_to_iso2($countries);

insert_covid_all("",$countries);