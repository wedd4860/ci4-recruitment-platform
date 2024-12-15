<?php

namespace App\Controllers\API\Auth;

use App\Controllers\API\APIController;
use App\Models\AuthTelModel;
use CodeIgniter\I18n\Time;
use App\Libraries\SendLib;

class TelController extends APIController
{
    private $aResponse = [];

    public function index()
    {
    }

    public function create()
    {
        if (!$this->request->isAJAX()) {
            return $this->respond([
                'status'   => 403,
                'code'     => [
                    'stat' => 'fail',
                    'token' => csrf_hash()
                ],
                'messages' => '헤더 값 형식이 올바른지 확인하세요.',
            ], 403);
        }

        $strToTel = $this->request->getPost('phone');
        $strToTel = str_replace("-", "", $strToTel);
        $strType = $this->request->getPost('type');
        if (!$strToTel || !in_array($strType, ['J', 'I', 'P'])) {
            return $this->respond([
                'status'   => 404,
                'code'     => 'fail',
                'messages' => '잘못된 접근입니다.',
            ], 404);
        }

        $strBeforetime = new Time('now');
        $strBeforetime = $strBeforetime->subMinutes(3)->toLocalizedString('yyyy-MM-dd HH:mm:ss');

        $authTelModel = new AuthTelModel();
        $strBeforeDate = date("Y-m-d H:i:s", strtotime("-3 minutes"));
        $iCount = $authTelModel->selectCount('idx')
            ->where([
                'auth_tel' => $strToTel,
                'auth_start_date >' => $strBeforeDate,
                'auth_type' => $strType
            ])->first()['idx'];
        if ($iCount < 3) {
            $iCertNumber = rand(100000, 999999);

            if($strType == "I"){
                $strMsg = "[인증번호:" . $iCertNumber . "] 하이버프 인터뷰\n아이디찾기 인증번호입니다.";
            }else if($strType == "P"){
                $strMsg = "[인증번호:" . $iCertNumber . "] 하이버프 인터뷰\n패스워드찾기 인증번호입니다.";
            }else{
                $strMsg = "[인증번호:" . $iCertNumber . "] 하이버프 인터뷰\n회원가입 인증번호입니다.";
            }
    
            $sendLib = new SendLib();
            $smsResult = $sendLib->sendSMS($strToTel, $strMsg);

            if ($smsResult == 200) {
                $result = $this->masterDB->table('iv_auth_tel')
                    ->set([
                        'auth_tel' => $strToTel,
                        'auth_code' => $iCertNumber,
                        'auth_type' => $strType
                    ])
                    ->set(['auth_start_date' => 'NOW()'], '', false)
                    ->insert();

                if ($result) {
                    $this->aResponse = [
                        'status'   => 201,
                        'code'     => [
                            'stat' => 'success',
                            'token' => csrf_hash()
                        ],
                        'messages' => '문자메세지를 요청하였습니다.',
                    ];
                } else {
                    $this->aResponse = [
                        'status'   => 400,
                        'code'     => [
                            'stat' => 'fail',
                            'token' => csrf_hash()
                        ],
                        'messages' => '쓰기 작업은 허용되지 않습니다.',
                    ];
                }
            } else {
                $this->aResponse = [
                    'status'   => $smsResult,
                    'code'     => [
                        'stat' => 'fail',
                        'token' => csrf_hash()
                    ],
                    'messages' => '현재 API 요청을 받을 수 없습니다. 다시 시도하세요.',
                ];
            }
        } else {
            //3분이내 3번 요청
            $this->aResponse = [
                'status'   => 403,
                'code'     => [
                    'stat' => 'fail',
                    'token' => csrf_hash()
                ],
                'messages' => '3분이내 동일한 번호로 3번 요청하였습니다.',
            ];
        }
        return $this->respond($this->aResponse, $this->aResponse['status']);
    }

    public function inquire(string $tel, int $code)
    {
        if (!$this->request->isAJAX()) {
            return $this->respond([
                'status'   => 403,
                'code'     => 'fail',
                'messages' => '헤더 값 형식이 올바른지 확인하세요.',
            ], 403);
        }

        $authTelModel = new AuthTelModel();
        $aRowData = $authTelModel->where([
            'auth_tel' => $tel,
            'auth_code' => $code,
            'auth_type' => 'J',
        ])->first();
        $strBeforetime = new Time('now');
        $strBeforetime = $strBeforetime->subMinutes(3)->toLocalizedString('yyyy-MM-dd HH:mm:ss');
        if (isset($aRowData['idx']) && $aRowData['idx'] > 0) {
            if ($aRowData['auth_start_date'] > $strBeforetime) {
                $this->aResponse = [
                    'status'   => 200,
                    'code'     => [
                        'stat' => 'success',
                        'token' => csrf_hash()
                    ],
                    'messages' => '인증되었습니다.',
                ];
            } else {
                $this->aResponse = [
                    'status'   => 200,
                    'code'     => [
                        'stat' => 'fail',
                        'token' => csrf_hash()
                    ],
                    'messages' => '인증시간을 초과하였습니다.',
                ];
            }
        } else {
            $this->aResponse = [
                'status'   => 400,
                'code'     => [
                    'stat' => 'fail',
                    'token' => csrf_hash()
                ],
                'messages' => '잘못된 요청입니다.',
            ];
        }
        return $this->respond($this->aResponse, $this->aResponse['status']);
    }

    public function show($id = null)
    {
    }

    public function update($id = null)
    {
    }

    public function delete($id = null)
    {
    }
}
