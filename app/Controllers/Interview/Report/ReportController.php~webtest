<?php

namespace App\Controllers\Interview\Report;

use App\Controllers\Interview\WwwController;

use Config\Database;
use Config\Services;
use App\Models\ApplierModel;
use App\Models\ReportResultModel;
use App\Models\SearchModel;
use App\Models\JobCategoryModel;
use App\Models\ReportScoreRankModel;

class ReportController extends WwwController
{
    private $encrypter;

    public function index()
    {
        $this->list();
    }

    public function test()
    {
        $this->commonData();

        $applierModel = new ApplierModel;
        $test = $applierModel->getGrade();

        $aGradeTemp = [];

        foreach ($test as $key => $val) {
            $test[$key]['repo_analysis'] = json_decode($val['repo_analysis'], true);
            $test[$key]['repo_analysis'] = $test[$key]['repo_analysis']['grade'];

            $jobDepth = $test[$key]['job_depth_1'];
            $grade = $test[$key]['repo_analysis'];

            $aGradeTemp[$jobDepth][$grade] = 0;
            $aGradeTemp['Total'][$grade] = 0;
        }

        foreach ($test as $key => $val) {
            $jobDepth = $test[$key]['job_depth_1'];
            $grade = $test[$key]['repo_analysis'];

            ++$aGradeTemp[$jobDepth][$grade];
            ++$aGradeTemp['Total'][$grade];
        }

        //트랜잭션 start
        $this->masterDB->transBegin();
        foreach ($aGradeTemp as $rankType => $aValue) {
            if ($rankType === 'Total') {
                foreach ($aValue as $grade => $countMember) {
                    $this->masterDB->table('set_report_score_rank')
                        ->set([
                            'score_rank_count_member' => $countMember
                        ])
                        ->set(['score_rank_reg_date' => 'NOW()'], '', false)
                        ->where([
                            'score_rank_type' => 'T',
                            'score_rank_grade' => $grade,
                        ])
                        ->update();
                }
            } else {
                foreach ($aValue as $grade => $countMember) {
                    $this->masterDB->table('set_report_score_rank')
                        ->set([
                            'score_rank_count_member' => $countMember
                        ])
                        ->set(['score_rank_reg_date' => 'NOW()'], '', false)
                        ->where([
                            'score_rank_type' => 'C',
                            'job_depth_1' => $rankType,
                            'score_rank_grade' => $grade,
                        ])
                        ->update();
                }
            }
        }

        // 트랜잭션 end
        if ($this->masterDB->transStatus() === false) {
            $this->masterDB->transRollback();
            return alert_back($this->globalvar->aMsg['error3']);
        } else {
            $this->masterDB->transCommit();
            alert_back($this->globalvar->aMsg['success4']);
            exit;
        }

        $this->header();
        exit;
    }


    public function list()
    {
        // data init
        $this->commonData();
        $this->encrypter = Services::encrypter();
        $iAppCount = 0;

        if ($this->aData['data']['session']['id']) { // 로그인
            $iMemberIdx = $this->aData['data']['session']['idx'];
            $strType = $this->request->getGet('type') ?? 'all';
            $applierModel = new ApplierModel();
            $allCount = $applierModel->getApplierAllCount($iMemberIdx);
            $openCount = $applierModel->getApplierOpenCount($iMemberIdx);

            if ($strType === 'all') { // 전체 레포트
                $strReportType = $this->request->getGet('reportType') ?? 'all';
                $strReportSort = $this->request->getGet('reportSort') ?? 'date';

                if (in_array($strReportType, ['all', '0', '1', 'company']) && in_array($strReportSort, ['date', 'max', 'min'])) {
                } else {
                    alert_back($this->globalvar->aMsg['error1']);
                    exit;
                }
                $applierModel->getApplierAllList($iMemberIdx, $strReportType, $strReportSort);
                $aReport = $applierModel->paginate(2, 'report');

                if (!$aReport) {
                    $strType = 'none';
                } else {

                    $reportResultModel = new ReportResultModel();
                    $countReport = $reportResultModel->getReportPoint($iMemberIdx);

                    foreach ($countReport as $val) {
                        $aRepoPoints[] = $val['repo_analysis']['sum'] ?? 0;
                    }

                    foreach ($aReport as $key => $val) {
                        $aReport[$key]['repo_analysis'] = json_decode($val['repo_analysis'], true);
                        $aReport[$key]['repo_analysis'] = $aReport[$key]['repo_analysis']['grade'] ?? '';
                        $aReport[$key]['queCount'] = $reportResultModel->getQueCount($val['idx']);
                        $aReport[$key]['idx'] = base64url_encode($this->encrypter->encrypt(json_encode($val['idx'])));
                    }
                }
            } else if ($strType === 'open') { // 공개중인 레포트
                $applierModel->getApplierOpenList($iMemberIdx);
                $aReport = $applierModel->paginate(2, 'report');

                if (!$aReport) {
                    $strType = 'none';
                } else {
                    foreach ($aReport as $key => $val) {
                        $aReport[$key]['repo_analysis'] = json_decode($val['repo_analysis'], true);
                        $aReport[$key]['repo_analysis'] = $aReport[$key]['repo_analysis']['grade'] ?? '';
                        $aReport[$key]['idx'] = base64url_encode($this->encrypter->encrypt(json_encode($val['idx'])));
                        $iAppCount += $val['app_count'];
                    }
                }
            } else {
                alert_back($this->globalvar->aMsg['error1']);
                exit;
            }
        } else { //비로그인
            return $this->sample();
        }
        $this->aData['data']['appCount'] = $iAppCount;
        $this->aData['data']['reportType'] = $strReportType ?? '';
        $this->aData['data']['reportSort'] = $strReportSort ?? '';
        $this->aData['data']['points'] = $aRepoPoints ?? [];
        $this->aData['data']['pager'] = $applierModel->pager;
        $this->aData['data']['list'] = $aReport;
        $this->aData['data']['allCount'] = $allCount;
        $this->aData['data']['openCount'] = $openCount;
        $this->aData['data']['type'] = $strType;
        $this->header();
        echo view("www/report/reportList", $this->aData);
    }

