<?php

namespace App\Controllers\Interview\My\Scrap;

use App\Controllers\Interview\WwwController;

use Config\Database;
use Config\Services;
use App\Models\ApplierModel;
use App\Models\ReportResultModel;
use App\Models\SearchModel;
use App\Models\JobCategoryModel;
use App\Models\CompnaySuggestModel;
use App\Models\ConfigCompnaySuggestModel;

class SuggestController extends WwwController
{
    private $encrypter;

    public function index()
    {
        $this->list();
    }

    public function list()
    {
        $this->commonData();
        if (!$this->aData['data']['session']['id']) {
            return redirect($this->globalvar->getlogin());
        }

        // data init
        $this->encrypter = Services::encrypter();
        $typeChk = ['A' => '인터뷰', 'I' => '대면 면접', 'O' => '이직 및 포지션'];
        $iMemberIdx = $this->aData['data']['session']['idx'];
        $strSort = $this->request->getGet('sort') ?? 'new';
        if (!in_array($strSort, ['new', 'old'])) {
            alert_back($this->globalvar->aMsg['error2']);
            exit;
        }

        $compnaySuggestModel = new compnaySuggestModel();
        $compnaySuggestModel->getSuggestList($iMemberIdx);

        if ($strSort === 'new') {
            $compnaySuggestModel->orderBy('sug_reg_date', 'DESC'); // 최신순
        } else if ($strSort === 'old') {
            $compnaySuggestModel->orderBy('sug_end_date', 'ASC'); // 기한임박순
        }

        $aSuggest = $compnaySuggestModel->paginate(3, 'suggest');
        

        foreach ($aSuggest as $key => $val) {
            if ($aSuggest[$key]['rec_end_date']) {
                $aSuggest[$key]['rec_end_date'] = $val['rec_end_date'] < date('Ymd') ? '기간만료' : $val['rec_end_date'];
            } else {
                $aSuggest[$key]['rec_end_date'] = $val['sug_end_date'] < date('Ymd') ? '기간만료' : $val['sug_end_date'];
            }
            $aSuggest[$key]['sug_type'] = $typeChk[$val['sug_type']];
            $aSuggest[$key]['sug_idx'] = base64url_encode($this->encrypter->encrypt(json_encode($val['sug_idx'])));
        }

        $this->aData['data']['total'] = $compnaySuggestModel->pager->getTotal('suggest');
        $this->aData['data']['pager'] = $compnaySuggestModel->pager;
        $this->aData['data']['suggest'] = $aSuggest;
        $this->aData['data']['sort'] = $strSort;

        $this->header();
        echo view("www/my/Scrap/suggest/list", $this->aData);
    }

    public function detail(string $strSuggestIdx)
    {
        $this->commonData();
        if (!$this->aData['data']['session']['id']) {
            return redirect($this->globalvar->getlogin());
        }

        // data init
        $this->encrypter = Services::encrypter();
        $iSuggestIdx = $this->encrypter->decrypt(base64url_decode($strSuggestIdx)); //복호화
        $iSuggestIdx = str_replace('"', '', $iSuggestIdx);

        $typeChk = ['A' => '인터뷰', 'I' => '대면 면접', 'O' => '이직 및 포지션'];
        $iMemberIdx = $this->aData['data']['session']['idx'];

        $compnaySuggestModel = new compnaySuggestModel();
        $compnaySuggestModel->getSuggestDetail($iMemberIdx, $iSuggestIdx);
        $aSuggest = $compnaySuggestModel->first();

        $aSuggest['sug_type'] = $typeChk[$aSuggest['sug_type']];

        if (!$aSuggest['rec_end_date']) {
            $aSuggest['rec_end_date'] = $aSuggest['sug_end_date'];
        }
        if ($aSuggest['sug_manager'] === 'C') {
            $aSuggest['sug_manager_name'] = '비공개';
            $aSuggest['sug_manager_tel'] = '';
        }

        $this->aData['data']['suggest'] = $aSuggest;
        $this->aData['data']['suggestIdx'] = $strSuggestIdx;

        $this->header();
        echo view("www/my/Scrap/suggest/detail", $this->aData);
    }

