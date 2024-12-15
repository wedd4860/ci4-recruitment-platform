<?php

namespace App\Controllers\Interview\Interview;

use App\Controllers\BaseController;
use App\Controllers\Interview\WwwController;

use App\Models\jobCategoryModel;
use App\Models\ApplierModel;
use App\Models\FileModel;

use Config\Database;
use Config\Services;

class InterviewController extends WwwController
{
    public function index(int $_idx)
    {
        $this->preview($_idx);
    }

    public function preview(int $_idx)
    {
        // data init
        $this->commonData();

        $this->header();
        echo view("www/Interview/preview", $this->aData);
    }

    public function ready()
    { //인터뷰 시작 전 가이드 및 type 선택 전
        $this->commonData();

        $this->header();
        echo view("www/interview/ready", $this->aData);
    }
    public function type()
    {
        $this->commonData();

        $this->header();

        $jobCategoryModel = new jobCategoryModel('iv_job_category');
        $this->aData['data']['jobCate'] = $jobCategoryModel->getAllcategory();
        echo view("www/interview/type", $this->aData);
    }

    public function typeAction()
    {
        $cateIdx = $this->request->getPost('cateIdx');
        $appType = $this->request->getPost('appType');
        $appBrowserName = $this->request->getPost('appBrowserName');
        $appBrowserVersion = $this->request->getPost('appBrowserVersion');
        $appPlatform = $this->request->getPost('appPlatform');
        $postCase = $this->request->getPost('postCase');
        $strBackUrl = $this->request->getPost('backUrl');

        if ($postCase == "" || $postCase == null) {
            alert_url($this->globalvar->aMsg['error1'], $strBackUrl ? $strBackUrl : "/");
            exit;
        }


        $session = session();

        //트랜잭션 start
        $this->masterDB->transBegin();

        $this->masterDB->table('iv_applier')
            ->set([
                'mem_idx' => $session->has('mem_id'),
                'job_idx' => $cateIdx,
                'app_type' => $appType,
                'app_platform' => $appPlatform,
                'app_browser_name' => $appBrowserName,
                'app_browser_version' => $appBrowserVersion,
            ])
            ->set(['app_reg_date' => 'NOW()'], '', false)
            ->set(['app_mod_date' => 'NOW()'], '', false)
            ->insert();
        $strIdx = $this->masterDB->insertID();

        for ($i = 1; $i <= 6; $i++) { //랜덤 질문 ~개 select 한거 배열에 담아서 count +1
            if ($i != 6) {
                $this->masterDB->table('iv_report_result')
                    ->set([
                        'applier_idx' => $strIdx,
                        'que_idx' => $i,
                        'que_type' => 'S'
                    ])
                    ->set(['repo_reg_date' => 'NOW()'], '', false)
                    ->set(['repo_mod_date' => 'NOW()'], '', false)
                    ->insert();
            } else {
                $this->masterDB->table('iv_report_result')
                    ->set([
                        'applier_idx' => $strIdx,
                        'que_type' => 'T'
                    ])
                    ->set(['repo_reg_date' => 'NOW()'], '', false)
                    ->set(['repo_mod_date' => 'NOW()'], '', false)
                    ->insert();
            }
        }

        // 트랜잭션 end
        if ($this->masterDB->transStatus() === false) {
            $this->masterDB->transRollback();
        } else {
            $this->masterDB->transCommit();
        }

        // 트랜잭션 검사
        if ($this->masterDB->transStatus()) {
        } else {
            alert_url($this->globalvar->aMsg['error3'], $strBackUrl != '' ? $strBackUrl : $this->backUrlList);
            exit;
        }

        $ApplierModel = new ApplierModel();
        $aApplierInfo = $ApplierModel->getStartApplier($strIdx);

        if ($aApplierInfo == "" || $aApplierInfo == null) {
            alert_url($this->globalvar->aMsg['error1'], $strBackUrl ? $strBackUrl : "/");
            exit;
        }

        cache()->save('is_applierStart_' . $strIdx . '_' . $session->has('mem_id'), $aApplierInfo, 3600);

        return redirect()->to('/interview/profile/' . $strIdx . '/' . $session->has('mem_id'));
    }

