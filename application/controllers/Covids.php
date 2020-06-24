<?php defined('BASEPATH') or exit();

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
        {
            echo $this->repo->getAll();
        }
    }
}