<?php

namespace App\Controllers\API\Error\Page;

use App\Controllers\API\APIController;
use App\Models\MemberRecruitScrapModel;

class ErrorController extends APIController
{
    private $aResponse = [];

    public function index()
    {
    }

    public function create(string $code)
    {
        if (!$this->request->isAJAX()) {
            return $this->respond([
                'status'   => 403,
                'code'     => 'fail',
                'messages' => '헤더 값 형식이 올바른지 확인하세요.',
            ], 403);
        }

        if (!in_array($code, ['applier'])) {
            return $this->respond([
                'status'   => 404,
                'code'     => 'fail',
                'messages' => '잘못된 접근입니다.',
            ], 404);
        }

        $session = session();
        $iMemIdx = $session->get('idx') ?? '';

        $result = false;
        if ($code == 'applier') {
            $iApplierIdx = $this->request->getPost('applierIdx');
            $iQuestionIdx = $this->request->getPost('questionIdx');
            $strFullPage = $this->request->getPost('pullPage');

            $aData = [
                'userIp' => $this->request->getIPAddress(),
                'serverProtocol' => $this->request->getServer('SERVER_PROTOCOL'),
                'userAgent' => $this->request->getServer('HTTP_USER_AGENT'),
                'fullPage' => $strFullPage,
                'memIdx' => $iMemIdx,
                'applierIdx' => $iApplierIdx,
                'questionIdx' => $iQuestionIdx,
            ];

            $result = $this->masterDB->table('log_error_page')
                ->set([
                    'error_page' => $code,
                    'error_data' => json_encode($aData),
                ])
                ->set(['error_reg_date' => 'NOW()'], '', false)
                ->insert();
        }

        if ($result) {
            $this->aResponse = [
                'status'   => 200,
                'code'     => [
                    'stat' => 'success',
                    'token' => csrf_hash()
                ],
                'messages' => '저장하였습니다.',
            ];
        } else {
            $this->aResponse = [
                'status'   => 500,
                'code'     => [
                    'stat' => 'fail',
                    'token' => csrf_hash()
                ],
                'messages' => '서버에 내부 오류가 발생했습니다. 요청을 다시 시도하세요.',
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

    public function delete(string $scrapType, int $memIdx, int $idx)
    {
    }
}
