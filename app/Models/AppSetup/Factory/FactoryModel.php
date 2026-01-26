<?php

namespace App\Models\AppSetup\Factory;

use App\Models\BaseModel;
use CodeIgniter\Model;

class FactoryModel extends BaseModel
{
    protected $table            = 'm_factory';
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
