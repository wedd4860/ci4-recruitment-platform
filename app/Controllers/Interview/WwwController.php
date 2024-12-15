<?php

namespace App\Controllers\Interview;

use App\Controllers\BaseController;
use App\Models\ConfigModel;
use Config\Database;

class WwwController extends BaseController
{
    public $masterDB;
    protected $aData = [];
    public function __construct()
    {
        $this->masterDB = Database::connect('master');
    }

    public function commonData()
    {
        // data init
        $aCommon = [];
        $aCommon['data'] = $this->viewData;
        $aCommon['data']['page'] = $this->request->getUri()->getPath();

        $session = session();
        $aCommon['data']['session'] = [
            'idx' => $session->get('idx') ?? '',
            'id' => $session->get('mem_id') ?? '',
            'name' => $session->get('mem_name') ?? ''
        ];
        $this->aData = $aCommon;
    }

    public function header()
    {
        if ($this->aData['data']['page'] == '/') {
            $this->aData['data']['class']['body'] = 'main';
        } else if ($this->aData['data']['page'] == '/splash') {
            //미구현
            $this->aData['data']['class']['body'] = 'splash';
        } else {
            $this->aData['data']['class']['body'] = 'sub';
        }
        echo view('www/templates/header', $this->aData);
    }

    public function footer()
    {
        $aConfig = [
            'private' => cache('config.private'),
            'agreement' => cache('config.agreement'),
        ];

        if (!$aConfig['private'] || !$aConfig['agreement']) {
            $strAgreement = '';
            $strPrivate = '';
            $configModel = new ConfigModel();
            $aConfig = $configModel->getConfigType('total');
            foreach ($aConfig as $val) {
                if ($val['cfg_type'] == 'A') {
                    $strAgreement = $val['cfg_content'];
                } else if ($val['cfg_type'] == 'P') {
                    $strPrivate = $val['cfg_content'];
                }
            }
            cache()->save('config.private', $strAgreement, 86400);
            cache()->save('config.agreement', $strPrivate, 86400);
            $aConfig['private'] = $strAgreement;
            $aConfig['agreement'] = $strPrivate;
        }
        $this->aData['data']['config']['agreement'] = $aConfig['agreement'];
        $this->aData['data']['config']['private'] = $aConfig['private'];

        echo view('www/templates/wrapBottom', $this->aData);
        if (!$this->aData['data']['session']['idx']) {
            $this->aData['data']['sns']['url']['kakao'] = 'https://kauth.kakao.com/oauth/authorize?client_id=' . $this->globalvar->aSnsInfo['kakao']['clientId'] . '&redirect_uri=' . urlencode($this->globalvar->aSnsInfo['kakao']['redirectUrl']) . '&response_type=code';
            $this->aData['data']['sns']['url']['apple'] = 'https://appleid.apple.com/auth/authorize?response_type=code&response_mode=form_post&client_id=' . $this->globalvar->aSnsInfo['apple']['clientId'] . '&redirect_uri=' . urlencode($this->globalvar->aSnsInfo['apple']['redirectUrl']) . '&scope=name%20email';
            $this->aData['data']['sns']['url']['naver'] = 'https://nid.naver.com/oauth2.0/authorize?client_id=' . $this->globalvar->aSnsInfo['naver']['clientId'] . '&response_type=code&redirect_uri=' . urlencode($this->globalvar->aSnsInfo['naver']['redirectUrl']) . '&state=RAMDOM_STATE';
            $this->aData['data']['sns']['url']['google'] = 'https://accounts.google.com/o/oauth2/v2/auth?response_type=code&client_id=' . $this->globalvar->aSnsInfo['google']['clientId'] . '&scope=profile%20email&redirect_uri=' . urlencode($this->globalvar->aSnsInfo['google']['redirectUrl']) . '&state=highbuff&nonce=1';
            echo view('www/modal/login', $this->aData);
        }
        echo view('www/modal/agreement', $this->aData);
        echo view('www/modal/privacy', $this->aData);
        echo view('www/templates/quick', $this->aData);
        //푸터 하단에 스크립트는 footer 작성
        echo view('www/templates/footer', $this->aData);
    }

    public function __destruct()
    {
        session_write_close();
        $this->masterDB->close();
    }
}
