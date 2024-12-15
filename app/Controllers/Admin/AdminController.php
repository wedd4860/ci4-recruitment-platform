<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Database;

class AdminController extends BaseController
{
    public $masterDB;
    protected $aData = [];

    public function __construct()
    {
        $this->masterDB = Database::connect('master');
    }
    public function commonData()
    {
        // data init
        $aCommon = [];
        $aCommon['data'] = $this->viewData;
        $aCommon['data']['page'] = $this->request->getUri()->getPath();

        $session = session();
        $aCommon['data']['session'] = [
            'idx' => $session->get('idx'),
            'id' => $session->get('mem_id'),
            'name' => $session->get('mem_name')
        ];

        $this->aData = $aCommon;
    }

    public function header()
    {
        echo view('prime/templates/header', $this->aData);
    }
    public function nav()
    {
        echo view('prime/templates/topNav', $this->aData);
        echo view('prime/templates/leftNav', $this->aData);
        echo view('prime/templates/contentHeader.php', $this->aData);
    }

    public function footer()
    {
        echo view('prime/templates/contentFooter.php', $this->aData);
        echo view('prime/templates/footer.php', $this->aData);
    }

    public function __destruct()
    {
        session_write_close();
        $this->masterDB->close();
    }
}
