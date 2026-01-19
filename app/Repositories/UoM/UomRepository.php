<?php

namespace App\Repositories\UoM;

use App\Repositories\CrudRepository;
use App\Models\AppSetup\UoM\UomModel;

class UomRepository extends CrudRepository
{
    protected $model;
    public function __construct()
    {
        $this->model = new UomModel();
    }
}
