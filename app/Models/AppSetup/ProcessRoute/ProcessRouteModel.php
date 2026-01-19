<?php

namespace App\Models\AppSetup\ProcessRoute;

use App\Models\BaseModel;
use CodeIgniter\Model;

class ProcessRouteModel extends BaseModel
{
    protected $table            = 'm_route';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'code',
        'name',
        'process_name',
        'description',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
    ];
}
