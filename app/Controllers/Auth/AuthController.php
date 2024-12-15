<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;

use Config\{
    Database,
    Services,
};
use App\Models\{
    ConfigModel,
    MemberModel,
    InterviewModel,
    InterestModel,
    AuthTelModel,
    JobCategoryModel,
    KoreaAreaModel,
};


use App\Libraries\{
    EncryptLib,
    SortUrlLib,
};

class AuthController extends BaseController
{
    public $masterDB;
    private $backUrlAdmin = '/prime/login';
    private $backUrl = '/login';
    private $aData = [];
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
            'idx' => $session->get('idx'),
            'id' => $session->get('mem_id'),
            'name' => $session->get('mem_name')
        ];

        $this->aData = $aCommon;
    }

    public function logout()
    {
        // 세션설정
        session()->destroy();
        return redirect($this->globalvar->getMain());
    }

    // start : interview 및 biz
    public function index()
    {
        $this->login();
    }

    public function smsSend()
    {
        $authNumber = sprintf('%06d', rand(000000, 999999));
        $memberModel = new MemberModel();
        return json_encode(ajax_csrf());
    }

    public function login()
    {
        // data init
        $this->commonData();
        // 세션설정
        $session = session();
        if ($session->has('mem_id')) {
            return redirect($this->globalvar->getMain());
        }
        echo view("/auth/login", $this->aData);
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

    public function footer($ignore = [])
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
            //로그인 중이 아닐때
            $this->aData['data']['sns']['url']['kakao'] = 'https://kauth.kakao.com/oauth/authorize?client_id=' . $this->globalvar->aSnsInfo['kakao']['clientId'] . '&redirect_uri=' . urlencode($this->globalvar->aSnsInfo['kakao']['redirectUrl']) . '&response_type=code';
            $this->aData['data']['sns']['url']['apple'] = 'https://appleid.apple.com/auth/authorize?response_type=code&response_mode=form_post&client_id=' . $this->globalvar->aSnsInfo['apple']['clientId'] . '&redirect_uri=' . urlencode($this->globalvar->aSnsInfo['apple']['redirectUrl']) . '&scope=name%20email';
            $this->aData['data']['sns']['url']['naver'] = 'https://nid.naver.com/oauth2.0/authorize?client_id=' . $this->globalvar->aSnsInfo['naver']['clientId'] . '&response_type=code&redirect_uri=' . urlencode($this->globalvar->aSnsInfo['naver']['redirectUrl']) . '&state=RAMDOM_STATE';
            $this->aData['data']['sns']['url']['google'] = 'https://accounts.google.com/o/oauth2/v2/auth?response_type=code&client_id=' . $this->globalvar->aSnsInfo['google']['clientId'] . '&scope=profile%20email&redirect_uri=' . urlencode($this->globalvar->aSnsInfo['google']['redirectUrl']) . '&state=highbuff&nonce=1';
            echo view('www/modal/login', $this->aData);
        }
        echo view('www/modal/agreement', $this->aData);
        echo view('www/modal/privacy', $this->aData);
        if (!in_array("quick", $ignore)) {
            echo view('www/templates/quick', $this->aData);
        }
        //푸터 하단에 스크립트는 footer 작성
        echo view('www/templates/footer', $this->aData);
    }

    public function join()
    {
        // data init
        $this->commonData();
        $this->header();
        echo view("/auth/join", $this->aData);
        $this->footer();
    }

    public function snsJoin()
    {
        // data init
        $this->commonData();
        $strCacheKey = $this->request->getGet('cacheKey');
        $encrypter = Services::encrypter();
        $cacheKey = $encrypter->decrypt(base64url_decode($strCacheKey));

        if (!$aSnsCache = cache($cacheKey)) {
            return redirect($this->globalvar->getLogin());
        }

        $strSnsProvider = substr($aSnsCache['snsType'], 0, 1);
        if ($strSnsProvider) {
            $strSnsProvider = strtoupper($strSnsProvider);
        } else {
            $strSnsProvider = 'E';
        }
        $this->aData['data']['sns']['cache']['id'] = $aSnsCache['snsType'] . '_' . $aSnsCache['mem_object_enc'];
        $this->aData['data']['sns']['cache']['snsType'] = $aSnsCache['snsType'];
        $this->aData['data']['sns']['cache']['key'] = $aSnsCache['mem_key'];
        $this->aData['data']['sns']['cache']['enc'] = $aSnsCache['mem_object_enc'];
        $this->aData['data']['sns']['cache']['provider'] = $strSnsProvider;
        $this->aData['data']['sns']['cache']['email'] = $aSnsCache['mem_email'];

        $this->header();
        echo view("/auth/sns/join", $this->aData);
        $this->footer();
    }

    public function interest()
    {
        // data init
        $this->commonData();
        if (!$aCacheJobcategory = cache('jobcategory.each')) {
            $aJobcategory = [];
            $jobCategoryModel = new JobCategoryModel();
            $aJobcategory = $jobCategoryModel->getJobCategory('interest');
            cache()->save('jobcategory.each', $aJobcategory, 86400);
            $aCacheJobcategory = $aJobcategory;
        }

        if (!$aCacheKoreaarea = cache('koreaarea.each')) {
            $aKoreaarea = [];
            $koreaAreaModel = new KoreaAreaModel();
            $aKoreaarea = $koreaAreaModel->getKoreaArea('interest');
            cache()->save('koreaarea.each', $aKoreaarea, 86400);
            $aCache['koreaarea'] = $aKoreaarea;
            $aCacheKoreaarea = $aKoreaarea;
        }

        $this->aData['data']['category'] = $aCacheJobcategory;
        $this->aData['data']['area'] = $aCacheKoreaarea;

        $this->header();
        echo view("/auth/interest", $this->aData);
        $this->footer(['quick']);
    }

    public function snsLoginCheck($snsType, $snsEncrypt)
    {
        $strCacheKey = "sns.{$snsType}.{$snsEncrypt}";
        if (!$aSnsCache = cache($strCacheKey)) {
            return redirect($this->globalvar->getLogin());
        }

        $strId = $aSnsCache['snsType'] . '_' . $aSnsCache['mem_object_enc'];
        $strPw = $aSnsCache['mem_object_enc'];
        $aParam = [
            'sns' => $snsType,
            'id' => $strId,
            'password' => $strPw
        ];
        $encryptLib = new EncryptLib();
        $encrypter = Services::encrypter();
        $memberModel = new MemberModel();
        $aRow = $memberModel->getMember($strId);

        $strGetResultPw = dot_array_search('mem_password', $aRow);

        if ($encryptLib->checkPassword($strPw, $strGetResultPw)) {
            // 로그인 진행
            $this->loginAction($aParam);
        } else {
            // 회원가입페이지 호출
            $this->commonData();
            $this->aData['data']['type'] = 'join';
            $this->aData['data']['cacheKey'] = base64url_encode($encrypter->encrypt($strCacheKey));
            echo view("/auth/sns/toJoin", $this->aData);
        }
    }

    public function loginAction($aParam = null)
    {
        $isSns = false;
        if ($aParam) {
            $isSns = true;
        }
        //action page
        if ($isSns) {
            $strId = $aParam['id'];
            $strPw = $aParam['password'];
        } else {
            $strId = $this->request->getPost('id');
            $strPw = $this->request->getPost('password');
        }
        //init
        $aContinueColumn = ['mem_password', 'mem_token', 'mem_join_path', 'mem_reg_date', 'mem_mod_date', 'mem_del_date', 'delyn'];
        $aRow = [];
        if (!$strPw || !$strId) {
            return redirect($this->globalvar->getLogin());
        }

        $aSubDate = [
            'allowedFields' => ['log_reg_date', '', ''], // allowedFields 에 들어갈 date 정보
            'createdField' => 'log_reg_date',
            'updatedField' => '',
            'deletedField' => '',
            'jsonField' => ['', ''],
            'useSoftDeletes' => true,
        ];

        $encryptLib = new EncryptLib();
        $memberModel = new MemberModel();
        $interviewModel = new InterviewModel('log_member_login', $aSubDate);
        $aRow = $memberModel->getMember($strId);

        $strGetResultPw = dot_array_search('mem_password', $aRow);
        if ($encryptLib->checkPassword($strPw, $strGetResultPw)) {
            //세션설정
            $session = session();
            $tmpArrData = [];
            foreach ($aRow as $key => $val) {
                if (in_array($key, $aContinueColumn)) {
                    continue;
                }
                $tmpArrData[$key] = $val;
            }
            $session->set($tmpArrData);
            //세션설정 끝

            //트랜잭션 start
            $this->masterDB->transBegin();

            $this->masterDB->table('iv_member')
                ->set('mem_visit_count', 'mem_visit_count + 1', false) //log update -> member table
                ->set('mem_visit_date', 'now()', false)
                ->where('mem_id', $strId)
                ->update();


            $this->masterDB->table('log_member_login')
                ->set(['mem_idx' => $session->get('idx')])
                ->set('log_reg_date', 'now()', false)
                ->insert(); //log insert -> log table

            // 트랜잭션 end
            if ($this->masterDB->transStatus() === false) {
                $this->masterDB->transRollback();
                session()->destroy();
                return alert_back($this->globalvar->aMsg['error3']);
            } else {
                $this->masterDB->transCommit();
                if ($isSns) {
                    $this->aData['data']['type'] = 'login';
                    echo view("/auth/sns/toJoin", $this->aData);
                } else {
                    return redirect($this->globalvar->getMain());
                }
            }
        } else {
            //1.0 비밀번호 체크
            $aRow = $memberModel->getOldMember($strId, $strPw);
            if ($aRow) {
                //세션설정
                $session = session();
                $tmpArrData = [];
                foreach ($aRow as $key => $val) {
                    if (in_array($key, $aContinueColumn)) {
                        continue;
                    }
                    $tmpArrData[$key] = $val;
                }
                $session->set($tmpArrData);
                //세션설정 끝

                //트랜잭션 start
                $this->masterDB->transBegin();

                $memberModel->set('mem_visit_count', 'mem_visit_count + 1', false) //log update -> member table
                    ->set('mem_visit_date', 'now()', false)
                    ->where('mem_id', $strId)
                    ->update();

                $interviewModel->save(['mem_idx' => $session->get('idx')]); //log insert -> log table

                // 트랜잭션 end
                if ($this->masterDB->transStatus() === false) {
                    $this->masterDB->transRollback();
                    session()->destroy();
                    return alert_back($this->globalvar->aMsg['error3']);
                } else {
                    $this->masterDB->transCommit();
                    if ($isSns) {
                        $this->aData['data']['type'] = 'login';
                        echo view("/auth/sns/toJoin", $this->aData);
                    } else {
                        return redirect($this->globalvar->getMain());
                    }
                }
            }
        }
        alert_back($this->globalvar->aMsg['error4']);
    }

    public function joinAction($type = null)
    {
        $this->backUrl = '/login';
        $isSns = false;
        if ($type) {
            $isSns = true;
        }
        //init
        if ($isSns) {
            $strKey = $this->request->getPost('loginKey');
            $strObjectSha = $this->request->getPost('loginPassword');
            $strProvider = $this->request->getPost('loginProvider');
            $strEmail = $this->request->getPost('loginEmail');
            $strPostCase = $this->request->getPost('postCase');
            $strBackUrl = $this->request->getPost('backUrl') ?? $this->backUrl;
            if ($strPostCase != 'join_write') {
                return alert_url($this->globalvar->aMsg['error1'], $strBackUrl);
            }
        } else {
            $strTel = $this->request->getPost('loginPhone');
        }
        $strId = $this->request->getPost('loginId');
        $strPw = $this->request->getPost('loginPassword');
        $strName = $this->request->getPost('loginName');
        $strPersonal1 = $this->request->getPost('mem_personal_type_1') ?? 'N';
        $strPersonal2 = $this->request->getPost('mem_personal_type_2') ?? 'N';

        $encryptLib = new EncryptLib();
        $strPw = $encryptLib->makePassword($strPw);

        $aSave = [
            'mem_type' =>  'M',
            'mem_id' => $strId,
            'mem_password' => $strPw,
            'mem_name' => $strName,
            'mem_personal_type_1' => $strPersonal1,
            'mem_personal_type_2' => $strPersonal2,
        ];
        if ($isSns) {
            $aSave['mem_sns_key'] = $strKey;
            $aSave['mem_email'] = $strEmail;
            $aSave['mem_sns_object_sha'] = $strObjectSha;
            $aSave['mem_sns_provider'] = $strProvider;
        } else {
            $aSave['mem_tel'] = $strTel;
            $aSave['mem_email'] = $strId;
        }
        //action page
        foreach ($aSave as $key => $val) {
            if (empty($val)) {
                return redirect($this->globalvar->getLogin());
            }
        }

        $memberModel = new MemberModel();
        $aRow = [];
        $aRow = $memberModel->getMember($strId);

        if ($aRow) {
            return alert_back($this->globalvar->aMsg['error5']);
        }

        $insertChk = $this->masterDB->table('iv_member')
            ->set($aSave)
            ->set(['mem_reg_date' => 'NOW()'], '', false)
            ->set(['mem_mod_date' => 'NOW()'], '', false)
            ->insert();

        if ($insertChk) {
            $aContinueColumn = ['mem_password', 'mem_token', 'mem_join_path', 'mem_reg_date', 'mem_mod_date', 'mem_del_date', 'delyn'];
            $aRow = $memberModel->getMember($strId);
            $session = session();
            $tmpArrData = [];
            foreach ($aRow as $key => $val) {
                if (in_array($key, $aContinueColumn)) {
                    continue;
                }
                $tmpArrData[$key] = $val;
            }
            $session->set($tmpArrData);

            return alert_url($this->globalvar->aMsg['success3'], '/interest');
        }
        alert_back($this->globalvar->aMsg['error4']);
    }

    public function interestAction()
    {
        //init
        $session = session();
        $this->backUrl = '/';

        $aRec = $this->request->getPost('rec');
        $aArea = $this->request->getPost('area');
        $iLeftPay = $this->request->getPost('leftPay');
        $iRightPay = $this->request->getPost('rightPay');
        $iTopPay = $this->request->getPost('topPay');
        $strPostCase = $this->request->getPost('postCase');
        $strBackUrl = $this->request->getPost('backUrl');
        $iMemIdx = $session->get('idx');
        $strMemType = $session->get('mem_type');
        $iRecMax = count($aRec);
        $iAreaMax = count($aArea);
        if ($iTopPay == '999') {
            //1억이상 체크
        }
        if (!is_numeric($iLeftPay) || !is_numeric($iRightPay) || $iLeftPay + $iRightPay > 198 || $strPostCase != 'interest_write' || $iRecMax > 4 || $iAreaMax > 4) {
            session()->destroy();
            return alert_url($this->globalvar->aMsg['error1'], $strBackUrl ? $strBackUrl : $this->backUrl);
        }

        if ($iTopPay == '999') {
            $strPay = '1억이상';
        } else if ($iLeftPay > $iRightPay) {
            $strPay = number_format($iRightPay . '000') . '~' . number_format($iLeftPay . '000');
        } else {
            $strPay = number_format($iLeftPay . '000') . '~' . number_format($iRightPay . '000');
        }

        //트랜잭션 start
        $this->masterDB->transBegin();

        // insert iv_member
        $saveData = [
            'mem_pay' => $strPay
        ];

        $this->masterDB->table('iv_member')
            ->set($saveData)
            ->set(['mem_mod_date' => 'NOW()'], '', false)
            ->where('idx', $iMemIdx)
            ->update();

        // insert iv_member_recruit_category
        $aMemRecCate = [];
        foreach ($aRec as $key => $val) {
            $aMemRecCate[] = [
                'mem_idx' => $iMemIdx,
                'job_idx' => $val,
                'mem_rec_type' => $strMemType,
            ];
        }
        $this->masterDB->table('iv_member_recruit_category')
            ->insertBatch($aMemRecCate);

        // insert iv_member_recruit_kor
        $aMemRecKor = [];
        foreach ($aArea as $key => $val) {
            $aMemRecKor[] = [
                'mem_idx' => $iMemIdx,
                'kor_area_idx' => $val,
                'mem_rec_type' => $strMemType,
            ];
        }

        $this->masterDB->table('iv_member_recruit_kor')
            ->insertBatch($aMemRecKor);

        // 트랜잭션 end
        if ($this->masterDB->transStatus() === false) {
            $this->masterDB->transRollback();
            return alert_url($this->globalvar->aMsg['error2'], $strBackUrl ? $strBackUrl : $this->backUrl);
        } else {
            $this->masterDB->transCommit();
            return alert_url($this->globalvar->aMsg['success3'], $this->backUrl);
        }
    }

    public function find()
    {
        // data init
        $this->commonData();

        // 세션설정
        $session = session();
        if ($session->has('mem_id')) {
            return redirect()->to($this->globalvar->getWwwUrl());
        }

        echo view("/auth/find", $this->aData);
    }



    public function findId(string $type)
    {
        $this->backUrl = '/login/find';
        if (!in_array($type, ['person', 'company'])) {
            return alert_url($this->globalvar->aMsg['error1'], $this->backUrl);
            exit;
        }
        $this->commonData();
        $this->aData['data']['aData'] = [
            'type' => $type
        ];

        $this->header();
        echo view("/auth/find/id", $this->aData);
        $this->footer(['quick']);

    }

    public function findPwd(string $type)
    {
        $this->backUrl = '/login/find';
        if (!in_array($type, ['person']) && !in_array($type, ['company'])) {
            return alert_url($this->globalvar->aMsg['error1'], $this->backUrl);
            exit;
        }
        $this->commonData();
        $this->aData['data']['aData'] = [
            'type' => $type
        ];

        $this->header();
        echo view("/auth/find/password", $this->aData);
        $this->footer(['quick']);
    }

    public function reset(string $type)
    {
        $this->backUrl = '/';
        $strUserData = $this->request->getGet('data');
        if (!in_array($type, ['person']) && !in_array($type, ['company']) || !$strUserData) {
            return alert_url($this->globalvar->aMsg['error1'], $this->backUrl);
            exit;
        }

        $encrypter = Services::encrypter();
        $aUserData = $encrypter->decrypt(base64url_decode($strUserData));
        $this->aData['data']['userData'] = json_decode($aUserData, true);

        echo view("/auth/find/resetPassword", $this->aData);
    }

    public function resetAction()
    {
        $strType = $this->request->getPost('mtype');
        $strPostCase = $this->request->getPost('postCase');
        $strId = $this->request->getPost('memId');
        $strPhone = $this->request->getPost('memPhone');
        $strAuth = $this->request->getPost('auth');
        $strNewPassword = $this->request->getPost('newpassword');
        $strbackUrl = $this->request->getPost('backUrl');
        if ($strPostCase != 'reset_write' || !$strType || !$strId || !$strNewPassword || !$strAuth || !$strPhone) {
            return alert_url($this->globalvar->aMsg['error1'], $strbackUrl ? $strbackUrl : $this->backUrl);
        }

        $encryptLib = new EncryptLib();
        $encryptNewPassword = $encryptLib->makePassword($strNewPassword);

        $memberModel = new MemberModel();
        $strBeforeDate = date("Y-m-d H:i:s", strtotime("-5 minutes"));
        // getLastPasswordDate() : 라스트 패스워드 변경일의 데이터가 있을시 비밀번호 변경 불가
        $aMemRow = $memberModel->getLastPasswordDate($strId, $strBeforeDate);
        if ($aMemRow) {
            return alert_back($this->globalvar->aMsg['error6']);
        }
        $strGetResultPw = dot_array_search('mem_password', $aMemRow);
        if ($encryptLib->checkPassword($encryptNewPassword, $strGetResultPw)) {
            return alert_back($this->globalvar->aMsg['error7']);
        }

        $authTelModel = new AuthTelModel();
        $aAuthData = $authTelModel->where([
            'auth_tel' => $strPhone,
            'auth_code' => $strAuth,
            'auth_start_date >' => $strBeforeDate,
        ])->first();

        if ($aAuthData) {
            $result = $this->masterDB->table('iv_member')
                ->set(['mem_password' => $encryptNewPassword])
                ->set(['mem_last_password_date' => 'NOW()'], '', false)
                ->where(['mem_id' => $strId, 'mem_type' => $strType])
                ->update();
            if ($result) {
                return alert_url($this->globalvar->aMsg['success4'], $strbackUrl ? $strbackUrl : $this->backUrl);
            } else {
                return alert_back($this->globalvar->aMsg['error3']);
            }
        } else {
            return alert_back($this->globalvar->aMsg['error8']);
        }
    }
    // end : interview 및 biz

    // start : admin
    public function adminMain()
    {
        // 세션설정
        $session = session();
        if ($session->has('mem_id') && $session->get('mem_type') == 'M') {
            return redirect($this->globalvar->getAdminMain());
        }
        return redirect($this->globalvar->getMain());
    }
    public function adminLogin()
    {
        // data init
        $aData = $this->commonData();

        // 세션설정
        $session = session();
        if ($session->has('mem_id') && $session->get('mem_type') == 'M') {
            return redirect($this->globalvar->getAdminMain());
        }
        echo view("prime/auth/login", $this->aData);
    }


    public function adminLoginAction()
    {
        //action page
        $strId = $this->request->getPost('id');
        $strPw = $this->request->getPost('password');
        $aMemberData = [];
        if (!$strPw || !$strId) {
            return redirect($this->globalvar->getAdminLogin());
        }

        $encryptLib = new EncryptLib();
        $memberModel = new MemberModel('iv_member');
        $aMemberData = $memberModel->getMember($strId);

        $strGetResultPw = dot_array_search('mem_password', $aMemberData);
        if ($encryptLib->checkPassword($strPw, $strGetResultPw)) {

            //트랜잭션 start
            $this->masterDB->transBegin();
            $intvisiteCount = $aMemberData['mem_visit_count'] ?? 0;
            $aMemberData['mem_visit_count'] = $intvisiteCount + 1;

            $this->masterDB->table('iv_member')
                ->set(['mem_visit_count' => $aMemberData['mem_visit_count']])
                ->set(['mem_visit_date' => 'NOW()'], '', false)
                ->where(['idx' => $aMemberData['idx']])
                ->update();
            $this->masterDB->table('log_member_login')
                ->set(['mem_idx' => $aMemberData['idx']])
                ->set(['log_reg_date' => 'NOW()'], '', false)
                ->insert();

            // 트랜잭션 end
            if ($this->masterDB->transStatus() === false) {
                $this->masterDB->transRollback();
                alert_url($this->globalvar->aMsg['error3'] . '(code:401)', $this->backUrlAdmin);
                exit;
            } else {
                $this->masterDB->transCommit();
                //세션설정
                $session = session();
                $tmpArrData = [];
                foreach ($aMemberData as $key => $val) {
                    if (in_array($key, ['mem_password', 'mem_token', 'mem_join_path', 'mem_reg_date', 'mem_mod_date', 'mem_del_date', 'delyn'])) {
                        continue;
                    }
                    $tmpArrData[$key] = $val;
                }
                $session->set($tmpArrData);
            }
            return redirect($this->globalvar->getAdminMain());
        } else {
            //관리자는 1.0 패스워드 방식 패스
            alert_back($this->globalvar->aMsg['error4']);
            exit;
        }
        return redirect($this->globalvar->getAdminLogin());
    }
    // end : admin

    public function __destruct()
    {
        session_write_close();
        $this->masterDB->close();
    }
}
