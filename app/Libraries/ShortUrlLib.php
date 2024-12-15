<?php

namespace App\Libraries;

use Config\Services;
use Config\Database;
use App\Libraries\GlobalvarLib;

class ShortUrlLib
{
    public function __construct()
    {
    }

    public function setShortUrl(string $url, string $addStr = ''): string
    {
        if(!$url){
            return '';
        }
        $strShortenUrl = $this->makeUrlCut(8) . $addStr;
        $this->masterDB = Database::connect('master');
        $this->masterDB->table('set_shorten_url')
            ->set([
                'shorten_url' => $strShortenUrl,
                'shorten_base_url' => $url,
            ])
            ->set(['shorten_reg_date' => 'NOW()'], '', false)
            ->insert();
        $this->masterDB->close();
        return $strShortenUrl;
    }

    private function makeUrlCut(int $length = 8): string
    {
        $characters = "0123456789";
        $characters .= "abcdefghijklmnopqrstuvwxyz";
        $characters .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $characters = str_shuffle($characters);
        $randomString = "";
        $loop = $length;
        while ($loop--) {
            $randomString .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}
