<?php

namespace App\Controllers\Interview\jobs;

use CodeIgniter\I18n\Time;
use App\Controllers\Interview\WwwController;
use App\Models\RecruitModel;
use App\Models\ApplierModel;
use App\Models\MemberRecruitCategoryModel;
use App\Models\ReportResultModel;
use App\Models\ResumeModel;
use App\Models\RecruitInfoModel;
use App\Models\MemberRecruitScrapModel;
use Config\Services;

class JobsController extends WwwController
{
    private $encrypter;
    private $backUrlList = '/jobs/list';

    public function index()
    {
        $this->list();
    }

    public function list()
    {
        $strSearchText = $this->request->getGet('searchText');
        $strSearchMyApply = $this->request->getGet('searchMyApply');
        $strSearchOrder = $this->request->getGet('searchOrder');

        if ($strSearchMyApply && $strSearchMyApply != 'M') {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
            exit;
        }

        if ($strSearchOrder && !in_array($strSearchOrder, ['rec_hit', 'rec_end_date', 'rec_start_date'])) {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
            exit;
        }

        //[1] 사용자 정보 가져오기
        $this->commonData();
        $iMemIdx = dot_array_search('data.session.idx', $this->aData);
        $strMemberType = 'guest';
        $aJobIdx = [];
        //$aJobIdx = [1, 2, 3]; //todo 삭제 되어야함 테스트용도로 임시값 넣음

        if ($iMemIdx) {
            $memberRecruitCategory = new MemberRecruitCategoryModel();
            $aCategoryInterview = $memberRecruitCategory->getJoinTypeInterview($iMemIdx);
            $tmpCate = dot_array_search('0.job_depth_1', $aCategoryInterview);
            $tmpInter = dot_array_search('0.inter_type', $aCategoryInterview);
            if (!$tmpCate && !$tmpCate) {
                //2.회원+관심사없음
                $strMemberType = 'login';
            } else if ($tmpCate && !$tmpInter) {
                //3.회원+관심사 있음
                $strMemberType = 'loginCate';
                $aJobIdx = dot_array_search('0.job_idx', $aCategoryInterview); // 관심직무
            } else {
                //4.회원+관심사있음 +인터뷰있음
                $strMemberType = 'loginAll';
            }
        }
        //[2] 관심직무 정보 가져오기 
        $recruitModel = new RecruitModel();
        if (in_array($strMemberType, ['guest', 'login'])) {
            $this->aData['data']['interest'] = false; //직무관심사없음
        } else if (in_array($strMemberType, ['loginCate', 'loginAll'])) {
            $this->aData['data']['interest'] = true; //직무관심사있음    
        }

        //[3] 채용정보목록  가져오기 
        $recruitModel->getRecruitList($aJobIdx, $strSearchText, $strSearchMyApply, $strSearchOrder);
        $this->aData['data']['recruit'] = $recruitModel->paginate(5, 'jobsList'); 
        $this->aData['data']['pager'] = $recruitModel->pager;
        $this->aData['data']['search'] = ['text' => $strSearchText];
        $this->aData['data']['searchMyApply'] = $strSearchMyApply;
        $this->aData['data']['searchOrder'] = $strSearchOrder;

        $aConfig = $this->globalvar->getConfig();
        $arrWorkDayKind = $aConfig['recruit']['work_day'];

        //[4] 채용정보목록데이터 가공
        foreach ($this->aData['data']['recruit'] as $key => $val){
            $recCareer = '';//[고용형태에따라 요일 or 경력 표시]
            if (in_array("3", explode(',', $val['recWorkType']))) {//고용형태가 아르바이트면 요일을 표시                
                $arrWorkDayConvert = [];
                $arrWorkDay = explode(',', $val['recWorkDay']);//근무요일가져오기 
                foreach ($arrWorkDay as $workDay) {
                    $arrWorkDayConvert[] = $arrWorkDayKind[$workDay];
                }
                $recCareer = implode(',', $arrWorkDayConvert); //ex 월,수
            } else { //고용형태가 아르바이트가 아니면 경력을 표시
                if ($val['recCareer'] == 'N') {
                    $recCareer  = '신입';
                } else if ($val['recCareer'] == 'C') {
                    $recCareer  = '경력';
                } else {
                    $recCareer  = '경력무관';
                }
            }
            $this->aData['data']['recruit'][$key]['recCareer'] =  $recCareer;

            //[마감일 Dday표시]
            if ($val['recEndDate']) {
                $strNow = Time::parse('now', 'Asia/Seoul')->toLocalizedString('yyyyMMdd');
                $timeCurrent = Time::parse($strNow, 'Asia/Seoul');
                $timeEndDate = Time::parse($val['recEndDate'], 'Asia/Seoul');
                $strDiffDate = $timeCurrent->difference($timeEndDate)->getDays();
                if ($strDiffDate > 0) {
                    $strDiffDate = 'D-' . $strDiffDate . '일';
                } else {
                    $strDiffDate = 'D ' . $strDiffDate . '일 지남';
                }
                $this->aData['data']['recruit'][$key]['recEndDate'] = $strDiffDate;
            }
            //[내인터뷰로 지원가능한 면접 표시]
            if ($val['recApply']) {
                if ($val['recApply'] == 'M') { //기업면접이면
                    $this->aData['data']['recruit'][$key]['recApply'] = '내인터뷰지원가능';
                } else {
                    $this->aData['data']['recruit'][$key]['recApply'] = '';
                }
            }
        }
        $this->header();
        echo view("www/jobs/list", $this->aData);
    }

