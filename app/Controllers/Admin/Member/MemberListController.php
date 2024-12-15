<?php

namespace App\Controllers\Admin\Member;

use App\Controllers\Admin\AdminController;
use App\Models\MemberModel;

class MemberListController extends AdminController
{
    private $backUrlList = '/prime/member/list';

    public function index()
    {
        alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
        exit;
    }

    public function list(string $code = 'm')
    {
        //get
        $strSearchText = $this->request->getGet('searchText');

        $strType = $code;
        if (!$strType || !in_array($strType, ['m', 'c', 'a', 'l'])) {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
            exit;
        }
        // data init
        $this->commonData();

        // $faqModel = new FaqModel();
        // $faqModel->where('delyn', 'N')->orderBy('idx', 'desc');

        // if ($strSearchText) {
        //     $faqModel->like('faq_question', $strSearchText, 'both');
        // }
        // $this->aData['data']['list'] = $faqModel->paginate(5, 'page');
        // $this->aData['data']['pager'] = $faqModel->pager;

        //SELECT COUNT(*) AS `numrows` FROM `iv_member` WHERE `mem_type` = 'M' AND `delyn` = 'N' AND `iv_member`.`mem_del_date` IS NULL
        //SELECT * FROM `iv_member` WHERE `mem_type` = 'M' AND `delyn` = 'N' AND `iv_member`.`mem_del_date` IS NULL ORDER BY `idx` DESC LIMIT 5
        //SELECT * FROM `iv_faq` WHERE `delyn` = 'N' AND `iv_faq`.`faq_del_date` IS NULL ORDER BY `idx` DESC LIMIT 5

        $strType = strtoupper($strType);
        //model
        $memberModel = new MemberModel();
        $memberModel->where(['mem_type' => $strType, 'delyn' => 'N'])->orderBy('idx', 'desc');
        if ($strSearchText) {
            $memberModel->like('mem_id', $strSearchText, 'both');
        }
        $this->aData['data']['list'] = $memberModel->paginate(10, 'member');
        $this->aData['data']['pager'] = $memberModel->pager;

        //검색정보
        $this->aData['data']['aData'] = [
            'code' => $code,
        ];

        $this->aData['data']['search'] = [
            'text' => $strSearchText
        ];

        // view
        $this->header();
        $this->nav();
        echo view('prime/member/list', $this->aData);
        $this->footer();
    }
}
