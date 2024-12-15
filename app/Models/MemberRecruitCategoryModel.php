<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DatabaseInterview;

class MemberRecruitCategoryModel extends Model
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
        $code = 'iv_member_recruit_category';
        $this->table = $code;
        $this->fields = DatabaseInterview::$code();
        if (is_array($this->fields)) {
            foreach ($this->fields as $key => $row) {
                if (isset($row['auto_increment']) && $row['auto_increment'] === true) {
                    $this->primaryKey = $key;
                }
            }
        }
    }

    public function save($data): bool
    {
        // $masterDB를 사용해 주세요.
        return false;
    }

    public function getJoinType($type, $idx): array
    {
        //type R:recruit, M:member, S:resume
        $aResult = [];
        if (!in_array($type, ['R', 'M', 'S']) || !$idx) {
            return $aResult;
        }
        switch ($type) {
            case 'R':
                $aResult = $this->join('iv_job_category', 'iv_member_recruit_category.job_idx = iv_job_category.idx', 'left')
                    ->where(['mem_rec_type' => $type, 'iv_member_recruit_category.rec_idx' => $idx])->findAll();
                break;
            case 'M':
                $aResult = $this->join('iv_job_category', 'iv_member_recruit_category.job_idx = iv_job_category.idx', 'left')
                    ->where(['mem_rec_type' => $type, 'iv_member_recruit_category.mem_idx' => $idx])->findAll();
                break;
            case 'S':
                $aResult = $this->join('iv_job_category', 'iv_member_recruit_category.job_idx = iv_job_category.idx', 'left')
                    ->where(['mem_rec_type' => $type, 'iv_member_recruit_category.res_idx' => $idx])->findAll();
                break;
        }
        return $aResult;
    }

    public function getJoinTypeInterview($idx): array
    {
        $aResult = [];
        if (!$idx) {
            return $aResult;
        }
        $aResult = $this
            ->join('iv_job_category', 'iv_member_recruit_category.job_idx = iv_job_category.idx', 'left')
            ->join('iv_interview', 'iv_member_recruit_category.mem_idx = iv_interview.mem_idx', 'left')
            ->where(['mem_rec_type' => 'M', 'iv_member_recruit_category.mem_idx' => $idx])->findAll();
        return $aResult;
    }

    public function getCategory(string $_idx): array
    {
        $aRowCategories = [];
        if (!$_idx) {
            return $aRowCategories;
        }

        $aRowCategories = $this
            ->select('iv_job_category.idx, iv_job_category.job_depth_text')
            ->join('iv_job_category', 'iv_member_recruit_category.job_idx=iv_job_category.idx', 'left')
            ->where('iv_member_recruit_category.rec_idx', $_idx)
            ->where('iv_member_recruit_category.mem_rec_type', 'R')
            ->where('iv_member_recruit_category.delyn', 'N')
            ->findAll();
        $categories = [];
        if ($aRowCategories) {
            for ($i = 0; $i < count($aRowCategories); $i++) {
                array_push($categories, $aRowCategories[$i]['job_depth_text']);
            }
        }
        return $categories;
    }

    public function getCategoryIdx(array $aIdx): array
    {
        $aRowCategoryIdx = [];
        if (!$aIdx) {
            return $aRowCategoryIdx;
        }

        $aRowCategoryIdx = $this
            ->distinct()
            ->select('job_idx')
            ->whereIn('rec_idx', $aIdx)
            ->where('mem_rec_type', 'R')
            ->where('delyn', 'N')
            ->findAll();
        $categoryIdx = [];
        if ($aRowCategoryIdx) {
            for ($i = 0; $i < count($aRowCategoryIdx); $i++) {
                array_push($categoryIdx, $aRowCategoryIdx[$i]['job_idx']);
            }
        }
        return $aRowCategoryIdx;
    }

    public function getSameCategoryRec(array $aCategoryIdx, $_idx, $limit): array
    {
        $ajobCategory = [];
        if (!$aCategoryIdx || !$_idx || !$limit) {
            return $ajobCategory;
        }

        foreach ($aCategoryIdx as $val) {
            array_push($ajobCategory, $val['job_idx']);
        }

        $aRowSameCatRec = $this
            ->distinct()
            ->select('rec_idx')
            ->where('mem_rec_type', 'R')
            ->whereIn('job_idx', $ajobCategory)
            ->where('rec_idx !=', $_idx)
            ->orderBy('rec_idx', 'random')
            ->findAll($limit);

        return $aRowSameCatRec;
    }

    public function getSameCategoryRecs(array $aCategoryIdx, $_idx, array $myApply, $limit): array
    {
        $ajobCategory = [];
        if (!$aCategoryIdx || !$_idx || !$limit || !$myApply) {
            return $ajobCategory;
        }
        foreach ($aCategoryIdx as $val) {
            array_push($ajobCategory, $val['job_idx']);
        }
        $aMyApplyIdx = [];
        foreach ($myApply as $val) {
            array_push($aMyApplyIdx, $val['rec_idx']);
        }

        $aRowSameCatRec = $this
            ->distinct()
            ->select('iv_member_recruit_category.rec_idx')
            ->join('iv_recruit', 'iv_member_recruit_category.rec_idx=iv_recruit.idx', 'left')
            ->where([
                'iv_recruit.rec_apply !=' => 'C',
                'iv_recruit.rec_end_date >=' => date("Ymd"),
                'iv_recruit.delyn' => 'N',
                'iv_member_recruit_category.mem_rec_type' => 'R',
                'iv_member_recruit_category.delyn' => 'N',
            ])
            ->whereIn('iv_member_recruit_category.job_idx', $ajobCategory)
            ->whereNotIn('iv_recruit.idx', $aMyApplyIdx)
            ->orderBy('iv_member_recruit_category.rec_idx', 'random')
            ->findAll($limit);

        return $aRowSameCatRec;
    }
}