    public function applierDeleteAction()
    {
        // data init
        $this->commonData();

        $this->header();

        if (!$this->aData['data']['session']['id']) {
            return redirect($this->globalvar->getlogin());
        }
        $strApplierIdx = $this->request->getPost('deleteIdx') ?? false;
        $this->encrypter = Services::encrypter();

        $applierModel = new ApplierModel;
        if ($strApplierIdx) {

            $iApplierIdx = $this->encrypter->decrypt(base64url_decode($strApplierIdx));
            $iApplierIdx = str_replace('"', '', $iApplierIdx);

            if ($applierModel->chkApplier($iApplierIdx, $this->aData['data']['session']['idx'])) {
                //트랜잭션 start
                $this->masterDB->transBegin();

                $this->masterDB->table('iv_applier')
                    ->set('delyn', 'Y')
                    ->set(['app_del_date' => 'NOW()'], '', false)
                    ->where(
                        [
                            'idx' => $iApplierIdx,
                            'delyn' => 'N'
                        ]
                    )
                    ->update();

                $this->masterDB->table('iv_report_result')
                    ->set('delyn', 'Y')
                    ->set(['repo_del_date' => 'NOW()'], '', false)
                    ->where(
                        [
                            'applier_idx' => $iApplierIdx,
                            'delyn' => 'N'
                        ]
                    )
                    ->update();
                // 트랜잭션 end
                if ($this->masterDB->transStatus() === false) {
                    $this->masterDB->transRollback();
                    return alert_back($this->globalvar->aMsg['error3']);
                } else {
                    $this->masterDB->transCommit();
                    alert_back($this->globalvar->aMsg['success2']);
                    exit;
                }
            }
        }
        alert_back($this->globalvar->aMsg['error1']);
        exit;
    }

    public function applierUpdateAction()
    {
        // data init
        $this->commonData();

        $this->header();
        if (!$this->aData['data']['session']['id']) {
            return redirect($this->globalvar->getlogin());
        }
        $strApplierIdx = $this->request->getPost('updateIdxMain') ?? false;

        $this->encrypter = Services::encrypter();
        $applierModel = new ApplierModel;

        if ($strApplierIdx) {

            $iApplierIdx = $this->encrypter->decrypt(base64url_decode($strApplierIdx));
            $iApplierIdx = str_replace('"', '', $iApplierIdx);

            if ($applierModel->chkApplier($iApplierIdx, $this->aData['data']['session']['idx'])) {
                $this->masterDB->table('iv_applier')
                    ->set('app_share', 0)
                    ->where(
                        [
                            'idx' => $iApplierIdx,
                            'delyn' => 'N'
                        ]
                    )
                    ->update();
                alert_url($this->globalvar->aMsg['success4'], '/report?type=open');
                exit;
            }
        }
        alert_back($this->globalvar->aMsg['error1']);
        exit;
    }

