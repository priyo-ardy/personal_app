<?php

namespace App\Models\AppSetup\Customer;

use App\Models\BaseModel;
use CodeIgniter\Model;

class VwCustomerModel extends BaseModel
{
    protected $table            = 'vw_customer';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;
    protected $allowedFields    = [];
}
