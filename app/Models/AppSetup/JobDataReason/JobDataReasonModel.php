<?php

namespace App\Models\AppSetup\JobDataReason;

use App\Models\BaseModel;
use CodeIgniter\Model;

class JobDataReasonModel extends BaseModel
{
    protected $table            = 'm_job_data_reason';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'action',
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
