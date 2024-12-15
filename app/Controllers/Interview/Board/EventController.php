<?php

namespace App\Controllers\Interview\Board;

use App\Controllers\Interview\WwwController;
use App\Models\BoardModel;

class EventController extends WwwController
{
    public function index()
    {
        $this->list();
    }

    public function list()
    {
        // data init
        $this->commonData();
        $strEventStat = $this->request->getGet('stat') ?? 'ing';

        if (!in_array($strEventStat, ['ing', 'end'])) {
            alert_back($this->globalvar->aMsg['error1']);
            exit;
        }

        $boardModel = new BoardModel('event', 'board');

        switch ($strEventStat) {
            case 'ing':
                $boardModel->where('bd_end_date >=', date('Y-m-d'));
                break;
            case 'end':
                $boardModel->where('bd_end_date <', date('Y-m-d'));
                break;
        }

        $boardModel
            ->select([
                'iv_board_event.idx',
                'file_save_name',
                'bd_start_date',
                'bd_end_date'
            ])
            ->join('iv_file', 'iv_file.idx = iv_board_event.file_idx_thumb', 'left')
            ->getBdList();
        $eventList = $boardModel->paginate(10, 'event');

        $this->aData['data']['eventList'] = $eventList;
        $this->aData['data']['pager'] = $boardModel->pager;

        $this->header();
        echo view("www/Board/event/list", $this->aData);
        $this->footer();
    }

    public function detail($iEventIdx)
    {
        // data init
        $this->commonData();

        $boardModel = new BoardModel('event', 'board');
        $eventList = $boardModel->getBdListRow($iEventIdx);

        $falg = $this->masterDB->table('iv_board_event')
            ->set('bd_hit', 'bd_hit + 1', false)
            ->where(
                [
                    'idx' => $iEventIdx,
                ]
            )
            ->update();

        if (!$falg) {
            alert_back($this->globalvar->aMsg['error1']);
            exit;
        }

        $this->aData['data']['eventList'] = $eventList;

        $this->header();
        echo view("www/Board/event/detail", $this->aData);
        $this->footer();
    }
}
