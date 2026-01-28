<?php

namespace App\Models\AppSetup\EmployeeFacility;

use App\Models\BaseModel;
use CodeIgniter\Model;

class EmployeeFacilityModel extends BaseModel
{
    protected $table            = 'm_employee_facility';
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
        'deleted_at'
    ];
}
