<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Covid_Model extends CI_Model
{
    public function __contruct()
    {
        parent::__contruct();
    }

    private function _map($array) 
    {
        return array_map([$this,"_mapDate"], $array);
    }

    private function  _mapDate($entity)
    {
        return array_change_key_case($entity,CASE_LOWER);;
    }

    public function getAll()
    {
        return $this->_map($this->db
                        ->group_by(["DATE_YEAR","DATE_MONTH","DATE_DAY","COUNTRY_ID"])
                        ->query("SELECT * FROM sys.COVID_COUTRIES")
                        ->result_array());
    }
}