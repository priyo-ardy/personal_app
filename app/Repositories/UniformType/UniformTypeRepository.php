<?php

namespace App\Repositories\UniformType;

use App\Models\AppSetup\UniformType\UniformTypeModel;
use App\Repositories\CrudRepository;

class UniformTypeRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new UniformTypeModel();
    }
}
