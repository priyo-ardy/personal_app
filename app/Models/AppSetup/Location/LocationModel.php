<?php

namespace App\Models\AppSetup\Location;

use App\Models\BaseModel;
use CodeIgniter\Model;

class LocationModel extends BaseModel
{
    protected $table            = 'm_location';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'code',
        'factory',
        'name',
        'description',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
    ];
}
