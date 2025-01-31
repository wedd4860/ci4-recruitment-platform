<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DatabaseInterview;

class InterviewModel extends Model
{
    protected $table = ''; // 데이터베이스 테이블을 지정
    protected $primaryKey = ''; // 테이블에서 레코드를 고유하게 식별하는 열(column)의 이름
    protected $useAutoIncrement = true; // (auto-increment) 기능을 사용할지 여부
    protected $tempReturnType = 'array'; // 반환되는값 지정 array,object 
    protected $useSoftDeletes; // true 일경우 실제로 행을 삭제하는 대신 deleted_at 필드를 설정
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

    public function __construct(string $code, array $aSubDate)
    {
        /**
         * $aSubDate = [
         *  'allowedFields' => ['mem_reg_date', 'mem_mod_date', 'mem_del_date'], // allowedFields 에 들어갈 date 정보
         *  'createdField' => 'mem_reg_date',
         *  'updatedField' => 'mem_mod_date',
         *  'deletedField' => 'mem_del_date',
         *  'jsonField' => ['board_list_item', 'board_list_basic'],
         *  'useSoftDeletes' => true,
         * ]
         */
        parent::__construct();
        $this->table = $code;
        $this->_db = new DatabaseInterview();
        $this->fields = DatabaseInterview::$code();
        $aAllowedFields = $aSubDate['allowedFields'] ?? [];
        if (is_array($this->fields)) {
            foreach ($this->fields as $key => $row) {
                if (isset($row['auto_increment']) && $row['auto_increment'] === true) {
                    $this->primaryKey = $key;
                } else if (!in_array($key, $aAllowedFields)) {
                    $this->allowedFields[] = $key;
                }
            }
        }
        
        $this->createdField = $aSubDate['createdField'] ?? '';
        $this->updatedField = $aSubDate['updatedField'] ?? '';
        $this->deletedField = $aSubDate['deletedField'] ?? '';
        $this->jsonField = $aSubDate['jsonField'] ?? [];
        $this->useSoftDeletes = $aSubDate['useSoftDeletes']??true;
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
}
