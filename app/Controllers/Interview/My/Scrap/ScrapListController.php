<?php

namespace App\Controllers\Interview\My\Scrap;

use App\Controllers\Interview\WwwController;
use App\Models\MemberRecruitScrapModel;
use CodeIgniter\I18n\Time;

class ScrapListController extends WwwController
{
    private $backUrlList = '/';
    public function index()
    {
    }

    public function list(string $type)
    {
        if (!in_array($type, ['recruit', 'company'])) {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
            exit;
        }

        $strRecruit = $this->request->getGet('recruit');
        $strPractice = $this->request->getGet('practice');

        $strType = $type == 'recruit' ? 'R' : 'C';

        $this->commonData();
        $session = session();
        $iMemIdx = $session->get('idx');
        $memberRecruitScrapModel = new MemberRecruitScrapModel();
        $tmpCount = $memberRecruitScrapModel
            ->select('scr_type')
            ->select('COUNT(scr_type) AS cnt', '', false)
            ->where(['mem_idx' => $iMemIdx, 'delyn' => 'N'])
            ->groupBy("scr_type")->orderBy('NULL', '', false)->findAll();
        $aRowCount = [
            'C' => 0,
            'R' => 0,
        ];
        foreach ($tmpCount as $key => $val) {
            if ($val['scr_type'] == 'R') {
                $aRowCount['R'] = $val['cnt'];
            } else if ($val['scr_type'] == 'C') {
                $aRowCount['C'] = $val['cnt'];
            }
        }

        if ($strType == 'R') {
            //채용공고
            $memberRecruitScrapModel
                ->select([
                    'iv_member_recruit_scrap.idx as scrapIdx',
                    'iv_recruit.idx as recIdx', 'iv_recruit.rec_title as recTitle', 'iv_recruit.rec_start_date as recStartDate', 'iv_recruit.rec_end_date as recEndDate',
                    'iv_recruit.rec_career as recCareer', 'iv_recruit.rec_apply as recApply',
                    'iv_korea_area.area_depth_text_1 as areaDepth1', 'iv_korea_area.area_depth_text_2 as areaDepth2',
                    'iv_company.com_name as comName', 'iv_file.file_save_name as fileComLogo'
                ])
                ->join('iv_recruit', 'iv_recruit.idx = iv_member_recruit_scrap.rec_idx', 'left')
                ->join('iv_company', 'iv_company.idx = iv_recruit.com_idx', 'left')
                ->join('iv_korea_area', 'iv_korea_area.idx = iv_recruit.kor_area_idx', 'left')
                ->orderBy('iv_recruit.idx', 'desc');
        } else if ($strType == 'C') {
            //기업
            $memberRecruitScrapModel->distinct()
                ->select([
                    'iv_member_recruit_scrap.rec_idx as scrapIdx',
                    'iv_company.idx as comIdx', 'iv_company.com_name as comName', 'iv_company.com_industry as comIndustry', 'iv_company.com_address as comAddress',
                    'iv_company.com_practice_interview as comPractice',
                    'iv_file.file_save_name as fileComLogo'
                ])
                ->join('iv_company', 'iv_company.idx = iv_member_recruit_scrap.com_idx', 'left')
                ->join('iv_recruit', 'iv_recruit.com_idx = iv_company.idx', 'left');
        }
        //공통
        $memberRecruitScrapModel
            ->join('iv_file', 'iv_file.idx = iv_company.file_idx_logo', 'left')
            ->where(['iv_member_recruit_scrap.mem_idx' => $iMemIdx, 'iv_member_recruit_scrap.scr_type' => $strType, 'iv_member_recruit_scrap.delyn' => 'N']);

        if ($strType == 'C') {
            $this->aData['data']['get']['recruit'] = '';
            $this->aData['data']['get']['practice'] = '';
            if ($strRecruit == 'Y') {
                //기업, 지금채용중
                $memberRecruitScrapModel
                    ->where(['iv_recruit.rec_end_date >=' => Date('Ymd')]);
                $this->aData['data']['get']['recruit'] = 'Y';
            } else if ($strPractice == 'Y') {
                //기업, 모의인터뷰 응시 가능
                $memberRecruitScrapModel
                    ->where(['iv_company.com_practice_interview' => 'Y']);
                $this->aData['data']['get']['practice'] = 'Y';
            }
        }
        $this->aData['data']['list'] = $memberRecruitScrapModel->paginate(10, 'scrap');
        $this->aData['data']['pager'] = $memberRecruitScrapModel->pager;
        $this->aData['data']['count'] = $aRowCount;
        //타입정보
        $this->aData['data']['aData'] = [
            'type' => $type,
        ];


        if ($strType == 'R') {
            foreach ($this->aData['data']['list'] as $key => $val) {
                if ($val['recCareer'] == 'N') {
                    $this->aData['data']['list'][$key]['recCareer'] = '신입';
                } else if ($val['recCareer'] == 'C') {
                    $this->aData['data']['list'][$key]['recCareer'] = '경력';
                } else {
                    $this->aData['data']['list'][$key]['recCareer'] = '경력무관';
                }

                if ($val['recEndDate']) {
                    $strNow = Time::parse('now', 'Asia/Seoul')->toLocalizedString('yyyyMMdd');
                    $timeCurrent = Time::parse($strNow, 'Asia/Seoul');
                    $timeEndDate = Time::parse($val['recEndDate'], 'Asia/Seoul');
                    $strDiffDate = $timeCurrent->difference($timeEndDate)->getDays();
                    if ($strDiffDate > 0) {
                        $strDiffDate = 'D-' . $strDiffDate . '일';
                    } else {
                        $strDiffDate = 'D-' . $strDiffDate . '일 지남';
                    }
                    $this->aData['data']['list'][$key]['recEndDate'] = $strDiffDate;
                }
            }
        }

        // view
        $this->header();
        echo view('www/my/scrap/list', $this->aData);
        $this->footer();
    }
}