    public function profile(int $applyIdx, int $memIdx)
    {
        $this->commonData();

        $this->header();
        $cache = \Config\Services::cache();
        $aApplier = $cache->get('is_applierStart_' . $applyIdx . '_' . $memIdx);

        $this->aData['data']['applyIdx'] = $applyIdx;
        $this->aData['data']['memIdx'] = $memIdx;

        echo view("www/interview/profile", $this->aData);
    }

    public function photo(int $applyIdx, int $memIdx)
    {
        $this->commonData();

        $this->header();

        $this->aData['data']['applyIdx'] = $applyIdx;
        $this->aData['data']['memIdx'] = $memIdx;

        echo view("www/interview/photo", $this->aData);
    }

    public function albumAction()
    {
        $strProfileFile = $this->request->getPost('profileFile');
        $strFilePath = $this->request->getPost('filePath');
        $strFileSize = $this->request->getPost('fileSize');
        $applyIdx = $this->request->getPost('applyIdx');
        $postCase = $this->request->getPost('postCase');
        $strBackUrl = $this->request->getPost('backUrl');

        if ($postCase == "" || $postCase == null) {
            alert_url($this->globalvar->aMsg['error1'], $strBackUrl ? $strBackUrl : "/");
            exit;
        }

        $session = session();

        $this->masterDB->table('iv_file')
            ->set([
                'file_type' => 'A',
                'file_org_name' => $strProfileFile,
                'file_save_name' => $strFilePath,
                'file_size' => $strFileSize,
            ])
            ->set(['file_reg_date' => 'NOW()'], '', false)
            ->set(['file_mod_date' => 'NOW()'], '', false)
            ->insert();

        $fileIdx = $this->masterDB->insertID();

        return redirect()->to('/interview/profile/check/' . $applyIdx . '/' . $session->has('mem_id') . '/' . $fileIdx);
    }

    public function check(int $applyIdx, int $memIdx, int $fileIdx)
    {
        $this->commonData();

        $this->header();

        $this->aData['data']['applyIdx'] = $applyIdx;
        $this->aData['data']['memIdx'] = $memIdx;
        $this->aData['data']['fileIdx'] = $fileIdx;

        $FileModel = new FileModel();

        $aProfileInfo = $FileModel->getProfile($fileIdx);
        $this->aData['data']['fileSaveDir'] = $aProfileInfo;


        echo view("www/interview/check", $this->aData);
    }

    public function exist(int $applyIdx, int $memIdx)
    {
        $this->commonData();

        $this->header();

        $this->aData['data']['applyIdx'] = $applyIdx;
        $this->aData['data']['memIdx'] = $memIdx;

        $FileModel = new FileModel();

        $aGetAllprofile = $FileModel->getAllprofile($memIdx);

        $this->aData['data']['getAllfile'] = $aGetAllprofile->paginate(6, 'getAllfile');
        $this->aData['data']['pager'] = $aGetAllprofile->pager;

        echo view("www/interview/exist", $this->aData);
    }

    public function setProfileAction()
    {
        $strapplyIdx = $this->request->getPost('applyIdx');
        $strmemIdx = $this->request->getPost('memIdx');
        $strfileIdx = $this->request->getPost('fileIdx');
        $postCase = $this->request->getPost('postCase');
        $strBackUrl = $this->request->getPost('backUrl');

        if ($postCase == "" || $postCase == null) {
            alert_url($this->globalvar->aMsg['error1'], $strBackUrl ? $strBackUrl : "/");
            exit;
        }
        $session = session();
        if ($strmemIdx != $session->has('mem_id')) {
            alert_url($this->globalvar->aMsg['error1'], $strBackUrl ? $strBackUrl : "/");
            exit;
        }

        $this->masterDB->table('iv_applier')
            ->set([
                'file_idx_thumb' => $strfileIdx,
                'app_iv_stat' => '1'
            ])
            ->where([
                'idx' => $strapplyIdx,
                'mem_idx' => $strmemIdx
            ])
            ->update();

        return redirect()->to('/interview/mic/' . $strapplyIdx . '/' . $session->has('mem_id'));
    }

