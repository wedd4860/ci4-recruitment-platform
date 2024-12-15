<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\DatabaseInterview;

class MemberModel extends Model
{
    protected $table = ''; // 데이터베이스 테이블을 지정
    protected $primaryKey = ''; // 테이블에서 레코드를 고유하게 식별하는 열(column)의 이름
    protected $useAutoIncrement = true; // (auto-increment) 기능을 사용할지 여부
    protected $tempReturnType = 'array'; // 반환되는값 지정 array,object 
    protected $useSoftDeletes = true; // true 일경우 실제로 행을 삭제하는 대신 deleted_at 필드를 설정
    protected $allowedFields = []; //save, insert, update 메소드를 통하여 설정할 수 있는 필드 이름
    protected $useTimestamps = true; // INSERT 및 UPDATE에 자동으로 추가되는지 여부를 결정.
    protected $createdField = 'mem_reg_date'; // 데이터 레코드 작성 타임스탬프를 유지하기 위해 사용하는 데이터베이스 필드
    protected $updatedField = 'mem_mod_date'; // 데이터 레코드 업데이트 타임스탬프를 유지하기 위해 사용할 데이터베이스 필드
    protected $deletedField = 'mem_del_date'; // 데이터 레코드 삭제 타임스탬프를 유지하기 위해 사용할 데이터베이스 필드
    protected $validationRules = [
        'mem_id' => 'required|valid_email|is_unique[iv_member.mem_id]',
        'mem_password' => 'required|min_length[4]',
    ]; // 유효성 검사 규칙 배열
    protected $validationMessages = []; // 유효성 검증 중에 사용해야하는 사용자 정의 오류 메시지 배열
    protected $skipValidation = false; // 모든 inserts와 updates의 유효성 검사를 하지 않을지 여부 기본값은 false이며 데이터의 유효성 검사를 항상 시도.


    public function __construct()
    {
        parent::__construct();
        $code = 'iv_member';
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

    public function checkMemberId(string $strId = ''): int
    {
        $iResult = 0;
        if ($strId == '') {
            return $iResult;
        }
        $iResult = $this->selectCount('0')
            ->where('mem_id', $strId)->countAllResults();
        return $iResult;
    }

    public function getList(string $range, string $type): array
    {
        $aResult = [];
        if (!$type) {
            return $aResult;
        }
        if ($range === 'all') {
            $aResult = $this
                ->where('mem_type', $type)
                ->where('delyn', 'N')
                ->orderBy('idx', 'desc')->findAll();
        } else {
            $aResult = $this
                ->where('mem_type', $type)
                ->where('delyn', 'N')
                ->orderBy('idx', 'desc')->first();
        }
        return $aResult ?? [];
    }

    public function getMember(string $strId = ''): array
    {
        $aResult = [];
        if ($strId == '') {
            return $aResult;
        }
        $aResult = $this
            ->where('mem_id', $strId)
            ->where('delyn', 'N')
            ->orderBy('idx', 'desc')->first();
        return $aResult ?? [];
    }

    public function getLastPasswordDate(string $strId = '', string $dateIn): array
    {
        //5분이내 이력확인
        $aResult = [];
        if ($strId == '') {
            return $aResult;
        }
        $readySQL = $this
            ->where([
                'mem_id' => $strId,
                'delyn' => 'N'
            ]);
        if ($dateIn) {
            $readySQL->where([
                'mem_last_password_date >' => $dateIn
            ]);
        }
        $aResult = $readySQL->orderBy('idx', 'desc')->first();
        return $aResult ?? [];
    }

    public function getMemberTel(string $strTel, string $type): array
    {
        $aResult = [];
        if (!$strTel || !$type || !in_array($type, ['M', 'C'])) {
            return $aResult;
        }
        $aResult = $this
            ->where([
                'mem_tel' => $strTel,
                'mem_type' => $type,
                'delyn' => 'N',
            ])
            ->orderBy('idx', 'desc')->first();
        return $aResult ?? [];
    }

    /**
     * @brief 하이버프인터뷰1.0 유저 체크
     */
    public function getOldMember(string $strId = '', string $strPw = ''): array
    {
        $aResult = [];
        if ($strId == '' || $strPw == '') {
            return $aResult;
        }
        $strEscPw = $this->escape($strPw);
        $aResult = $this
            ->where('mem_id', $strId)
            ->where('delyn', 'N')
            ->where("mem_password = PASSWORD(${strEscPw})")
            ->orderBy('idx', 'desc')->first();
        return $aResult ?? [];
    }

    //마이페이지 정보 가져오기
    public function MypageMem(int $_idx){
        $aMember = $this->select('*')
        ->where('idx', $_idx)
        ->where('delyn', 'N')
        ->first();
        
        return $aMember;
    }
}