    public function suggestAccept(string $strSuggestIdx)
    {
        $this->commonData();
        if (!$this->aData['data']['session']['id']) {
            return redirect($this->globalvar->getlogin());
        }

        // data init
        $this->encrypter = Services::encrypter();
        $iSuggestIdx = $this->encrypter->decrypt(base64url_decode($strSuggestIdx)); //복호화
        $iSuggestIdx = str_replace('"', '', $iSuggestIdx);

        $strMassage = $this->request->getPost('massage') ?? '';
        $iMemberIdx = $this->aData['data']['session']['idx'];

        $configCompnaySuggestModel = new ConfigCompnaySuggestModel();

        $chkSuggest = $configCompnaySuggestModel->chkSuggest($iSuggestIdx, $iMemberIdx);
        $chkInterview = $configCompnaySuggestModel->chkSuggestType($iSuggestIdx, $iMemberIdx);

        if ($chkSuggest && (in_array($chkInterview, ['I', 'O']))) {
            $this->masterDB->table('config_company_suggest')
                ->set(
                    [
                        'cfg_sug_mem_app_stat' => 'Y',
                        'cfg_sug_mem_app_massage' => $strMassage,
                        'cfg_sug_mem_app_type' => ''
                    ]
                )
                ->where(
                    [
                        'sug_idx' => $iSuggestIdx,
                        'delyn' => 'N'
                    ]
                )
                ->update();
        } else {
            alert_back($this->globalvar->aMsg['error12']);
            exit;
        }

        $this->header();
        alert_back($this->globalvar->aMsg['success1']);
        exit;
    }

    public function suggestAcceptInterview(string $strSuggestIdx)
    {
        $this->commonData();
        if (!$this->aData['data']['session']['id']) {
            return redirect($this->globalvar->getlogin());
        }

        // data init
        $this->encrypter = Services::encrypter();
        $iSuggestIdx = $this->encrypter->decrypt(base64url_decode($strSuggestIdx)); //복호화
        $iSuggestIdx = str_replace('"', '', $iSuggestIdx);

        $iMemberIdx = $this->aData['data']['session']['idx'];

        $configCompnaySuggestModel = new ConfigCompnaySuggestModel();

        $chkSuggest = $configCompnaySuggestModel->chkSuggest($iSuggestIdx, $iMemberIdx);
        $chkInterview = $configCompnaySuggestModel->chkSuggestType($iSuggestIdx, $iMemberIdx);

        if ($chkSuggest && $chkInterview === 'A') {
            $this->masterDB->table('config_company_suggest')
                ->set(
                    [
                        'cfg_sug_mem_app_stat' => 'Y',
                        'cfg_sug_mem_app_type' => null
                    ]
                )
                ->where(
                    [
                        'sug_idx' => $iSuggestIdx,
                        'delyn' => 'N'
                    ]
                )
                ->update();

            return redirect($this->globalvar->getlogin());
        } else {
            alert_back($this->globalvar->aMsg['error12']);
            exit;
        }

        $this->header();
        alert_back('인터뷰로 가야함');
        exit;
        //인터뷰 링크
    }

    public function suggestRefuse(string $strSuggestIdx)
    {
        $this->commonData();
        if (!$this->aData['data']['session']['id']) {
            return redirect($this->globalvar->getlogin());
        }

        // data init
        $this->encrypter = Services::encrypter();
        $iSuggestIdx = $this->encrypter->decrypt(base64url_decode($strSuggestIdx)); //복호화
        $iSuggestIdx = str_replace('"', '', $iSuggestIdx);

        $strSuggestType = $this->request->getPost('refuseType');
        $strMassage = $this->request->getPost('massage') ?? '';
        $iMemberIdx = $this->aData['data']['session']['idx'];

        if (!in_array($strSuggestType, ['A', 'B', 'C', 'D', 'Z'])) {
            alert_back($this->globalvar->aMsg['error1']);
            exit;
        }

        $configCompnaySuggestModel = new ConfigCompnaySuggestModel();

        if ($configCompnaySuggestModel->chkSuggest($iSuggestIdx, $iMemberIdx)) {
            $this->masterDB->table('config_company_suggest')
                ->set(
                    [
                        'cfg_sug_mem_app_stat' => 'N',
                        'cfg_sug_mem_app_massage' => $strMassage,
                        'cfg_sug_mem_app_type' => $strSuggestType
                    ]
                )
                ->where(
                    [
                        'sug_idx' => $iSuggestIdx,
                        'delyn' => 'N'
                    ]
                )
                ->update();
        } else {
            alert_back($this->globalvar->aMsg['error12']);
            exit;
        }

        $this->header();
        alert_back($this->globalvar->aMsg['success1']);
        exit;
    }
}
