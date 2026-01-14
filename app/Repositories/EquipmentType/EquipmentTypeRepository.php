<?php

namespace App\Repositories\EquipmentType;

use App\Repositories\CrudRepository;
use App\Models\AppSetup\EquipmentType\EquipmentTypeModel;
use CodeIgniter\Model;

class EquipmentTypeRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new EquipmentTypeModel();
    }
}
