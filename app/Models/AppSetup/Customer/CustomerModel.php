<?php

namespace App\Models\AppSetup\Customer;

use App\Models\BaseModel;
use CodeIgniter\Model;

class CustomerModel extends BaseModel
{
    protected $table            = 'm_customer';
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
        'category',
        'email',
        'email_hash',
        'phone',
        'phone_hash',
        'contact_person',
        'contact_person_email',
        'contact_person_email_hash',
        'contact_person_phone',
        'contact_person_phone_hash',
        'description',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at'
    ];
}
