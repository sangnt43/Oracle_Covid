<?php defined('BASEPATH') or exit('No direct script access allowed');

class Covid_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    private function _map($array)
    {
        $_array = [];
        $array = array_map([$this, "_mapDate"], $array);

        foreach($array as $value) {
            $date =  date_format(date_create("$value[date_year]-$value[date_month]-$value[date_day]"),"Y-m-d");

            $_find = array_search($date,array_column($_array,"date"));

            if($_find === FALSE) {
                $_find = count($_array);
                $_array[] = [ "date" => $date, "list" => [] ];
            }
            $_array[$_find]["list"][] = [
                "id" => $value["id"],
                "name" => $value["name"],
                "confirmed" => $value["confirmed"],
                "deaths" => $value["deaths"],
                "recovered" => $value["recoverd"],
                "active" => $value["active"]
            ];
        }
        return $_array;
    }

    private function  _mapDate($entity)
    {
        return array_change_key_case($entity, CASE_LOWER);;
    }

    public function getAll()
    {
        $query = "
        SELECT sys.COUNTRIES.ISO2 ID, sys.COUNTRIES.COUNTRY_NAME NAME, confirmed,deaths,recoverd,active, DATE_YEAR, DATE_MONTH, DATE_DAY FROM (            
            SELECT COUNTRY_ID, sum(confirmed) confirmed,sum(deaths) deaths,sum(recoverd) recoverd,sum(active) active, DATE_YEAR, DATE_MONTH, DATE_DAY
                FROM SYS.COVID_COUNTRIES GROUP BY DATE_YEAR, DATE_MONTH, DATE_DAY, COUNTRY_ID
                ORDER BY DATE_YEAR, DATE_MONTH, DATE_DAY, COUNTRY_ID) covid 
        JOIN sys.COUNTRIES ON sys.countries.id = covid.COUNTRY_ID
        ";
        return $this->_map($this->db->query($query)->result_array());
    }
    public function insert($countryId,$confirmed,$deaths,$recovered,$active,$date)
    {
        $query = "EXEC SP_INSERT_COVID_COUNTRIES ($countryId,$confirmed,$deaths,$recovered,$active,TO_DATE('".date_format(date_create($date),"Y-m-d H-i-s")."','DD-MM-YYYY HH24:MI:SS'));";
        $this->db->query($query);
        return $this->_map( $this->db->row_affect() );
    }
    public function getAllTmp()
    {
        return json_decode(file_get_contents(__DIR__."/../../public/data/covids.txt"));
    }
}