    public function detail(int $_idx)
    {
        $this->encrypter = Services::encrypter();
        $this->commonData();
        //로그인 유무 체크
        $session = session();
        $isLogin = false;
        if ($session->has('idx')) {
            $isLogin = true;
        }

        $recruitModel = new RecruitModel();
        $recruitRow = $recruitModel->getRecruit($_idx);
        $memberRecruitCategoryModel = new MemberRecruitCategoryModel();
        $categories = $memberRecruitCategoryModel->getCategory($_idx);

        $memberRecruitCategoryModel = new MemberRecruitCategoryModel(); //해당 공고의 job_category 추출
        $aCategoryIdx = $memberRecruitCategoryModel->getCategoryIdx([$_idx]);
        //이공고어때요? (해당공고와 카테고리가 일치하는 공고 랜덤으로 2개뽑기)
        $sameCategoryRec = $memberRecruitCategoryModel->getSameCategoryRec($aCategoryIdx, $_idx, 2);

        // 고용형태 explode
        $aWorkType = [];
        $aWorkType = explode(',', $recruitRow['rec_work_type']);

        $applierCategoryRow = [];
        $alreadyApplyRow = [];
        if ($isLogin) {
            $applierModel = new ApplierModel();
            $recruitInfoModel = new RecruitInfoModel();
            $memberIdx = $session->get('idx');
            $applierCategoryRow = $applierModel->getMemberCategoty($memberIdx, $categories);
            $alreadyApplyRow = $recruitInfoModel->alreadyApply($memberIdx, $_idx); //이미 지원한 공고인지 체크
        }else{
            $applierCategoryRow = 'beforeLogin';
        }

        $this->aData['data']['aData'] = [
            'idx' => $_idx
        ];
        $this->aData['data']['workType'] = $aWorkType;
        $this->aData['data']['randomInfo'] = $recruitModel->getRandomRecInfo($sameCategoryRec); //랜덤으로 뽑은 공고 정보 가져오기
        $this->aData['data']['categories'] = $categories;
        $this->aData['data']['job'] = $recruitRow;
        $this->aData['data']['tag'] = $recruitModel->getTags($_idx);
        $this->aData['data']['categoryIdx'] = $aCategoryIdx;
        $this->aData['data']['applierCategory'] = $applierCategoryRow;
        $this->aData['data']['alreadyApply'] = $alreadyApplyRow;

        $this->header();
        echo view("www/jobs/detail", $this->aData);
    }

    public function detailAction()
    {
        $_idx = $this->request->getPost('recIdx');
        $srtState = $this->request->getPost('state');
        $backUrl = $this->request->getPost('backUrl');
        $postCase = $this->request->getPost('postCase');

        if ($postCase != "detail_view" || !$_idx || !$srtState) {
            alert_url($this->globalvar->aMsg['error1'], $backUrl ? $backUrl : "/");
            exit;
        }

        $aData = [
            'idx' => $_idx,
            'state' => $srtState,
        ];

        //암호화
        $this->encrypter = Services::encrypter();
        $encodeData = base64url_encode($this->encrypter->encrypt(json_encode($aData)));
        return redirect()->to('/jobs/apply/?data=' . $encodeData);
    }

