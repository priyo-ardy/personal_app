<?php

namespace App\Models\AppSetup\PeriodSetup;

use App\Models\BaseModel;
use CodeIgniter\Model;

class PeriodModel extends BaseModel
{
    protected $table            = 'm_period';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'tgl_awal',
        'tgl_akhir',
        'user_name',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at'
    ];
}
