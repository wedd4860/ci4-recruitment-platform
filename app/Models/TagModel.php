<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DatabaseInterview;

class TagModel extends Model
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

    public function getTag(): array
    {
        $aResult = $this
            // ->select(['idx', 'tag_txt'])
            ->where('delyn', 'N')
            ->findAll();
        return $aResult ?? [];
    }

    public function getCompanyIdx(array $aTagIdx): array
    {
        $aResult = $this
            ->whereIn('config_tag_idx', $aTagIdx)
            ->findColumn('com_idx');
        return $aResult ?? [-1];
    }

}
