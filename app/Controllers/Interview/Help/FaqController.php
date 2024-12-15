<?php

namespace App\Controllers\Interview\Help;

use App\Controllers\Interview\WwwController;

use App\Models\FaqModel;

use Config\Services;

class FaqController extends WwwController
{

    public function index()
    {
        $this->list();
    }

    public function list()
    {
        $this->commonData();

        $faqModel = new FaqModel();
        $faqModel->where('delyn', 'N')->orderBy('idx', 'desc');

        $this->aData['data']['list'] = $faqModel->findAll();
        $this->aData['data']['pager'] = $faqModel->pager;

        // view
        $this->header();
        echo view('www/help/faq/list', $this->aData);
        $this->footer();
    }
}
