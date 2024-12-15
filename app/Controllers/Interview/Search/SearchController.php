<?php

namespace App\Controllers\Interview\Search;

use App\Controllers\Interview\WwwController;

use App\Models\SearchModel;
use App\Models\TagModel;
use App\Models\KoreaAreaModel;
use App\Models\JobCategoryModel;

class SearchController extends WwwController
{
    public function index()
    {
        $this->search();
    }

    public function search()
    {
        // data init
        $this->commonData();
        $cookie = get_cookie('searchKeyword');
        if ($cookie) {
            $cookie = (json_decode($cookie));
        }
        $this->aData['data']['keyword'] = $cookie;

        $this->header();
        echo view("www/search/index", $this->aData);
    }

    public function deleteKeyword()
    {
        $keyword = $this->request->getGet('keyword');
        $cookie = get_cookie('searchKeyword');
        if ($cookie) {
            $aCookie = json_decode($cookie);

            if (count($aCookie) == 1) {
                setcookie('searchKeyword', '', time() - 1);
                return json_encode(['status' => 201]);
            }

            foreach ($aCookie as $val) {
                if ($val == $keyword) {
                    continue;
                }
                $aList[] = $val;
            }
            $aList = json_encode($aList);
            setcookie('searchKeyword', $aList);
            return json_encode(['status' => 200]);
        } else {
            alert_back($this->globalvar->aMsg['error1']);
            exit;
        }
        alert_back($this->globalvar->aMsg['error1']);
        exit;
    }

