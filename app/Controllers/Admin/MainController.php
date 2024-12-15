<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use Config\Database;

use App\Controllers\Admin\AdminController;
class MainController extends AdminController
{
    public function index()
    {
        $this->main();
    }

    public function main()
    {
        // data init
        $this->commonData();
        // view
        $this->header();
        $this->nav();
        echo view('prime/index', $this->aData);
        $this->footer();
    }
}
