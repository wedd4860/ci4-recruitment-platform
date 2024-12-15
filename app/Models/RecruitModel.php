<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DatabaseInterview;
use DateTime;

class RecruitModel extends Model
{
    protected $table = ''; // 데이터베이스 테이블을 지정
    protected $primaryKey = ''; // 테이블에서 레코드를 고유하게 식별하는 열(column)의 이름
    protected $useAutoIncrement = true; // (auto-increment) 기능을 사용할지 여부
    protected $tempReturnType = 'array'; // 반환되는값 지정 array,object 
    protected $useSoftDeletes = false; // true 일경우 실제로 행을 삭제하는 대신 deleted_at 필드를 설정
    protected $allowedFields = []; //save, insert, update 메소드를 통하여 설정할 수 있는 필드 이름
    protected $useTimestamps = true; // INSERT 및 UPDATE에 자동으로 추가되는지 여부를 결정.
    protected $createdField = 'rec_reg_date'; // 데이터 레코드 작성 타임스탬프를 유지하기 위해 사용하는 데이터베이스 필드
    protected $updatedField = 'rec_mod_date'; // 데이터 레코드 업데이트 타임스탬프를 유지하기 위해 사용할 데이터베이스 필드
    protected $deletedField = 'rec_del_date'; // 데이터 레코드 삭제 타임스탬프를 유지하기 위해 사용할 데이터베이스 필드
    protected $validationRules = []; // 유효성 검사 규칙 배열
    protected $validationMessages = []; // 유효성 검증 중에 사용해야하는 사용자 정의 오류 메시지 배열
    protected $skipValidation = false; // 모든 inserts와 updates의 유효성 검사를 하지 않을지 여부 기본값은 false이며 데이터의 유효성 검사를 항상 시도.

    public function __construct()
    {
        parent::__construct();
        $code = 'iv_recruit';
        $this->table = $code;
        $this->fields = DatabaseInterview::$code();
        if (is_array($this->fields)) {
            foreach ($this->fields as $key => $row) {
                if (isset($row['auto_increment']) && $row['auto_increment'] === true) {
                    $this->primaryKey = $key;
                } else if (!in_array($key, ['rec_reg_date', 'rec_mod_date', 'rec_del_date'])) {
                    $this->allowedFields[] = $key;
                }
            }
        }
    }

    public function getRecruit(string $_idx): array
    {
        $aResult = [];
        if (!$_idx) {
            return $aResult;
        }

        $aResult = $this
            ->join('iv_file', 'iv_recruit.file_idx_recruit=iv_file.idx', 'left')
            ->join('iv_company', 'iv_recruit.com_idx=iv_company.idx', 'left')
            ->where('iv_recruit.idx', $_idx)->first();
        return $aResult;
    }

    // 공고 여러개 지원할때 여러 회사 값 불러오기
    public function getRecruits(array $_idx): array
    {
        $aResult = [];
        if (!$_idx) {
            return $aResult;
        }

        $aResult = $this->select('iv_recruit.idx as recIdx, iv_company.idx as comIdx, iv_company.com_name, iv_recruit.rec_title, iv_recruit.rec_resume')
            ->join('iv_file', 'iv_recruit.file_idx_recruit=iv_file.idx', 'left')
            ->join('iv_company', 'iv_recruit.com_idx=iv_company.idx', 'left')
            ->whereIn('iv_recruit.idx', $_idx)->findAll();

        $aResults = [];
        for ($i = 0; $i < count($aResult); $i++) {
            array_push($aResults, $aResult[$i]);
        }

        return $aResults;
    }

    // 공고태그들 불러오기
    public function getTags(string $_idx): array
    {
        $aRow = [];
        if (!$_idx) {
            return $aRow;
        }

        $aRow = $this
            ->select('config_company_tag.tag_txt')
            ->join('iv_company_tag', 'iv_recruit.com_idx=iv_company_tag.com_idx', 'left')
            ->join('config_company_tag', 'config_company_tag.idx=iv_company_tag.config_tag_idx', 'left')
            ->where('iv_recruit.idx', $_idx)->findAll();
        $aTagRow = [];
        if ($aRow) {
            for ($i = 0; $i < count($aRow); $i++) {
                array_push($aTagRow, $aRow[$i]['tag_txt']);
            }
        }
        return $aTagRow;
    }

