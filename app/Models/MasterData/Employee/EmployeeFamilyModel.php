<?php

namespace App\Models\MasterData\Employee;

use App\Models\BaseModel;
use CodeIgniter\Model;

class EmployeeFamilyModel extends BaseModel
{
    protected $table            = 'm_karyawan_keluarga';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'employee_id',
        'row_no',
        'relation',
        'name',
        'ocupation',
        'remark',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at'
    ];
}
