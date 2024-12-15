<?php

namespace App\Libraries;

use Config\Services;
use App\Libraries\GlobalvarLib;

class SendLib
{
    public function sendSMS(string $tel, string $msg)
    {
        $strMsg = $msg ?? '';
        $strToTel = str_replace("-", "", $tel);
        if (!$strMsg || !$strToTel) {
            return [];
        }
        $globalvar = new GlobalvarLib();
        $apikey = $globalvar->getSmsAPIKey();
        $strFromTel = "18554549";
        $strUserkey = time() . rand(100, 999);
        $strMsglen = mb_strlen($strMsg, "euc-kr");

        if (substr($tel, 0, 3) === "010") { //010으로 시작하면 sms
            $strCurl = "https://api.wideshot.co.kr/api/v1/message/sms";
        } else { //isms
            $strCurl = "https://api.wideshot.co.kr/api/v1/message/isms";
        }

        if ($strMsglen >= 85) { // lms
            $strCurl = "https://api.wideshot.co.kr/api/v1/message/lms";
        }

        $postData = [
            'callback' => $strFromTel,
            'contents' => $strMsg,
            'receiverTelNo' => $strToTel,
            'title' => "하이버프",
            'userKey' => $strUserkey,
            'sejongApiKey' => $apikey,
        ];
        $client = Services::curlrequest();
        $response = $client->request('post',  $strCurl, [
            'headers' => [
                'sejongApiKey' => $apikey
            ],
            'form_params' => $postData
        ]);
        //https://forum.codeigniter.com/thread-79154.html
        return $response->getStatusCode();
    }

    public function telegramSend(string $msg, string $botType = null)
    {
        $addr = $_SERVER["REMOTE_ADDR"];
        $globalvar = new GlobalvarLib();
        if ($globalvar->getServerHost() == 'test') {
            //test
            $strCurl = "https://api.telegram.org/bot1626097026:AAHsx6T0sUYtZtJyGfb4XA-_le8BKqCauxA/sendmessage?chat_id=1618274214&text="; //테스트용 텔레그램은 한방에 다 보내기
        } else {
            //real
            if ($botType == "HB") {
                $strCurl = "https://api.telegram.org/bot717979983:AAGhwOUPdtx-BxbD05F3iofJJW9K5Ktlxqk/sendmessage?chat_id=-1001426729381&text="; //HB_PB
            } else if ($botType == "HB_TEST") {
                $strCurl = "https://api.telegram.org/bot709119014:AAH62cjLzTUTAY7-cn6dxobrTlsLkuMlDzE/sendmessage?chat_id=-312690750&text="; //HB_PB_TEST
            } else if ($botType == "inputmoneycs") {
                $strCurl = "https://api.telegram.org/bot1064502462:AAEfITT34pWRc1SG2gTns4H_S6OK6Pe3X0EU/sendmessage?chat_id=-366137723&text="; //bluevisor(입출금관련내용)
            } else if ($botType == "DEV") {
                $strCurl = "https://api.telegram.org/bot5078097512:AAFlbpDsfxa0VertR3_SDkzP3golEVmDieQ/sendMessage?chat_id=-1001765236622&parse_mode=HTML&text=";
            } else if ($botType == "company") {
                $strCurl = 'https://api.telegram.org/bot5090857619:AAEPEvPD9MxC0-_GTAwa7AAK7j-ZSSieIvc/sendmessage?chat_id=-693228471&parse_mode=HTML&text=';
            } else if ($botType == "LABELING") {
                $strCurl = "https://api.telegram.org/bot2019878841:AAHeJCIKWs0UTS98zB7ab4XVa8ujnaBx1eg/sendmessage?chat_id=-1001689923646&parse_mode=HTML&text=";
            } else if ($botType == "attendance") {
                $strCurl = "https://api.telegram.org/bot1831356147:AAEVbfsl7tnSBmMaadRsqFBeQleioxnELBc/sendmessage?chat_id=-549708316&parse_mode=HTML&text=";
            } else {
                return 404;
            }
        }

        $strMsg = urlencode($msg . "\n\n접속정보 : " . $addr . "\n시간 : " . date("Y-m-d H:i:s"));
        $client = Services::curlrequest();
        $response = $client->request('get', $strCurl . $strMsg, [
            'curl' => [
                CURLOPT_RETURNTRANSFER => 1,
            ]
        ]);
        var_dump($response->getStatusCode());
    }
}
