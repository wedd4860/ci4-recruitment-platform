<?php
namespace App\Controllers\Interview\Interview\Guide;
use App\Controllers\Interview\WwwController;

class GuideController extends WwwController
{
    public function index()
    {
        $this->homeGuideList();
    }

    public function homeGuideList()
    {
        $this->commonData(); 
        $this->header();
        echo view("www/interview/guide/list", $this->aData);
    }

    public function interviewGuideList()
    {
        $this->commonData(); 
        $this->header();
        echo view("www/interview/guide/interview", $this->aData);
    }
}