<?php

namespace App\Controllers\Both;

use Config\Database;
use App\Controllers\BaseController;
use App\Libraries\ShortUrlLib;
use App\Models\ShortenUrlModel;

class ShortenUrlController extends BaseController
{
    public $masterDB;
    private $backUrl = '/';
    public function __construct()
    {
        $this->masterDB = Database::connect('master');
    }

    public function index(string $code)
    {
        if (!$code) {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrl);
            exit;
        }
        $shortenUrlModel = new ShortenUrlModel();
        $result = $shortenUrlModel->getSortUrl($code);

        if (!$result || !$result['shorten_base_url']) {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrl);
            exit;
        }
        return redirect()->to($result['shorten_base_url']);
    }

    public function __destruct()
    {
        $this->masterDB->close();
    }
}
