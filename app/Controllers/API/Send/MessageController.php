<?php

namespace App\Controllers\API\Send;

use App\Controllers\API\APIController;
use CodeIgniter\I18n\Time;
use App\Libraries\SendLib;

class MessageController extends APIController
{
    private $aResponse = [];

    public function index()
    {
    }

    public function create()
    {
    }

    public function show(string $code)
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

        $strMsg =  $this->request->getPost('msg');
        $strBotType = $this->request->getPost('botType');

        if (!in_array($code, ['telegram']) || !$strMsg) {
            return $this->respond([
                'status'   => 404,
                'code'     => 'fail',
                'messages' => '잘못된 접근입니다.',
            ], 404);
        }

        $sendLib = new SendLib();
        $sendResult = $sendLib->telegramSend($strMsg, $strBotType);
        if ($sendResult == 200) {
            $this->aResponse = [
                'status'   => 200,
                'code'     => [
                    'stat' => 'success',
                    'token' => csrf_hash()
                ],
                'messages' => '텔레그램으로 메세지를 보냈습니다.',
            ];
        } else {
            $this->aResponse = [
                'status'   => $sendResult,
                'code'     => [
                    'stat' => 'fail',
                    'token' => csrf_hash()
                ],
                'messages' => '현재 API 요청을 받을 수 없습니다. 다시 시도하세요.',
            ];
        }
        return $this->respond($this->aResponse, $this->aResponse['status']);
    }

    public function update($id = null)
    {
    }

    public function delete($id = null)
    {
    }
}
