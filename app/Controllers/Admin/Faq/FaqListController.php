<?php

namespace App\Controllers\Admin\Faq;

use App\Models\FaqModel;

use App\Controllers\Admin\AdminController;

class FaqListController extends AdminController
{
    public function index()
    {
        $this->list();
    }

    public function list()
    {
        $this->commonData();
        //get
        $strSearchText = $this->request->getGet('searchText');

        $faqModel = new FaqModel();
        $faqModel->where('delyn', 'N')->orderBy('idx', 'desc');

        if ($strSearchText) {
            $faqModel->like('faq_question', $strSearchText, 'both');
        }
        $this->aData['data']['list'] = $faqModel->paginate(5, 'faq');
        $this->aData['data']['pager'] = $faqModel->pager;

        // view
        $this->header();
        $this->nav();
        echo view('prime/faq/list', $this->aData);
        $this->footer();
    }
}
