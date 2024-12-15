<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DatabaseInterview;

class ApplierModel extends Model
{
    protected $table = ''; // 데이터베이스 테이블을 지정
    protected $primaryKey = ''; // 테이블에서 레코드를 고유하게 식별하는 열(column)의 이름
    protected $useAutoIncrement = true; // (auto-increment) 기능을 사용할지 여부
    protected $tempReturnType = 'array'; // 반환되는값 지정 array,object 
    protected $useSoftDeletes = false; // true 일경우 실제로 행을 삭제하는 대신 deleted_at 필드를 설정
    protected $allowedFields = []; //save, insert, update 메소드를 통하여 설정할 수 있는 필드 이름
    protected $useTimestamps = true; // INSERT 및 UPDATE에 자동으로 추가되는지 여부를 결정.
    protected $createdField = 'app_reg_date'; // 데이터 레코드 작성 타임스탬프를 유지하기 위해 사용하는 데이터베이스 필드
    protected $updatedField = 'app_mod_date'; // 데이터 레코드 업데이트 타임스탬프를 유지하기 위해 사용할 데이터베이스 필드
    protected $deletedField = 'app_del_date'; // 데이터 레코드 삭제 타임스탬프를 유지하기 위해 사용할 데이터베이스 필드
    protected $validationRules = []; // 유효성 검사 규칙 배열
    protected $validationMessages = []; // 유효성 검증 중에 사용해야하는 사용자 정의 오류 메시지 배열
    protected $skipValidation = false; // 모든 inserts와 updates의 유효성 검사를 하지 않을지 여부 기본값은 false이며 데이터의 유효성 검사를 항상 시도.


