<?php

namespace App\Models\AppSetup\Supplier;

use App\Models\BaseModel;
use CodeIgniter\Model;

class SupplierModel extends BaseModel
{
    protected $table            = 'm_supplier';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'code',
        'name',
        'address',
        'phone',
        'phone_hash',
        'email',
        'email_hash',
        'contact_person',
        'contact_person_email',
        'contact_person_email_hash',
        'contact_person_phone',
        'contact_person_phone_hash',
        'npwp_no',
        'npwp_hash',
        'bank_name',
        'bank_account_no',
        'bank_account_no_hash',
        'bank_account_name',
        'description',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at'
    ];
}
