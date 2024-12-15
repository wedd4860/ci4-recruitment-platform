<?php

namespace App\Controllers\API\Auth\Find;

use App\Controllers\API\APIController;
use App\Libraries\ShortUrlLib;
use Config\Services;
use App\Libraries\SendLib;
use App\Models\MemberModel;
use App\Models\AuthTelModel;
use App\Models\LogSendEmailModel;
use CodeIgniter\I18n\Time;

class FindController extends APIController
{
    private $aResponse = [];

    public function index()
    {
    }

    public function create()
    {
    }

    public function findId(string $type)
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

        $strTel = $this->request->getPost('tel');
        $strCode = $this->request->getPost('code');
        $strPostCase = $this->request->getPost('postCase');
        if (!in_array($type, ['person', 'company']) || $strPostCase != 'find_id' || !$strTel || !$strCode) {
            return $this->respond([
                'status'   => 400,
                'code'     => [
                    'stat'     => 'fail',
                    'messages' => '잘못된 접근입니다.',
                    'token' => csrf_hash(),
                ]
            ], 400);
        }

        $authTelModel = new AuthTelModel();
        $aAuthData = $authTelModel->where([
            'auth_tel' => $strTel,
            'auth_code' => $strCode,
            'auth_type' => 'I',
        ])->first();

        if ($aAuthData && $aAuthData['auth_tel']) {
            $memberModel = new MemberModel();
            $aMemberData = $memberModel->getMemberTel($strTel, $type == 'person' ? 'M' : 'C');

            if ($aMemberData && $aMemberData['mem_id']) {
                $this->aResponse = [
                    'status'   => 200,
                    'code'     => [
                        'stat' => 'success',

                        'id' =>  $aMemberData['mem_id']
                    ],
                    'messages' => '조회하였습니다.',
                ];
            } else {
                $this->aResponse = [
                    'status'   => 400,
                    'code'     => [
                        'stat' => 'fail',
                        'token' => csrf_hash(),
                        'id' => ''
                    ],
                    'messages' => '데이터를 찾을수 없습니다.',
                ];
            }
        } else {
            $this->aResponse = [
                'status'   => 400,
                'code'     => [
                    'stat' => 'fail',
                    'token' => csrf_hash(),
                    'id' => ''
                ],
                'messages' => '인증번호를 확인해 주세요.',
            ];
        }
        return $this->respond($this->aResponse, $this->aResponse['status']);
    }

    public function findPassword(string $type)
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

        $strToTel = $this->request->getPost('tel');
        $strCode = $this->request->getPost('code');
        $strId = $this->request->getPost('id');
        $strPostCase = $this->request->getPost('postCase');
        $page = $this->request->getPost('page');

        if (!in_array($type, ['person', 'company']) || !$strToTel || !$strCode || !$strId || $strPostCase != 'find_pwd') {
            return $this->respond([
                'status'   => 400,
                'code'     => [
                    'stat' => 'fail',
                    'token' => csrf_hash()
                ],
                'messages' => '잘못된 접근입니다.',
            ], 400);
        }

        $memberModel = new MemberModel();
        $amemberData = $memberModel->where([
            'mem_id' => $strId,
            'mem_tel' => $strToTel,
            'mem_type' => $type == 'person' ? 'M' : 'C'
        ])->first();


        if (!$amemberData) {
            return $this->respond([
                'status'   => 400,
                'code'     => [
                    'stat' => 'fail',
                    'token' => csrf_hash()
                ],
                'messages' => '아이디와 핸드폰 정보를 다시 확인해 주세요.',
            ], 400);
        }

        $strToTel = str_replace("-", "", $strToTel);

        $authTelModel = new AuthTelModel();
        $logSendEmailModel = new LogSendEmailModel();

        $strBeforeDate = date("Y-m-d H:i:s", strtotime("-5 minutes"));

        $iCount = $logSendEmailModel->selectCount('idx')
            ->where([
                'send_email_to' => $strId,
                'send_email_reg_date >' => $strBeforeDate
            ])->first()['idx'];

        if ($iCount < 1) {

            $aAuthData = $authTelModel->where([
                'auth_tel' => $strToTel,
                'auth_code' => $strCode,
                'auth_type' => 'P',
            ])->first();

            if ($aAuthData) {
                $aUserData = [
                    'id' => $strId,
                    'phone' => $strToTel,
                    'auth' => $strCode
                ];

                //비밀번호 reset URL 생성    
                $encrypter = Services::encrypter();
                $ciphertext = base64url_encode($encrypter->encrypt(json_encode($aUserData)));

                if ($type == 'person') {
                    $strResetUrl = "/login/reset/person/pwd?data=" . $ciphertext;
                } else {
                    $strResetUrl = "/login/reset/company/pwd?data=" . $ciphertext;
                }

                $shortUrlLib = new ShortUrlLib();
                $strShortUrl = $shortUrlLib->setShortUrl($strResetUrl, date('is'));
                $strReseturl = $this->globalvar->getShortUrl() . '/' . $strShortUrl;

                $strTitle = '하이버프 인터뷰 [비밀번호 변경 url] 입니다.';
                $strMsg = "<p>하이버프 인터뷰\n비밀번호 변경 url 입니다.\n$strReseturl<p>";
                
                $email = Services::email();
                $email->clear();
                $email->setTo($strId);
                $email->setFrom($this->globalvar->getEmailFromMail(), $this->globalvar->getEmailFromName());
                $email->setSubject($strTitle);
                $email->setMessage($strMsg);
                $emailResult = $email->send();

                if ($emailResult == 200) {
                    $result = $this->masterDB->table('log_send_email')
                        ->set([
                            'send_email_to' => $strId,
                            'send_email_page' => $page
                        ])
                        ->set(['send_email_reg_date' => 'NOW()'], '', false)
                        ->insert();

                    if ($result) {
                        $this->aResponse = [
                            'status'   => 200,
                            'code'     => [
                                'stat' => 'success',
                                'token' => csrf_hash()
                            ],
                            'messages' => '이메일을 전송하였습니다.',
                        ];
                    } else {
                        $this->aResponse = [
                            'status'   => 200,
                            'code'     => [
                                'stat' => 'fail',
                                'token' => csrf_hash()
                            ],
                            'messages' => '일시적인 오류입니다. 다시 시도해 주세요.',
                        ];
                    }
                } else {
                    $this->aResponse = [
                        'status'   => 400,
                        'code'     => [
                            'stat' => 'fail',
                            'token' => csrf_hash()
                        ],
                        'messages' => '이메일 전송에 실패하였습니다.',
                    ];
                }
            } else {
                $this->aResponse = [
                    'status'   => 400,
                    'code'     => [
                        'stat' => 'fail',
                        'token' => csrf_hash()
                    ],
                    'messages' => '인증번호를 확인해 주세요.',
                ];
            }
        } else {
            //5분이내 1번 요청
            $this->aResponse = [
                'status'   => 403,
                'code'     => [
                    'stat' => 'fail',
                    'token' => csrf_hash()
                ],
                'messages' => '5분동안 한번만 요청 가능합니다.',
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
