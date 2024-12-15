<?php

namespace App\Controllers\Admin\Member;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;

use Config\Database;

class Member extends BaseController
{
    public $db;
    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function index()
    {
    }

    public function create()
    {
    }

    public function show($id = null)
    {
    }

    public function update($id = null)
    {
    }

    public function delete($id = null)
    {
    }

    public function __destruct()
    {
        $this->db->close();
    }
}
