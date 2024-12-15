<?php

namespace App\Controllers\Admin\Board;

use App\Controllers\Admin\AdminController;
use App\Models\BoardModel;

class BoardListController extends AdminController
{
    public function index($code)
    {
        $this->list($code);
    }

    public function list(string $code = 'notice')
    {
        $strSearchText = $this->request->getGet('searchText');

        $this->commonData();
        $boardModel = new BoardModel('iv_board_list', 'list');
        //aBoardListId init
        $aRowBdList = $boardModel->select(['board_list_name', 'board_list_id'])->findAll();
        foreach ($aRowBdList as $val) {
            $aBoardListId[] = $val['board_list_id'];
        }
        if (!in_array($code, $aBoardListId)) {
            return alert_url('잘못된 접근입니다.', '/prime/board/list');
        }

        //model
        $boardModel = new BoardModel($code, 'board');
        $aRowBd = $boardModel->where('delyn', 'N')->orderBy('idx', 'desc');
        if ($strSearchText) {
            $aRowBd = $aRowBd->like('bd_title', $strSearchText, 'both');
        }
        $this->aData['data']['bdList'] = $aRowBdList;
        $this->aData['data']['bd'] = $aRowBd->paginate(5, 'board');
        $this->aData['data']['pager'] = $aRowBd->pager;
        //기타정보
        $this->aData['data']['aData'] = [
            'code' => $code
        ];

        //검색정보
        $this->aData['data']['search'] = [
            'text' => $strSearchText
        ];

        // view
        $this->header();
        $this->nav();
        echo view('prime/board/boardList', $this->aData);
        $this->footer();
    }

    public function setList()
    {
        $this->commonData();

        //게시글정보
        $boardModel = new BoardModel('iv_board_list', 'list');
        $boardModel->getBdList();
        $this->aData['data']['list'] = $boardModel->paginate(10, 'set');
        $this->aData['data']['pager'] = $boardModel->pager;
        foreach ($this->aData['data']['list'] as $key => $val) {
            $this->aData['data']['list'][$key]['board_list_reg_date'] = substr($val['board_list_reg_date'], 0, 10);
        }

        // view
        $this->header();
        $this->nav();
        echo view('prime/board/setList', $this->aData);
        $this->footer();
    }
}
