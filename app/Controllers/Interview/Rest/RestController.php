<?php

namespace App\Controllers\Interview\Rest;

use App\Controllers\BaseController;

use Config\Database;
use Config\Services;

class RestController extends BaseController
{
    public $db;
    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function commonData(): array
    {
        // data init
        $aData = [];
        $aData['data'] = $this->viewData;
        return $aData;
    }

    public function header()
    {
        // data init
        $aData = $this->commonData();
        echo view('www/templates/header', $aData);
    }

    public function nav()
    {
        // data init
        $aData = $this->commonData();
    }

    public function footer()
    {
        // data init
        $aData = $this->commonData();
    }

    public function index()
    {
        $this->list();
    }

    public function list()
    {
        // data init
        $aData = $this->commonData();

        $this->header();
        echo view("www/rest/list", $aData);
    }
    public function detail()
    {
        // data init
        $aData = $this->commonData();

        $this->header();
        echo view("www/rest/detail", $aData);
    }

    public function __destruct()
    {
        $this->db->close();
    }
}
