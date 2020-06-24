<?php defined('BASEPATH') or exit("No direct script access allowed");

class Covids extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Covid_Model","repo");
    }
    public function getAll()
    {
        if(isset(getallheaders()["HTTP_X_REQUESTED_WITH"]))
            echo json_encode($this->repo->getAll());
    }
}