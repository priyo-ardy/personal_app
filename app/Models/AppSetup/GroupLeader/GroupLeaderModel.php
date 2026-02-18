<?php

namespace App\Models\AppSetup\GroupLeader;

use App\Models\BaseModel;
use CodeIgniter\Model;

class GroupLeaderModel extends BaseModel
{
    protected $table            = 'm_group_leader';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'employee_id',
        'remark',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at'
    ];
}
