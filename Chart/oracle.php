<?php
$conn = oci_connect('system', '12345', 'localhost:1521/orcl');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);

    die;
} else {
    function insert($conn)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.covid19api.com/countries",
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
        $prefix = "INSERT INTO sys.countries VALUES ";

        foreach (json_decode($response, true) as $key => $value) {
            $query = "$prefix(" . ($key + 1) . ",'" . str_replace("'", "''", $value["Country"]) . "','$value[Slug]','$value[ISO2]','','')";

            $stid = oci_parse($conn, $query);

            $res = oci_execute($stid);
        };

        if (empty(oci_error($stid))) {
        } else {
            print_r(oci_error($stid));
        }

        oci_close($conn);
    }
    function select($conn)
    {
        $s = oci_parse($conn, 'select * from sys.countries order by id');
        oci_execute($s);
        oci_fetch_all($s, $res);
        return $res;
    }
    function insert_covid($conn, $id, $code)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.covid19api.com/live/country/$code",
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

        // echo "<pre>";
        //     print_r(json_decode($response));
        // echo "</pre>";

        $query = "";
        $prefix = "INSERT INTO sys.covid_countries(COUNTRY_ID,CONFIRMED,DEATHS,RECOVERD,ACTIVE,DATE_TIME,DATE_YEAR,DATE_MONTH,DATE_DAY) VALUES ";

        foreach (json_decode($response, true) as $key => $value) {
            $date = date_create($value["Date"]);

            $query =
                "$prefix($id,$value[Confirmed],$value[Deaths],$value[Recovered],$value[Active],TO_DATE('" . date_format($date, "Y-m-d H-i-s") . "', 'yyyy/mm/dd hh24:mi:ss')," . date_format($date, "Y") . "," . date_format($date, "m") . "," . date_format($date, "d") . ")";

            echo $query . "<br>";

            $stid = oci_parse($conn, $query);

            $res = oci_execute($stid);
        };

        if (empty(oci_error($stid))) {
        } else print_r(oci_error($stid));

        oci_close($conn);
    }
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
            $stid = oci_parse($conn, $query);
            $res = oci_execute($stid);
        }
        if (empty(oci_error($stid))) {
        } else print_r(oci_error($stid));
        oci_close($conn);
    }
    function index_to_iso2($array)
    {
        foreach ($array as $key => $value) {
            $array[$value["ISO2"]] = $value;
            unset($array[$key]);
        }
        return $array;
    }
    
    $countries = index_to_iso2(select($conn));

    insert_covid_all($conn, $countries);


    // echo "<pre>";

    // print_r($countries);

    // echo "</pre>";

}
