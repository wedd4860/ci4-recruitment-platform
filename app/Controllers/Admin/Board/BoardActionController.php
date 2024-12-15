<?php

namespace App\Controllers\Admin\Board;

use App\Models\BoardModel;
use Config\Services;
use App\Libraries\EncryptLib;

use App\Controllers\Admin\AdminController;

class BoardActionController extends AdminController
{
    private $backUrlList = '/prime/board/list';

    public function index($code)
    {
        if ($code == 'set') {
            $this->setWriteAction($code);
        } else {
            $this->writeAction($code);
        }
    }

    public function setWriteAction($code)
    {
        if ($code != 'set') {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
            exit;
        }
        $iIdx = $this->request->getPost('idx');
        $strMod = $this->request->getPost('stat');
        $strTable = $this->request->getPost('table');
        $aBdListName = $this->request->getPost('board_list_name');
        $aBdListType = $this->request->getPost('board_list_type');
        $aBdListSkin = $this->request->getPost('board_list_skin');
        $aBdListBasic = $this->request->getPost('board_list_basic');
        $aBdListAuth = $this->request->getPost('board_list_auth');
        $aBdListList = $this->request->getPost('board_list_list');
        $aBdListOutline = $this->request->getPost('board_list_outline');
        $aBdListTotal = $this->request->getPost('board_list_total');
        $strBackUrl = $this->request->getPost('backUrl');

        if ($strTable != 'board_list' || !in_array($strMod, ['write']) || !$strBackUrl) {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
            exit;
        }

        $this->commonData();
        switch ($strMod) {
            case 'write':
                // $aConverter = new Converter($aBdListItem);
                // [{},{}] 형태일경우 arrToRow
                // $aBdListItem = $aConverter->arrToRow();
                break;
            case 'add':
                break;
        }

        $saveData = [
            'board_list_name' => $aBdListName,
            'board_list_type' => $aBdListType,
            'board_list_skin' => $aBdListSkin,
            'board_list_basic' => json_encode($aBdListBasic),
            'board_list_auth' => json_encode($aBdListAuth),
            'board_list_list' => json_encode($aBdListList),
            'board_list_outline' => json_encode($aBdListOutline),
            'board_list_Total' => $aBdListTotal
        ];

        $readyDB = $this->masterDB->table('iv_board_list')
            ->set($saveData)
            ->set(['board_list_mod_date' => 'NOW()'], '', false);
        if ($iIdx) {
            $result = $readyDB->where('idx', $iIdx)
                ->update();
        } else {
            $result = $readyDB
                ->set(['board_list_reg_date' => 'NOW()'], '', false)
                ->insert();
        }

        if ($result) {
            alert_url($this->globalvar->aMsg['success1'], $strBackUrl != '' ? $strBackUrl : $this->backUrlList);
            exit;
        } else {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
            exit;
        }
    }