    public function applierShare()
    {
        // data init
        $this->commonData();

        if (!$this->aData['data']['session']['id']) {
            return redirect($this->globalvar->getlogin());
        }
        $strApplierIdx = $this->request->getGet('report') ?? false;

        $applierModel = new ApplierModel();
        $this->encrypter = Services::encrypter();

        if ($strApplierIdx) {
            $iApplierIdx = $this->encrypter->decrypt(base64url_decode($strApplierIdx));
            $iApplierIdx = str_replace('"', '', $iApplierIdx);

            if ($applierModel->chkApplier($iApplierIdx, $this->aData['data']['session']['idx'])) {
                $iQueCount = 0;
                $aReport = $applierModel->getDetail($iApplierIdx);
                foreach ($aReport as $key => $val) {
                    $aReport[$key]['idx'] = base64url_encode($this->encrypter->encrypt(json_encode($val['idx'])));
                    if ($val['que_type'] === 'T') {
                        $aReport[$key]['repo_analysis'] = json_decode($val['repo_analysis'], true);
                        $aReport = $aReport[$key];
                        unset($aReport[$key]);
                    } else {
                        ++$iQueCount;
                        unset($aReport[$key]);
                    }
                }
            } else {
                alert_back($this->globalvar->aMsg['error1']);
                exit;
            }
        }

        $applierModel->getApplierAllList($this->aData['data']['session']['idx'], 'no', 'no');
        $reportList = $applierModel->findAll();
        foreach ($reportList as $key => $val) {
            $reportList[$key]['idx'] = base64url_encode($this->encrypter->encrypt(json_encode($val['idx'])));
        }

        $jobCategoryModel = new JobCategoryModel('iv_job_category');
        $job = $jobCategoryModel->getJobCategory();

        $this->aData['data']['queCount'] = $iQueCount ?? 0;
        $this->aData['data']['reportList'] = $reportList ?? [];
        $this->aData['data']['report'] = $aReport ?? [];
        $this->aData['data']['jobs'] = $job ?? [];

        $this->header();
        echo view("www/report/reportShare", $this->aData);
    }

    public function applierShareAction()
    {
        // data init
        $this->commonData();

        $this->header();

        if (!$this->aData['data']['session']['id']) {
            return redirect($this->globalvar->getlogin());
        }

        $strApplierIdx = $this->request->getPost('updateIdx') ?? false;
        $strShareType = $this->request->getPost('share') ?? null;
        $strShareCompanyType = $this->request->getPost('shareCompany') ?? 'off';
        $iMemberIdx = $this->aData['data']['session']['idx'];

        $applierModel = new ApplierModel;
        $this->encrypter = Services::encrypter();

        if ($strApplierIdx) {

            $iApplierIdx = $this->encrypter->decrypt(base64url_decode($strApplierIdx));
            $iApplierIdx = str_replace('"', '', $iApplierIdx);
            
            if ($applierModel->chkApplier($iApplierIdx, $iMemberIdx)) {
                $aChk = ['on' => '1',  'off' => '0'];
                $aChk2 = ['off' => '0', 'half' => '1', 'all' => '2'];

                $iShareType = $aChk[$strShareType] ?? false;
                $iShareCompanyType = $aChk2[$strShareCompanyType] ?? false;

                // $iShareCompanyType = $strShareType === 'off' ? 'off' : $iShareCompanyType;
                if (in_array($strShareType, ['on', 'off']) && in_array($strShareCompanyType, ['off', 'half', 'all'])) {

                    $iJobIdx = $applierModel->select('job_idx')->where(['idx' => $iApplierIdx, 'delyn' => 'N'])->first();

                    //트랜잭션 start
                    $this->masterDB->transBegin();

                    if ($applierModel->getJobIdx($iApplierIdx, $iMemberIdx, $iJobIdx['job_idx'])) {
                        $this->masterDB->table('iv_applier')
                            ->set([
                                'app_share' => 0,
                                'app_share_company' => 0
                            ])
                            ->where(
                                [
                                    'job_idx' => $iJobIdx,
                                    'delyn' => 'N'
                                ]
                            )
                            ->update();
                    }

                    $this->masterDB->table('iv_applier')
                        ->set([
                            'app_share' => $iShareType,
                            'app_share_company' => $iShareCompanyType
                        ])
                        ->where(
                            [
                                'idx' => $iApplierIdx,
                                'delyn' => 'N'
                            ]
                        )
                        ->update();

                    // 트랜잭션 end
                    if ($this->masterDB->transStatus() === false) {
                        $this->masterDB->transRollback();
                        return alert_back($this->globalvar->aMsg['error3']);
                    } else {
                        $this->masterDB->transCommit();
                        alert_back($this->globalvar->aMsg['success4']);
                        exit;
                    }
                }
            }
        }
        alert_back($this->globalvar->aMsg['error1']);
        exit;
    }

    public function sample()
    {
        // data init

        $this->header();
        echo view("www/report/sample", $this->aData);
    }

