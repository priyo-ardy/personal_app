<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'm_users';
    protected $primaryKey       = 'user_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'user_name',
        'full_name',
        'user_email',
        'user_phone',
        'user_password',
        'email_hash',
        'phone_hash',
        'user_image',
        'user_status',
        'user_level',
        'remark',
        'login_attempts',
        'last_login',
        'login_from',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setTimestamptzInsert'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['setTimestamptzUpdate'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function setTimestamptzInsert(array $data)
    {
        $now = date('Y-m-d H:i:sP'); // Output: 2025-12-31 20:00:00+07:00

        $data['data'][$this->createdField] = $now;
        $data['data'][$this->updatedField] = $now;

        return $data;
    }

    protected function setTimestamptzUpdate(array $data)
    {
        $data['data'][$this->updatedField] = date('Y-m-d H:i:sP');
        return $data;
    }
}
