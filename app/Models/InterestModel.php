<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DatabaseInterview;

class InterestModel extends Model
{
    protected $table = ''; // 데이터베이스 테이블을 지정

    public function __construct(string $table)
    {
        parent::__construct();
        $this->table = $table;
        $this->fields = DatabaseInterview::$table();
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

    public function getJobCategory(): array
    {
        $aResult = [];
        $aResult = $this
            ->select(['idx', 'job_depth_text'])
            ->where([
                'delyn' => 'N',
                'job_depth_2' => NULL
            ])
            ->findAll();
        return $aResult;
    }

    public function getKoreaArea(): array
    {
        $aResult = [];
        $aResult = $this
            ->select(['idx', 'area_depth_text_1'])
            ->where([
                'delyn' => 'N',
                'area_depth_2' => NULL
            ])
            ->findAll();
        return $aResult;
    }
}
