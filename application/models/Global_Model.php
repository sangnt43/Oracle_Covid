<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Global_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    private function _map($array) 
    {
        return array_map([$this,"_mapDate"], $array);
    }

    private function  _mapDate($entity)
    {
        $entity["date"] = date_format(date_create("$entity[DATE_YEAR]-$entity[DATE_MONTH]-$entity[DATE_DAY]"),"YY-m-d");
        return array_change_key_case($entity,CASE_LOWER);;
    }

    // public function getAll()
    // {
    //     return $this->_map($this->db->query("SELECT * FROM sys.GLOBALS")->result_array());
    // }
    
    public function getAll()
    {
        $query = "SELECT * FROM sys.GLOBALS ORDER BY DATE_YEAR,DATE_MONTH,DATE_DAY";

        return $this->_map($this->db->query($query)->result_array());
    }
    public function getTotalByCountry()
    {
        $query = "SELECT * FROM sys.COUNTRIES
        LEFT JOIN (SELECT sys.covid_countries.country_id, MAX(confirmed) confirmed,MAX(deaths) DEATHES,MAX(recoverd) RECOVERED,MAX(active) ACTIVE 
                    FROM sys.COVID_COUNTRIES GROUP BY country_id) covid
        ON covid.country_id = sys.countries.id
        ORDER BY sys.COUNTRIES.id";
        
        return $this->_map($this->db->query($query)->result_array());

    }
    public function getAllTmp()
    {
        return json_decode(file_get_contents(__DIR__."/../../public/data/globals.txt"));
    }
}