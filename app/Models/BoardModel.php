<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DatabaseInterview;

class BoardModel extends Model
{
    protected $table = ''; // 데이터베이스 테이블을 지정
    protected $primaryKey = ''; // 테이블에서 레코드를 고유하게 식별하는 열(column)의 이름
    protected $useAutoIncrement = true; // (auto-increment) 기능을 사용할지 여부
    protected $tempReturnType = 'array'; // 반환되는값 지정 array,object 
    protected $useSoftDeletes = true; // true 일경우 실제로 행을 삭제하는 대신 deleted_at 필드를 설정
    protected $allowedFields = []; //save, insert, update 메소드를 통하여 설정할 수 있는 필드 이름
    protected $useTimestamps = true; // INSERT 및 UPDATE에 자동으로 추가되는지 여부를 결정.
    protected $createdField = ''; // 데이터 레코드 작성 타임스탬프를 유지하기 위해 사용하는 데이터베이스 필드
    protected $updatedField = ''; // 데이터 레코드 업데이트 타임스탬프를 유지하기 위해 사용할 데이터베이스 필드
    protected $deletedField = ''; // 데이터 레코드 삭제 타임스탬프를 유지하기 위해 사용할 데이터베이스 필드
    protected $validationRules = []; // 유효성 검사 규칙 배열
    protected $validationMessages = []; // 유효성 검증 중에 사용해야하는 사용자 정의 오류 메시지 배열
    protected $skipValidation = false; // 모든 inserts와 updates의 유효성 검사를 하지 않을지 여부 기본값은 false이며 데이터의 유효성 검사를 항상 시도.
    protected $jsonField = [];
    protected $afterFind = ['jsonToArray'];

    public function __construct(string $code, string $division)
    {
        parent::__construct();
        if (in_array($division, ['board', 'comment'])) {
            $this->table = ($division == 'board') ? 'iv_board_' . $code : 'iv_comment_' . $code;
            $division = 'iv_' . $division;
        } else {
            $this->table = $code;
            $division = $code;
        }
        $this->_db = new DatabaseInterview();
        $this->fields = DatabaseInterview::$division();
        $aNotAllowedFields = [
            'board_list_reg_date', 'board_list_mod_date', 'board_list_del_date',
            'bd_reg_date', 'bd_mod_date', 'bd_del_date',
            'cmt_reg_date', 'cmt_mod_date', 'cmt_del_date',
        ];
        if ($code == 'iv_board_list') {
            //개시판 설정
            $this->createdField = 'board_list_reg_date';
            $this->updatedField = 'board_list_mod_date';
            $this->deletedField = 'board_list_del_date';
            $this->jsonField = ['board_list_item', 'board_list_basic', 'board_list_auth', 'board_list_list', 'board_list_outline'];
        } else if (in_array($code, ['notice', 'event', 'free', 'qna', 'reference'])) {

            //개별 게시판
            if ($division == 'iv_board') {
                $this->createdField = 'bd_reg_date';
                $this->updatedField = 'bd_mod_date';
                $this->deletedField = 'bd_del_date';
            } else {
                $this->createdField = 'cmt_reg_date';
                $this->updatedField = 'cmt_mod_date';
                $this->deletedField = 'cmt_del_date';
            }
        }
        if (is_array($this->fields)) {
            foreach ($this->fields as $key => $row) {
                if (isset($row['auto_increment']) && $row['auto_increment'] === true) {
                    $this->primaryKey = $key;
                } else if (!in_array($key, $aNotAllowedFields)) {
                    $this->allowedFields[] = $key;
                }
            }
        }
    }

    public function jsonToArray(array $data): array
    {
        if (isset($data['data'])) {
            foreach ($data['data'] as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $k_ => $v_)
                        if (in_array($k_, $this->jsonField)) $data['data'][$key][$k_] = json_decode($v_, true);
                } else {
                    if (in_array($key, $this->jsonField)) {
                        $data['data'][$key] = json_decode($val, true);
                    }
                }
            }
        }
        return $data;
    }

    public function save($data): bool
    {
        // $masterDB를 사용해 주세요.
        return false;
    }

    public function getBdList(): object
    {
        $aData = [];
        $strSort = '';
        if ($this->table == 'iv_faq') {
            $strSort = 'faq_sort';
        }
        if ($strSort != '') {
            $aData = $this->where('delyn', 'N')->orderBy('`idx` DESC, `' . $strSort . '` ASC');
        } else {
            $table = $this->table;
            $aData = $this->where("{$table}.delyn", 'N')->orderBy("{$table}.idx", 'desc');
        }
        return  $aData;
    }

    public function getBdListRow(int $idx): array
    {
        $aData = $this->where(['idx' => $idx])->first();
        return $aData;
    }

    public function getBdListFistRow(string $code): array
    {
        $aData = $this->where(['board_list_id' => $code])->first();
        return $aData;
    }
}
