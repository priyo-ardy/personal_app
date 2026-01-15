<?php

namespace App\Models\AppSetup\Machine;

use App\Models\BaseModel;
use CodeIgniter\Model;

class MachineModel extends BaseModel
{
    protected $table            = 'm_machine';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'code',
        'name',
        'specification',
        'workshop',
        'brand',
        'serial_no',
        'tonnage',
        'rate',
        'mfg_date',
        'puchase_date',
        'description',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
    ];
}
