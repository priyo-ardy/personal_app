<?php

namespace App\Models\AppSetup\JobDataAction;

use App\Models\BaseModel;
use CodeIgniter\Model;

class JobDataActionModel extends BaseModel
{
    protected $table            = 'm_job_data_action';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'code',
        'name',
        'remark',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
    ];
}
