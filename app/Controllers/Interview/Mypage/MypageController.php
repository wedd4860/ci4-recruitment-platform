<?php

namespace App\Controllers\Interview\Mypage;

use App\Controllers\BaseController;
use App\Models\MemKorModel;
use App\Models\MemberModel;
use App\Models\MemCateModel;
use App\Models\jobCategoryModel;
use App\Models\KoreaAreaModel;
use Config\Database;
use Config\Services;

class MypageController extends BaseController
{
    public $db;
    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function commonData(): array
    {
        // data init
        $aData = [];
        $aData['data'] = $this->viewData;
        return $aData;
    }

    public function header()
    {
        // data init
        $aData = $this->commonData();
        echo view('www/templates/header', $aData);
    }

    public function nav()
    {
        // data init
        $aData = $this->commonData();
    }

    public function footer()
    {
        // data init
        $aData = $this->commonData();
    }

    public function main()
    {
        $this->header();
        // data init
        $aData = $this->commonData();

        //멤버 테이블
        $memberModel = new MemberModel('iv_member');

        //멤버가 선택한 희망직무 테이블
        $memCateModel = new MemCateModel('iv_member_recruit_category');

        //멤버가 선택한 희망지역 테이블
        $memKorModel = new MemKorModel('iv_member_recruit_kor');

        $this->aData['data']['session']['memIdx'] = 1;
        $memidx = $this->aData['data']['session']['memIdx'];

        //기본적인 마이페이지 정보 가져오기 (이름 등)
        $aRowMember = $this->aData['data']['Member'] = $memberModel->MypageMem($memidx);



        if ($aRowMember) {
            if ($aRowMember['mem_name'] == null || $aRowMember['mem_name'] == "") {
                $this->aData['data']['Member']['mem_name'] = '사용자';
            }

            // if($aRowMember['area_idx']==null || $aRowMember['area_idx']==""){
            //     $this->aData['data']['Member']['area_idx'] = '관심 직무 입력하고, 맞춤 정보 추천받기';
            // }
        }

        //희망지역 가져오기
        $aRowKor = $this->aData['data']['kor'] = $memKorModel->getKorArea($memidx);

        if ($aRowKor == null || $aRowKor == '') {
            $this->aData['data']['kor'][0]['area_depth_text_1'] = '관심 직무 입력하고, ';
            $this->aData['data']['kor'][0]['area_depth_text_2'] = '맞춤 정보 추천받기';
        }

        //희망직무 가져오기
        $aRowCategory = $this->aData['data']['category'] = $memCateModel->getJopcategory($memidx);

        if ($aRowCategory == null || $aRowCategory == '') {
            $this->aData['data']['category'][0]['job_depth_text'] = '어떤 포지션에서 일하고 싶나요?';
        }

        echo view("www/mypage/main", $this->aData);
    }

    public function modify()
    {
        // data init
        $aData = $this->commonData();

        $this->header();

        $this->aData['data']['session']['memIdx'] = 1;
        $memidx = $this->aData['data']['session']['memIdx'];

        //수정할 멤버 테이블
        $memberModel = new MemberModel('iv_member');
        //멤버가 선택한 희망직무 테이블
        $memCateModel = new MemCateModel('iv_member_recruit_category');

        //멤버가 선택한 희망지역 테이블
        $memKorModel = new MemKorModel('iv_member_recruit_kor');

        $aRowMember = $this->aData['data']['member'] = $memberModel->MypageMem($memidx);

        $today = date("Y");
        //나이를 출생년도로 변경
        $this->aData['data']['member']['mem_age'] = $today - $this->aData['data']['member']['mem_age'];

        // var_dump($this->aData['data']['modify']['mem_age']);
        // file_idx_profile //개인 프로필
        // job_idx_position //관심 직무(희망직무)
        // area_idx //관심지역(희망지역)
        // mem_pay //희망 연봉

        //희망직무 가져오기
        $aRowCategory = $this->aData['data']['category'] = $memCateModel->getJopcategory($memidx);

        //희망지역 가져오기
        $aRowKor = $this->aData['data']['kor'] = $memKorModel->getKorArea($memidx);


        if ($aRowKor == null || $aRowKor == '' || $aRowCategory == null || $aRowCategory == '' || $aRowMember['mem_pay'] == null || $aRowMember['mem_pay'] == '') {
            $this->aData['data']['wantCate'] = 0;
        } else {
            $this->aData['data']['wantCate'] = 1;
        }

        // echo '<pre>';
        // print_r($this->aData);
        // echo '<br>';

        echo view("www/mypage/modify", $this->aData);
    }

    public function interest()
    {
        $aData = $this->commonData();

        $this->header();
        $this->aData['data']['session']['memIdx'] = 1;
        $memidx = $this->aData['data']['session']['memIdx'];

        //수정할 멤버 테이블
        $memberModel = new MemberModel('iv_member');
        $jobCategoryModel = new JobCategoryModel('iv_job_category');
        $koreaAreaModel = new KoreaAreaModel('iv_korea_area');

        $aRowMember = $this->aData['data']['member'] = $memberModel->MypageMem($memidx);

        $aRowJob = $this->aData['data']['job'] = $jobCategoryModel->getAllcategory();

        $aRowArea = $this->aData['data']['area'] = $koreaAreaModel->getAllarea();

        echo view("www/mypage/interest", $this->aData);
    }

    public function interestAction()
    {
        $aInputdata = $this->request->getPost(); // (2)
        // $this->aData['data']['post'] = $aInputdata;

        //저장한 input 데이터 캐시
        if (!$aInput = cache('postData')) {
            $aInput = $this->aData['data']['post'];
            cache()->save('postData', $aInputdata, 600);
        }
        // cache()->save('aTestData', ['aaa' => "bbb"], 60);
        $this->response->redirect('/mypage/modify');

        // $this->aData['data']['session']['memIdx']=1;
        // $memidx=$this->aData['data']['session']['memIdx'];

        //  //수정할 멤버 테이블
        //  $memberModel= new MemberModel('iv_member');
        //  //멤버가 선택한 희망직무 테이블
        //  $memCateModel= new MemCateModel('iv_member_recruit_category');

        //  //멤버가 선택한 희망지역 테이블
        //  $memKorModel= new MemKorModel('iv_member_recruit_kor');

        // //멤버 테이블 가져오기
        // $aRowMember=$this->aData['data']['member']=$memberModel->MypageMem($memidx);

        // //희망직무 가져오기
        // $aRowCategory=$this->aData['data']['category']=$memCateModel->getJopcategory($memidx);

        // //희망지역 가져오기
        // $aRowKor= $this->aData['data']['kor']=$memKorModel->getKorArea($memidx);

        // $today=date("Y");
        // //나이를 출생년도로 변경
        // $this->aData['data']['member']['mem_age']=$today-$this->aData['data']['member']['mem_age'];

        // //희망직무, 지역, 연봉 있는지 확인
        // if($aRowKor==null || $aRowKor=='' || $aRowCategory==null || $aRowCategory=='' || $aRowMember['mem_pay']==null || $aRowMember['mem_pay']=='' ){
        //     $this->aData['data']['wantCate']=0;
        // } else {
        //     $this->aData['data']['wantCate']=1;
        // }

        // echo view("www/mypage/modify",$this->aData);
    }

    public function __destruct()
    {
        $this->db->close();
    }
}
