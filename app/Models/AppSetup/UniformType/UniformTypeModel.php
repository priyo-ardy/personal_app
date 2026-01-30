<?php

namespace App\Models\AppSetup\UniformType;

use App\Models\BaseModel;
use CodeIgniter\Model;

class UniformTypeModel extends BaseModel
{
    protected $table            = 'm_uniform_type';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'code',
        'name',
        'description',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
    ];
}