    public function getRandomRecInfo(array $ranIdx): array
    {
        $aRanRec = [];

        if (!$ranIdx) {
            return $aRanRec;
        }

        for ($i = 0; $i < count($ranIdx); $i++) {
            array_push($aRanRec, $ranIdx[$i]['rec_idx']);
        }
        $temp = implode(",", $aRanRec);
        $aRow = $this
            ->select('iv_recruit.idx, iv_company.com_name, iv_recruit.rec_title, iv_korea_area.area_depth_text_1, iv_korea_area.area_depth_text_2, iv_recruit.rec_apply, iv_recruit.rec_end_date, iv_recruit.rec_career, iv_file.file_save_name')
            ->join('iv_company', 'iv_recruit.com_idx = iv_company.idx', 'left')
            ->join('iv_member_recruit_kor', 'iv_recruit.idx = iv_member_recruit_kor.rec_idx', 'left')
            ->join('iv_korea_area', 'iv_member_recruit_kor.kor_area_idx = iv_korea_area.idx', 'left')
            ->join('iv_file', 'iv_recruit.file_idx_recruit = iv_file.idx', 'left')
            ->whereIn('iv_member_recruit_kor.rec_idx', $aRanRec)
            ->whereIn('iv_recruit.idx', $aRanRec)
            ->orderBy('FIELD(iv_recruit.idx,' . $temp . ')')
            ->findAll();
        return $aRow;
    }

    public function getRecruitTitles(array $aIdx): array
    {
        $aRow = [];

        if (!$aIdx) {
            return $aRow;
        }

        $aRow = $this->select('idx, rec_title')
            ->whereIn('idx', $aIdx)
            ->findAll();

        return $aRow;
    }
    // <summary>
    // 지원자의 관심사 조건에 따라 채용공고목록 가져오기
    // 조건1. 공고마감일이 현재날짜보다 같거나 작음
    // 조건2. 공고조회수가 높음  
    // 조건3. 사용자 관심직무가 있을경우 공고의 직무와 사용자 관심직무와 같음
    // <param name="aJobIdx">사용자관심직무배열</param>
    // <param name="strSearchText">검색어</param>
    // <param name="strSearchRecApply">내인터뷰로지원가능한면접여부(M)</param>
    // <param name="strSearchOrder">정렬조건</param>
    // </summary>
    public function getRecruitList(array $aJobIdx  = null, string $strSearchText = null, string $strSearchRecApply = null, string $strSearchOrder = null): object
    {
        $aRow = [];
        $this
            ->select(
                'iv_recruit.idx as recIdx, 
                 iv_recruit.rec_title as recTitle, 
                 iv_recruit.job_idx as jobIdx, 
                 iv_recruit.rec_career as recCareer, 
                 iv_recruit.rec_end_date as recEndDate, 
                 iv_company.com_name as comName, 
                 iv_company.com_address as comAddress, 
                 iv_file.file_save_name as fileComLogo,
                 iv_recruit.rec_apply as recApply, 
                 iv_recruit.rec_work_type as recWorkType, 
                 iv_recruit.rec_work_day as recWorkDay'
            )
            ->join('iv_company', 'iv_recruit.com_idx = iv_company.idx', 'left')
            ->join('iv_file', 'iv_file.idx = iv_company.file_idx_logo', 'left')
            ->where(["iv_recruit.rec_end_date >=" => date("Ymd")]);

        if ($strSearchRecApply) { //인터뷰지원타입에 대한 조건이 있으면
            $this->where('iv_recruit.rec_apply', $strSearchRecApply);
        }

        if ($aJobIdx) { //관심직무가 있으면
            $aOrWhere = [];
            for ($i = 0; $i < count($aJobIdx); $i++) {
                array_push($aOrWhere, $aJobIdx[$i]);
            }
            $this->whereIn('iv_recruit.job_idx', $aOrWhere);
        }

        if ($strSearchText) { //검색어가 있으면
            $this->like('iv_recruit.rec_title', $strSearchText, 'both');
        }

        if ($strSearchOrder) { //정렬조건이 있으면
            $aRow = $this->orderBy($strSearchOrder, 'DESC');
        } else {
            $aRow = $this->orderBy('rec_hit', 'DESC');
        }
        return $aRow;
    }

    public function getCurrentRecruit(int $comIdx): array
    {
        $aRow = [];
        if (!$comIdx) {
            return $aRow;
        }

        $aRow = $this
            ->select('idx, rec_title')
            ->where([
                'com_idx' => $comIdx,
                'rec_end_date >=' => date("Ymd")
            ])
            ->orderBy('idx', 'asc')
            ->findAll();

        return $aRow ?? [];
    }
}