    public function writeAction(string $code)
    {
        $strPostCase = $this->request->getPost('postCase');
        $strParent = $this->request->getPost('parentYN');
        $iIdx = $this->request->getPost('idx');
        $strBackUrl = $this->request->getPost('backUrl') ?? 'reload';
        $strNotice = $this->request->getPost('bd_notice');
        $strTitle = $this->request->getPost('bd_title');
        $strContent = $this->request->getPost('bd_content');

        if (!in_array($strNotice, ['Y', 'N'])  || !in_array($strParent, ['Y', 'N']) || !$strTitle || !$strContent || $strPostCase != 'board_write') {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
            exit;
        }
        //선택사항
        $strSecret = $this->request->getPost('bd_secret');
        $strPassword = $this->request->getPost('bd_password');
        $strOldFileDataThumb = $this->request->getPost('old_file_data_thumb');
        $strOldFileData1 = $this->request->getPost('old_file_data_1');
        $strOldFileData2 = $this->request->getPost('old_file_data_2');
        $strOldFileData3 = $this->request->getPost('old_file_data_3');
        $strOldFileData4 = $this->request->getPost('old_file_data_4');
        $strOldFileData5 = $this->request->getPost('old_file_data_5');
        if ($strSecret != 'Y') {
            $strPassword = '';
        } else {
            if ($strPassword != '') {
                //비밀글 모드가 Y이면서 패스워드를 입력했을 경우만 등록
                $encryptLib = new EncryptLib();
                $strPassword = $encryptLib->makePassword($strPassword);
            }
        }

        $saveData = [];
        switch ($strPostCase) {
            case 'board_write':
                $saveData = [
                    'bd_notice' => $strNotice,
                    'bd_ip' => $this->request->getIPAddress(),
                    'bd_title' => $strTitle,
                    'bd_content' => $strContent,
                    'bd_secret' => 'N',
                    'file_idx_thumb' => $strOldFileDataThumb,
                    'file_idx_data_1' => $strOldFileData1,
                    'file_idx_data_2' => $strOldFileData2,
                    'file_idx_data_3' => $strOldFileData3,
                    'file_idx_data_4' => $strOldFileData4,
                    'file_idx_data_5' => $strOldFileData5
                ];
                $saveData['file_idx_thumb'] = '';
                $boardModelList = new BoardModel('iv_board_list', 'list');
                $aRowBdList = $boardModelList->where(['board_list_id' => $code])->first();
                $aThumb = [];
                $aThumb['width'] = $aRowBdList['board_list_basic']['thum_width'] == '' ? 600 : $aRowBdList['board_list_basic']['thum_width'];
                $aThumb['height'] = $aRowBdList['board_list_basic']['thum_height'] == '' ? 600 : $aRowBdList['board_list_basic']['thum_height'];

                $aCode = ['code' => $code, 'type' => 'board'];
                if ($strSecret) {
                    $saveData['bd_secret'] = $strSecret;
                    $saveData['bd_password'] = $strPassword;
                }

                break;
        }
        //트랜잭션 start
        $this->masterDB->transBegin();

        //file
        switch ($strPostCase) {
            case 'board_write':
                //upload                
                // $fileModel = new FileModel();
                $aFileDataThumb = $this->request->getFile('file_data_thumb');
                if ($aFileDataThumb->isValid()) {
                    $aFileResult =  $this->upload($code, $strBackUrl, $aThumb, $aFileDataThumb);
                    $this->masterDB->table('iv_file')
                        ->set([
                            'file_type' => 'B',
                            'file_org_name' => $aFileResult['file_org_name'],
                            'file_save_name' => $aFileResult['file_save_name'],
                            'file_size' => $aFileResult['file_size'],
                        ])
                        ->set(['file_mod_date' => 'NOW()'], '', false)
                        ->set(['file_reg_date' => 'NOW()'], '', false)
                        ->insert();
                    $saveData['file_idx_thumb'] = $this->masterDB->insertID();
                }
                $aFileData1 = $this->request->getFile('file_data_1');
                if ($aFileData1->isValid()) {
                    $aFileResult =  $this->upload($code, $strBackUrl, $aThumb, $aFileData1);
                    $this->masterDB->table('iv_file')
                        ->set([
                            'file_type' => 'B',
                            'file_org_name' => $aFileResult['file_org_name'],
                            'file_save_name' => $aFileResult['file_save_name'],
                            'file_size' => $aFileResult['file_size'],
                        ])
                        ->set(['file_mod_date' => 'NOW()'], '', false)
                        ->set(['file_reg_date' => 'NOW()'], '', false)
                        ->insert();
                    $saveData['file_idx_data_1'] = $this->masterDB->insertID();
                }
                $aFileData2 = $this->request->getFile('file_data_2');
                if ($aFileData2->isValid()) {
                    $aFileResult =  $this->upload($code, $strBackUrl, $aThumb, $aFileData2);
                    $this->masterDB->table('iv_file')
                        ->set([
                            'file_type' => 'B',
                            'file_org_name' => $aFileResult['file_org_name'],
                            'file_save_name' => $aFileResult['file_save_name'],
                            'file_size' => $aFileResult['file_size'],
                        ])
                        ->set(['file_mod_date' => 'NOW()'], '', false)
                        ->set(['file_reg_date' => 'NOW()'], '', false)
                        ->insert();
                    $saveData['file_idx_data_2'] = $this->masterDB->insertID();
                }
                $aFileData3 = $this->request->getFile('file_data_3');
                if ($aFileData3->isValid()) {
                    $aFileResult =  $this->upload($code, $strBackUrl, $aThumb, $aFileData3);
                    $this->masterDB->table('iv_file')
                        ->set([
                            'file_type' => 'B',
                            'file_org_name' => $aFileResult['file_org_name'],
                            'file_save_name' => $aFileResult['file_save_name'],
                            'file_size' => $aFileResult['file_size'],
                        ])
                        ->set(['file_mod_date' => 'NOW()'], '', false)
                        ->set(['file_reg_date' => 'NOW()'], '', false)
                        ->insert();
                    $saveData['file_idx_data_3'] = $this->masterDB->insertID();
                }
                $aFileData4 = $this->request->getFile('file_data_4');
                if ($aFileData4->isValid()) {
                    $aFileResult =  $this->upload($code, $strBackUrl, $aThumb, $aFileData4);
                    $this->masterDB->table('iv_file')
                        ->set([
                            'file_type' => 'B',
                            'file_org_name' => $aFileResult['file_org_name'],
                            'file_save_name' => $aFileResult['file_save_name'],
                            'file_size' => $aFileResult['file_size'],
                        ])
                        ->set(['file_mod_date' => 'NOW()'], '', false)
                        ->set(['file_reg_date' => 'NOW()'], '', false)
                        ->insert();
                    $saveData['file_idx_data_4'] = $this->masterDB->insertID();
                }
                $aFileData5 = $this->request->getFile('file_data_5');
                if ($aFileData5->isValid()) {
                    $aFileResult =  $this->upload($code, $strBackUrl, $aThumb, $aFileData5);
                    $this->masterDB->table('iv_file')
                        ->set([
                            'file_type' => 'B',
                            'file_org_name' => $aFileResult['file_org_name'],
                            'file_save_name' => $aFileResult['file_save_name'],
                            'file_size' => $aFileResult['file_size'],
                        ])
                        ->set(['file_mod_date' => 'NOW()'], '', false)
                        ->set(['file_reg_date' => 'NOW()'], '', false)
                        ->insert();
                    $saveData['file_idx_data_5'] = $this->masterDB->insertID();
                }
                break;
        }


        $readyDB = $this->masterDB->table('iv_board_' . $code)
            ->set($saveData)
            ->set(['bd_mod_date' => 'NOW()'], '', false);
        if ($iIdx) {
            $result = $readyDB
                ->where('idx', $iIdx)
                ->update();
        } else {
            $result = $readyDB
                ->set(['bd_reg_date' => 'NOW()'], '', false)
                ->insert();
        }
        if (isset($aCode['code'])) {
            // 게시판일 경우만 사용
            if (!$iIdx) {
                $iInsertId = $this->masterDB->insertID();
            }
            if ((!$iIdx && $strParent == 'N') && $result) {
                // idx, parent N 이면 신규 글쓰기
                $this->masterDB->table('iv_board_' . $code)
                    ->set(['bd_family' => $iInsertId])
                    ->where('idx', $iInsertId)
                    ->update();
                if ($aCode['type'] == 'board') {
                    $this->masterDB->table('iv_board_list')
                        ->set('board_list_total', 'board_list_total + 1', false)
                        ->where(['board_list_id' => $aCode['code']])
                        ->update();
                }
            } else if ((!$iIdx && $strParent == 'Y') && $result) {
                // // idx없고 parent Y 이면 답변글
                // $sorts = Board::getSorts($strParent, $code['code'], $code['type']);
                // $boardModel->set(['bd_family' => $sorts['bd_family'], 'bd_sort' => $sorts['sorts'], 'bd_depth' => $sorts['depth']])
                //     ->where('idx', $iInsertId)
                //     ->update();
                // if ($aCode['type'] == 'board') {
                //     $this->masterDB->table('iv_board_list')->set(['board_list_total' => '(board_list_total+1)'])->where(['board_list_id' => $aCode['code']])->update();
                // }
            }
        }

        // 트랜잭션 end
        if ($this->masterDB->transStatus() === false) {
            $this->masterDB->transRollback();
        } else {
            $this->masterDB->transCommit();
        }


        if ($this->masterDB->transStatus()) {
            //transCommit
            if (!$iIdx) {
                alert_url($this->globalvar->aMsg['success1'], $strBackUrl != '' ? $strBackUrl : $this->backUrlList);
            } else {
                alert_back($this->globalvar->aMsg['success1'], $strBackUrl != '' ? $strBackUrl : $this->backUrlList);
            }
            exit;
        } else {
            //transRollback
            alert_url($this->globalvar->aMsg['error3'], $strBackUrl != '' ? $strBackUrl : $this->backUrlList);
            exit;
        }
    }

