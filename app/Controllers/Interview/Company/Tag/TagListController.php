<?php

namespace App\Controllers\Interview\Company\Tag;

use App\Controllers\Interview\WwwController;
use App\Models\{
    CompanyModel,
    ConfigCompanyTagModel,
    MemberRecruitScrapModel,
};
use Config\Services;
use CodeIgniter\Cookie\Cookie;
use CodeIgniter\Cookie\CookieStore;
use DateTime;

class TagListController extends WwwController
{
    private $backUrlList = '/';
    public function index()
    {
    }

    public function list()
    {
        $this->commonData();
        $aTagCheck = $this->request->getGet('tagCheck');

        $configCompanyTag = new ConfigCompanyTagModel();
        $aConfigTag = $configCompanyTag->getTagList();

        $companyModel = new CompanyModel();
        if ($aTagCheck) {
            $companyModel->getTagList($aTagCheck);
        } else {
            $companyModel->getTagList();
        }

        $this->aData['data']['list'] = $companyModel->paginate(10, 'tagCompany');
        $this->aData['data']['pager'] = $companyModel->pager;
        $this->aData['data']['tag'] = $aConfigTag;
        $this->aData['data']['get']['tag'] = $aTagCheck;

        // view
        $this->header();
        echo view('www/company/tag/list', $this->aData);
        $this->footer();
    }
}
