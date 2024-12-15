<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DatabaseInterview;

class KoreaAreaModel extends Model
{
    protected $table = ''; // 데이터베이스 테이블을 지정

    public function __construct()
    {
        parent::__construct();
        $code = 'iv_korea_area';
        $this->table = $code;
        $this->fields = DatabaseInterview::$code();
        if (is_array($this->fields)) {
            foreach ($this->fields as $key => $row) {
                if (isset($row['auto_increment']) && $row['auto_increment'] === true) {
                    $this->primaryKey = $key;
                } else if (!in_array($key, ['mem_reg_date', 'mem_mod_date', 'mem_del_date'])) {
                    $this->allowedFields[] = $key;
                }
            }
        }
    }

    public function save($data): bool
    {
        // $masterDB를 사용해 주세요.
        return false;
    }

    public function getKoreaArea($type = null): array
    {
        $aResult = [];
        $this->where('delyn', 'N');
        if ($type == 'interest') {
            $this->where('area_depth_2', NULL);
            $this->where('area_depth_3', NULL);
        }
        $aResult = $this->findAll();
        return $aResult ?? [];
    }

    public function getCompanyIdx(array $aAreaIdx): array
    {
        $aResult = [];
        if (!$aAreaIdx) {
            return $aResult;
        }

        $aResult = $this
            ->where('mem_rec_type', 'R')
            ->whereIn('kor_area_idx', $aAreaIdx)
            ->findColumn('rec_idx');
        return $aResult ?? [-1];
    }
}
