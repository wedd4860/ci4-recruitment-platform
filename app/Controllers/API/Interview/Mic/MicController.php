<?php

namespace App\Controllers\API\Interview\Mic;

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
                'code'     => 'fail',
                'messages' => '헤더 값 형식이 올바른지 확인하세요.',
            ], 403);
        }

        $strWord = $this->request->getPost('word');
        $device = $this->request->getPost('device');
        $strFileName = $this->request->getPost('fileName');
        $index = $this->request->getPost('index');
        $micFile = $this->request->getFile('micFile');

        $session = session();
        $boolSuccess = true;
        if (!$session->has('idx') || !$strWord || !$device || !$strFileName || !$micFile) {
            $boolSuccess = false;
        }

        if ($boolSuccess) {
            if ($micFile['file']->getName() != "" && $micFile['file']->getSize() != 0) {
                if (!$micFile['file']->hasMoved()) {
                    $uploadPath = WRITEPATH . '/uploads/audioCheck/' . date("Ymd");
                    $result = $micFile['file']->move($uploadPath, $strFileName);

                    if ($result) {
                        $file_dir = WRITEPATH . $uploadPath . $strFileName . ".wav";
                        $result_dir = WRITEPATH . $uploadPath . $strFileName . ".flac";

                        try {
                            $ffmpeg = FFMpeg::create([
                                'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
                                'ffprobe.binaries' => '/usr/bin/ffprobe',
                                'timeout'          => 3600,
                                'ffmpeg.threads'   => 12
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
                            $gs_uri = "gs://audio_check/".$strFileName . '.flac';
                            // return $gs_uri;

                            $this->aResponse = [
                                'status'   => 200,
                                'code'     => [
                                    'stat' => 'success',
                                    'token' => csrf_hash()
                                ],
                                'messages' => '저장하였습니다.',
                            ];
                        } catch (\Exception $e) {
                            echo json_encode(array("result" => 503, "message" => "파일 변환을 실패하였습니다."));
                        }

                        function recogAudio($url, $rate)
                        {
                            global $sample_rate_list, $file_dir, $result_dir, $word, $sample_rate_index, $strCode;
                            putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $_SERVER["DOCUMENT_ROOT"] . '/crontab/google-api-key/interview-310505-08ef86b8f61b.json');
                            $audio = (new RecognitionAudio())->setUri($url);
                            $config = new RecognitionConfig([
                                'encoding' => AudioEncoding::FLAC,
                                'sample_rate_hertz' => $rate,
                                'language_code' => 'ko-KR'
                            ]);

                            $client = new SpeechClient();
                            try {
                                $response = $client->recognize($config, $audio);
                            } catch (\Exception $e) {
                                $sample_rate_index++;
                                recogAudio($url, $sample_rate_list[$sample_rate_index]);
                                return;
                            }

                            try {
                                $transcript = "";
                                foreach ($response->getResults() as $result) {
                                    $alternatives = $result->getAlternatives();
                                    $mostLikely = $alternatives;
                                    $transcript .= $mostLikely->getTranscript();
                                }
                            } catch (\Exception $e) {
                                echo json_encode(array("result" => 503, "message" => "음성 인식을 실패하였습니다."));
                            } finally {
                                $client->close();
                                unlink($file_dir);
                                unlink($result_dir);
                            }

                            $set_trans = preg_replace("/\s+/", "", $transcript); //공백제거
                            $sentence = "안녕하세요" . $word . "입니다";
                            if ($sentence == $set_trans) {
                                echo json_encode(["result" => 200, "code" => $strCode]);
                            } else {
                                echo json_encode(array("result" => 999, "message" => "음성 인식을 실패하였습니다.<br>좀 더 큰 목소리로 읽어주세요.", "speech_to_text" => $transcript));
                            }
                        } //function recogAudio

                        $sample_rate_list = array(8000, 12000, 16000, 22050, 24000, 25050, 44100, 48000);
                        $sample_rate_index = 0;
                        recogAudio($gs_url, $sample_rate_list[$sample_rate_index]);
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
}
