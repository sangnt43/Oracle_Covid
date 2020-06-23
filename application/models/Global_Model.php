<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Global_Model extends CI_Model
{
    public function __contruct()
    {
        parent::__contruct();
    }

    public function getAll()
    {
        return $this->db->query("SELECT * FROM sys.GLOBALS")->result_array();
    }
}