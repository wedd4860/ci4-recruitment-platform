<?php

namespace App\Controllers\Admin\Member;

use App\Models\MemberModel;
use App\Controllers\Admin\AdminController;

class MemberActionController extends AdminController
{
    private $backUrlList = '/prime/member/list';
    public function index()
    {
        alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
        exit;
    }

    public function memberAction()
    {
        $strPostCase = $this->request->getPost('postCase');
        $iMemIdx = $this->request->getPost('memIdx');
        $strBackUrl = $this->request->getPost('backUrl');
        $strMemType = $this->request->getPost('memType');
        $strMemAge = $this->request->getPost('memAge');
        $strMemWorkState = $this->request->getPost('memWorkState');
        $strMemGender = $this->request->getPost('memGender');
        $strMemCareer = $this->request->getPost('memCareer');
        $strMemEducation = $this->request->getPost('memEducation');
        $strMemAddressPostcode = $this->request->getPost('memAddressPostcode');
        $strMemAddress = $this->request->getPost('memAddress');
        $strMemAddressDetail = $this->request->getPost('memAddressDetail');
        $strMemMajor = $this->request->getPost('memMajor');
        $strMemPersonalType1 = $this->request->getPost('memPersonalType1');
        $strMemPersonalType2 = $this->request->getPost('memPersonalType2');
        $strMemPersonalType3 = $this->request->getPost('memPersonalType3');
        $strMemPersonalType4 = $this->request->getPost('memPersonalType4');
        $strMemPersonalType5 = $this->request->getPost('memPersonalType5');


        if (!$strMemType || !$iMemIdx || $strPostCase != 'member_write') {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
            exit;
        }
        if (!in_array($strMemType, ['M', 'A', 'L', 'C']) || !in_array($strMemWorkState, ['Y', 'N', ''])) {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
            exit;
        }

        if (!in_array($strMemPersonalType1, ['Y', 'N', '']) || !in_array($strMemPersonalType2, ['Y', 'N', '']) || !in_array($strMemPersonalType3, ['Y', 'N', '']) || !in_array($strMemPersonalType4, ['Y', 'N', '']) || !in_array($strMemPersonalType5, ['Y', 'N', ''])) {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
            exit;
        }

        $this->commonData();
        $updateData = [
            'mem_type' => $strMemType,
            'mem_age' => $strMemAge,
            'mem_work_state' => $strMemWorkState,
            'mem_gender' => $strMemGender,
            'mem_career' => $strMemCareer,
            'mem_education' => $strMemEducation,
            'mem_address_postcode' => $strMemAddressPostcode,
            'mem_address' => $strMemAddress,
            'mem_address_detail' => $strMemAddressDetail,
            'mem_major' => $strMemMajor,
            'mem_personal_type_1' => $strMemPersonalType1,
            'mem_personal_type_2' => $strMemPersonalType2,
            'mem_personal_type_3' => $strMemPersonalType3,
            'mem_personal_type_4' => $strMemPersonalType4,
            'mem_personal_type_5' => $strMemPersonalType5,
        ];

        $result = $this->masterDB->table('iv_member')
            ->set($updateData)
            ->set(['mem_mod_date' => 'NOW()'], '', false)
            ->where(['idx' => $iMemIdx])
            ->update();

        if ($result) {
            alert_url($this->globalvar->aMsg['success1'], $strBackUrl != '' ? $strBackUrl : $this->backUrlList);
            exit;
        } else {
            alert_back($this->globalvar->aMsg['error2']);
            exit;
        }
    }
}
