<?php

namespace App\Models\AppSetup\ApqpSetup;

use App\Models\BaseModel;
use CodeIgniter\Model;

class DocumentStagesModel extends BaseModel
{
    protected $table            = 'm_stages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'stage_name',
        'stage_type',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
    ];
}
