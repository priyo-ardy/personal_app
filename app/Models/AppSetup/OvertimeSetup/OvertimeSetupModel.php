<?php

namespace App\Models\AppSetup\OvertimeSetup;

use App\Models\BaseModel;
use CodeIgniter\Model;

class OvertimeSetupModel extends BaseModel
{
    protected $table            = 'm_overtime';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'code',
        'name',
        'rate',
        'day_type',
        'total_row',
        'description',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at'
    ];
}
