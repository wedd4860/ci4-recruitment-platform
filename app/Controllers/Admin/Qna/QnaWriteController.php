<?php

namespace App\Controllers\Admin\Qna;

use App\Models\QnaModel;
use App\Controllers\Admin\AdminController;

class QnaWriteController extends AdminController
{
    private $backUrlList = '/prime/qna/list';
    public function index(string $code, int $idx = null)
    {
        alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
        exit;
    }

    public function write(int $idx = null)
    {
        if (!$idx) {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
            exit;
        }
        $this->commonData();

        //model
        $qnaModel = new QnaModel();
        $aQnaRow = $qnaModel
            ->select([
                'iv_member.idx as memIdx', 'iv_member.mem_id as memId', 'iv_member.mem_name as memName',
                'iv_qna.idx as qnaIdx', 'iv_qna.qna_reg_date as qnaRegDate', 'iv_qna.qna_title as qnaTitle', 'iv_qna.qna_question as qnaQuestion', 'iv_qna.qna_answer as qnaAnswer',
            ])
            ->join('iv_member', 'iv_qna.mem_idx_m = iv_member.idx')
            ->where(['iv_qna.delyn' => 'N', 'iv_qna.idx' => $idx])->first();

        $this->aData['data']['list'] = $aQnaRow;

        // view
        $this->header();
        $this->nav();
        echo view('prime/qna/write', $this->aData);
        $this->footer();
    }
}
