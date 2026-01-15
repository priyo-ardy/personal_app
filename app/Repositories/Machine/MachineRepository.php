<?php

namespace App\Repositories\Machine;

use App\Models\AppSetup\Machine\MachineModel;
use App\Repositories\CrudRepository;


class MachineRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new MachineModel();
    }
}
