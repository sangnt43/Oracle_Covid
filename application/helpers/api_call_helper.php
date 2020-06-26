<?php

if (!function_exists("debug")) {
    function debug($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
}

if (!function_exists("schedule")) {
    function schedule($id, $iso2)
    {
        $ci = get_instance();
        $ci->load->model("Covid_Model", "repo");

        $time_1 = date("Y-m-d\TH:i:s\Z", time() - 12 * 60 * 60);
        $time_2 = date("Y-m-d\TH:i:s\Z", time() - 2 * 60 * 60);
        $url = "https://api.covid19api.com/country/$iso2/status/confirmed?from=$time_1&to=$time_2";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL =>  $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "HTTP_X_REQUESTED_WITH: AJAX"
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        foreach (json_decode($response, true) as $key => $value) 
            $ci->repo->insert($id, $value["Confirmed"], $value["Deaths"], $value["Recovered"], $value["Active"], $value["Date"]);
    }
}
