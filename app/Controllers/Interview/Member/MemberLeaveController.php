<?php

namespace App\Controllers\Interview\Member;

use App\Controllers\Interview\WwwController;
use App\Models\MemberLeaveModel;
use App\Models\MemberModel;
use App\Libraries\EncryptLib;

class MemberLeaveController extends WwwController
{
    public $backUrlList = '/my/leave';
    public function index()
    {
        $this->leave('step1');
    }

    public function leave(string $code)
    {
        if (!in_array($code, ['step1', 'step2', 'step3'])) {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
            exit;
        }
        // data init
        $this->commonData();

        if (in_array($code, ['step2', 'step3'])) {
            if (!cache('is_leaveMemIdx_' . $this->aData['data']['session']['idx'])) {
                alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
                exit;
            }
        }

        $this->aData['data']['leave'] = $this->globalvar->aMemberLeave;

        echo view("www/member/leave/" . $code, $this->aData);
    }

    public function memberLeaveAction()
    {

        $iReason = $this->request->getPost('reason');
        $strMemo = $this->request->getPost('memo');
        $strPostCase = $this->request->getPost('postCase');
        $strBackUrl = $this->request->getPost('backUrl');

        $session = session();
        if (!is_numeric($iReason) || $iReason > 9 || !$session->has('idx') || $strPostCase != 'leave_write') {
            alert_url($this->globalvar->aMsg['error1'], $strBackUrl ? $strBackUrl : $this->backUrlList);
            exit;
        }
        $iIdx = $session->get('idx');


        if (!cache('is_leaveMemIdx_' . $iIdx)) {
            alert_url($this->globalvar->aMsg['error1'], $strBackUrl ? $strBackUrl : $this->backUrlList);
            exit;
        }

        //트랜잭션 start
        $this->masterDB->transBegin();
        $this->masterDB->table('iv_member')
            ->set(['delyn' => 'Y'])
            ->set(['mem_del_date' => 'NOW()'], '', false)
            ->where('idx', $iIdx)
            ->update();

        $this->masterDB->table('iv_member_leave')
            ->set([
                'mem_idx' =>  $iIdx,
                'mem_leave_reason' =>  $iReason,
                'mem_leave_reason_memo' =>  $strMemo
            ])
            ->set(['mem_leave_reg_date' => 'NOW()'], '', false)
            ->insert();

        // 트랜잭션 end
        if ($this->masterDB->transStatus() === false) {
            $this->masterDB->transRollback();
            return alert_back($this->globalvar->aMsg['error2']);
        } else {
            $this->masterDB->transCommit();
            session()->destroy();
        }
        return redirect()->to('/my/leave/step3');
    }

    public function memberLeavePwdCheckAction()
    {
        $memberModel = new MemberModel();
        $strPassword = $this->request->getPost('password');
        $strPostCase = $this->request->getPost('postCase');
        $strBackUrl = $this->request->getPost('backUrl');

        $session = session();
        if (!$strPassword || !$session->has('idx') || $strPostCase != 'check_password') {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
        }
        $this->commonData();
        $strId = $session->get('mem_id');

        $encryptLib = new EncryptLib();
        $memberModel = new MemberModel();

        $aRow = $memberModel->getMember($strId);
        $strGetResultPw = dot_array_search('mem_password', $aRow);
        if ($encryptLib->checkPassword($strPassword, $strGetResultPw)) {

            cache()->save('is_leaveMemIdx_' . $session->get('idx'), 'Y', 600);
            return redirect()->to('/my/leave/step2');
        } else {
            //1.0 비밀번호 체크
            $aRow = $memberModel->getOldMember($strId, $strPassword);
            if (count($aRow) > 0) {
                cache()->save('is_leaveMemIdx_' . $session->get('idx'), 'Y', 600);
                return redirect()->to('/my/leave/step2');
            }
        }
        alert_url($this->globalvar->aMsg['error4'], $strBackUrl ? $strBackUrl : $this->backUrlList);
    }
}