    public function apply()
    {
        $this->commonData();
        $aData = $this->request->getGet('data');

        //공고 idx 복호화
        $this->encrypter = Services::encrypter();

        $decodeData = json_decode(
            $this->encrypter->decrypt(base64url_decode($aData)),
            true
        );

        $aIdx = $decodeData['idx'];

        $this->aData['data']['aData'] = [
            'get' => [
                'recIdx' => $aData,
                'state' => $decodeData['state']
            ]
        ];

        //Model
        $recruitModel = new RecruitModel();
        $memberRecruitCategoryModel = new MemberRecruitCategoryModel();
        $resumeModel = new ResumeModel();
        $applierModel = new ApplierModel();
        $reportResultModel = new ReportResultModel();

        $this->aData['data']['job'] = $recruitModel->getRecruits($aIdx);

        $rCount = 0;
        foreach ($this->aData['data']['job'] as $key => $val) {
            if ($this->aData['data']['job'][$key]['rec_resume'] == 'R') {
                $rCount++;
            }
        }

        $this->aData['data']['rCount'] = $rCount;

        $memIdx = $this->aData['data']['session']['idx'];

        $aCategoryIdx = $memberRecruitCategoryModel->getCategoryIdx($aIdx); //해당 공고의 job_category 추출

        $applierIdxInfo = $this->aData['data']['applier'] = $applierModel->getApplierData($memIdx, $aCategoryIdx);  //로그인한 회원의 동일 카테고리 인터뷰 job_category

        //해당공고 job_idx
        $recruitApplierJobIdx = [];
        for ($i = 0; $i < count($aCategoryIdx); $i++) {
            array_push($recruitApplierJobIdx, $aCategoryIdx[$i]['job_idx']);
        }
        $this->aData['data']['jobIdx'] = $recruitApplierJobIdx;

        //회원의 job_idx
        $aplierJobIdx = [];
        for ($i = 0; $i < count($applierIdxInfo); $i++) {
            array_push($aplierJobIdx, $applierIdxInfo[$i]['job_idx']);
        }

        // 공고의 job_idx와 회원의 job_idx 비교(직무가 일치한지 안한지 체크)
        $iCompareCnt = 0;
        for ($i = 0; $i < count($recruitApplierJobIdx); $i++) {
            for ($j = 0; $j < count($aplierJobIdx); $j++) {
                if ($recruitApplierJobIdx[$i] == $aplierJobIdx[$j]) {
                    $iCompareCnt++;
                }
            }
        }

        //회원의 idx만 추출
        $applierIdx = [];
        for ($i = 0; $i < count($applierIdxInfo); $i++) {
            array_push($applierIdx, $applierIdxInfo[$i]['idx']);
        }

        $maxApplierIdx = $this->aData['data']['maxApplierIdx'] = $reportResultModel->getMaxScoreInterview($applierIdx); //관련직무 인터뷰중 점수가 제일높은 applier_idx

        $this->aData['data']['_idx'] = $aIdx;
        $this->aData['data']['categoryIdx'] = $aCategoryIdx;
        $this->aData['data']['interviewInfo'] = $applierModel->getApplierInfo($maxApplierIdx);  //최고점수 인터뷰정보 (날짜, 카테고리, 프로필, 공개여부, iv_applier idx)
        $this->aData['data']['reportInfo'] = $reportResultModel->getReportInfo($maxApplierIdx); //최고점수 인터뷰정보 (질문개수(3), 등급(A))
        $this->aData['data']['resume'] = $resumeModel->applyResume($memIdx);    //이력서(제일 최근 1개)
        $this->aData['data']['compareCnt'] = $iCompareCnt;  //compareCnt가 0이면 일치하는 jo_idx가 없음 -> 직무가 일치하지 않음

        $this->header();
        echo view("www/jobs/apply_interview", $this->aData);
    }

    public function complete()
    {
        $this->commonData();
        $aData = $this->request->getGet('data');
        if (!$aData) {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
            exit;
        }

        //공고 idx 복호화
        $this->encrypter = Services::encrypter();
        $decodeData = json_decode(
            $this->encrypter->decrypt(base64url_decode($aData)),
            true
        );
        $recIdx = $decodeData['idx'];
        $recState = $decodeData['state'];

        //model
        $recruitModel = new RecruitModel();
        $recruitInfoModel = new RecruitInfoModel();
        $memberRecruitCategoryModel = new MemberRecruitCategoryModel();
        $memberRecruitScrapModel = new MemberRecruitScrapModel();

        $myApplyRow = $recruitInfoModel->getMyApply($this->aData['data']['session']['idx'])->findAll(); // 내가 지원한 공고 idx
        $aCategoryIdx = $memberRecruitCategoryModel->getCategoryIdx($recIdx); //해당 공고의 job_category 추출
        //이공고어때요? (해당공고와 카테고리가 일치하는 공고 랜덤으로 5개뽑기)
        $sameCategoryRec = $memberRecruitCategoryModel->getSameCategoryRecs($aCategoryIdx, $recIdx, $myApplyRow, 3);
        //랜덤으로 뽑은 공고 즐겨찾기 여부
        $aRandomRcruitScrap = $memberRecruitScrapModel->getRecScrap($this->aData['data']['session']['idx'], $sameCategoryRec);

        $this->aData['data']['categoryIdx'] = $aCategoryIdx;
        $this->aData['data']['randomInfo'] = $recruitModel->getRandomRecInfo($sameCategoryRec); //랜덤으로 뽑은 공고 정보 가져오기        
        $this->aData['data']['recruitTitles'] = $recruitModel->getRecruitTitles($recIdx); //지원완료한 공고표시
        $this->aData['data']['scrap'] = $aRandomRcruitScrap;
        $this->aData['data']['aData'] = [
            'get' => [
                'idx' => $recIdx,
                'state' => $recState
            ]
        ];

        //view
        $this->header();
        echo view("www/jobs/complete_apply", $this->aData);
    }

