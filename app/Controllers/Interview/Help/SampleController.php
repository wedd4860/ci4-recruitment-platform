<?php

namespace App\Controllers\Interview\Help;

use App\Controllers\BaseController;
use App\Controllers\Interview\WwwController;



use App\Models\ApplierModel;
use App\Models\jobCategoryModel;

use Config\Database;
use Config\Services;

class SampleController extends WwwController
{
    public $db;
    public function __construct()
    {
        $this->db = Database::connect();
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
        $this->commonData();

        $this->header();

        $aCheck = $this->request->getGet('updown');
        $aCateCheck = $this->request->getGet('cateCheck');
        $ApplierModel = new ApplierModel();
        $jobCategoryModel = new jobCategoryModel('iv_job_category');


        if ($aCheck || $aCateCheck) {
            if ($aCateCheck) {
                $aGetCheckCate = $jobCategoryModel->getCheckCate($aCateCheck);
                $aSampleInfo = $ApplierModel->sampleList($aCheck, $aGetCheckCate);
            } else {
                $aSampleInfo = $ApplierModel->sampleList($aCheck, $aCateCheck);
            }
        } else {
            $aSampleInfo = $ApplierModel->sampleList();
        }

        if (!cache('aAllCate')) {
            $this->aData['data']['jobCate'] = $jobCategoryModel->getAllcategory();
        } else {
            $this->aData['data']['jobCate'] = cache('aAllCate');
        }



        $this->aData['data']['sampleList'] = $aSampleInfo->paginate(3, 'sample');
        $this->aData['data']['pager'] = $aSampleInfo->pager;
        $this->aData['data']['get']['cate'] = $aCateCheck;
        $this->aData['data']['get']['updown'] = $aCheck;

        echo view("www/help/sample/list", $this->aData);
    }

    public function __destruct()
    {
        $this->db->close();
    }
}
