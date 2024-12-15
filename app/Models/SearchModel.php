<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DatabaseInterview;

class SearchModel extends Model
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
        if (is_array($data)) {
        }
        return parent::save($data);
    }

    public function getRecruit(string $keyword, string $sort) // object or array
    {
        $aResult = [];
        if(!$keyword || !$sort){
            return $aResult;
        }

        $aResult = $this
            ->join('iv_company', 'iv_recruit.com_idx = iv_company.idx', 'inner')
            ->join('iv_job_category', 'iv_recruit.job_idx = iv_job_category.idx', 'inner')
            ->where('iv_recruit.delyn', 'N')

            ->groupStart() // 키워드
            ->like('rec_title', $keyword, 'both')
            ->orLike('com_name', $keyword, 'both')
            ->orLike('job_depth_text', $keyword, 'both')
            ->groupEnd()

            ->orderBy($sort, 'ASC');
        return $aResult ?? [];
    }

    public function getRecruitDetail(string $keyword, string $sort, array $deepSearch)// object or array
    {
        $aResult = [];
        if(!$sort || !$deepSearch){
            return $aResult;
        }

        $aResult = $this
            ->join('iv_company', 'iv_recruit.com_idx = iv_company.idx', 'inner')
            ->join('iv_job_category', 'iv_recruit.job_idx = iv_job_category.idx', 'inner');

        $aResult = $this
            ->where('iv_recruit.delyn', 'N')
            ->groupStart() // 키워드
            ->like('rec_title', $keyword, 'both')
            ->orLike('com_name', $keyword, 'both')
            ->orLike('job_depth_text', $keyword, 'both')
            ->groupEnd();

        foreach ($deepSearch as $key => $val) {
            if ($key === 'rec_work_type' || $key === 'rec_work_day') {
                $aResult = $this
                    ->groupStart();
                foreach ($deepSearch[$key] as $k => $v) {
                    $aResult = $this
                        ->orLike($key, $v);
                }
                $aResult = $this
                    ->groupEnd();
            } else if ($key === 'iv_recruit.idx1' || $key === 'iv_recruit.idx2') {
                $aResult = $this
                    ->groupStart()
                    ->whereIn('iv_recruit.idx', $deepSearch[$key])
                    ->groupEnd();
            } else {
                if (is_array($deepSearch[$key])) {
                    $aResult = $this
                        ->groupStart()
                        ->whereIn($key, $deepSearch[$key])
                        ->groupEnd();
                } else {
                    $aResult = $this
                        ->groupStart()
                        ->where($key, $deepSearch[$key])
                        ->groupEnd();
                }
            }
        }

        $aResult = $this
            ->orderBy($sort, 'ASC');
        return $aResult ?? [];
    }

    public function getCompany(string $keyword)// object or array
    {
        $aResult = [];
        if(!$keyword){
            return $aResult;
        }

        $aResult = $this
            ->where('delyn', 'N')
            ->like('com_name', $keyword, 'both')
            ->orLike('com_industry', $keyword, 'both');
        return $aResult ?? [];
    }
}
