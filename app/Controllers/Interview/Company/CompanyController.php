<?php

namespace App\Controllers\Interview\company;

use App\Controllers\Interview\WwwController;
use App\Models\CompanyModel;
use App\Models\RecruitModel;
use App\Models\CompanyTagModel;
use App\Models\ConfigCompanyFileModel;
use App\Models\ConfigCompanyNewsModel;
use App\Models\MemberRecruitKor;
use App\Models\MemberRecruitScrapModel;
use App\Models\ConfigCompanyTagModel;

class CompanyController extends WwwController
{
    private $backUrlList = '/';

    public function detail(int $comIdx)
    {
        $this->commonData();

        if (!$comIdx) {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
            exit;
        }

        $companyModel = new CompanyModel();
        $recruitModel = new RecruitModel();
        $companyTagModel = new CompanyTagModel();
        $configCompanyFileModel = new ConfigCompanyFileModel();
        $configCompanyNewsModel = new ConfigCompanyNewsModel();
        $memberRecruitKor = new MemberRecruitKor();
        
        $companyInfo = $companyModel->getCompanyInfo($comIdx);  // 기업정보
        $currentRecruit = $recruitModel->getCurrentRecruit($comIdx);    // 기업이 올린 공고정보 (공고명, 회사명, 해당공고지역, 해당공고recIdx 즐겨찾기)
        $getCompanyTag = $companyTagModel->getCompanyTag($comIdx);  //기업태그
        $getCompanyFile = $configCompanyFileModel->getCompanyFile($comIdx); //기업 상세 이미지
        $getCompanyNews = $configCompanyNewsModel->getCompanyNews($comIdx); //기업 기사
        
        $aCurrentRecruitIdx = [];
        foreach ($currentRecruit as $val) {
            array_push($aCurrentRecruitIdx, $val['idx']);
        }

        $getRecruitKor = $memberRecruitKor->getRecruitKor($aCurrentRecruitIdx); // 기업공고지역
        $scrapComState = 0;

        //세션 체크해서 즐겨찾기 여부 표시
        $session = session();
        $isLogin = false;
        if ($session->has('idx')) {
            $isLogin = true;
            $memberIdx = $session->get('idx');
            
            $memberRecruitScrapModel = new MemberRecruitScrapModel();
            $getMyComScrap = $memberRecruitScrapModel->getMyComScrap($memberIdx);
            
            //기업 스크랩 여부
            foreach ($getMyComScrap as $comScrapKey => $comScrapVal) {
                if($comScrapVal['com_idx'] == $comIdx){
                    $scrapComState++;
                }
            }

            //공고 idx 즐겨찾기 여부 알기
            $getRecScrap = $memberRecruitScrapModel->getRecScrap($this->aData['data']['session']['idx'], $aCurrentRecruitIdx);  

            $this->aData['data']['getRecScrap'] = $getRecScrap;
        }
        
        $this->aData['data']['comIdx'] = $comIdx;
        $this->aData['data']['info'] = $companyInfo;
        $this->aData['data']['companyRec'] = $currentRecruit;
        $this->aData['data']['companyTag'] = $getCompanyTag;
        $this->aData['data']['companyFile'] = $getCompanyFile;
        $this->aData['data']['companyNews'] = $getCompanyNews;
        $this->aData['data']['recruitKor'] = $getRecruitKor;
        $this->aData['data']['isLogin'] = $isLogin;
        $this->aData['data']['scrapComState'] = $scrapComState; //로그인인 상태에서 $scrapComState가 1이상이면 스크랩되어있는 기업
        $this->aData['data']['memIdx'] = $memberIdx ?? '';

        $this->header();
        echo view("www/company/detail", $this->aData);
    }

    public function explore()
    {
        $this->commonData();

        $configCompanyTagModel = new ConfigCompanyTagModel();
        $companyModel = new CompanyModel();

        $getCompanyTag = $configCompanyTagModel->getCompanyTag()->findAll(4);

        //캐시설정(AI 인터뷰로 적극 채용중인 기업)
        if(!$allCompany = cache('allCompany')){
            $getAllCompany = $companyModel->getAllCompany()->findAll(100);
            $aAllCom = $getAllCompany;
            cache()->save('allCompany', $aAllCom, 3600);
            $allCompany = $aAllCom;
        }

        shuffle($allCompany);

        $this->aData['data']['companyTag'] = $getCompanyTag;
        $this->aData['data']['company'] = $allCompany;

        $this->header();
        echo view("www/company/explore", $this->aData);
    }
}