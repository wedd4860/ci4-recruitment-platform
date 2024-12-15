<?php

namespace App\Controllers\Admin\Member;

use App\Models\MemberModel;
use App\Models\MemberRecruitCategoryModel;
use App\Models\MemberRecruitKor;
use App\Controllers\Admin\AdminController;

class MemberWriteController extends AdminController
{
    private $backUrlList = '/prime/qna/list';
    public function index()
    {
        alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
        exit;
    }

    public function write(int $idx = null)
    {
        if (!$idx) {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
            exit;
        }
        $this->commonData();

        //model
        $memberModel = new MemberModel();
        $aMemberRow = $memberModel
            ->select([
                'iv_member.idx as memIdx', 'iv_member.com_idx as comIdx',
                'iv_member.mem_id as memId',  'iv_member.mem_name as memName', 'iv_member.mem_tel as memTel', 'iv_member.mem_type as memType',
                'iv_member.mem_age as memAge', 'iv_member.mem_gender as memGender', 'iv_member.mem_career as memCareer', 'iv_member.mem_work_state as memWorkState',
                'iv_member.mem_pay as memPay', 'iv_member.mem_education as memEducation', 'iv_member.mem_address as memAddress', 'iv_member.mem_major as memMajor',
                'iv_member.mem_personal_type_1 as memPersonalType1', 'iv_member.mem_personal_type_2 as memPersonalType2', 'iv_member.mem_personal_type_3 as memPersonalType3',  'iv_member.mem_personal_type_4 as memPersonalType4',
                'iv_member.mem_personal_type_5 as memPersonalType5', 'iv_member.mem_personal_type_6 as memPersonalType6', 'iv_member.mem_personal_type_7 as memPersonalType7',
                'iv_member.mem_visit_count as memVisitCount', 'iv_member.mem_last_password_date as memLastPasswordDate',
                'iv_member.mem_address_postcode as memAddressPostcode', 'iv_member.mem_address_detail memAddressDetail', 'iv_member.mem_visit_date as memVisitDate',
                'iv_file.idx as fileIdx', 'iv_file.file_org_name as fileOrgName', 'iv_file.file_save_name as fileSaveName',
            ])
            ->join('iv_file', 'iv_file.idx = iv_member.file_idx_profile', 'left')
            ->where([
                'iv_member.delyn' => 'N',
                'iv_member.idx' => $idx,
            ])->first();

        $this->aData['data']['list'] = $aMemberRow;

        $memberRecruitCategory = new MemberRecruitCategoryModel();
        $this->aData['data']['category'] = $memberRecruitCategory->getJoinType('M', $idx);

        $memberRecruitKor = new MemberRecruitKor();
        $this->aData['data']['area'] = $memberRecruitKor->getJoinType('M', $idx);

        // view
        $this->header();
        $this->nav();
        echo view('prime/member/write', $this->aData);
        $this->footer();
    }
}
