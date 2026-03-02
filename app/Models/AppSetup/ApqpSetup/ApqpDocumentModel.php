<?php

namespace App\Models\AppSetup\ApqpSetup;

use App\Models\BaseModel;
use CodeIgniter\Model;

class ApqpDocumentModel extends BaseModel
{
    protected $table            = 'm_apqp_document';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'apqp_id',
        'baris',
        'document_level',
        'document_name',
        'uploader',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
    ];
}
