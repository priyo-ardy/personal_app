<?php

namespace App\Models\AppSetup\JobData;

use App\Models\BaseModel;
use CodeIgniter\Model;

class JobDataModel extends BaseModel
{
    protected $table            = 'm_job_data';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'employee_id',
        'action',
        'reason',
        'effective_date',
        'work_relationship',
        'no_contract',
        'durasi_kontrak',
        'tipe_durasi',
        'akhir_kontrak',
        'superior',
        'status',
        'remark',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
    ];
}
