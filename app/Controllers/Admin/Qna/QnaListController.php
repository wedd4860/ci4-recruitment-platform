<?php

namespace App\Controllers\Admin\Qna;

use App\Controllers\Admin\AdminController;
use App\Models\QnaModel;

class QnaListController extends AdminController
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

        //model
        $qnaModel = new QnaModel();
        $qnaModel
            ->select([
                'iv_member.idx as memIdx', 'iv_member.mem_id as memId', 'iv_member.mem_name as memName',
                'iv_qna.idx as qnaIdx', 'iv_qna.mem_idx_a as memIdxA', 'iv_qna.qna_reg_date as qnaRegDate', 'iv_qna.qna_title as qnaTitle',
            ])
            ->join('iv_member', 'iv_qna.mem_idx_m = iv_member.idx')
            ->where('iv_qna.delyn', 'N')
            ->orderBy('qnaIdx', 'desc');

        if ($strSearchText) {
            $qnaModel->like('iv_qna.qna_title', $strSearchText, 'both');
        }
        $this->aData['data']['list'] = $qnaModel->paginate(5, 'qna');
        $this->aData['data']['pager'] = $qnaModel->pager;

        //검색정보
        $this->aData['data']['search'] = [
            'text' => $strSearchText
        ];

        // view
        $this->header();
        $this->nav();
        echo view('prime/qna/list', $this->aData);
        $this->footer();
    }
}
