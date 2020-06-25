<?php defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $this->load->view("index");
    }
    // public function getDashBoard()
    // {
    //     $this->load->model("Global_Model", "global");
    //     $this->load->model("Country_Model", "countries");
    //     $this->load->model("Covid_Model", "covid");
    //     return [
    //         "global" => $this->global->getAll(),
    //         "countries" => $this->countries->getAll(),
    //         "covid" => $this->covid->getAll()
    //     ];
    // }
}
