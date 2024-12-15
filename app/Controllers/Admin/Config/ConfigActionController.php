<?php

namespace App\Controllers\Admin\Config;

use App\Models\ConfigModel;
use App\Controllers\Admin\AdminController;

class ConfigActionController extends AdminController
{
    private $backUrlList = '/prime/config/terms';

    public function index()
    {
        alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
        exit;
    }

    public function configAction()
    {
        //action page
        $iIdx = $this->request->getPost('idx');
        $strPostCase = $this->request->getPost('postCase');
        $strCfgType = $this->request->getPost('cfgType');
        $strCfgUseYN = $this->request->getPost('cfgUseYN');
        $strCfgContent = $this->request->getPost('cfgContent');
        $strBackUrl = $this->request->getPost('backUrl');

        if (!$strCfgContent || $strPostCase != 'config_wrtie' || !in_array($strCfgType, ['T', 'A', 'P']) || !in_array($strCfgUseYN, ['Y', 'N'])) {
            return alert_back('잘못된접근입니다.');
        }

        $this->commonData();
        $readyDB = $this->masterDB->table('iv_config')
            ->set([
                'cfg_type' => $strCfgType,
                'cfg_useYN' => $strCfgUseYN,
                'cfg_content' => $strCfgContent,
            ])
            ->set(['cfg_mod_date' => 'NOW()'], '', false);
        if ($iIdx) {
            $result = $readyDB
                ->where('idx', $iIdx)
                ->update();
        } else {
            $result = $readyDB
                ->set(['cfg_reg_date' => 'NOW()'], '', false)
                ->insert();
        }

        if ($result) {
            alert_url($this->globalvar->aMsg['success1'], $strBackUrl != '' ? $strBackUrl : $this->backUrlList);
            exit;
        } else {
            alert_back($this->globalvar->aMsg['error2']);
            exit;
        }
    }
}
