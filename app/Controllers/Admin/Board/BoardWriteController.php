<?php

namespace App\Controllers\Admin\Board;

use App\Models\BoardModel;
use App\Models\Board;

use App\Controllers\Admin\AdminController;

class BoardWriteController extends AdminController
{
    private $backUrlList = '/prime/board/list';

    public function index(string $code, int $idx = null)
    {
        if ($code == 'set') {
            $this->setWrite($code, $idx);
        } else {
            $this->write($code, $idx);
        }
    }

    public function setWrite($code, $idx)
    {
        helper('filesystem');
        $this->commonData();
        $strIdx = $idx;
        //게시글정보
        $boardModel = new BoardModel('iv_board_list', 'list');
        $skinList = get_filenames(APPPATH . 'Views/www/skin/board/');
        $skinData = [];
        foreach ($skinList as $val) {
            $skinName = explode('.', $val);
            if (!isset($skinName[1])) {
                $skinData[] = $val;
            }
        }
        $this->aData['data']['skin'] = $skinData;

        $aViewData = [];
        $aRow = $boardModel->getBdListRow($strIdx);
        $aViewData['idx'] = $aRow['idx'];
        $aViewData['board_list_id'] = $aRow['board_list_id'];
        $aViewData['board_list_name'] = $aRow['board_list_name'];
        $aViewData['board_list_type'] = $aRow['board_list_type'];
        $aViewData['board_list_skin'] = $aRow['board_list_skin'];
        $aViewData['board_list_basic'] = $aRow['board_list_basic'];
        $aViewData['board_list_auth'] = $aRow['board_list_auth'];
        $aViewData['board_list_list'] = $aRow['board_list_list'];
        $aViewData['board_list_outline'] = $aRow['board_list_outline'];
        $aViewData['board_list_total'] = $aRow['board_list_total'];
        $aViewData['board_list_reg_date'] = $aRow['board_list_reg_date'];
        $aViewData['board_list_mod_date'] = $aRow['board_list_mod_date'];

        $this->aData['data']['list'] = $aViewData;

        // view
        $this->header();
        $this->nav();
        echo view('prime/board/setWrite', $this->aData);
        $this->footer();
    }

    public function write(string $code, int $idx = null)
    {
        if (!$code) {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
            exit;
        }
        $this->commonData();

        $aRowBd = [];
        if ($idx) {
            $boardModel = new BoardModel($code, 'board');
            $aRowBd = $boardModel->where(['idx' => $idx])->withDeleted()->first();
            $board = new Board($code, $idx);
            $aRowFile = $board->getFile($aRowBd);
        }

        $boardListModel = new BoardModel('iv_board_list', 'list');
        $aRowBdList = $boardListModel->where(['board_list_id' => $code])->first();

        $this->aData['data']['bdList'] = $aRowBdList;
        $this->aData['data']['aData'] = [
            'code' => $code,
            'idx' => $idx,
        ];

        if ($idx) {
            $this->aData['data']['bd_data'] = $aRowBd;
            $this->aData['data']['bd_file'] = $aRowFile;
        }

        // view
        $this->header();
        $this->nav();
        echo view('prime/board/write', $this->aData);
        $this->footer();
    }
}
