<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DatabaseInterview;

class JobCategoryModel extends Model
{
    protected $table = ''; // 데이터베이스 테이블을 지정
    protected $primaryKey = ''; // 테이블에서 레코드를 고유하게 식별하는 열(column)의 이름
    protected $useAutoIncrement = true; // (auto-increment) 기능을 사용할지 여부
    protected $tempReturnType = 'array'; // 반환되는값 지정 array,object 
    protected $useSoftDeletes = false; // true 일경우 실제로 행을 삭제하는 대신 deleted_at 필드를 설정
    protected $allowedFields = []; //save, insert, update 메소드를 통하여 설정할 수 있는 필드 이름
    protected $useTimestamps = true; // INSERT 및 UPDATE에 자동으로 추가되는지 여부를 결정.
    protected $createdField = 'job_reg_date'; // 데이터 레코드 작성 타임스탬프를 유지하기 위해 사용하는 데이터베이스 필드
    protected $updatedField = 'job_mod_date'; // 데이터 레코드 업데이트 타임스탬프를 유지하기 위해 사용할 데이터베이스 필드
    protected $deletedField = 'job_del_date'; // 데이터 레코드 삭제 타임스탬프를 유지하기 위해 사용할 데이터베이스 필드
    protected $validationRules = []; // 유효성 검사 규칙 배열
    protected $validationMessages = []; // 유효성 검증 중에 사용해야하는 사용자 정의 오류 메시지 배열
    protected $skipValidation = false; // 모든 inserts와 updates의 유효성 검사를 하지 않을지 여부 기본값은 false이며 데이터의 유효성 검사를 항상 시도.


    public function __construct(string $table)
    {
        parent::__construct();
        $this->table = $table;
        $this->fields = DatabaseInterview::$table();
        if (is_array($this->fields)) {
            foreach ($this->fields as $key => $row) {
                if (isset($row['auto_increment']) && $row['auto_increment'] === true) {
                    $this->primaryKey = $key;
                } else if (!in_array($key, ['job_reg_date', 'job_mod_date', 'job_del_date'])) {
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

    public function getJobCategory(): array
    {
        $aResult = [];
        $aResult = $this
            ->where([
                'delyn' => 'N',
            ])
            ->findAll();
        return $aResult ?? [];
    }

    public function getCompanyIdx(array $aJobIdx): array
    {
        $aResult = [];
        if (!$aJobIdx) {
            return $aResult;
        }

        $aResult = $this
            ->where('mem_rec_type', 'R')
            ->whereIn('job_idx', $aJobIdx)
            ->findColumn('rec_idx');
        return $aResult ?? [-1];
    }

    //전체 카테고리 가져오기 (interview type 에서 사용)
    public function getAllcategory(): array
    {
        if (!$aAllCate = cache('aAllCate')) {
            $aAllCategory = $this->select('job_depth_1, job_depth_text')
                ->where([
                    'job_depth_2' => NULL,
                    'delyn' => 'N'
                ])
                ->findAll();

            $aCategory = [];
            for ($i = 0; $i < count($aAllCategory); $i++) {
                $aAllCate2 = $this->select('*')
                    ->where([
                        'job_depth_1' => $aAllCategory[$i]['job_depth_1'],
                        'delyn' => 'N'
                    ])
                    ->findAll();
                array_push($aCategory, $aAllCate2);
            }

            cache()->save('aAllCate', $aCategory, 86400);
            $aAllCate = $aCategory;
        }
        return cache('aAllCate');
    }

    public function getCheckCate(array $checkCate){

        if (!$checkCate) {
            return $checkCate;
        }

        $aCategory=$this->select('iv_job_category.job_depth_1')
        ->whereIn('iv_job_category.idx', $checkCate)
        ->findAll();

        return $aCategory;
    }
}
