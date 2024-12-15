<?php

namespace App\Controllers\API\My\Scrap;

use App\Controllers\API\APIController;
use App\Models\MemberRecruitScrapModel;

class ScrapController extends APIController
{
    private $aResponse = [];

    public function index()
    {
    }

    public function create(string $scrapType, int $memIdx, int $idx)
    {
        if (!$this->request->isAJAX()) {
            return $this->respond([
                'status'   => 403,
                'code'     => 'fail',
                'messages' => '헤더 값 형식이 올바른지 확인하세요.',
            ], 403);
        }
        if (!in_array($scrapType, ['recruit', 'company'])) {
            return $this->respond([
                'status'   => 404,
                'code'     => 'fail',
                'messages' => '잘못된 접근입니다.',
            ], 404);
        }
        $session = session();
        $boolSuccess = true;
        if (!$session->has('idx') || !$memIdx || $session->get('idx') != $memIdx || !$idx) {
            $boolSuccess = false;
        }

        $memberRecruitScrapModel = new MemberRecruitScrapModel();
        $iCountScrap = $memberRecruitScrapModel->selectCount('idx')->where(['mem_idx' => $memIdx, 'delyn' => 'N']);
        if ($scrapType == 'recruit') {
            $iCountScrap = $iCountScrap->where(['rec_idx' => $idx])->first();
        } else {
            $iCountScrap = $iCountScrap->where(['com_idx' => $idx])->first();
        }

        if ($iCountScrap['idx'] == 0 && $boolSuccess) {
            $readyDB = $this->masterDB->table('iv_member_recruit_scrap')
                ->set([
                    'mem_idx' => $memIdx,
                ])
                ->set(['scr_reg_date' => 'NOW()'], '', false)
                ->set(['scr_mod_date' => 'NOW()'], '', false);
            if ($scrapType == 'recruit') {
                $result = $readyDB
                    ->set([
                        'rec_idx ' => $idx,
                        'scr_type' => 'R',
                    ])->insert();
            } else {
                $result = $readyDB
                    ->set([
                        'com_idx ' => $idx,
                        'scr_type' => 'C',
                    ])->insert();
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
        } else {
            $this->aResponse = [
                'status'   => 404,
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

    public function delete(string $scrapType, int $memIdx, int $idx)
    {
        if (!$this->request->isAJAX()) {
            return $this->respond([
                'status'   => 403,
                'code'     => 'fail',
                'messages' => '헤더 값 형식이 올바른지 확인하세요.',
            ], 403);
        }
        if (!in_array($scrapType, ['recruit', 'company'])) {
            return $this->respond([
                'status'   => 404,
                'code'     => 'fail',
                'messages' => '잘못된 접근입니다.',
            ], 404);
        }
        $session = session();
        $boolSuccess = true;
        if (!$session->has('idx') || !$memIdx || $session->get('idx') != $memIdx || !$idx) {
            $boolSuccess = false;
        }

        $memberRecruitScrapModel = new MemberRecruitScrapModel();

        $iCountScrap = $memberRecruitScrapModel->selectCount('idx')->where(['mem_idx' => $memIdx, 'delyn' => 'N']);
        if ($scrapType == 'recruit') {
            $iCountScrap = $iCountScrap->where(['rec_idx' => $idx])->first();
        } else {
            $iCountScrap = $iCountScrap->where(['com_idx' => $idx])->first();
        }

        if ($iCountScrap['idx'] > 0 && $boolSuccess) {
            $readyDB = $this->masterDB->table('iv_member_recruit_scrap')
                ->set(['delyn' => 'Y'])
                ->set(['scr_del_date' => 'NOW()'], '', false)
                ->where(['mem_idx' => $memIdx, 'delyn' => 'N']);
            if ($scrapType == 'recruit') {
                $result = $readyDB->where(['rec_idx' => $idx])->update();
            } else {
                $result = $readyDB->where(['com_idx' => $idx])->update();
            }
            if ($result) {
                $this->aResponse = [
                    'status'   => 200,
                    'code'     => [
                        'stat' => 'success',
                        'token' => csrf_hash()
                    ],
                    'messages' => '삭제하였습니다.',
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
        } else {
            $this->aResponse = [
                'status'   => 404,
                'code'     => [
                    'stat' => 'fail',
                    'token' => csrf_hash()
                ],
                'messages' => '잘못된 요청입니다.',
            ];
        }
        return $this->respond($this->aResponse, $this->aResponse['status']);
    }
}