    public function __construct()
    {
        parent::__construct();
        $code = 'iv_applier';
        $this->table = $code;
        $this->fields = DatabaseInterview::$code();
        if (is_array($this->fields)) {
            foreach ($this->fields as $key => $row) {
                if (isset($row['auto_increment']) && $row['auto_increment'] === true) {
                    $this->primaryKey = $key;
                } else if (!in_array($key, ['app_reg_date', 'app_mod_date', 'app_del_date'])) {
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

    // 공고 카테고리가 내가 본 면접중에 있는지 체크
    public function getMemberCategoty(string $id, $categories)
    {
        $strMemberCategory = '';
        if (!$id || !$categories) {
            return $strMemberCategory;
        }

        $aMemberCategory = $this
            ->select('iv_job_category.job_depth_text')
            ->join('iv_job_category', 'iv_applier.job_idx=iv_job_category.idx', 'left')
            ->where('iv_applier.mem_idx', $id)->findAll();

        if ($aMemberCategory) {
            foreach ($aMemberCategory as $val) {
                if (in_array($val['job_depth_text'], $categories)) {
                    $strMemberCategory = 'get'; //공고카테고리 = 인터뷰 카테고리가
                    break;
                }
                $strMemberCategory = 'not'; //인터뷰가 있지만 동일한 카테고리가 존재하지않음
            }
        } else {
            $strMemberCategory = 'none'; //인터뷰가 없음
        }
        return $strMemberCategory;
    }

    public function getApplierData(string $id, array $jobCategory): array
    {
        $ajobCategory = [];

        if (!$id || !$jobCategory) {
            return $ajobCategory;
        }

        for ($i = 0; $i < count($jobCategory); $i++) {
            array_push($ajobCategory, $jobCategory[$i]['job_idx']);
        }

        $this
            ->select('idx, job_idx')
            ->where('mem_idx', $id)
            ->where('app_iv_stat', 4);
        if (count($ajobCategory) > 0) {
            $this->whereIn('job_idx', $ajobCategory);
        }
        $aMemberJobCategory = $this->findAll();
        $aApplierIdx = [];

        //같은 job_catrgory가 있는 경우
        if ($aMemberJobCategory) {
            foreach ($aMemberJobCategory as $val) {
                array_push($aApplierIdx, $val);
            }
        } else {
            //같은 job_catrgory가 없는 경우
            $aAllCategory = $this
                ->select('idx, job_idx')
                ->where('app_iv_stat', 4)
                ->where('mem_idx', $id)
                ->findAll();

            for ($i = 0; $i < count($aAllCategory); $i++) {
                array_push($aApplierIdx, $aAllCategory[$i]);
            }
        }

        return $aApplierIdx;
    }

    public function getApplierInfo(int $applierIdx): array
    {
        $aInfo = [];
        if (!$applierIdx) {
            return $aInfo;
        }

        $aApplierInfo = $this
            ->select('*')
            ->join('iv_job_category', 'iv_applier.job_idx=iv_job_category.idx', 'left')
            ->where('iv_applier.idx', $applierIdx)
            ->first();

        $aApplierInfoImg = $this
            ->select('iv_file.file_save_name')
            ->join('iv_file', 'iv_applier.file_idx_thumb=iv_file.idx', 'left')
            ->where('iv_file.file_type', 'A')
            ->where('iv_file.delyn', 'N')
            ->first();

        $aApplierInfoShare = $this
            ->select('app_share, idx')
            ->where('iv_applier.idx', $applierIdx)
            ->where('delyn', 'N')
            ->first();

        if ($aApplierInfo && $aApplierInfoImg && $aApplierInfoShare) {
            if ($aApplierInfoShare['app_share'] == 0) {
                $aApplierInfoShare['app_share'] = '비공개';
            } else {
                $aApplierInfoShare['app_share'] = '공개';
            }
            array_push($aInfo, $aApplierInfo['app_reg_date'], $aApplierInfo['job_depth_text'], $aApplierInfoImg['file_save_name'], $aApplierInfoShare['app_share'], $aApplierInfoShare['idx']);
        }

        return $aInfo;
    }

    public function getMemberInterview($memIdx): array
    {
        $aRow = [];
        if (!$memIdx) {
            return $aRow;
        }

        $aRow = $this
            ->select('iv_applier.idx, iv_job_category.job_depth_text, iv_file.file_save_name, iv_applier.app_reg_date, iv_report_result.repo_analysis, iv_applier.app_share')
            ->join('iv_job_category', 'iv_applier.job_idx = iv_job_category.idx', 'left')
            ->join('iv_file', 'iv_applier.file_idx_thumb = iv_file.idx', 'left')
            ->join('iv_report_result', 'iv_applier.idx = iv_report_result.applier_idx', 'left')
            ->where('iv_applier.app_type', 'M')
            ->where('iv_report_result.que_type', 'T')
            ->where('mem_idx', $memIdx)
            ->where('app_iv_stat', 4)
            ->findAll();

        $aInterviewCnt = [];
        for ($i = 0; $i < count($aRow); $i++) {
            $aCnt = $this
                ->select('COUNT(iv_report_result.idx) as cnt')
                ->join('iv_report_result', 'iv_applier.idx = iv_report_result.applier_idx', 'left')
                ->where('iv_report_result.applier_idx', $aRow[$i]['idx'])
                ->where('iv_report_result.que_type', 'S')
                ->first();

            array_push($aInterviewCnt, $aCnt);
        }

        $aInfo = [];
        array_push($aInfo, array("itv" => $aRow, "cnt" => $aInterviewCnt));

        return $aInfo;
    }

    public function getApplierAllList(int $iMemberIdx, string $strType, string $strSort) // array or object
    {
        $aResult = [];
        if (!$iMemberIdx) {
            return $aResult;
        }

        $aResult = $this
            ->select([
                'iv_applier.idx',
                'iv_applier.job_idx',
                'iv_applier.app_count',
                'iv_applier.app_iv_stat',
                'iv_applier.app_type',
                'iv_applier.app_share',
                'iv_applier.app_reg_date',
                'iv_applier.app_like_count',
                'iv_report_result.repo_analysis',
                'iv_job_category.job_depth_text'
            ])
            ->join('iv_report_result', 'iv_applier.idx = iv_report_result.applier_idx', 'inner')
            ->join('iv_job_category', 'iv_applier.job_idx = iv_job_category.idx', 'inner')
            ->where(
                [
                    'iv_report_result.delyn' => 'N',
                    'iv_applier.app_iv_stat >=' => '3',
                    'que_type' => 'T',
                ]
            )
            ->where('mem_idx', $iMemberIdx);
        if ($strType === '0') {
            $aResult = $this
                ->orderBy('iv_applier.app_share', 'ASC');
        } else if ($strType === '1') {
            $aResult = $this
                ->orderBy('iv_applier.app_share', 'DESC');
        } else if ($strType === 'company') {
            $aResult = $this
                ->orderBy('iv_applier.app_type', 'ASC');
        }

        if ($strSort === 'all') {
            $aResult = $this
                ->orderBy('iv_applier.app_reg_date', 'ASC');
        } else if ($strSort === 'max') {
            $aResult = $this
                ->orderBy('iv_report_result.repo_analysis', 'DESC');
        } else if ($strSort === 'min') {
            $strSort = $this
                ->orderBy('iv_report_result.repo_analysis', 'ASC');
        } else {
            $aResult = $this
                ->orderBy('iv_applier.app_reg_date', 'ASC');
        }
        return $aResult;
    }

    public function getApplierAllCount(int $iMemberIdx): int
    {
        $aResult = 0;
        if (!$iMemberIdx) {
            return $aResult;
        }

        $aResult = $this
            ->where(
                [
                    'delyn' => 'N',
                    'app_iv_stat >=' => '3',
                ]
            )
            ->where('mem_idx', $iMemberIdx)
            ->countAllResults();
        return $aResult;
    }

    public function getApplierOpenList(int $iMemberIdx) // array or object
    {
        $aResult = [];
        if (!$iMemberIdx) {
            return $aResult;
        }

        $aResult = $this
            ->select([
                'iv_applier.idx',
                'iv_applier.app_count',
                'iv_applier.app_iv_stat',
                'iv_applier.app_type',
                'iv_applier.app_share',
                'iv_applier.app_reg_date',
                'iv_applier.app_like_count',
                'iv_report_result.repo_analysis',
                'iv_job_category.job_depth_text'
            ])
            ->join('iv_report_result', 'iv_applier.idx = iv_report_result.applier_idx', 'inner')
            ->join('iv_job_category', 'iv_applier.job_idx = iv_job_category.idx', 'inner')
            ->where(
                [
                    'iv_applier.app_share' => '1',
                    'iv_applier.delyn' => 'N',
                    'iv_applier.app_iv_stat >=' => '3',
                    'que_type' => 'T',
                ]
            )
            ->where('mem_idx', $iMemberIdx);
        return $aResult;
    }

    public function getApplierOpenCount(int $iMemberIdx): int
    {
        $iResult = 0;
        if (!$iMemberIdx) {
            return $iResult;
        }

        $iResult = $this
            ->where(
                [
                    'delyn' => 'N',
                    'app_iv_stat >=' => '3',
                    'app_share' => '1',
                ]
            )
            ->where('mem_idx', $iMemberIdx)
            ->countAllResults();
        return $iResult;
    }

    public function getDetail(int $iApplierIdx)
    {
        $aResult = [];
        if (!$iApplierIdx) {
            return $aResult;
        }

        $aResult = $this
            ->select(
                [
                    'iv_applier.idx',
                    'iv_applier.app_reg_date',
                    'iv_report_result.que_type',
                    'iv_report_result.repo_score',
                    'iv_report_result.repo_analysis',
                    'iv_job_category.job_depth_text',
                    'iv_job_category.job_depth_1',
                    'iv_applier.app_share',
                    'iv_applier.app_share_company'
                ]
            )
            ->join('iv_report_result', 'iv_applier.idx = iv_report_result.applier_idx', 'inner')
            ->join('iv_job_category', 'iv_applier.job_idx = iv_job_category.idx', 'inner')
            ->where('iv_applier.delyn', 'N')
            ->where('iv_applier.idx', $iApplierIdx)
            ->findAll();
        return $aResult;
    }

    public function getFail(int $iApplierIdx)
    {
        $aResult = [];
        if (!$iApplierIdx) {
            return $aResult;
        }

        $aResult = $this
            ->select(
                [
                    'iv_applier.idx',
                    'iv_applier.app_reg_date',
                    'iv_report_result.que_type',
                    'iv_job_category.job_depth_text',
                ]
            )
            ->join('iv_report_result', 'iv_applier.idx = iv_report_result.applier_idx', 'inner')
            ->join('iv_job_category', 'iv_applier.job_idx = iv_job_category.idx', 'inner')
            ->where('iv_applier.delyn', 'N')
            ->where('iv_applier.idx', $iApplierIdx)
            ->findAll();
        return $aResult;
    }

    public function chkApplier(string $iApplierIdx, int $iMemberIdx): bool
    {
        $boolResult = false;
        if (!$iApplierIdx) {
            return $boolResult;
        }

        $boolResult = $this
            ->where(
                [
                    'delyn' => 'N',
                    'app_iv_stat' => 4,
                    'mem_idx' => $iMemberIdx,
                    'iv_applier.idx' => $iApplierIdx,
                ]
            )
            ->first();
        return $boolResult ? true : false;
    }

    public function chkApplierShare(string $iApplierIdx): bool
    {
        $boolResult = false;
        if (!$iApplierIdx) {
            return $boolResult;
        }

        $boolResult = $this
            ->where(
                [
                    'delyn' => 'N',
                    'app_iv_stat' => 4,
                    'idx' => $iApplierIdx,
                    'app_share' => 1
                ]
            )
            ->first();
        return $boolResult ? true : false;
    }

    public function chkApplierFail(string $iApplierIdx, int $iMemberIdx): bool
    {
        $boolResult = false;
        if (!$iApplierIdx) {
            return $boolResult;
        }

        $boolResult = $this
            ->where(
                [
                    'delyn' => 'N',
                    'app_iv_stat' => 3,
                    'mem_idx' => $iMemberIdx,
                    'iv_applier.idx' => $iApplierIdx,
                ]
            )
            ->first();
        return $boolResult ? true : false;
    }

    public function getGrade(): array
    {
        $aResult = [];

        $aResult = $this
            ->select(
                [
                    'iv_report_result.repo_analysis',
                    'iv_job_category.job_depth_1',
                ]
            )
            ->join('iv_report_result', 'iv_applier.idx = iv_report_result.applier_idx', 'inner')
            ->join('iv_job_category', 'iv_applier.job_idx = iv_job_category.idx', 'inner')
            ->where(
                [
                    'iv_applier.delyn' => 'N',
                    'que_type' => 'T',
                    'app_iv_stat' => 4,
                ]
            )
            ->findAll();
        return $aResult ?? [];
    }

    public function getJobIdx(int $iApplierIdx, int $iMemberIdx, int $iJobIdx): bool
    {
        $boolResult = false;
        $boolResult = $this
            ->select(
                [
                    'job_idx'
                ]
            )
            ->where(
                [
                    'idx !=' => $iApplierIdx,
                    'mem_idx' => $iMemberIdx,
                    'job_idx' => $iJobIdx,
                    'delyn' => 'N',
                    'app_share' => 1
                ]
            )
            ->first();
        return $boolResult ? true : false;
    }

    public function getStartApplier(int $applierIdx): array
    {
        $aStartInfo = [];

        if (!$applierIdx) {
            return $aStartInfo;
        }

        $aStartApplier = $this
            ->select('*')
            ->where([
                'iv_applier.idx' => $applierIdx,
                'delyn' => 'N'
            ])
            ->first();

        $aStartQuestion = $this
            ->select('iv_report_result.que_idx,iv_report_result.repo_answer_time')
            ->join('iv_report_result', 'iv_applier.idx=iv_report_result.applier_idx', 'left')
            ->where([
                'iv_applier.idx' => $applierIdx,
                'iv_applier.delyn' => 'N'
            ])
            ->findAll();

        $aStartVideo = $this
            ->select('iv_video.idx')
            ->join('iv_video', 'iv_applier.idx=iv_video.app_idx', 'left')
            ->where([
                'iv_video.app_idx' => $applierIdx,
                'iv_applier.delyn' => 'N'
            ])
            ->findAll();

        array_push($aStartInfo, array(
            'idx' => $applierIdx, 'mem_idx' => $aStartApplier['mem_idx'], 'com_idx' => $aStartApplier['com_idx'],
            'job_idx' => $aStartApplier['job_idx'], 'rec_idx' => $aStartApplier['rec_idx'], 'file_idx_thumb' => $aStartApplier['file_idx_thumb'], 'app_type' => $aStartApplier['app_type'], 'app_stt_stat' => $aStartApplier['app_stt_stat'], 'app_iv_stat' => $aStartApplier['app_iv_stat'], 'report_result' => $aStartQuestion, 'video_idx' => $aStartVideo
        ));

        return $aStartInfo;
    }

    //면접 진행중 
    public function startInterview(int $applierIdx): array
    {
        $aStartInfo = [];

        if (!$applierIdx) {
            return $aStartInfo;
        }

        $aStartApplier = $this
            ->select('*')
            ->where([
                'iv_applier.idx' => $applierIdx,
                'delyn' => 'N'
            ])
            ->first();

        $aStartQuestion = $this
            ->select('iv_report_result.que_idx,iv_report_result.repo_answer_time, iv_report_result.idx,iv_question.que_question')
            ->join('iv_report_result', 'iv_applier.idx=iv_report_result.applier_idx', 'left')
            ->join('iv_question', 'iv_report_result.que_idx=iv_question.idx', 'left')
            ->where([
                'iv_applier.idx' => $applierIdx,
                'iv_report_result.que_type !=' => 'T',
                'iv_applier.delyn' => 'N'
            ])
            ->findAll();

        $aCompleteVideo = $this
            ->select('iv_video.idx, iv_video.app_idx')
            ->join('iv_video', 'iv_applier.idx=iv_video.app_idx', 'left')
            ->where([
                'iv_video.app_idx' => $applierIdx,
                'iv_applier.delyn' => 'N'
            ])
            ->findAll();

        $aNextQuestion = $this
            ->select('iv_report_result.que_idx,iv_report_result.idx,iv_question.que_type,iv_question.que_question,iv_report_result.repo_answer_time')
            ->join('iv_report_result', 'iv_applier.idx=iv_report_result.applier_idx', 'left')
            ->join('iv_question', 'iv_report_result.que_idx=iv_question.idx', 'left')
            ->where([
                'iv_applier.idx' => $applierIdx,
                'iv_report_result.repo_process' => 0,
                'iv_report_result.que_type !=' => 'T',
                'iv_applier.delyn' => 'N'
            ])
            ->first();

        array_push($aStartInfo, array(
            'idx' => $applierIdx, 'mem_idx' => $aStartApplier['mem_idx'], 'com_idx' => $aStartApplier['com_idx'],
            'job_idx' => $aStartApplier['job_idx'], 'rec_idx' => $aStartApplier['rec_idx'], 'file_idx_thumb' => $aStartApplier['file_idx_thumb'], 'app_type' => $aStartApplier['app_type'], 'app_stt_stat' => $aStartApplier['app_stt_stat'], 'app_iv_stat' => $aStartApplier['app_iv_stat'], 'report_result' => $aStartQuestion, 'video' => $aCompleteVideo, "next_question" => $aNextQuestion
        ));

        return $aStartInfo;
    }

    public function endInterview(int $applierIdx, int $memIdx)
    {
        $aEndInfo = [];

        if (!$applierIdx) {
            return $aEndInfo;
        }

        $aEndInfo = $this
            ->select('*')
            ->where([
                'iv_applier.idx' => $applierIdx,
                'iv_applier.mem_idx' => $memIdx,
                'delyn' => 'N'
            ])
            ->first();

        return $aEndInfo;
    }

    public function sampleList(string $upDown = null, array $cateCheck = null)
    {
        $aSampleInfo = [];

        $aSampleInfo = $this
            ->select('iv_applier.idx, iv_applier.mem_idx,iv_file.file_save_name,REPLACE(json_extract(iv_report_result.repo_analysis,"$.sum"),"\"","") as sum,REPLACE(json_extract(iv_report_result.repo_analysis,"$.grade"),"\"","") as grade,iv_job_category.job_depth_text')
            ->join('iv_file', 'iv_applier.file_idx_thumb=iv_file.idx', 'left')
            ->join('iv_report_result', 'iv_applier.idx=iv_report_result.applier_idx', 'left')
            ->join('iv_job_category', 'iv_applier.job_idx=iv_job_category.idx', 'left')
            ->where([
                'iv_applier.mem_idx' => 1,
                'iv_applier.app_iv_stat' => 3,
                'iv_report_result.que_type' => 'T',
                'iv_applier.delyn' => 'N'
            ]);

        if ($cateCheck) {
            $cateIdx = [];

            for ($i = 0; $i < count($cateCheck); $i++) {
                array_push($cateIdx, $cateCheck[$i]['job_depth_1']);
            }
            $aSampleInfo->whereIn('iv_job_category.job_depth_1', $cateIdx);
        }

        if ($upDown) {
            if ($upDown == "up") {
                $aSampleInfo->orderBy('sum', 'DESC');
            } else {
                $aSampleInfo->orderBy('sum', 'ASC');
            }
        }
        return $aSampleInfo;
    }
}
