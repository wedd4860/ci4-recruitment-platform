<?php

namespace App\Controllers\Admin\Qna;
use App\Controllers\Admin\AdminController;

class QnaActionController extends AdminController
{
    private $backUrlList = '/prime/qna/list';
    public function index()
    {
        alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
        exit;
    }

    public function qnaAction()
    {
        $strPostCase = $this->request->getPost('postCase');
        $iIdx = $this->request->getPost('idx');
        $strBackUrl = $this->request->getPost('backUrl');
        $IAdminIdx = $this->request->getPost('adminIdx');
        $strQnaAnswer = $this->request->getPost('qnaAnswer');

        if (!$iIdx || !$IAdminIdx || $strPostCase != 'qna_write') {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
            exit;
        }
        $this->commonData();

        //query
        $readyDB = $this->masterDB->table('iv_qna')
            ->set([
                'mem_idx_a' => $IAdminIdx,
                'qna_answer' => $strQnaAnswer
            ])
            ->set(['qna_mod_date' => 'NOW()'], '', false);
        if ($iIdx) {
            $result = $readyDB
                ->where('idx', $iIdx)
                ->update();
        } else {
            $result = $readyDB
                ->set(['qna_reg_date' => 'NOW()'], '', false)
                ->insert();
        }

        if ($result) {
            alert_url($this->globalvar->aMsg['success1'], $strBackUrl != '' ? $strBackUrl : $this->backUrlList);
            exit;
        } else {
            alert_back($this->globalvar->aMsg['error2']);
            exit;
        }
    }
}
