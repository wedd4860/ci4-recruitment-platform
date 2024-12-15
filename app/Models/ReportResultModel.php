<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DatabaseInterview;

class ReportResultModel extends Model
{
    protected $table = ''; // 데이터베이스 테이블을 지정
    protected $primaryKey = ''; // 테이블에서 레코드를 고유하게 식별하는 열(column)의 이름
    protected $useAutoIncrement = true; // (auto-increment) 기능을 사용할지 여부
    protected $tempReturnType = 'array'; // 반환되는값 지정 array,object 
    protected $useSoftDeletes = true; // true 일경우 실제로 행을 삭제하는 대신 deleted_at 필드를 설정
    protected $allowedFields = []; //save, insert, update 메소드를 통하여 설정할 수 있는 필드 이름
    protected $useTimestamps = true; // INSERT 및 UPDATE에 자동으로 추가되는지 여부를 결정.
    protected $createdField = 'repo_reg_date'; // 데이터 레코드 작성 타임스탬프를 유지하기 위해 사용하는 데이터베이스 필드
    protected $updatedField = 'repo_mod_date'; // 데이터 레코드 업데이트 타임스탬프를 유지하기 위해 사용할 데이터베이스 필드
    protected $deletedField = 'repo_del_date'; // 데이터 레코드 삭제 타임스탬프를 유지하기 위해 사용할 데이터베이스 필드
    protected $validationRules = []; // 유효성 검사 규칙 배열
    protected $validationMessages = []; // 유효성 검증 중에 사용해야하는 사용자 정의 오류 메시지 배열
    protected $skipValidation = false; // 모든 inserts와 updates의 유효성 검사를 하지 않을지 여부 기본값은 false이며 데이터의 유효성 검사를 항상 시도.
    protected $jsonField = ['repo_analysis'];
    protected $afterFind = ['jsonToArray'];


    public function __construct()
    {
        parent::__construct();
        $code = 'iv_report_result';
        $this->table = $code;
        $this->fields = DatabaseInterview::$code();
        if (is_array($this->fields)) {
            foreach ($this->fields as $key => $row) {
                if (isset($row['auto_increment']) && $row['auto_increment'] === true) {
                    $this->primaryKey = $key;
                } else if (!in_array($key, ['repo_reg_date', 'repo_mod_date', 'repo_del_date'])) {
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

    public function getMaxScoreInterview($applierIdx): int
    {
        $maxApplierIdx = 0;
        if (!$applierIdx) {
            return $maxApplierIdx;
        }

        $aMaxScoreInterview = $this
            ->where('que_type', 'T')
            ->whereIn('applier_idx', $applierIdx)
            ->findAll();
        $aRepoAnalysis = [];
        if ($aMaxScoreInterview) {
            for ($i = 0; $i < count($aMaxScoreInterview); $i++) {
                array_push($aRepoAnalysis, array($aMaxScoreInterview[$i]['applier_idx'], $aMaxScoreInterview[$i]['repo_analysis']['sum']));
            }

            //이차원배열에서 최대값 추출
            $aValues = [];
            foreach ($aRepoAnalysis as $val) {
                if ($val[1]) {
                    $aValues[] = $val[1];
                }
            }
            $max = max(array_values($aValues));
            $key = array_search($max, array_column($aRepoAnalysis, 1));

            $maxApplierIdx = $aRepoAnalysis[$key][0];
        }
        return $maxApplierIdx;
    }

    public function getReportInfo(int $applierIdx): array
    {
        $aReportInfo = [];

        if (!$applierIdx) {
            return $aReportInfo;
        }

        $aReportInfoCnt = $this
            ->select('COUNT(*) as cnt', '', false)
            ->where('applier_idx', $applierIdx)
            ->where('que_type', 'S')
            ->first();

        $aReportInfoGrade = $this
            ->select('repo_analysis')
            ->where('applier_idx', $applierIdx)
            ->where('que_type', 'T')
            ->first();

        if ($aReportInfoCnt && $aReportInfoGrade) {
            array_push($aReportInfo, $aReportInfoCnt['cnt'], $aReportInfoGrade['repo_analysis']['grade']);
        }

        return $aReportInfo;
    }

    public function getQueCount(int $iApplierIdx): int
    {
        $iResult = [];
        if (!$iApplierIdx) {
            return $iResult;
        }

        $iResult = $this
            ->where(
                [
                    'delyn' => 'N',
                    'que_type' => 'S',
                ]
            )
            ->where('applier_idx', $iApplierIdx)
            ->countAllResults();
        return $iResult ?? 0;
    }

    public function getReportPoint(int $iMemberIdx): array
    {
        $aResult = [];
        if (!$iMemberIdx) {
            return $aResult;
        }

        $aResult = $this
            ->select('repo_analysis')
            ->join('iv_applier', 'iv_report_result.applier_idx = iv_applier.idx ', 'inner')
            ->where(
                [
                    'iv_applier.delyn' => 'N',
                    'iv_applier.app_iv_stat' => 4,
                    'que_type' => 'T',
                ]
            )
            ->where('iv_applier.mem_idx', $iMemberIdx)
            ->findAll();
        return $aResult ?? [];
    }
}
