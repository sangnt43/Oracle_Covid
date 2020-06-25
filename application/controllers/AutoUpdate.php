<?php defined("BASEPATH") or exit("No direct script access allowed");

class AutoUpdate extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model("Country_Model", "repo");
    }

    public function index()
    {
        $data = $this->repo->getAll();

        foreach ($data as $key => $value)
            schedule($value["id"], $value["slug"]);
    }
}
