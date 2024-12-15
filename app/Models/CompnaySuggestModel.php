<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DatabaseInterview;

class CompnaySuggestModel extends Model
{
    protected $table = ''; // 데이터베이스 테이블을 지정
    protected $primaryKey = ''; // 테이블에서 레코드를 고유하게 식별하는 열(column)의 이름
    protected $useAutoIncrement = true; // (auto-increment) 기능을 사용할지 여부
    protected $tempReturnType = 'array'; // 반환되는값 지정 array,object 
    protected $useSoftDeletes = false; // true 일경우 실제로 행을 삭제하는 대신 deleted_at 필드를 설정
    protected $allowedFields = []; //save, insert, update 메소드를 통하여 설정할 수 있는 필드 이름
    protected $useTimestamps = true; // INSERT 및 UPDATE에 자동으로 추가되는지 여부를 결정.
    protected $createdField = ''; // 데이터 레코드 작성 타임스탬프를 유지하기 위해 사용하는 데이터베이스 필드
    protected $updatedField = ''; // 데이터 레코드 업데이트 타임스탬프를 유지하기 위해 사용할 데이터베이스 필드
    protected $deletedField = ''; // 데이터 레코드 삭제 타임스탬프를 유지하기 위해 사용할 데이터베이스 필드
    protected $validationRules = []; // 유효성 검사 규칙 배열
    protected $validationMessages = []; // 유효성 검증 중에 사용해야하는 사용자 정의 오류 메시지 배열
    protected $skipValidation = false; // 모든 inserts와 updates의 유효성 검사를 하지 않을지 여부 기본값은 false이며 데이터의 유효성 검사를 항상 시도.


    public function __construct()
    {
        parent::__construct();
        $code = 'iv_company_suggest';
        $this->table = $code;
        $this->fields = DatabaseInterview::$code();
        if (is_array($this->fields)) {
            foreach ($this->fields as $key => $row) {
                if (isset($row['auto_increment']) && $row['auto_increment'] === true) {
                    $this->primaryKey = $key;
                } else if (!in_array($key, [''])) {
                    $this->allowedFields[] = $key;
                }
            }
        }
    }

    public function save($data): bool
    {
        // $masterDB를 사용해 주세요.
        return false;
    }

    public function getSuggestList(int $iMemberIdx) // array or object
    {
        $aResult = [];
        if (!$iMemberIdx) {
            return $aResult;
        }

        $aResult = $this
            ->select([
                'iv_company_suggest.idx',
                'rec_end_date',
                'sug_end_date',
                'sug_type',
                'sug_idx',
                'com_name',
                'rec_title',
            ])
            ->join('iv_company', 'iv_company.idx = iv_company_suggest.com_idx', 'inner')
            ->join('config_company_suggest', 'config_company_suggest.sug_idx = iv_company_suggest.idx', 'inner')
            ->join('iv_applier', 'iv_applier.idx = iv_company_suggest.app_idx', 'inner')
            ->join('iv_recruit', 'iv_recruit.idx = iv_applier.rec_idx', 'left')
            ->where(
                [
                    'config_company_suggest.mem_idx' => $iMemberIdx,
                    'iv_company_suggest.delyn' => 'N',
                    'iv_company.delyn' => 'N',
                    'config_company_suggest.delyn' => 'N',
                    'iv_applier.delyn' => 'N',
                ]
            );
        return $aResult;
    }

    public function getSuggestDetail(int $iMemberIdx, int $iSuggestIdx) // array or object
    {
        $aResult = [];
        if (!$iMemberIdx) {
            return $aResult;
        }

        $aResult = $this
            ->select([
                'iv_company_suggest.idx',
                'iv_company.idx as comIdx',
                'sug_type',
                'rec_end_date',
                'sug_end_date',
                'sug_manager',
                'com_name',
                'rec_title',
                'sug_massage',
                'sug_manager_name',
                'sug_manager_tel'
            ])
            ->join('iv_company', 'iv_company.idx = iv_company_suggest.com_idx', 'inner')
            ->join('config_company_suggest', 'config_company_suggest.sug_idx = iv_company_suggest.idx', 'inner')
            ->join('iv_applier', 'iv_applier.idx = iv_company_suggest.app_idx', 'inner')
            ->join('iv_recruit', 'iv_recruit.idx = iv_applier.rec_idx', 'left')
            ->where(
                [
                    'iv_company_suggest.idx' => $iSuggestIdx,
                    'config_company_suggest.mem_idx' => $iMemberIdx,
                    'iv_company_suggest.delyn' => 'N',
                    'iv_company.delyn' => 'N',
                    'config_company_suggest.delyn' => 'N',
                    'iv_applier.delyn' => 'N',
                ]
            );
        return $aResult;
    }
}
