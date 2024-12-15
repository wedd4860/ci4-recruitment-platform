<?php

namespace App\Controllers\Interview\Help;

use App\Controllers\Interview\WwwController;

use Config\Services;

class GuideController extends WwwController
{
    public function index()
    {
        $this->interview();
    }

    public function interview()
    {
        // data init
        $this->commonData();

        $this->header();
        echo view("www/help/guide/interview", $this->aData);
    }
}