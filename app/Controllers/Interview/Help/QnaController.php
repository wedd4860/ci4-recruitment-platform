<?php

namespace App\Controllers\Interview\Help;

use App\Controllers\Interview\WwwController;

use App\Models\QnaModel;
use App\Models\FileModel;
use Config\Services;

class QnaController extends WwwController
{

    public function index()
    {
        $this->list();
    }

    public function list()
    {
        $this->commonData();
        $iMemberIdx = $this->aData['data']['session']['idx'];
        $qnaModel = new QnaModel();
        $qnaModel->where('delyn', 'N')
            ->where('mem_idx_m', $iMemberIdx)
            ->orderBy('idx', 'desc');

        $aList = $qnaModel->paginate(5, 'qna');

        $fileModel = new FileModel();
        foreach ($aList as $key => $val) {
            if (!$aList[$key]['file_idx_data_1'] = cache("file.save.name{$val['file_idx_data_1']}")) {
                $aList[$key]['file_idx_data_1'] = $fileModel->getFileSaveName($val['file_idx_data_1']);
                cache()->save("file.save.name{$val['file_idx_data_1']}", $aList[$key]['file_idx_data_1'], 600);
            }
            if (!$aList[$key]['file_idx_data_2'] = cache("file.save.name{$val['file_idx_data_2']}")) {
                $aList[$key]['file_idx_data_2'] = $fileModel->getFileSaveName($val['file_idx_data_2']);
                cache()->save("file.save.name{$val['file_idx_data_2']}", $aList[$key]['file_idx_data_2'], 600);
            }
            if (!$aList[$key]['file_idx_data_3'] = cache("file.save.name{$val['file_idx_data_3']}")) {
                $aList[$key]['file_idx_data_3'] = $fileModel->getFileSaveName($val['file_idx_data_3']);
                cache()->save("file.save.name{$val['file_idx_data_3']}", $aList[$key]['file_idx_data_3'], 600);
            }
            if (!$aList[$key]['file_idx_data_4'] = cache("file.save.name{$val['file_idx_data_4']}")) {
                $aList[$key]['file_idx_data_4'] = $fileModel->getFileSaveName($val['file_idx_data_4']);
                cache()->save("file.save.name{$val['file_idx_data_4']}", $aList[$key]['file_idx_data_4'], 600);
            }
            // $aList[$key]['file_idx_data_1'] = $fileModel->getFileSaveName($val['file_idx_data_1']);
            // $aList[$key]['file_idx_data_2'] = $fileModel->getFileSaveName($val['file_idx_data_2']);
            // $aList[$key]['file_idx_data_3'] = $fileModel->getFileSaveName($val['file_idx_data_3']);
            // $aList[$key]['file_idx_data_4'] = $fileModel->getFileSaveName($val['file_idx_data_4']);
        }

        $this->aData['data']['list'] = $aList;
        $this->aData['data']['total'] = $qnaModel->pager->getTotal('qna');
        $this->aData['data']['pager'] = $qnaModel->pager;

        // view
        $this->header();
        echo view('www/help/qna/list', $this->aData);
        $this->footer();
    }

    public function write()
    {
        $this->commonData();

        // view
        $this->header();
        echo view('www/help/qna/write', $this->aData);
        $this->footer();
    }

    public function writeAction()
    {
        $this->commonData();
        $iMemberIdx = $this->aData['data']['session']['idx'];

        if (!$iMemberIdx) {
            return redirect($this->globalvar->getlogin());
        }

        $strTitle = $this->request->getPost('title') ?? false;
        $strQuestion = $this->request->getPost('question') ?? false;

        if (!$strTitle || !$strQuestion) {
            alert_back($this->globalvar->aMsg['error1']);
            exit;
        }
        $this->masterDB->transBegin();

        $aFileData1 = $this->request->getFile('file1');
        if ($aFileData1->isValid()) {
            $aFileResult =  $this->upload('qna', $aFileData1);
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
        $aFileData2 = $this->request->getFile('file2');
        if ($aFileData2->isValid()) {
            $aFileResult =  $this->upload('qna', $aFileData2);
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
        $aFileData3 = $this->request->getFile('file3');
        if ($aFileData3->isValid()) {
            $aFileResult =  $this->upload('qna', $aFileData3);
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
        $aFileData4 = $this->request->getFile('file4');
        if ($aFileData4->isValid()) {
            $aFileResult =  $this->upload('qna', $aFileData4);
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

        $this->masterDB->table('iv_qna')
            ->set([
                'mem_idx_m' => $iMemberIdx,
                'qna_title' => $strTitle,
                'qna_question' => $strQuestion,
                'file_idx_data_1' => $saveData['file_idx_data_1'] ?? null,
                'file_idx_data_2' => $saveData['file_idx_data_2'] ?? null,
                'file_idx_data_3' => $saveData['file_idx_data_3'] ?? null,
                'file_idx_data_4' => $saveData['file_idx_data_4'] ?? null,
            ])
            ->set(['qna_mod_date' => 'NOW()'], '', false)
            ->set(['qna_reg_date' => 'NOW()'], '', false)
            ->insert();

        // 트랜잭션 end
        if ($this->masterDB->transStatus() === false) {
            $this->masterDB->transRollback();
            return alert_back($this->globalvar->aMsg['error3']);
        } else {
            $this->masterDB->transCommit();
            alert_url($this->globalvar->aMsg['success1'], '/help/qna');
            exit;
        }
    }

    private function upload(string $code, $file): array
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
            // if (in_array($file->getClientMimeType(), ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/bmp'])) {
            //     //이미지는 썸네일 제작
            //     try {
            //         // Services::image()
            //         //     ->withFile(WRITEPATH . $uploadPath . '/' . $uploadName)
            //         //     ->fit($aThumb['width'], $aThumb['height'], 'left')
            //         //     ->save(WRITEPATH . $uploadPath . '/' . 'thumb_' . $uploadName);
            //     } catch (CodeIgniter\Images\ImageException $e) {
            //         alert_back($this->globalvar->aMsg['error3']);
            //         exit;
            //     }
            // }
        }
        return $aResult;
    }
}