    public function searchAction()
    {
        // data init
        $strType = $this->request->getGet('type') ?? 'recruit';
        $keyword = $this->request->getGet('keyword');
        $pureKeyword = $this->request->getGet('keyword');
        $deepSearchChk = $this->request->getGet('deepSearchChk');
        $this->commonData();

        if (!$keyword) {
            if ($strType === 'deepSearch') {
                if ($deepSearchChk) { // deepSearchChk가 있으면 검색 안되도록
                    $keyword = '$!@^#%#$#$%@^%^$';
                }
            } else {
                // alert_back($this->globalvar->aMsg['error10']);
                // exit;
            }
        } else {
            $keyword = trim($keyword, '/[0-9\@\.\;\" "]+/');
            $cookie = get_cookie('searchKeyword');
            if ($cookie) {
                $aCookie = json_decode($cookie);
                $aTempList[] = $keyword;

                foreach ($aCookie as $key => $val) {
                    if ($val === $keyword) {
                        continue;
                    }
                    $aTempList[] = $val;
                }

                $aTempList = array_unique($aTempList);
                if (count($aTempList) > 10) {
                    while (true) {
                        if (count($aTempList) == 10) {
                            break;
                        }
                        array_pop($aTempList);
                    }
                }

                $aTempList = json_encode($aTempList);
                setcookie('searchKeyword', $aTempList, time() + 9999999999);
            } else {
                $list = json_encode([0 => $keyword]);
                setcookie('searchKeyword', $list, time() + 9999999999);
            }
        }

        $JobCategoryModel = new JobCategoryModel('iv_job_category');
        $JobCategory = $JobCategoryModel->getJobCategory();
        foreach ($JobCategory as $val) {
            if ($val['job_depth_2'] == null) {
                $this->aData['data']['jobsCategory'][$val['job_depth_1']][] = ['jobName' => $val['job_depth_text'], 'idx' => $val['idx']];
            } else {
                $this->aData['data']['jobsCategory'][$val['job_depth_1']][] = ['jobName' => $val['job_depth_text'], 'idx' => $val['idx']];
            }
        }

        $koreaAreaModel = new KoreaAreaModel('iv_korea_area');
        $aKoreaArea = $koreaAreaModel->getKoreaArea();

        foreach ($aKoreaArea as $val) {
            if ($val['area_depth_2'] == null) {
                $this->aData['data']['areaCategory'][$val['area_depth_1']][] = ['aArea' => $val['area_depth_text_1'], 'idx' => $val['idx']];
            } else {
                $this->aData['data']['areaCategory'][$val['area_depth_1']][] = ['aArea' => $val['area_depth_text_2'], 'idx' => $val['idx']];
            }
        }
        $tagModel = new TagModel('config_company_tag');
        $this->aData['data']['tagCategory'] = $tagModel->getTag();

        if ($strType === 'recruit') {  // 통합 검색
            $sortChk = [
                '1' => 'rec_title',
                '2' => 'rec_start_date',
                '3' => 'rec_end_date',
                '4' => 'rec_pay_unit',
                '5' => 'kor_area_idx',
            ];
            $sort = $this->request->getGet('sort') ?? '1';
            $strSortValue = $sortChk[$sort] ?? 'rec_title'; // sort에 이상한 값이 들어오면 rec_title로 고정

            $searchModel = new SearchModel('iv_recruit');
            $searchModel->getRecruit($keyword, $strSortValue);
        } else if ($strType === 'company') {  // 기업 검색
            $sortChk = [
                '6' => 'rec_title',
                '7' => 'rec_start_date',
                '8' => 'rec_end_date',
            ];
            $sort = $this->request->getGet('sort') ?? '6';
            $strSortValue = $sortChk[$sort] ?? 'rec_title'; // sort에 이상한 값이 들어오면 rec_title로 고정

            $searchModel = new SearchModel('iv_company');
            $searchModel->getCompany($keyword);
        } else if ($strType === 'deepSearch') {  // 상세 검색
            $sortChk = [
                '1' => 'rec_title',
                '2' => 'rec_start_date',
                '3' => 'rec_end_date',
                '4' => 'rec_pay_unit',
                '5' => 'kor_area_idx',
            ];
            $sort = $this->request->getGet('sort') ?? '1';
            $strSortValue = $sortChk[$sort] ?? 'rec_title'; // sort에 이상한 값이 들어오면 rec_title로 고정

            $deepSearch = [];
            $deepSearch['aWorkType'] = $this->request->getGet('aWorkType') ?? ''; // like
            $deepSearch['aArea'] = $this->request->getGet('aArea') ?? [];
            $deepSearch['aJobs'] = $this->request->getGet('aJobs') ?? [];
            $deepSearch['strCareer'] = $this->request->getGet('strCareer') ?? '';
            $deepSearch['iCareerMonth'] = $this->request->getGet('iCareerMonth') ?? 0;
            $deepSearch['strApply'] = $this->request->getGet('strApply') ?? '';
            $deepSearch['iApply'] = $this->request->getGet('질문개이하') ?? 0;
            $deepSearch['aTag'] = $this->request->getGet('aTag') ?? [];
            $deepSearch['strPayType'] = $this->request->getGet('strPayType') ?? '';
            $deepSearch['iPayUnit'] = $this->request->getGet('iPayUnit') ?? 0;
            $deepSearch['strPeriod'] = $this->request->getGet('strPeriod') ?? '';
            $deepSearch['aGender'] = $this->request->getGet('aGender') ?? [];
            $deepSearch['aWorkDay'] = $this->request->getGet('aWorkDay') ?? []; // like
            $deepSearch['aEducation'] = $this->request->getGet('aEducation') ?? [];
            if (count($deepSearch['aArea']) > 10 || count($deepSearch['aJobs']) > 10) {
                alert_back($this->globalvar->aMsg['error1']);
                exit;
            }

            $deepSearch['iCareerMonth'] = intval($deepSearch['iCareerMonth']);
            $deepSearch['iApply'] = intval($deepSearch['iApply']);
            $deepSearch['iPayUnit'] = intval($deepSearch['iPayUnit']) * 10000;

            $chk = [ // 받은 값 유효성 체크
                'fullTime' => 1,
                'halfTime' => 2,
                'intern' => 3,
                'partTime' => 4,
                'foreign' => 5,
                'new' => 'N',
                'old' => 'C',
                'my' => 'M',
                'you' => 'C',
                'time' => 'T',
                'month' => 'M',
                'year' => 'Y',
                'after' => 'N',
                '1month' => 1,
                '3month' => 3,
                '6month' => 6,
                '12month' => 12,
                'm' => 'M',
                'w' => 'W',
                'n' => 'A',
                'week' => 8,
                'weekEnd' => 9,
                'mon' => 1,
                'tues' => 2,
                'wednes' => 3,
                'thurs' => 4,
                'fri' => 5,
                'satur' => 6,
                'sun' => 7,
                'none' => 0,
                'middle' => 1,
                'high' => 2,
                'college' => 3,
                'university' => 4,
                'master' => 5,
                'doctor' => 6,
            ];

            foreach ($this->aData['data']['tagCategory'] as $tagRow) { //ex} "T자율복장" => (tag의IDX)
                $strTagValue = $tagRow['tag_txt'];
                $chk["T{$strTagValue}"] = $tagRow['idx'];
            }
            foreach ($this->aData['data']['areaCategory'] as $cityRow) { //ex} "A(서울의IDX)" => (서울의IDX)
                foreach ($cityRow as $v) {
                    $strCityValue = $v['idx'];
                    $chk["A{$strCityValue}"] = $v['idx'];
                }
            }
            foreach ($this->aData['data']['jobsCategory'] as $key => $jobRow) { //ex} "J(경영사무의 IDX)") => (경영사무의 IDX)
                foreach ($jobRow as $k => $v) {
                    $strJobValue = $v['idx'];
                    $chk["J{$strJobValue}"] = $v['idx'];
                }
            }
            $filter = [ // get값의 KEY => 검색할 컬럼
                'aWorkType' => 'rec_work_type',
                'aArea' => 'tempIdx1',
                'aJobs' => 'tempIdx2',
                'strCareer' => 'rec_career',
                'iCareerMonth' => 'rec_career_month',
                'strApply' => 'rec_apply ',
                'aTag' => 'tempIdx3',
                'strPayType' => 'rec_pay_type',
                'iPayUnit' => 'rec_pay_unit >=',
                'strPeriod' => 'rec_period ',
                'aGender' => 'rec_gender ',
                'aWorkDay' => 'rec_work_day ',
                'aEducation' => 'rec_education',
                'iv_recruit.delyn' => 'iv_recruit.delyn',
            ];
            $iTemp = 0;

            foreach ($deepSearch as $key => $value) {
                if ($value) {
                    if (is_array($deepSearch[$key])) {
                        foreach ($deepSearch[$key] as $k => $v) {
                            if ($chk[$k] ?? false) {
                                $aDeepSearchList[$filter[$key]][] = $chk[$k];
                                $this->aData['data'][$key][$k] = 'on';
                            } else {
                                alert_back($this->globalvar->aMsg['error1']);
                                exit;
                            }
                        }
                    } elseif (is_int($deepSearch[$key])) {
                        if ($key === 'iCareerMonth') {
                            if ($deepSearch['strCareer'] === 'old') {
                            } else {
                                alert_back($this->globalvar->aMsg['error1']);
                                exit;
                            }
                        } else if ($key === 'iPayUnit') {
                            if ($deepSearch['strPayType'] === 'after') {
                                alert_back($this->globalvar->aMsg['error1']);
                                exit;
                            }
                        }
                        $aDeepSearchList[$filter[$key]] = $value;
                        $this->aData['data'][$key] = $value;
                    } else {
                        if ($chk[$value] ?? false) {
                            $aDeepSearchList[$filter[$key]] = $chk[$value];
                            $this->aData['data'][$key] = $value;
                        } else {
                            alert_back($this->globalvar->aMsg['error1']);
                            exit;
                        }
                    }
                    ++$iTemp;
                }
            }
            $iTemp === 0 ? $aDeepSearchList['iv_recruit.delyn'] = 'N' : '';

            if (isset($aDeepSearchList['tempIdx1'])) {
                $companyTag = new KoreaAreaModel('iv_member_recruit_kor');
                $aDeepSearchList['iv_recruit.idx1'] = array_unique($companyTag->getCompanyIdx($aDeepSearchList['tempIdx1']));
                unset($aDeepSearchList['tempIdx1']);
            }

            if (isset($aDeepSearchList['tempIdx2'])) {
                $companyTag = new JobCategoryModel('iv_member_recruit_category');
                $aDeepSearchList['iv_recruit.idx2'] = array_unique($companyTag->getCompanyIdx($aDeepSearchList['tempIdx2']));
                unset($aDeepSearchList['tempIdx2']);
            }

            if (isset($aDeepSearchList['tempIdx3'])) {
                $companyTag = new TagModel('iv_company_tag');
                $aDeepSearchList['iv_recruit.com_idx'] = array_unique($companyTag->getCompanyIdx($aDeepSearchList['tempIdx3']));
                unset($aDeepSearchList['tempIdx3']);
            }

            $searchModel = new SearchModel('iv_recruit');

            $searchModel->getRecruitDetail($keyword, $strSortValue, $aDeepSearchList);
        } else {
            alert_back($this->globalvar->aMsg['error1']);
            exit;
        }

        $this->aData['data']['recruitList'] = $searchModel->paginate(3, 'search');
        $this->aData['data']['pageCount'] = $searchModel->pager->getPageCount('search');
        $this->aData['data']['count'][$strType] = $searchModel->pager->getTotal('search');
        $this->aData['data']['pager'] = $searchModel->pager;

        $this->aData['data']['type'] = $strType;
        $this->aData['data']['keyword'] = $pureKeyword;
        $this->aData['data']['deepSearchChk'] = $deepSearchChk;
        $this->aData['data']['sort'] = $sort;

        $this->header();
        echo view("www/search/search", $this->aData);
    }
}
