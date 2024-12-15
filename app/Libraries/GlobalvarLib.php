<?php

namespace App\Libraries;

class GlobalvarLib
{
    private $login = ''; // 로그인
    private $main = ''; // 메인
    private $wwwUrl = ''; // www url
    private $shortUrl = ''; // sort url
    private $menuUrl = ''; // menu url
    private $mediaUrl = ''; //media url
    private $mediaPort = ''; //media port

    private $serverHost = ''; // serverHost
    private $aDevIp = []; // 개발자 아이피
    public $aMemberLeave = []; //탈퇴사유

    //admin
    private $adminLogin = ''; // admin 로그인
    private $adminMain = ''; // admin 메인
    private $smsAPIKey = ''; // sms api키

    private $emailFromMail = '';
    private $emailFromName = '';

    //sns
    public $aSnsInfo = []; //sns 정보

    //msg
    public $aMsg = [];
    // '근무(,중복) 월:0,화:1...일:6,평일:8,주말:9',
    // '고용형태(,중복) 정규직:0,계약직:1,인턴직:2,아르바이트:3,해외취업:4',
    // '학력 고졸이하:1,고등학교:2,대학(2,3년재):3,대학교(4년재):4,석사:5,박사:6,박사이상:7,무관:0',
    // config
    private $aConfig = [];
    public function __construct()
    {
        // webtest 연결 테스트 2
        //controller 사용
        $this->login = '\App\Controllers\Auth\AuthController::login';
        $this->main = '\App\Controllers\Interview\MainController::main';
        $this->adminLogin = '\App\Controllers\Auth\AuthController::adminLogin';
        $this->adminMain = '\App\Controllers\Admin\MainController::main';
        $this->aDevIp = ['121.174.144.33'];
        $this->aMsg = [
            'success1' => '저장하였습니다.',
            'success2' => '삭제하였습니다.',
            'success3' => '가입완료.',
            'success4' => '변경을 완료하였습니다.',
            'error1' => '잘못된 접근입니다.',
            'error2' => '문제가 발생하였습니다.',
            'error3' => '일시적인 오류입니다. 다시 시도해 주세요.',
            'error4' => '아이디와 패스워드를 확인해주세요.',
            'error5' => '이미 사용중인 아이디입니다.',
            'error6' => '변경한 이력이 있습니다.',
            'error7' => '같은 비밀번호로는 변경이 불가능합니다.',
            'error8' => '인증시간이 초과하였습니다.',
            'error9' => '현재 로그인 중입니다.',
            'error10' => '검색어를 입력해 주세요.',
            'error11' => '로그인이 필요한 서비스 입니다.',
            'error12' => '기간이 만료되었습니다.',
            'error13' => '잘못된 요청 입니다.',
        ];
        /**
         * error3 401 : 트랜젝션 에러, 롤백진행한경우
         */

        $this->aMemberLeave = [
            '0' => '하이버프로 직장을 구했어요.',
            '1' => '다른 서비스로 직장을 구했어요.',
            '2' => '채용공고가 부족해요.',
            '3' => '잘못된 접근입니다.',
            '4' => '앱 오류가 잦아요.',
            '5' => '기타 (직접 입력).',
        ];
        //config
        $this->aConfig = [
            'recruit' => [
                'work_day' => ['일', '월', '화', '수', '목', '금', '토'],
                'work_type' => ['정규직', '계약직', '인턴직', '아르바이트', '해외취업'],
                'education' => ['무관', '고졸이하', '고등학교', '대학(2,3년재)', '대학교(4년재)', '석사', '박사', '박사이상'],

            ],
            'company' => [
                'company_form' => ['공기업', '대기업', '중견기업', '중소/스타트업'],
            ]
        ];

        //sns 로그인
        // 구글, 카카오, 네이버별 각 client _id, client_secret, redirect_uri 상수 설정
        // 하정님이 통합관리
        //GOOGLE 21.06.03 완료
        $this->aSnsInfo = [
            'google' => [
                'clientId' => '265440924719-5a242gqtatmolvgino9l4vtr7jpnnq2u.apps.googleusercontent.com',
                'clientSecret' => 'lXPgmBWQO3QUuLcoddTK9ifp',
                'redirectUrl' => 'https://interview.highbuff.com/sns/google/web/call',
            ],
            //KAKAO 21.06.02 완료
            'kakao' => [
                'clientId' => 'c622851e95a98fbe13ba6a94d0598a5b',
                'clientSecret' => '',
                'redirectUrl' => 'https://interview.highbuff.com/sns/kakao/web/call',
            ],
            //NAVER 21.06.03 완료
            'naver' => [
                'clientId' => 'xgw7omXoMTrWdMLU9cw2',
                'clientSecret' => 'Xd1WE28MgA',
                'redirectUrl' => 'https://interview.highbuff.com/sns/naver/web/call',
            ],
            //APPLE 21.06.02 완료
            'apple' => [
                'clientId' => 'interview.bluevisor.com',
                'clientSecret' => '6LFD4FPL6S',
                'redirectUrl' => 'https://interview.highbuff.com/sns/apple/web/call',
            ],
        ];

        $this->emailFromMail = 'help@bluevisor.kr';
        $this->emailFromName = 'bluevisor';

        //view 사용
        $this->serverHost = 'test';
        if (gethostname() == 'new-interview') {
            $this->serverHost = 'real';
        } else if (gethostname() == 'iv-test') {
            $this->serverHost = 'webtest';
        }
        $this->wwwUrl = 'https://interview.highbuff.com';
        $this->menuUrl = 'https://interview.highbuff.com';
        $this->shortUrl = 'https://interview.highbuff.com/short';
        $this->mediaUrl = 'https://media.highbuff.com';
        $this->mediaPort = '3000';
        $this->smsAPIKey = 'VTN6dXVKUlVWT2U4K1REZEJtZzhpYlN0WlFuOWxIM3dWVE8veE40Tk81T0czdGxTNTErVkhGb09BZGZseC9LdVJCS2F2cWVxMTVNR0tEa01zSThCWVE9PQ==';
        if ($this->serverHost == 'webtest') {
            $this->wwwUrl = 'https://webtestinterviewr.highbuff.com';
            $this->menuUrl = 'https://webtestinterviewr.highbuff.com';
            $this->sortUrl = 'https://webtestinterviewr.highbuff.com/sort';
            $this->smsAPIKey = 'TFhCMjdhR28yR210KzVtU1lHWVhIUEl3WmJSQXRucmlrdkxPZEFMdEhWakdyUjVJendzQlBWTkowNUF2N3ZneFRtK0lmbk0vaklVcmdCTFc0NytIRUE9PQ==';

            // 구글, 카카오, 네이버별 각 client _id, client_secret, redirect_uri 상수 설정
            // 하정님이 통합관리
            //GOOGLE 21.06.03 완료
            $this->aSnsInfo = [
                'google' => [
                    'clientId' => '265440924719-5a242gqtatmolvgino9l4vtr7jpnnq2u.apps.googleusercontent.com',
                    'clientSecret' => 'lXPgmBWQO3QUuLcoddTK9ifp',
                    'redirectUrl' => 'https://webtestinterviewr.highbuff.com/sns/google/web/call',
                ],
                //KAKAO 21.06.02 완료
                'kakao' => [
                    'clientId' => 'c622851e95a98fbe13ba6a94d0598a5b',
                    'clientSecret' => '',
                    'redirectUrl' => 'https://webtestinterviewr.highbuff.com/sns/kakao/web/call',
                ],
                //NAVER 21.06.03 완료
                'naver' => [
                    'clientId' => 'xgw7omXoMTrWdMLU9cw2',
                    'clientSecret' => 'Xd1WE28MgA',
                    'redirectUrl' => 'https://webtestinterviewr.highbuff.com/sns/naver/web/call',
                ],
                //APPLE 21.06.02 완료
                'apple' => [
                    'clientId' => 'interview.bluevisor.com',
                    'clientSecret' => '6LFD4FPL6S',
                    'redirectUrl' => 'https://webtestinterviewr.highbuff.com/sns/apple/web/call',
                ],
            ];
        } else if ($this->serverHost == 'test') {
            $this->wwwUrl = 'https://localinterviewr.highbuff.com';
            $this->menuUrl = 'https://localinterviewr.highbuff.com';
            $this->sortUrl = 'https://localinterviewr.highbuff.com/sort';
            $this->smsAPIKey = 'TFhCMjdhR28yR210KzVtU1lHWVhIUEl3WmJSQXRucmlrdkxPZEFMdEhWakdyUjVJendzQlBWTkowNUF2N3ZneFRtK0lmbk0vaklVcmdCTFc0NytIRUE9PQ==';

            // 구글, 카카오, 네이버별 각 client _id, client_secret, redirect_uri 상수 설정
            // 하정님이 통합관리
            //GOOGLE 21.06.03 완료
            $this->aSnsInfo = [
                'google' => [
                    'clientId' => '265440924719-5a242gqtatmolvgino9l4vtr7jpnnq2u.apps.googleusercontent.com',
                    'clientSecret' => 'lXPgmBWQO3QUuLcoddTK9ifp',
                    'redirectUrl' => 'https://localinterviewr.highbuff.com/sns/google/web/call',
                ],
                //KAKAO 21.06.02 완료
                'kakao' => [
                    'clientId' => 'c622851e95a98fbe13ba6a94d0598a5b',
                    'clientSecret' => '',
                    'redirectUrl' => 'https://localinterviewr.highbuff.com/sns/kakao/web/call',
                ],
                //NAVER 21.06.03 완료
                'naver' => [
                    'clientId' => 'xgw7omXoMTrWdMLU9cw2',
                    'clientSecret' => 'Xd1WE28MgA',
                    'redirectUrl' => 'https://localinterviewr.highbuff.com/sns/naver/web/call',
                ],
                //APPLE 21.06.02 완료
                'apple' => [
                    'clientId' => 'interview.bluevisor.com',
                    'clientSecret' => '6LFD4FPL6S',
                    'redirectUrl' => 'https://localinterviewr.highbuff.com/sns/apple/web/call',
                ],
            ];
        }
    }


    public function getConfig(): array
    {
        return $this->aConfig;
    }

    public function getEmailFromMail(): string
    {
        return $this->emailFromMail;
    }

    public function getEmailFromName(): string
    {
        return $this->emailFromName;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getMain(): string
    {
        return $this->main;
    }

    public function getWwwUrl(): string
    {
        return $this->wwwUrl;
    }

    public function getShortUrl(): string
    {
        return $this->shortUrl;
    }

    public function getMediaUrl(): string
    {
        return $this->mediaUrl;
    }

    public function getMediaFullUrl(): string
    {
        return $this->mediaUrl . ':' . $this->mediaPort;
    }

    public function getMenuUrl(): string
    {
        return $this->menuUrl;
    }

    public function getServerHost(): string
    {
        return $this->serverHost;
    }

    public function getAdminLogin(): string
    {
        return $this->adminLogin;
    }

    public function getAdminMain(): string
    {
        return $this->adminMain;
    }

    public function getADevIp(): array
    {
        return $this->aDevIp;
    }

    public function getSmsAPIKey(): string
    {
        return $this->smsAPIKey;
    }
}