    public function detail($strApplierIdx)
    {
        // data init
        $this->commonData();
        $this->encrypter = Services::encrypter();
        $iStypeCount = 0;

        $iApplierIdx = $this->encrypter->decrypt(base64url_decode($strApplierIdx));
        $iApplierIdx = str_replace('"', '', $iApplierIdx);

        $loginFlag = true;
        if (!$this->aData['data']['session']['id']) {
            $loginFlag = false;
        }

        $applierModel = new ApplierModel();
        if ($applierModel->chkApplierShare($iApplierIdx)) {
        } else if ($loginFlag) {
            if ($applierModel->chkApplier($iApplierIdx, $this->aData['data']['session']['idx'])) {
            } else {
                alert_back($this->globalvar->aMsg['error1']);
                exit;
            }
        } else {
            alert_back($this->globalvar->aMsg['error1']);
            exit;
        }

        $aReport = $applierModel->getDetail($iApplierIdx);

        foreach ($aReport as $val) {
            $aTempT = [];
            $aTempS = [];
            if ($val['que_type'] === 'T') {
                $aScoreT = json_decode($val['repo_score'], true);
                $aAnalysisT = json_decode($val['repo_analysis'], true);

                foreach ($aScoreT as $k => $v) {
                    if (in_array($k, ['age', 'gender', 'hair_length', 'glasses', 'skin', 'beard'])) {
                        continue;
                    } else {
                        $aTempT[$k] = $v;
                    }
                }

                if (!$reportScoreRank = cache("report.score.{$val['job_depth_1']}")) {
                    $reportScoreRankModel = new ReportScoreRankModel();
                    $reportScoreRank = $reportScoreRankModel->getScoreInfo($val['job_depth_1']);
                    cache()->save("report.score.{$val['job_depth_1']}", $reportScoreRank, 600);
                }

                foreach ($reportScoreRank as $v) {
                    if ($v['score_rank_type'] === 'T') {
                        unset($v['score_rank_type']);
                        $reportScoreRankT[] = $v;
                    } else {
                        unset($v['score_rank_type']);
                        $reportScoreRankC[] = $v;
                    }
                }

                $aGender['man'] = $aScoreT['gender'][0];
                $aGender['woman'] = $aScoreT['gender'][1];

                $aAge['age10'] = $aScoreT['age'][0];
                $aAge['age20'] = $aScoreT['age'][1];
                $aAge['age30'] = $aScoreT['age'][2];
                $aAge['age40'] = $aScoreT['age'][3];
                $aAge['age50'] = $aScoreT['age'][4];
            } else if ($val['que_type'] === 'S') {
                $aScoreS = json_decode($val['repo_score'], true);
                $aAnalysisS = json_decode($val['repo_analysis'], true);

                foreach ($aScoreS as $k => $v) {
                    if (in_array($k, ['age', 'gender', 'hair_length', 'glasses', 'skin', 'beard'])) {
                        continue;
                    } else {
                        $aTempS[$k] = $v;
                    }
                }

                $this->aData['data']['S'][$iStypeCount]['temp'] = $aTempS ?? [];
                $this->aData['data']['S'][$iStypeCount]['score'] = $aScoreS ?? [];
                $this->aData['data']['S'][$iStypeCount]['analysis'] = $aAnalysisS ?? [];
                ++$iStypeCount;
            }
        }

        $this->aData['data']['T']['reportScoreRank']['T'] = $reportScoreRankT ?? [];
        $this->aData['data']['T']['reportScoreRank']['C'] = $reportScoreRankC ?? [];
        $this->aData['data']['T']['gender'] = $aGender ?? [];
        $this->aData['data']['T']['age'] = $aAge ?? [];
        $this->aData['data']['T']['temp'] = $aTempT ?? [];
        $this->aData['data']['T']['score'] = $aScoreT ?? [];
        $this->aData['data']['T']['analysis'] = $aAnalysisT ?? [];
        $this->aData['data']['job'] = $aReport[0]['job_depth_text'] ?? '';
        $this->header();
        echo view("www/report/reportDetail", $this->aData);
    }

    public function fail($iApplierIdx)
    {
        // data init
        $this->commonData();

        if (!$this->aData['data']['session']['id']) {
            return redirect($this->globalvar->getlogin());
        }

        $applierModel = new ApplierModel();
        if ($applierModel->chkApplierFail($iApplierIdx, $this->aData['data']['session']['idx'])) {
        } else {
            alert_back($this->globalvar->aMsg['error1']);
            exit;
        }

        $iStypeCount = 0;

        $aReport = $applierModel->getFail($iApplierIdx);

        foreach ($aReport as $val) {
            if ($val['que_type'] === 'T') {

                $this->aData['data']['T'] = $val ?? [];
            } else if ($val['que_type'] === 'S') {

                $this->aData['data']['S'][$iStypeCount][] = $val ?? [];
                $this->aData['data']['S'][$iStypeCount][] = $val ?? [];
                ++$iStypeCount;
            }
        }

        $this->aData['data']['job'] = $aReport[0]['job_depth_text'] ?? '';
        $this->header();
        echo view("www/report/reportFail", $this->aData);
    }
}