    public function mic(int $applyIdx, int $memIdx)
    {
        $this->commonData();

        $this->header();

        $this->aData['data']['applyIdx'] = $applyIdx;
        $this->aData['data']['memIdx'] = $memIdx;

        echo view("www/interview/mic", $this->aData);
    }

    public function skipMicAction()
    {
        $strapplyIdx = $this->request->getPost('applyIdx');
        $strmemIdx = $this->request->getPost('memIdx');
        $strProcess = $this->request->getPost('process');
        $postCase = $this->request->getPost('postCase');
        $strBackUrl = $this->request->getPost('backUrl');

        if ($postCase == "" || $postCase == null) {
            alert_url($this->globalvar->aMsg['error1'], $strBackUrl ? $strBackUrl : "/");
            exit;
        }
        $session = session();
        if ($strmemIdx != $session->has('mem_id')) {
            alert_url($this->globalvar->aMsg['error1'], $strBackUrl ? $strBackUrl : "/");
            exit;
        }

        $this->masterDB->table('iv_applier')
            ->set([
                'app_iv_stat' => $strProcess
            ])
            ->where([
                'idx' => $strapplyIdx,
                'mem_idx' => $strmemIdx
            ])
            ->update();

        return redirect()->to('/interview/timer/' . $strapplyIdx . '/' . $session->has('mem_id'));
    }

    public function timer(int $applyIdx, int $memIdx)
    {
        $this->commonData();

        $this->header();

        $this->aData['data']['applyIdx'] = $applyIdx;
        $this->aData['data']['memIdx'] = $memIdx;
        $this->aData['data']['selfTimer'] = ['15', '30', '45', '60'];

        echo view("www/interview/timer", $this->aData);
    }

    public function timerAction()
    {
        $strapplyIdx = $this->request->getPost('applyIdx');
        $strmemIdx = $this->request->getPost('memIdx');
        $stranswerTimer = $this->request->getPost('answerTimer');
        $postCase = $this->request->getPost('postCase');
        $strBackUrl = $this->request->getPost('backUrl');

        if ($postCase == "" || $postCase == null) {
            alert_url($this->globalvar->aMsg['error1'], $strBackUrl ? $strBackUrl : "/");
            exit;
        }
        $session = session();
        if ($strmemIdx != $session->has('mem_id')) {
            alert_url($this->globalvar->aMsg['error1'], $strBackUrl ? $strBackUrl : "/");
            exit;
        }

        $this->masterDB->table('iv_report_result')
            ->set([
                'repo_answer_time' => $stranswerTimer
            ])
            ->where('applier_idx', $strapplyIdx)
            ->update();

        return redirect()->to('/interview/start/' . $strapplyIdx . '/' . $session->has('mem_id'));
    }

    public function start(int $applyIdx, int $memIdx)
    {
        $this->commonData();

        $this->header();

        $ApplierModel = new ApplierModel();
        $aStartInterview = $ApplierModel->startInterview($applyIdx);

        $this->aData['data']['startInterview'] = $aStartInterview;

        $aReportResult = json_encode($aStartInterview[0]['report_result']);

        $this->aData['data']['applyIdx'] = $applyIdx;
        $this->aData['data']['memIdx'] = $memIdx;
        $this->aData['data']['reportResult']=$aReportResult;

        echo view("www/interview/start", $this->aData);
    }

    public function end(int $applyIdx, int $memIdx)
    {
        $this->commonData();

        $this->header();

        $ApplierModel = new ApplierModel();
        $aEndInterview = $ApplierModel->endInterview($applyIdx, $memIdx);

        $this->aData['data']['endInterview'] = $aEndInterview;

        $this->aData['data']['applyIdx'] = $applyIdx;
        $this->aData['data']['memIdx'] = $memIdx;

        echo view("www/interview/end", $this->aData);
    }
}
