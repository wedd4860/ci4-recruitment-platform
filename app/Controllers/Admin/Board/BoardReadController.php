<?php

namespace App\Controllers\Admin\Board;

use App\Controllers\Admin\AdminController;
use App\Models\BoardModel;
use App\Models\Board;
use Config\Services;

class BoardReadController extends AdminController
{
    private $backUrlList = '/prime/board/list';

    public function index(string $code, int $idx)
    {
        $this->read($code, $idx);
    }

    public function read(string $code, int $idx)
    {
        if (!$code || !$idx) {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
            exit;
        }
        $this->commonData();

        $board = new Board($code, $idx);
        $aReadBd = $board->read(true);

        if ($aReadBd['code'] != 200) {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
            exit;
        }

        $commentModel = new BoardModel($code, 'comment');
        $commentTable = 'iv_comment_' . $code;
        $aCommentRow = $commentModel
            ->select([
                'iv_member.idx as memIdx', 'iv_member.mem_name as memName', $commentTable . '.idx as cmtIdx', $commentTable . '.cmt_comment as cmtComment', $commentTable . '.cmt_reg_date as cmtRegDate'
            ])
            ->join('iv_member', $commentTable . '.mem_idx = iv_member.idx')
            ->where([$commentTable . '.cmt_bd_idx' => $idx, $commentTable . '.delyn' => 'N'])->orderBy('`cmt_family` DESC, `cmt_sort` ASC, `cmt_depth` ASC')->findAll();

        $this->aData['data']['bd'] = $aReadBd;
        $this->aData['data']['cmt'] = $aCommentRow;
        $this->aData['data']['aData'] = [
            'code' => $code,
            'idx' => $idx,
        ];

        // view
        $this->header();
        $this->nav();
        echo view('prime/board/boardRead', $this->aData);
        $this->footer();
    }
}
