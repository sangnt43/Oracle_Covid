<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Global_Model extends CI_Model
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
        $entity["date"] = date_format(date_create("$entity[DATE_YEAR]-$entity[DATE_MONTH]-$entity[DATE_DAY]"),"Y-m-d");
        return array_change_key_case($entity,CASE_LOWER);;
    }

    public function getAll()
    {
        return $this->_map($this->db->query("SELECT * FROM sys.GLOBALS")->result_array());
    }
}