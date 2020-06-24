<?php defined('BASEPATH') or exit('No direct script access allowed');

class Covid_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    private function _map($array)
    {
        return array_map([$this, "_mapDate"], $array);
    }

    private function  _mapDate($entity)
    {
        return array_change_key_case($entity, CASE_LOWER);;
    }

    public function getAll()
    {
        return $this->_map($this->db
            ->group_by(["DATE_YEAR", "DATE_MONTH", "DATE_DAY", "COUNTRY_ID"])
            ->query("SELECT * FROM sys.COVID_COUTRIES")
            ->result_array());
    }
    public function getAllTmp()
    {
        return [
            [
                "country_id" => 1,
                "country_name" => "Viet Nam",
                "slug" => "viet-nam",
                "iso2" => "VN",
                "confirmed" => 3,
                "deathes" => 1,
                "recovered" => 1,
                "active" => 1,
                "date" => "2020-05-23"
            ],
            [
                "country_id" => 1,
                "country_name" => "Viet Nam",
                "slug" => "viet-nam",
                "iso2" => "VN",
                "confirmed" => 3,
                "deathes" => 1,
                "recovered" => 1,
                "active" => 1,
                "date" => "2020-05-22"
            ]
        ];
    }
}
