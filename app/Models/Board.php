<?php

namespace App\Models;

use App\Models\BoardModel;
use App\Models\FileModel;
use \Config\Services;

class Board
{
    protected $request;
    protected $encrypter;
    private $aParams = ['code' => '', 'idx' => '', 'page' => 1, 'pageNm' => 10];
    private $head_data = [];
    private $head = [];
    private $search_data = [];
    private $list_data = [];
    private $model = null;
    //board row setting
    private $board_set = [];

    public function __construct($code = null, $idx = null)
    {
        $this->request = Services::request();
        $this->encrypter = Services::encrypter();
        $this->aParams['code'] = $code ?? '';
        $this->aParams['idx'] = $idx ?? '';
        $this->aParams['page'] = $this->request->getGet('page') ?? 1;
        $this->aParams['pageNm'] = $this->request->getGet('pageNm') ?? 10;
    }

    public static function createBoard(string $code): bool
    {
        // 게시판 추가는 수동으로 진행
        // $create = new BoardModel($code, 'board');
        // $create = new BoardModel($code, 'comment');
        return false;
    }

    public static function deleteBoard(string $code): bool
    {
        //삭제는 미구현
        return false;
    }

    public function getSetBoard()
    {
        $model = new BoardModel('iv_board_list', 'list');

        if ($this->aParams['code'] != '') {
            $this->board_set = $model->where(['board_list_id' => $this->aParams['code']])->first();
        }
        //todo
        // if (isset($this->board_set['board_list_item']) && is_array($this->board_set['board_list_item'])) {
        //     foreach ($this->board_set['board_list_item'] as $val) {
        //         if ($val['is_list'] == 'Y') $this->head[] = ['name' => $val['name'], 'code' => $val['code'], 'type' => $val['type']];
        //         if ($val['is_search'] == 'y') $this->search_data[] = ['name' => $val['name'], 'code' => $val['code'], 'type' => $val['type']];
        //     }
        // }
    }

    public function setSearch()
    {
        $strSearchText = $this->request->getGet('searchText');
        if ($strSearchText) {
            $this->model->like('bd_title', $strSearchText, 'both');
        }
    }

    public function setData(bool $boolAdmin = false)
    {
        if ($boolAdmin) {
            $this->model->withDeleted();
        }
        $this->list = $this->model->orderBy('`bd_family` DESC, `bd_sort` ASC, `bd_depth` ASC')->paginate($this->aParams['pageNm']);
    }
    public function userSkinSet(string $f_skin, string $b_skin, string $type): array
    {
        helper('filesystem');
        $body = ['board', $type];
        if (get_file_info(APPPATH . 'Views' . $f_skin . 'board/' . $b_skin . '/' . $type . '.html', 'name')) {
            $body = ['board', $b_skin, $type];
        }
        return $body;
    }
    public static function setListTitle(string $title, string $delDt = null): string
    {
        if (!is_null($delDt)) $title = '삭제된 글 입니다.';
        return $title;
    }
    public static function setListHref(string $secret, string $delDt = null, string $code, int $ano): string
    {
        $href = '/board/read/' . $code . '/' . $ano;
        if ($secret == 'y') $href = 'javascript:front_board.read_secret(\'' . $code . '\',\'' . $ano . '\')';
        if (!is_null($delDt)) $href = '#.';
        return $href;
    }
    public static function setListIcons($stdTime, $stdHit, $cmpTime, $cmpHit): array
    {
        $icon = [];
        $new_date = date('Y-m-d H:i:s', strtotime('-' . $stdTime . ' hours'));
        if ($cmpHit >= $stdHit) $icon[] = 'board_hot';
        if (strtotime($cmpTime) >= strtotime($new_date)) $icon[] = 'board_new';
        return $icon;
    }
    public static function setListClass(string $secret, string $delDt = null, int $depth = 0): string
    {
        $class = 'board-normal';
        if ($secret == 'y') $class = 'board-secret';
        if (!is_null($delDt)) $class = 'board-delete';
        if ($depth) $class .= ' board-depth';
        return $class;
    }

    public function setListNo()
    {
        $this->_no =  $this->model->pager->getTotal() - $this->aParams['pageNm'] * ($this->aParams['page'] - 1);
    }

