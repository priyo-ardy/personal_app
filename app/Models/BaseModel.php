<?php

namespace App\Models;

use CodeIgniter\Model;

class BaseModel extends Model
{
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
    protected $beforeUpdate   = ['setTimestamptzInsert'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function setTimestamptzInsert(array $data)
    {
        $now = date('Y-m-d H:i:sP');

        if ($this->useTimestamps) {
            if (!empty($this->createdField) && !isset($data['data'][$this->createdField])) {
                $data['data'][$this->createdField] = $now;
            }
            if (!empty($this->updatedField) && !isset($data['data'][$this->updatedField])) {
                $data['data'][$this->updatedField] = $now;
            }
        }

        return $data;
    }

    /**
     * Set timestamp with timezone for UPDATE
     */
    protected function setTimestamptzUpdate(array $data)
    {
        if ($this->useTimestamps && !empty($this->updatedField)) {
            $data['data'][$this->updatedField] = date('Y-m-d H:i:sP');
        }

        return $data;
    }

    /**
     * Optional: Add soft delete timestamp
     */
    protected function setTimestamptzDelete(array $data)
    {
        if ($this->useSoftDeletes && !empty($this->deletedField)) {
            $data['data'][$this->deletedField] = date('Y-m-d H:i:sP');
        }

        return $data;
    }
}
