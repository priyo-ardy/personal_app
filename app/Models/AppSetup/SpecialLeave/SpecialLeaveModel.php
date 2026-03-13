<?php

namespace App\Models\AppSetup\SpecialLeave;

use App\Models\BaseModel;
use CodeIgniter\Model;

class SpecialLeaveModel extends BaseModel
{
    protected $table            = 'm_cuti_khusus';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'code',
        'name',
        'jml_hari',
        'dokumen',
        'upload_dokumen',
        'remark',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
    ];
}