    public function set_custom_body()
    {
        $this->setListNo();
        $max_ = count($this->list);
        $this->list_data = [];
        for ($i = 0, $this->_no; $i < $max_; $i++, $this->_no--) {
            $this->list_data[$i] = $this->list[$i];
            $this->list_data[$i]['No'] = $this->_no;
            $this->list_data[$i]['regDt'] = ($this->board_set['list']['date'] == 'date') ? substr($this->list[$i]['regDt'], 0, 10) : $this->list[$i]['regDt'];
            $this->list_data[$i]['data']['title'] = $this->setListTitle($this->list_data[$i]['data']['title'], $this->list_data[$i]['delDt']);
            $this->list_data[$i]['href'] = $this->setListHref($this->list_data[$i]['is_secret'], $this->list_data[$i]['delDt'], $this->aParams['code'], $this->list[$i]['idx']);
            $this->list_data[$i]['icon'] = $this->setListIcons($this->board_set['basic']['new'], $this->board_set['basic']['hot'], $this->list[$i]['regDt'], $this->list_data[$i]['hit']);
            $this->list_data[$i]['class'] = $this->setListClass($this->list_data[$i]['is_secret'], $this->list_data[$i]['delDt'], $this->list_data[$i]['depth']);
        }
    }
    public function checkAuth(string $type): bool
    {
        $session = session();
        $strMemType = $session->get('mem_type') ?? '';
        $data = false;
        if (in_array($strMemType, ['A', 'M', 'L', 'C'])) {
            $data = $this->checkAuthReplay($type);
        }
        return $data;
    }
    public function checkAuthReplay(string $type): bool
    {
        helper('array');

        $data = false;
        if (in_array($type, ['write', 'replay', 'comment'])) {
            switch ($type) {
                case 'replay':
                    if (dot_array_search('board_list_basic.replay', $this->board_set) == 'y') {
                        $data = true;
                    }
                    break;
                case 'comment':
                    if (dot_array_search('board_list_basic.comment', $this->board_set) == 'y') {
                        $data = true;
                    }
                    break;
                default:
                    $data = true;
            }
        }
        return $data;
    }
    public function get_this_notice(): array
    {
        $notice = [];
        // list[notice] // 수량
        if ($this->board_set['list']['notice_all'] == 'n' || ($this->board_set['list']['notice_all'] == 'y' && $this->aParams['page'] == 1)) {
            $notice = $this->model->where('is_notice', 'y')
                ->orderBy('`ano` DESC')
                ->limit($this->board_set['list']['notice'])
                ->findAll();
        }
        foreach ($notice as $key => $row) {
            $notice[$key] = $row;
            $notice[$key]['No'] = '<span class="label label-primary">공지</span>';
            $notice[$key]['regDt'] = ($this->board_set['list']['date'] == 'date') ? substr($row['regDt'], 0, 10) : $row['regDt'];
            $notice[$key]['href'] = $this->setListHref($row['is_secret'], $row['delDt'], $this->aParams['code'], $row['idx']);
            $notice[$key]['icon'] = [];
            $notice[$key]['class'] = 'board-normal board-notice';
        }
        return $notice;
    }

    public function get_front_list(bool $is_custom = false)
    {
        $this->bothSet();

        $this->setData(true);
        if (!$is_custom) {
            $this->set_custom_body();
        } else {
            $this->setHeader();
            $this->setBody(false);
        }
        $notice = $this->get_this_notice();
        return [
            'auth' => $this->checkAuth('list'),
            'auth_set' => [
                'write' => $this->checkAuth('write'),
            ],
            'header' => $this->head_data ?? [],
            'board_set' => $this->board_set,
            'search_data' => $this->search_data,
            'page' => ($this->model) ? $this->model->pager : null,
            'data' => array_merge($notice, $this->list_data),
            'count' => count($this->list_data),
            'total' => $this->total
        ];
    }

    public function bothSet(bool $boolAdmin = false)
    {
        $this->getSetBoard();

        if (isset($this->board_set['list']['pageNm'])) {
            $this->aParams['pageNm'] = $this->board_set['list']['pageNm'];
        }

        $this->model = new BoardModel($this->aParams['code'], 'board');

        $this->total = $this->model->countAll();
        $this->setSearch();
    }

    public function adminSet($boolAdmin)
    {
        $this->setHeader($boolAdmin);
        $this->setData($boolAdmin);
        $this->setBody($boolAdmin);
    }

