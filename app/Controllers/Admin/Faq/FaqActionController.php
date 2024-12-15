<?php

namespace App\Controllers\Admin\Faq;

use App\Models\FaqModel;

use App\Controllers\Admin\AdminController;

class FaqActionController extends AdminController
{
    private $backUrlList = '/prime/faq/list';
    public function index()
    {
        alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
        exit;
    }

    public function writeAction()
    {
        //action page
        $strStat = $this->request->getPost('faqStat');
        $iIdx = $this->request->getPost('idx');
        $strSort = $this->request->getPost('faqSort');
        $strQuestion = $this->request->getPost('faqQuestion');
        $strAnswer = $this->request->getPost('faqAnswer');
        if ((!$strQuestion || !$strAnswer) && in_array($strStat, ['mod', 'add'])) {
            alert_back($this->globalvar->aMsg['error1']);
            exit;
        }

        //query
        $readyDB = $this->masterDB->table('iv_faq')
            ->set([
                'faq_question' => $strQuestion,
                'faq_answer' => $strAnswer
            ])
            ->set(['faq_mod_date' => 'NOW()'], '', false);
        if ($strSort) {
            $readyDB->set(['faq_sort' => $strSort]);
        }
        if ($iIdx) {
            $result = $readyDB
                ->where('idx', $iIdx)
                ->update();
        } else {
            $result = $readyDB
                ->set(['faq_reg_date' => 'NOW()'], '', false)
                ->insert();
        }
        if ($result) {
            alert_url($this->globalvar->aMsg['success1'], $this->backUrlList);
            exit;
        } else {
            alert_back($this->globalvar->aMsg['error2']);
            exit;
        }
    }

    public function faqDel()
    {
        //action page
        $strStat = $this->request->getPost('faqStat');
        //del idx
        $aDelIdx = $this->request->getPost('faqDelIdx');
        if ($strStat == 'del' && count($aDelIdx) == 0) {
            return alert_back('잘못된접근입니다.');
            exit;
        }

        //query
        $result = $this->masterDB->table('iv_faq')
            ->set(['delyn' => 'Y'])
            ->set(['faq_del_date' => 'NOW()'], '', false)
            ->whereIn('idx', $aDelIdx)
            ->update();

        if ($result) {
            alert_url($this->globalvar->aMsg['success1'], $this->backUrlList);
            exit;
        } else {
            alert_back($this->globalvar->aMsg['error2']);
            exit;
        }
    }
}
