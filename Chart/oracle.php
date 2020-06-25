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
            CURLOPT_URL => "https=>//api.covid19api.com/countries",
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
            CURLOPT_URL => "https=>//api.covid19api.com/live/country/$code",
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
                "$prefix($id,$value[Confirmed],$value[Deaths],$value[Recovered],$value[Active],TO_DATE('" . date_format($date, "Y-m-d H-i-s") . "', 'yyyy/mm/dd hh24=>mi=>ss')," . date_format($date, "Y") . "," . date_format($date, "m") . "," . date_format($date, "d") . ")";

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
            CURLOPT_URL => "https=>//api.covid19api.com/live/country/vietnam",
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
                "$prefix($id,$value[Confirmed],$value[Deaths],$value[Recovered],$value[Active],TO_DATE('" . date_format($date, "Y-m-d H-i-s") . "', 'yyyy/mm/dd hh24=>mi=>ss')," . date_format($date, "Y") . "," . date_format($date, "m") . "," . date_format($date, "d") . ")";
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
    function update_population($conn, $countries_populations)
    {
        foreach ($countries_populations as $ISO2 => $population) {

            $query = "UPDATE sys.COUNTRIES SET POPULATIONS = $population WHERE ISO2 = '$ISO2'";

            $stid = oci_parse($conn, $query);

            $res = oci_execute($stid);
        }

        if (empty(oci_error($stid))) {
        } else {
            print_r(oci_error($stid));
        }

        oci_close($conn);
    }

    $countries_populations = [
        "AD" => "84000",
        "AE" => "4975593",
        "AF" => "29121286",
        "AG" => "86754",
        "AI" => "13254",
        "AL" => "2986952",
        "AM" => "2968000",
        "AN" => "300000",
        "AO" => "13068161",
        "AQ" => "0",
        "AR" => "41343201",
        "AS" => "57881",
        "AT" => "8205000",
        "AU" => "21515754",
        "AW" => "71566",
        "AX" => "26711",
        "AZ" => "8303512",
        "BA" => "4590000",
        "BB" => "285653",
        "BD" => "156118464",
        "BE" => "10403000",
        "BF" => "16241811",
        "BG" => "7148785",
        "BH" => "738004",
        "BI" => "9863117",
        "BJ" => "9056010",
        "BL" => "8450",
        "BM" => "65365",
        "BN" => "395027",
        "BO" => "9947418",
        "BQ" => "18012",
        "BR" => "201103330",
        "BS" => "301790",
        "BT" => "699847",
        "BV" => "0",
        "BW" => "2029307",
        "BY" => "9685000",
        "BZ" => "314522",
        "CA" => "33679000",
        "CC" => "628",
        "CD" => "70916439",
        "CF" => "4844927",
        "CG" => "3039126",
        "CH" => "7581000",
        "CI" => "21058798",
        "CK" => "21388",
        "CL" => "16746491",
        "CM" => "19294149",
        "CN" => "1330044000",
        "CO" => "47790000",
        "CR" => "4516220",
        "CS" => "10829175",
        "CU" => "11423000",
        "CV" => "508659",
        "CW" => "141766",
        "CX" => "1500",
        "CY" => "1102677",
        "CZ" => "10476000",
        "DE" => "81802257",
        "DJ" => "740528",
        "DK" => "5484000",
        "DM" => "72813",
        "DO" => "9823821",
        "DZ" => "34586184",
        "EC" => "14790608",
        "EE" => "1291170",
        "EG" => "80471869",
        "EH" => "273008",
        "ER" => "5792984",
        "ES" => "46505963",
        "ET" => "88013491",
        "FI" => "5244000",
        "FJ" => "875983",
        "FK" => "2638",
        "FM" => "107708",
        "FO" => "48228",
        "FR" => "64768389",
        "GA" => "1545255",
        "GB" => "62348447",
        "GD" => "107818",
        "GE" => "4630000",
        "GF" => "195506",
        "GG" => "65228",
        "GH" => "24339838",
        "GI" => "27884",
        "GL" => "56375",
        "GM" => "1593256",
        "GN" => "10324025",
        "GP" => "443000",
        "GQ" => "1014999",
        "GR" => "11000000",
        "GS" => "30",
        "GT" => "13550440",
        "GU" => "159358",
        "GW" => "1565126",
        "GY" => "748486",
        "HK" => "6898686",
        "HM" => "0",
        "HN" => "7989415",
        "HR" => "4284889",
        "HT" => "9648924",
        "HU" => "9982000",
        "ID" => "242968342",
        "IE" => "4622917",
        "IL" => "7353985",
        "IM" => "75049",
        "IN" => "1173108018",
        "IO" => "4000",
        "IQ" => "29671605",
        "IR" => "76923300",
        "IS" => "308910",
        "IT" => "60340328",
        "JE" => "90812",
        "JM" => "2847232",
        "JO" => "6407085",
        "JP" => "127288000",
        "KE" => "40046566",
        "KG" => "5776500",
        "KH" => "14453680",
        "KI" => "92533",
        "KM" => "773407",
        "KN" => "51134",
        "KP" => "22912177",
        "KR" => "48422644",
        "KW" => "2789132",
        "KY" => "44270",
        "KZ" => "15340000",
        "LA" => "6368162",
        "LB" => "4125247",
        "LC" => "160922",
        "LI" => "35000",
        "LK" => "21513990",
        "LR" => "3685076",
        "LS" => "1919552",
        "LT" => "2944459",
        "LU" => "497538",
        "LV" => "2217969",
        "LY" => "6461454",
        "MA" => "33848242",
        "MC" => "32965",
        "MD" => "4324000",
        "ME" => "666730",
        "MF" => "35925",
        "MG" => "21281844",
        "MH" => "65859",
        "MK" => "2062294",
        "ML" => "13796354",
        "MM" => "53414374",
        "MN" => "3086918",
        "MO" => "449198",
        "MP" => "53883",
        "MQ" => "432900",
        "MR" => "3205060",
        "MS" => "9341",
        "MT" => "403000",
        "MU" => "1294104",
        "MV" => "395650",
        "MW" => "15447500",
        "MX" => "112468855",
        "MY" => "28274729",
        "MZ" => "22061451",
        "NA" => "2128471",
        "NC" => "216494",
        "NE" => "15878271",
        "NF" => "1828",
        "NG" => "154000000",
        "NI" => "5995928",
        "NL" => "16645000",
        "NO" => "5009150",
        "NP" => "28951852",
        "NR" => "10065",
        "NU" => "2166",
        "NZ" => "4252277",
        "OM" => "2967717",
        "PA" => "3410676",
        "PE" => "29907003",
        "PF" => "270485",
        "PG" => "6064515",
        "PH" => "99900177",
        "PK" => "184404791",
        "PL" => "38500000",
        "PM" => "7012",
        "PN" => "46",
        "PR" => "3916632",
        "PS" => "3800000",
        "PT" => "10676000",
        "PW" => "19907",
        "PY" => "6375830",
        "QA" => "840926",
        "RE" => "776948",
        "RO" => "21959278",
        "RS" => "7344847",
        "RU" => "140702000",
        "RW" => "11055976",
        "SA" => "25731776",
        "SB" => "559198",
        "SC" => "88340",
        "SD" => "35000000",
        "SE" => "9828655",
        "SG" => "4701069",
        "SH" => "7460",
        "SI" => "2007000",
        "SJ" => "2550",
        "SK" => "5455000",
        "SL" => "5245695",
        "SM" => "31477",
        "SN" => "12323252",
        "SO" => "10112453",
        "SR" => "492829",
        "SS" => "8260490",
        "ST" => "175808",
        "SV" => "6052064",
        "SX" => "37429",
        "SY" => "22198110",
        "SZ" => "1354051",
        "TC" => "20556",
        "TD" => "10543464",
        "TF" => "140",
        "TG" => "6587239",
        "TH" => "67089500",
        "TJ" => "7487489",
        "TK" => "1466",
        "TL" => "1154625",
        "TM" => "4940916",
        "TN" => "10589025",
        "TO" => "122580",
        "TR" => "77804122",
        "TT" => "1228691",
        "TV" => "10472",
        "TW" => "22894384",
        "TZ" => "41892895",
        "UA" => "45415596",
        "UG" => "33398682",
        "UM" => "0",
        "US" => "310232863",
        "UY" => "3477000",
        "UZ" => "27865738",
        "VA" => "921",
        "VC" => "104217",
        "VE" => "27223228",
        "VG" => "21730",
        "VI" => "108708",
        "VN" => "89571130",
        "VU" => "221552",
        "WF" => "16025",
        "WS" => "192001",
        "XK" => "1800000",
        "YE" => "23495361",
        "YT" => "159042",
        "ZA" => "49000000",
        "ZM" => "13460305",
        "ZW" => "13061000"
    ];

    // $countries = index_to_iso2(select($conn));

    // insert_covid_all($conn, $countries);

    update_population($conn,$countries_populations);
}
