<?php

namespace App\Controllers\Interview\Board;

use App\Controllers\Interview\WwwController;
use App\Models\BoardModel;

class NoticeController extends WwwController
{
    public function index()
    {
        $this->list();
    }

    public function list()
    {
        // data init
        $this->commonData();

        $boardModel = new BoardModel('notice', 'board');

        $boardModel
            ->select([
                'idx',
                'bd_title',
                'bd_reg_date',
                'bd_content'
            ])
            ->getBdList();
        $noticeList = $boardModel->paginate(10, 'notice');

        $this->aData['data']['noticeList'] = $noticeList;
        $this->aData['data']['pager'] = $boardModel->pager;

        $this->header();
        echo view('www/Board/notion/list', $this->aData);
        $this->footer();
    }
}
