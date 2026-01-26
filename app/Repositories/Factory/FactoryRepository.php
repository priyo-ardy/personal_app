<?php

namespace App\Repositories\Factory;

use App\Models\AppSetup\Factory\FactoryModel;
use App\Repositories\CrudRepository;

class FactoryRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new FactoryModel();
    }
}
