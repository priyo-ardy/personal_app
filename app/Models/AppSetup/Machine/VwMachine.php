<?php

namespace App\Models\AppSetup\Machine;

use CodeIgniter\Model;

class VwMachine extends Model
{
    protected $table            = 'vw_machine';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;
    protected $allowedFields    = [];
}