    public function jobApplyAction()
    {
        $enData = $this->request->getPost('enData');    // 암호화된 data
        $memIdx = $this->request->getPost('memIdx');    // 멤버 idx
        $comIdx = $this->request->getPost('comIdx');    // 회사 idx
        $recIdx = $this->request->getPost('recIdx');    // 공고 idx
        $resIdx = $this->request->getPost('resIdx');    // 이력서 idx
        $recFile = $this->request->getFiles('file');       // 파일
        $code = 'applyFiles';

        $this->backUrl = '/';

        if (!$enData || !$memIdx || !$comIdx || !$recIdx) {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrl);
            exit;
        }

        $aFileIdx = [];

        //트랜잭션 start
        $this->masterDB->transBegin();

        // FILE : name이 비어있지 않고, 사이즈가 0이 아니면
        for ($i = 0; $i < count($recFile['file']); $i++) {
            if ($recFile['file'][$i]->getName() != "" && $recFile['file'][$i]->getSize() != 0) {
                if (!$recFile['file'][$i]->hasMoved()) {
                    $fileName = $recFile['file'][$i]->getName();
                    $iFilesize = $recFile['file'][$i]->getSize();
                    $uploadName = $recFile['file'][$i]->getRandomName();
                    $uploadPath = '/' . 'uploads/' . $code . '/' . date("Ymd");
                    $originalName = $recFile['file'][$i]->getClientName();
                    $recFile['file'][$i]->move(WRITEPATH . $uploadPath, $uploadName);
                }

                // iv_file로 INSERT
                $this->masterDB->table('iv_file')
                    ->set([
                        'file_type' => 'O',
                        'file_org_name' => $fileName,
                        'file_save_name' => $uploadPath . '/' . $uploadName,
                        'file_size' => $iFilesize,
                    ])
                    ->set(['file_mod_date' => 'NOW()'], '', false)
                    ->set(['file_reg_date' => 'NOW()'], '', false)
                    ->insert();

                $fileIdx = $this->masterDB->insertID();

                array_push($aFileIdx, $fileIdx);
            }
        }

        // INSERT iv_recruit_info
        foreach ($recIdx as $key => $val) {
            $readyDB = $this->masterDB->table('iv_recruit_info')
                ->set([
                    'mem_idx' => $memIdx,
                    'com_idx' => $comIdx[$key],
                    'rec_idx' => $recIdx[$key],
                    'res_idx' => $resIdx,
                ])
                ->set(['res_info_reg_date' => 'NOW()'], '', false)
                ->set(['res_info_mod_date' => 'NOW()'], '', false);

            foreach ($aFileIdx as $aFileKey => $aFileVal) {
                $readyDB->set(['file_idx_data_' . ($aFileKey + 1) => $aFileIdx[$aFileKey]]);
            }
            $readyDB->insert();
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
            alert_url($this->globalvar->aMsg['error3'], $this->backUrl);
            exit;
        }

        return redirect()->to('/jobs/complete/?data=' . $enData);
    }

    public function applyAtOnce()
    {
        $recState = $this->request->getPost('state');    // 지원상태(M:내인터뷰, C:기업인터뷰)
        $recIdxs = $this->request->getPost('recIdx');    // 멤버 idx

        $strBackUrl = '/';

        if (!$recState || !$recIdxs) {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrl);
        }

        $recIdx = explode(' ', $recIdxs);

        //암호화
        $this->encrypter = Services::encrypter();
        $aIdx = json_encode($recIdx);
        $strEnIdx = base64url_encode($this->encrypter->encrypt($aIdx));

        return redirect()->to('/jobs/apply/?state=' . $recState . '&ridx=' . $strEnIdx);
    }
}
