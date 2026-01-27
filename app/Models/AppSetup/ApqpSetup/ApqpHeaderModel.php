<?php

namespace App\Models\AppSetup\ApqpSetup;

use App\Models\BaseModel;
use CodeIgniter\Model;

class ApqpHeaderModel extends BaseModel
{
    protected $table            = 'm_apqp_header';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'sequence',
        'name',
        'remark',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at'
    ];
}
