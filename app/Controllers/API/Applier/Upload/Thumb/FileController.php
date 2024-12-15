<?php

namespace App\Controllers\API\Applier\Upload\Thumb;

use App\Controllers\API\APIController;

use App\Models\ApplierModel;

class FileController extends APIController
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

        $session = session();
        $iMemIdx = $this->request->getPost('memIdx');
        $strFileOrgName = $this->request->getPost('fileOrgName');
        $strFileSaveName = $this->request->getPost('fileSaveName');
        $strFileSize = $this->request->getPost('fileSize');
        $strFileType = $this->request->getPost('fileType');
        $strPostCase = $this->request->getPost('postCase');

        if (!$session->has('idx') || $session->get('idx') != $iMemIdx || $strPostCase != 'file_write' || !$strFileOrgName || !$strFileSaveName || !$strFileSize || !$strFileType) {
            return $this->respond([
                'status'   => 400,
                'code'     => [
                    'stat' => 'fail',
                    'token' => csrf_hash()
                ],
                'messages' => '잘못된 접근 입니다.',
            ], 400);
        }

        $result = $this->masterDB->table('iv_file')
            ->set([
                'file_type' => 'A',
                'file_org_name' => $strFileOrgName,
                'file_save_name' => $strFileSaveName,
                'file_size' => $strFileSize,
            ])
            ->set(['file_reg_date' => 'NOW()'], '', false)
            ->set(['file_mod_date' => 'NOW()'], '', false)
            ->insert();

        if ($result) {
            $this->aResponse = [
                'status'   => 200,
                'code'     => [
                    'stat' => 'success',
                    'token' => csrf_hash()
                ],
                'fileIdx' => $this->masterDB->insertID(),
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

    public function updateInterview()
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
        $session = session();
        $iMemIdx = $this->request->getPost('memIdx');
        $strAppliyIdx = $this->request->getPost('index');
        $strCount = $this->request->getPost('count');
        $strQidx = $this->request->getPost('q_idx');
        $strTime = $this->request->getPost('time');
        $strRand = $this->request->getPost('rand');
        $strPostCase = $this->request->getPost('postCase');
        $strBackUrl = $this->request->getPost('BackUrl');

        if (!$session->has('idx') || $session->get('idx') != $iMemIdx || $strPostCase != 'video_upload' || !$strAppliyIdx || !$strCount || !$strQidx || !$strTime || !$strRand || !$strBackUrl) {
            return $this->respond([
                'status'   => 400,
                'code'     => [
                    'stat' => 'fail',
                    'token' => csrf_hash()
                ],
                'messages' => '잘못된 접근 입니다.',
            ], 400);
        }

        $result = $this->masterDB->table('iv_video')
            ->set([
                'app_idx' => $strAppliyIdx,
                'que_idx' => $strQidx,
                'video_record' => $strAppliyIdx . '-record_' . $strCount . '-' . $strQidx . '-' . $strTime . '-' . $strRand . '.webm',
            ])
            ->set(['video_reg_date' => 'NOW()'], '', false)
            ->set(['video_mod_date' => 'NOW()'], '', false)
            ->insert();

        $result2 =  $this->masterDB->table('iv_report_result')
            ->set([
                'repo_process' => 1,
            ])
            ->set(['repo_mod_date' => 'NOW()'], '', false)
            ->where('applier_idx', $strAppliyIdx)
            ->where('que_idx', $strQidx)
            ->update();


        $ApplierModel = new ApplierModel();
        $aAllapplierInfo = $ApplierModel->startInterview($strAppliyIdx);



        if ($aAllapplierInfo[0]['next_question'] == "" || $aAllapplierInfo[0]['next_question'] == null) {

            $result3 =  $this->masterDB->table('iv_applier')
                ->set([
                    'app_iv_stat' => 3,
                ])
                ->set(['app_mod_date' => 'NOW()'], '', false)
                ->where('idx', $strAppliyIdx)
                ->where('mem_idx', $iMemIdx)
                ->update();

            if ($result3) {
                $this->aResponse = [
                    'status'   => 201,
                    'code'     => [
                        'stat' => 'success',
                        'token' => csrf_hash()
                    ],
                    'messages' => '완료되었습니다.',
                ];
            } else {
                $this->aResponse = [
                    'status'   => 501,
                    'code'     => [
                        'stat' => 'fail',
                        'token' => csrf_hash()
                    ],
                    'messages' => '서버에 내부 오류가 발생했습니다. 요청을 다시 시도하세요.',
                ];
            }
        } else {
            if ($result && $result2) {
                $this->aResponse = [
                    'status'   => 200,
                    'code'     => [
                        'stat' => 'success',
                        'token' => csrf_hash()
                    ],
                    'All' => $aAllapplierInfo,
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
        }

        return $this->respond($this->aResponse, $this->aResponse['status']);
    }

    public function delete(int $idx)
    {
    }
}
