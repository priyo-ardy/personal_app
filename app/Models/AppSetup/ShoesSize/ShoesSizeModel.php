<?php

namespace App\Models\AppSetup\ShoesSize;

use CodeIgniter\Model;

class ShoesSizeModel extends Model
{
    protected $table            = 'm_shoes_size';
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
