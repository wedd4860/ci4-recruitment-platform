<?php


namespace App\Controllers\Interview\Company\Practice;

use App\Controllers\Interview\WwwController;
use App\Models\CompanyModel;


class PracticeListController extends WwwController
{
    public function index()
    {
        $this->list();
    }

    public function list()
    {
        $this->commonData();

        //[1]기업형태종류가져오기
        $aConfig = $this->globalvar->getConfig();
        $aCompanyForm = $aConfig['company']['company_form'];
        $this->aData['data']['comForm'] = $aCompanyForm;

        //[2]검색어가져오기
        $strSearchText = $this->request->getGet('searchText');
        
        //[3]검색기업형태가져오기
        $aSearchComForm = $this->request->getGet('searchCompanyForm');

        $tmpSeachComForm = [];
        if ($aSearchComForm) {
            foreach ($aSearchComForm as $val) {
                $tmpSeachComForm[] = $aCompanyForm[$val];
            }
        }

        //[4]모의면접기업정보목록 가져오기 
        $companyModel = new CompanyModel();
        $companyModel->getPracticeList($strSearchText, $tmpSeachComForm);

        $this->aData['data']['list'] = $companyModel->paginate(5, 'practiceList');
        $this->aData['data']['pager'] = $companyModel->pager;
        $this->aData['data']['search'] = ['text' => $strSearchText];
        $this->aData['data']['check'] = $aSearchComForm;

        //[4]회원여부가져오기
        $iMemIdx = dot_array_search('data.session.idx', $this->aData);
        if ($iMemIdx) {
            $this->aData['data']['member'] = true;
        } else {
            $this->aData['data']['member'] = false;
        }

        $this->header();
        echo view("www/company/practice/list", $this->aData);
    }
}
