<?php

namespace App\Models\AppSetup\ShiftSetup;

use App\Models\BaseModel;
use CodeIgniter\Model;

class ShiftModel extends BaseModel
{
    protected $table            = 'm_shift';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'code',
        'name',
        'working_day',
        'early_in',
        'std_in',
        'late_in',
        'early_out',
        'std_out',
        'late_out',
        'break',
        'overday',
        'working_hour_type',
        'working_hour',
        'min_overtime',
        'auto_overtime',
        'default_overtime',
        'overtime_type',
        'overtime_in',
        'overtime_out',
        'overtime_break',
        'overtime_rate',
        'overtime_index',
        'x15',
        'x20',
        'x30',
        'x40',
        'default_absence_status',
        'remark',
        'effective_date',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at'
    ];
}
