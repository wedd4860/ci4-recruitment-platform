<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use Config\Database;
//에러코드 참고 사이트
//https://docs.microsoft.com/ko-kr/rest/api/storageservices/common-rest-api-error-codes
class APIController extends BaseController
{
    use ResponseTrait;

    public $masterDB;
    protected $aData = [];

    public function __construct()
    {
        $this->masterDB = Database::connect('master');
    }
    
    public function commonData()
    {
        // data init
        $aCommon = [];
        $aCommon['data'] = $this->viewData;
        $aCommon['data']['page'] = $this->request->getUri()->getPath();

        $this->aData = $aCommon;
    }

    public function __destruct()
    {
        $this->masterDB->close();
    }
}
