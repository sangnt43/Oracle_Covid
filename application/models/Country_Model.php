<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Country_Model extends CI_Model
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
        array_pop($entity); array_pop($entity);
        return array_change_key_case($entity,CASE_LOWER);;
    }

    public function getAll()
    {
        return $this->_map($this->db->query("SELECT * FROM sys.Countries ORDER BY ID")->result_array());
    }
    public function getAllTmp()
    {
        return json_decode(file_get_contents(__DIR__."/../../public/data/countries.txt"));
    }
}