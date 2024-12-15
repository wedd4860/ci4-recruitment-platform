<?php

namespace App\Controllers\API\Applier\Upload\Mic;

use App\Controllers\API\APIController;

use FFMpeg\FFMpeg;
use FFMpeg\Format\Audio\Flac;
use Google\Cloud\Storage\StorageClient;



use Google\Cloud\Speech\V1\SpeechClient;
use Google\Cloud\Speech\V1\SpeechContext;
use Google\Cloud\Speech\V1\RecognitionAudio;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\RecognitionConfig\AudioEncoding;

class MicController extends APIController
{
    private $aResponse = [];

    public function index()
    {
    }

    public function check()
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
        $strWord = $this->request->getPost('word');
        $strDevice = $this->request->getPost('checkDevice');
        $strApplyIdx = $this->request->getPost('applyIdx');
        $micFile = $this->request->getPost('file');
        $strPostCase = $this->request->getPost('postCase');

        $strFileName = time() . "-" . $strApplyIdx;

        // print_r($strWord);
        // echo "<br>";

        // print_r($strDevice);
        // echo "<br>";

        // print_r($strApplyIdx);
        // echo "<br>";
        //exit;

        $boolSuccess = true;

        if (!$session->has('idx') || $session->get('idx') != $iMemIdx || $strPostCase != 'mic_file' || !$strWord || !$strDevice || !$strApplyIdx) {
            $boolSuccess = false;
            return $this->respond([
                'status'   => 400,
                'code'     => [
                    'stat' => 'fail',
                    'token' => csrf_hash()
                ],
                'messages' => '잘못된 접근 입니다.!!!!!!!!!!!!!!!!!!',
            ], 400);
        }
        $uploadPath = '/writable/uploads/audioCheck/' . date("Ymd");
        $result = $micFile['file']->move($uploadPath, $strFileName);

        return $this->respond([
            'status'   => 200,
            'code'     => [
                'stat' => 'fail',
                'token' => csrf_hash()
            ],
            'messages' => '정상적인',
            'micFile' => $micFile['file'],
        ], 200);

        

        if ($boolSuccess) {
            if ($micFile['file']->getName() != "" && $micFile['file']->getSize() != 0) {
                if (!$micFile['FILE']->hasMove()) {
                    $uploadPath = WRITEPATH . '/uploads/audioCheck/' . date("Ymd");
                    $result = $micFile['file']->move($uploadPath, $strFileName);

                    //return; 

                    if ($result) {
                        $file_dir = WRITEPATH . $uploadPath . $strFileName . ".wav";
                        $result_dir = WRITEPATH . $uploadPath . $strFileName . ".flac";



                        try {
                            $ffmpeg = FFMpeg::create([
                                'ffmpeg.binaries' => '/usr/bin/ffmpeg',
                                'ffprobe.binaries' => '/usr/bin/ffprobe',
                                'timeout' => 3600,
                                'ffmpeg.threads' => 12
                            ]);



                            $video = $ffmpeg->open($file_dir);
                            $audio_format = new Flac();
                            $video->save($audio_format, $result_dir);
                            //$gs_url = upload_object("audio_check", $strFileName . '.flac', $result_dir);



                            $storage = new StorageClient();
                            $file = fopen($result_dir, 'r');
                            $bucket = $storage->bucket('audio_check');
                            $object = $bucket->upload($file, [
                                'name' => $strFileName . '.flac'
                            ]);
                            $gs_uri = "gs://audio_check/" . $strFileName . '.flac';
                            // return $gs_uri;



                            $this->aResponse = [
                                'status' => 200,
                                'code' => [
                                    'stat' => 'success',
                                    'token' => csrf_hash()
                                ],
                                'messages' => '저장하였습니다.',
                            ];
                        } catch (\Exception $e) {
                            //echo json_encode(array("result" => 503, "message" => "파일 변환을 실패하였습니다."));
                        }
                    }
                }
            }
        }
        // $result = $this->masterDB->table('iv_file')
        //     ->set([
        //         'file_type' => 'A',
        //         'file_org_name' => $strFileOrgName,
        //         'file_save_name' => $strFileSaveName,
        //         'file_size' => $strFileSize,
        //     ])
        //     ->set(['file_reg_date' => 'NOW()'], '', false)
        //     ->set(['file_mod_date' => 'NOW()'], '', false)
        //     ->insert();

        // if ($result) {
        //     $this->aResponse = [
        //         'status'   => 200,
        //         'code'     => [
        //             'stat' => 'success',
        //             'token' => csrf_hash()
        //         ],
        //         'fileIdx' => $this->masterDB->insertID(),
        //         'messages' => '저장하였습니다.',
        //     ];
        // } else {
        //     $this->aResponse = [
        //         'status'   => 500,
        //         'code'     => [
        //             'stat' => 'fail',
        //             'token' => csrf_hash()
        //         ],
        //         'messages' => '서버에 내부 오류가 발생했습니다. 요청을 다시 시도하세요.',
        //     ];
        // }
        // $this->aResponse = [
        //     'status'   => 500,
        //     'code'     => [
        //         'stat' => 'fail',
        //         'token' => csrf_hash()
        //     ],
        //     'messages' => '서버에 내부 오류가 발생했습니다. 요청을 다시 시도하세요.',
        // ];
        return $this->respond($this->aResponse, $this->aResponse['status']);
    }

    public function show($id = null)
    {
    }

    public function update($id = null)
    {
    }

    public function delete(int $idx)
    {
    }
}
