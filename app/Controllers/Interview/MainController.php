<?php

namespace App\Controllers\Interview;

use App\Controllers\Interview\WwwController;
use Config\Services;

class MainController extends WwwController
{
    public function index()
    {
        $this->main();
    }

    public function main()
    {
        // data init
        $this->commonData();

        $this->header();
        echo view("www/main", $this->aData);
        $this->footer();
    }

    private function _email()
    {
        $email = Services::email();
        $email->clear();
        $email->setTo('mseon@naver.com');
        $email->setFrom($this->globalvar->getEmailFromMail(), $this->globalvar->getEmailFromName());
        $email->setSubject('제목');
        $email->setMessage('<p>내용</p>');
        $result = $email->send();
        exit;
    }
}
