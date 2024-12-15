<?php

namespace App\Models;

use \Config\Services;
use App\Models\FileModel;

class File extends \CodeIgniter\Controller
{
    protected $encrypter;
    private $savePath = null;
    private $urlPath = null;
    private $fileNm = null;
    private $file = null;
    public function __construct($file = null)
    {
        $this->encrypter = Services::encrypter();
        $this->file = $file;
    }

    public function clear()
    {
        $this->savePath = null;
        $this->urlPath = null;
        $this->fileNm = null;
        $this->file = null;
        return $this;
    }

    public function resetFile($file)
    {
        $this->file = $file;
        $this->fileNm = null;
        return $this;
    }

    public function pathExplode($path)
    {
        $path = explode('/', urldecode($path));
        return array_filter($path);
    }

    public function download()
    {
        helper('url');
        $this->request = Services::request();
        $srtFrom = $this->request->getGet('from');
        $fileIdx = $this->encrypter->decrypt(base64url_decode($srtFrom));
        if(!is_numeric($fileIdx)){
            return false;
        }
        
        $fileModel = new FileModel();
        $aRow = $fileModel->where(['idx'=>$fileIdx])->first();
        if($aRow){
            $srtSaveUrl = $aRow['file_save_name'];
            $strFileName = $aRow['file_org_name'];
            $realUrl = $this->pathExplode($srtSaveUrl);
            $srtTo = end($realUrl);
            $realUrl = implode('/', $realUrl);
            $strOriUrl = WRITEPATH . $realUrl;
            $headers = $this->getHeaderInfo(urldecode($srtTo));

            $ContentType = \Config\Mimes::guessTypeFromExtension(substr(strrchr($srtTo, '.'), 1));
            header('Pragma: ' . $headers['header_pragma']);
            header('Expires: 0');
            header('Cache-Control: ' . $headers['header_cachecontrol']);
            header("Cache-Control: private", false);
            header('Content-Type: ' . $ContentType);
            header('Content-Disposition: attachment; filename="' . $strFileName . '"');
            header('Content-Transfer-Encoding: binary');
            ob_clean();
            flush();
            readfile($strOriUrl);
            exit;
        }
        return false;
    }
    public function getHeaderInfo($name)
    {
        $ie = isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false);
        $edge = isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Edge') !== false);
        if ($edge) {
            $filename = rawurlencode($name);
            $filename = preg_replace('/\./', '%2e', $filename, substr_count($filename, '.') - 1);
            $header_cachecontrol = 'private, no-transform, no-store, must-revalidate';
            $header_pragma = 'no-cache';
        } else {
            if ($ie) {
                $filename = iconv('utf-8', 'euc-kr', $name);
                $header_cachecontrol = 'must-revalidate, post-check=0, pre-check=0';
                $header_pragma = 'public';
            } else {
                $filename = $name;
                $header_cachecontrol = 'private, no-transform, no-store, must-revalidate';
                $header_pragma = 'no-cache';
            }
        }
        return ['filename' => $filename, 'header_cachecontrol' => $header_cachecontrol, 'header_pragma' => $header_pragma];
    }
}
