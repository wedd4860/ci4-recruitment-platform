<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DatabaseInterview;

class CompanyModel extends Model
{
    protected $table = ''; // 데이터베이스 테이블을 지정
    protected $primaryKey = ''; // 테이블에서 레코드를 고유하게 식별하는 열(column)의 이름
    protected $useAutoIncrement = true; // (auto-increment) 기능을 사용할지 여부
    protected $tempReturnType = 'array'; // 반환되는값 지정 array,object 
    protected $useSoftDeletes = true; // true 일경우 실제로 행을 삭제하는 대신 deleted_at 필드를 설정
    protected $allowedFields = []; //save, insert, update 메소드를 통하여 설정할 수 있는 필드 이름
    protected $useTimestamps = true; // INSERT 및 UPDATE에 자동으로 추가되는지 여부를 결정.
    protected $createdField = 'com_reg_date'; // 데이터 레코드 작성 타임스탬프를 유지하기 위해 사용하는 데이터베이스 필드
    protected $updatedField = 'com_mod_date'; // 데이터 레코드 업데이트 타임스탬프를 유지하기 위해 사용할 데이터베이스 필드
    protected $deletedField = 'com_del_date'; // 데이터 레코드 삭제 타임스탬프를 유지하기 위해 사용할 데이터베이스 필드
    protected $validationRules = []; // 유효성 검사 규칙 배열
    protected $validationMessages = []; // 유효성 검증 중에 사용해야하는 사용자 정의 오류 메시지 배열
    protected $skipValidation = false; // 모든 inserts와 updates의 유효성 검사를 하지 않을지 여부 기본값은 false이며 데이터의 유효성 검사를 항상 시도.


    public function __construct()
    {
        parent::__construct();
        $code = 'iv_company';
        $this->table = $code;
        $this->fields = DatabaseInterview::$code();
        if (is_array($this->fields)) {
            foreach ($this->fields as $key => $row) {
                if (isset($row['auto_increment']) && $row['auto_increment'] === true) {
                    $this->primaryKey = $key;
                } else if (!in_array($key, ['com_reg_date', 'com_mod_date', 'com_del_date'])) {
                    $this->allowedFields[] = $key;
                }
            }
        }
    }

    // <summary>
    // 모의면접을 허용한 기업 정보 가져오기
    // 조건1. 모의면접을 허용
    // <param name="strSearchText">검색어</param>
    // <param name="strSearchComForm">기업의형태</param>
    // </summary>
    public function getPracticeList(string $strSearchText = null, array $aSearchComForm = null): object
    {
        $aRow = [];
        $this
            ->select([
                'iv_company.idx as comIdx', //회사idx
                'iv_company.com_name as comName', //회사명
                'iv_company.com_industry as comIdustry', //업종
                'iv_company.com_form as comForm', //형태
                'iv_company.com_address as comAddress', //주소
                'iv_file.file_save_name as fileComLogo'  //로고
            ])
            ->join('iv_file', 'iv_file.idx = iv_company.file_idx_logo', 'left')
            ->where('iv_company.com_practice_interview', 'Y'); //모의면접 허용

        if ($strSearchText) { //검색어가 있으면
            $this->like('iv_company.com_name', $strSearchText, 'both'); //회사명에서 검색
        }

        if ($aSearchComForm) { //회사형태에 대한 검색어가 있으면
            $aOrWhere = [];
            for ($i = 0; $i < count($aSearchComForm); $i++) {
                array_push($aOrWhere, $aSearchComForm[$i]);
            }
            $this->whereIn('iv_company.com_form', $aOrWhere);
        }

        $aRow = $this->orderBy('iv_company.com_score', 'DESC');

        return $aRow;
    }
    public function save($data): bool
    {
        // $masterDB를 사용해 주세요.
        return false;
    }

    public function getTagList(array $aTagIdx = []): object
    {
        $session = session();
        $iMemIdx =  '';
        if ($session->has('idx')) {
            $iMemIdx = $session->get('idx');
        }

        $readyDB = $this->select([
            'iv_company.idx as comIdx', 'iv_company.com_name as comName', 'iv_company.com_industry as comIdustry', 'iv_company.com_industry',
            'iv_company.com_address as comAddr',
            'iv_file.file_save_name as fileSave',
            'config_company_tag.tag_txt as configTagTxt',
            'iv_recruit.idx as recIdx'
        ])
            ->select(['count(config_company_tag.tag_txt) as tagCnt'], '', false)
            ->join('iv_company_tag', 'iv_company_tag.com_idx = iv_company.idx', 'left')
            ->join('config_company_tag', 'iv_company_tag.config_tag_idx = config_company_tag.idx', 'left')
            ->join('iv_recruit', 'iv_recruit.com_idx = iv_company.idx', 'left')
            ->join('iv_file', 'iv_file.idx = iv_company.file_idx_logo', 'left');

        if ($iMemIdx) {
            $readyDB->select(['iv_member_recruit_scrap.mem_idx as scrapMemIdx'])
                ->join('iv_member_recruit_scrap', 'iv_company.idx = iv_member_recruit_scrap.com_idx', 'left');
        }
        if ($aTagIdx) {
            $readyDB->whereIn('iv_company_tag.config_tag_idx', $aTagIdx);
        }
        return $readyDB->groupBy('iv_company.idx ');
    }

    public function getCompanyInfo(int $comIdx): array
    {
        $aRow = [];
        if (!$comIdx) {
            return $aRow;
        }

        $aRow = $this
            ->select('iv_company.com_name, iv_company.file_idx_logo, iv_company.com_introduce, iv_company.com_video_url, iv_company.com_practice_interview, iv_file.file_save_name')
            ->join('iv_file', 'iv_company.file_idx_logo = iv_file.idx')
            ->where([
                'iv_company.idx' => $comIdx,
                'iv_file.file_type' => 'C',
                'iv_file.delyn' => 'N'
            ])
            ->first();

        return $aRow ?? [];
    }

    public function getAllCompany(): object
    {
        $aRow = $this
            ->select('iv_company.idx, iv_company.com_name, iv_company.com_industry, iv_company.com_address')
            ->select('COUNT(iv_recruit.idx) AS recCnt', '', false)
            ->join('iv_recruit', 'iv_company.idx = iv_recruit.com_idx', 'left')
            ->where('iv_recruit.delyn', 'N')
            ->groupBy('iv_company.idx')
            ->orderBy('recCnt', 'DESC');

        return $aRow ?? [];
    }
}
