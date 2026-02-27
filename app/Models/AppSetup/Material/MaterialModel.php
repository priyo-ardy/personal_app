<?php

namespace App\Models\AppSetup\Material;

use App\Models\BaseModel;
use CodeIgniter\Model;

class MaterialModel extends BaseModel
{
    protected $table            = 'm_material';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'code',
        'name',
        'specification',
        'category',
        'cust_part_no',
        'cust_part_name',
        'color',
        'workshop',
        'property',
        'uom',
        'shift_capacity',
        'spq',
        'qty_per_bag',
        'net_weight',
        'gross_weight',
        'cavity',
        'image',
        'mold_no',
        'process_route',
        'description',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at'
    ];
}
