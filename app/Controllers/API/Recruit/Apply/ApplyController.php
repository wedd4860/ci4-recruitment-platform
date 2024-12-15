<?php

namespace App\Controllers\API\Recruit\Apply;

use App\Controllers\API\APIController;
use Config\Services;
use App\Models\ApplierModel;
use App\Models\ResumeModel;

class ApplyController extends APIController
{
    private $aResponse = [];

    public function index()
    {
    }

    public function create()
    {
    }

    public function show(string $type, string $idx)
    {
        if (!$this->request->isAJAX()) {
            return $this->respond([
                'status'   => 403,
                'code'     => 'fail',
                'messages' => '헤더 값 형식이 올바른지 확인하세요.',
            ], 403);
        }

        // apply페이지에 인터뷰 리스트 가져오기
        $iMemIdx = $idx;
        $strType = $type;
        $session = session();
        $session->set(['idx' => '1']);
        if (!$session->has('idx') || !$idx || $iMemIdx != $session->get('idx') || !in_array($strType, ['applier', 'resume'])) {
            return $this->respond([
                'status'   => 404,
                'code'     => 'fail',
                'messages' => '잘못된 접근입니다.',
            ], 404);
        }

        $aRowData = [];
        if ($strType == 'applier') {
            $applierModel = new ApplierModel();
            $aRowData = $applierModel->getMemberInterview($iMemIdx);
        } else {
            $resumeModel = new ResumeModel();
            $aRowData = $resumeModel->getResumeList($iMemIdx);
        }

        if (count($aRowData)) {
            $this->aResponse = [
                'status'   => 200,
                'code'     => [
                    'stat' => 'success',
                    'token' => csrf_hash(),
                    'info' => $aRowData
                ],
                'messages' => '조회하였습니다.',
            ];
        } else {
            $this->aResponse = [
                'status'   => 404,
                'code'     => [
                    'stat' => 'fail',
                    'token' => csrf_hash(),
                    'info' => ''
                ],
                'messages' => '데이터를 찾을수 없습니다.',
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