    public function setBody($boolAdmin)
    {
        $this->setListNo();
        $max_ = count($this->list);
        $this->list_data = [];
        for ($i = 0, $this->_no; $i < $max_; $i++, $this->_no--) {
            $list_data = null;
            if ($boolAdmin) {
                $list_data[] = ['data' => '<input class="anos" type="checkbox" value="' . $this->list[$i]['idx'] . '" id="ano-' . $i . '"><label class="no-margin" for="ano-' . $i . '"></label>', 'class' => 'text-center'];
                $strDelYN = $this->list[$i]['delyn'];
                $list_data[] = ['data' => $strDelYN, 'class' => 'text-center'];
                $list_data[] = ['data' => $this->list[$i]['bd_notice'], 'class' => 'text-center'];
                $list_data[] = ['data' => $this->list[$i]['bd_title'], 'class' => 'text-center'];
            } else {
                $list_data[] = ['data' => $this->_no, 'class' => 'text-center'];
            }
            foreach ($this->head as $v) {
                if (isset($this->list[$i]['data'][$v['code']])) {
                    $data = Converter::getListText($this->list[$i]['data'][$v['code']], $v['type']);
                    $read_href = ($boolAdmin) ? '#' . $this->list[$i]['idx'] : '/board/read/' . $this->aParams['code'] . '/' . $this->list[$i]['idx'];
                    if ($v['code'] == 'title') {
                        $class = '';
                        $class_ = ($this->list[$i]['depth'] != 0) ? 'board-replay' : '';
                        $data = '<a href="' . $read_href . '" class="' . $class_ . '" style="margin-left:' . $this->list[$i]['depth'] . '0px;">' . $data . '</a>';
                    } else {
                        $class = 'text-center';
                    }
                    $list_data[] = ['data' => $data, 'class' => $class, 'href' => $read_href, 'depth' => $this->list[$i]['depth']];
                } else {
                    $list_data[] = ['data' => '', 'class' => 'text-center'];
                }
            }
            if ($this->board_set['board_list_type'] == 'event') {
                $list_data[] = ['data' => $this->list[$i]['sdate'] . ' ~ ' . $this->list[$i]['edate'], 'class' => 'text-center'];
            }

            if ($this->board_set['board_list_list']['date'] != 'none') {
                $regDt = ($this->board_set['board_list_list']['date'] == 'date') ? substr($this->list[$i]['bd_reg_date'], 0, 10) : $this->list[$i]['bd_reg_date'];
                $list_data[] = ['data' => $regDt, 'class' => 'text-center'];
            }
            if ($this->board_set['board_list_list']['hit'] == 'y') {
                $list_data[] = ['data' => number_format($this->list[$i]['bd_hit']), 'class' => 'text-center'];
            }
            if ($boolAdmin) {
                $list_data[] = ['data' => '<a href="/prime/board/' . $this->aParams['code'] . '/read/' . $this->list[$i]['idx'] . '" class="btn btn-sm btn-white">보기</a>', 'class' => 'text-center'];
            }
            $this->list_data[$i] = $list_data;
            /*
            * 이벤트와 겔러리 형일때 썸네일이 없을경우 본문에 있는 썸네일을 가지고 온다
            */
            if ($this->board_set['board_list_type'] == 'event' || $this->board_set['board_list_type'] == 'gallery') {
                $this->new_model = new BoardModel($this->aParams['code'], 'board');
                $thumbnail_data = $this->new_model->where('idx', $this->list[$i]['idx'])->first();
                if (!$thumbnail_data['thumbnail']) {
                    $content = '';
                    foreach ($this->board_set['item'] as $new_key => $new_val) {
                        if ($new_val['type'] == 'editor') {
                            $content = $this->list[$i]['data'][$new_val['code']];
                            break;
                        }
                    }
                    $this->setThumbnail($content, $this->list[$i]['idx']);
                }
            }
        }
    }

    public function setHeader(bool $boolAdmin = false)
    {
        $this->head_data = null;
        if ($boolAdmin) {
            $this->head_data[] = ['data' => '<input name="" type="checkbox" value="" id="allClick" ><label for="allClick" class="no-margin"></label>', 'class' => 'text-center'];
            $this->head_data[] = ['data' => '공지', 'class' => 'text-center'];
            $this->head_data[] = ['data' => '제목', 'class' => 'text-center'];
            $this->head_data[] = ['data' => '삭제', 'class' => 'text-center'];
        } else {
            $this->head_data[] = 'No.';
        }

        foreach ($this->head as $val) {
            $this->head_data[] = $val['name'];
        }

        if ($this->board_set['board_list_type'] == 'event') {
            $this->head_data[] = ['data' => '이벤트 기간', 'class' => 'text-center'];
        }
        if ($this->board_set['board_list_list']['date'] != 'none') {
            $this->head_data[] = ['data' => '작성일', 'class' => 'text-center'];
        }
        if ($this->board_set['board_list_list']['hit'] == 'y') {
            $this->head_data[] = ['data' => '조회수', 'class' => 'text-center'];
        }
        if ($boolAdmin) {
            $this->head_data[] = ['data' => '보기', 'class' => 'text-center'];
        }
    }