    public function commentAction(string $code)
    {
        $iIdx = $this->request->getPost('idx');
        $strPostCase = $this->request->getPost('postCase');
        $iMemIdx = $this->request->getPost('mem_idx');
        $strCmtFamily = $this->request->getPost('cmt_family');
        $iDepth = $this->request->getPost('cmt_depth');
        $iBdIdx = $this->request->getPost('cmt_bd_idx');
        $strCmtComment = $this->request->getPost('cmt_comment');
        $strBackUrl = $this->request->getPost('backUrl') ?? 'reload';

        if (!in_array($strPostCase, ['comment_write'])) {
            alert_url($this->globalvar->aMsg['error1'], $strBackUrl != '' ? $strBackUrl : $this->backUrlList);
            exit;
        }

        $saveData = [];
        switch ($strPostCase) {
            case 'comment_write':
                $saveData = [
                    'mem_idx' => $iMemIdx,
                    'cmt_family' => $strCmtFamily,
                    'cmt_depth' => $iDepth,
                    'cmt_bd_idx' => $iBdIdx,
                    'cmt_ip' => $this->request->getIPAddress(),
                    'cmt_comment' => $strCmtComment
                ];
                break;
        }
        $readyDB = $this->masterDB->table('iv_comment_' . $code)
            ->set($saveData)
            ->set(['cmt_mod_date' => 'NOW()'], '', false);
        if ($iIdx) {
            $result = $readyDB
                ->where('idx', $iIdx)
                ->update();
        } else {
            $result = $readyDB
                ->set(['cmt_reg_date' => 'NOW()'], '', false)
                ->insert();
        }
        if ($result) {
            alert_url($this->globalvar->aMsg['success1'], $strBackUrl != '' ? $strBackUrl : $this->backUrlList);
            exit;
        } else {
            alert_url($this->globalvar->aMsg['error3'], $strBackUrl != '' ? $strBackUrl : $this->backUrlList);
            exit;
        }
    }

