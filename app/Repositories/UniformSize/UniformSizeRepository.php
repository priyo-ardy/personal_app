<?php

namespace App\Repositories\UniformSize;

use App\Repositories\CrudRepository;
use App\Models\AppSetup\UniformSize\UniformSizeModel;

class UniformSizeRepository extends CrudRepository
{
    public function __construct()
    {
        $this->model = new UniformSizeModel();
    }
}