    public function getList(bool $boolAdmin = false, string $code = '')
    {
        //삭제예정
        // $this->bothSet($boolAdmin);
        // $this->adminSet($boolAdmin);
        // return [
        //     'header' => $this->head_data,
        //     'board_set' => $this->board_set,
        //     'search_data' => $this->search_data,
        //     'page' => ($this->model) ? $this->model->pager : null,
        //     'bd_data' => $this->list_data,
        //     'count' => count($this->list_data),
        //     'total' => $this->total
        // ];
    }

    public function write(string $type): bool
    {
        $this->getSetBoard();
        return $this->checkAuth($type);
    }

    public function updateReadCount()
    {
        $masterDB = \Config\Database::connect('master');
        $session = session();
        $push = $this->aParams['code'] . '/' . $this->aParams['idx'];
        if (!($session->has('read') && in_array($push, $session->read))) {
            $result = $masterDB->table('iv_board_' . $this->aParams['code'])->set(['bd_hit' => 'bd_hit+1'])
                ->where('idx', $this->aParams['idx'])
                ->update();
            if ($result)
                $null = ($session->has('read')) ? $session->push('read', [$push]) : $session->set('read', [$push]);
        }
        $masterDB->close();
    }

    public function getFile($aRow)
    {
        $fileModel = new FileModel();
        $aTmpFileIdx = [];
        $aTmpFileRow = [
            'file_idx_thumb' => [],
            'file_idx_data_1' => [],
            'file_idx_data_2' => [],
            'file_idx_data_3' => [],
            'file_idx_data_4' => [],
            'file_idx_data_5' => []
        ];
        foreach ($aTmpFileRow as $fileKey => $fileVal) {
            if ($aRow[$fileKey] && $aRow[$fileKey] != 0) {
                $aTmpFileRow[$fileKey]['parent'] = $aRow[$fileKey];
                $aTmpFileIdx[] = $aRow[$fileKey];
            }
        }
        $aFileRow = [];
        if (count($aTmpFileIdx) > 0) {
            $aFileRow = $fileModel->select(['idx', 'file_org_name', 'file_save_name', 'file_size'])
                ->whereIn('idx', $aTmpFileIdx)->where(['delyn' => 'n', 'file_type' => 'B'])->findAll();
            foreach ($aTmpFileRow as $fileKey => $fileVal) {
                if ($fileVal) {
                    foreach ($aFileRow as $val) {
                        if ($fileVal['parent'] == $val['idx']) {
                            $aTmpFileRow[$fileKey] = [
                                'idx' => $val['idx'],
                                'file_org_name' => $val['file_org_name'],
                                'file_save_name' => $val['file_save_name'],
                                'file_size' => $val['file_size'],
                                'file_download' => base64url_encode($this->encrypter->encrypt($val['idx']))
                            ];
                        }
                    }
                }
            }
        }
        return $aTmpFileRow;
    }

    public function read(bool $boolAdmin = false)
    {
        helper('url');
        $this->getSetBoard();
        $model = new BoardModel($this->aParams['code'], 'board');

        /* 사용자 일때 조회수 증가 */
        $auth = $this->checkAuth('read');
        if (!$boolAdmin && $auth) {
            $this->updateReadCount();
        }
        $aRow = $model->where(['idx' => $this->aParams['idx'], 'delyn' => 'n'])->first();
        $aFileIdx = $this->getFile($aRow);
        return [
            'auth' => $auth,
            'code' => 200,
            'board_set' => $this->board_set,
            'auth_set' => [
                'replay' => $this->checkAuth('replay'),
                'comment' => $this->checkAuth('comment'),
            ],
            'bd_data' => $aRow,
            'bd_file' => $aFileIdx
        ];
    }

    public function setReadBody(array $aData)
    {
        if (isset($this->board_set['board_list_item']) && is_array($this->board_set['board_list_item'])) {
            foreach ($this->board_set['board_list_item'] as $k => $v) {
                $this->list_data[$k] = [
                    'title' => $v['name'],
                    'data' => isset($aData[$v['code']]) ? Converter::getListText($aData[$v['code']], $v['type'], null, false) : '-',
                    'type' => $v['type']
                ];
            }
        }
    }
}
