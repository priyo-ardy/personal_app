<?php

namespace App\Models\AppSetup\UoM;

use App\Models\BaseModel;
use CodeIgniter\Model;

class UomModel extends BaseModel
{
    protected $table            = 'm_uom';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'code',
        'name',
        'symbol',
        'description',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at'
    ];
}
