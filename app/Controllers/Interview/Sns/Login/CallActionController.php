<?php

namespace App\Controllers\Interview\Sns\Login;

use App\Controllers\Interview\WwwController;
use Config\Services;
use App\Models\{
    MemberModel,
};

use App\Libraries\{
    EncryptLib,
};

class CallActionController extends WwwController
{
    private $backUrlList = '/';
    public function index()
    {
    }

    public function web($snsType)
    {
        if (!in_array($snsType, ['kakao', 'naver', 'google', 'apple'])) {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
            exit;
        }

        $encryptLib = new EncryptLib();
        if ($snsType == 'kakao') {
            $strCode = $this->request->getGet('code');
            if (!$strCode) {
                //todo telegram
                alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
                exit;
            }

            $client = Services::curlrequest();
            $responseToken = $client->request('POST', 'https://kauth.kakao.com/oauth/token', [
                'form_params' => [
                    'grant_type' => "authorization_code",
                    'client_id' => $this->globalvar->aSnsInfo['kakao']['clientId'],
                    'redirect_uri' => $this->globalvar->aSnsInfo['kakao']['redirectUrl'],
                    'code' => $strCode
                ],
            ]);
            
            if ($responseToken->getStatusCode() == 200) {
                $aGetToken = json_decode($responseToken->getBody(), true);

                $responseUser = $client->request('POST', 'https://kapi.kakao.com/v2/user/me', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $aGetToken["access_token"]
                    ],
                ]);

                if ($responseUser->getStatusCode() == 200) {
                    //로그인
                    $aGetUser = json_decode($responseUser->getBody(), true);
                    $strMemKey = $aGetUser['id'];
                    $strObjectEncrayt = $encryptLib->getSha1($strMemKey);
                    $cacheKey = 'sns.' . $snsType . '.' . $strObjectEncrayt;
                    cache()->save($cacheKey, [
                        'snsType' => $snsType,
                        'mem_key' => $strMemKey,
                        'mem_email' => $aGetUser['kakao_account']['email'],
                        'mem_object_enc' => $strObjectEncrayt,
                    ], 300);
                    return redirect()->to("/login/sns/action/{$snsType}/{$strObjectEncrayt}");
                }
                //todo 에러로그 쌓아야함
            } else {
                //todo 에러로그 쌓아야함
            }
        }
        alert_url($this->globalvar->aMsg['error12'], $this->backUrlList);
        //todo 에러로그 쌓아야함
        //window.close(); 창닫은후 종료
        exit;
    }
}
