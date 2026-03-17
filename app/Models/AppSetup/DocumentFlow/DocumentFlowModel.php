<?php

namespace App\Models\AppSetup\DocumentFlow;

use App\Models\BaseModel;
use CodeIgniter\Model;

class DocumentFlowModel extends BaseModel
{
    protected $table            = 'm_document_flow';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'level',
        'parent_id',
        'child_id',
        'is_mandatory',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
    ];
}