    public function commentDel(string $code, string $idx)
    {
        if (!$code || !$idx) {
            alert_url($this->globalvar->aMsg['error1'], $this->backUrlList);
            exit;
        }

        $result = $this->masterDB->table('iv_comment_' . $code)
            ->set(['delyn' => 'Y'])
            ->set(['cmt_del_date' => 'NOW()'], '', false)
            ->where('idx', $idx)
            ->update();

        if ($result) {
            alert_back($this->globalvar->aMsg['success2']);
            exit;
        } else {
            alert_url($this->globalvar->aMsg['error3'], $this->backUrlList);
            exit;
        }
    }

    private function upload(string $code, string $strBackUrl, array $aThumb, $file): array
    {
        $aResult = [];
        if (!$file->hasMoved()) {
            $iFilesize = $file->getSize();
            $uploadName = $file->getRandomName();
            $uploadPath = 'uploads/' . $code . '/' . date("Ymd");
            $originalName = $file->getClientName();
            $file->move(WRITEPATH . $uploadPath, $uploadName);
            $aResult = [
                'file_save_name' => '/' . $uploadPath . '/' . $uploadName,
                'file_org_name' => $originalName,
                'file_size' => $iFilesize
            ];
            if (in_array($file->getClientMimeType(), ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/bmp'])) {
                //이미지는 썸네일 제작
                try {
                    Services::image()
                        ->withFile(WRITEPATH . $uploadPath . '/' . $uploadName)
                        ->fit($aThumb['width'], $aThumb['height'], 'left')
                        ->save(WRITEPATH . $uploadPath . '/' . 'thumb_' . $uploadName);
                } catch (CodeIgniter\Images\ImageException $e) {
                    alert_url($this->globalvar->aMsg['error3'], $strBackUrl != '' ? $strBackUrl : $this->backUrlList);
                    exit;
                }
            }
        }
        return $aResult;
    }
}
