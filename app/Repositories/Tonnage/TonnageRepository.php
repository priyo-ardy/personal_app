<?php

namespace App\Repositories\Tonnage;

use App\Models\AppSetup\Tonnage\TonnageModel;
use App\Repositories\CrudRepository;

class TonnageRepository extends CrudRepository
{
    protected $model;
    public function __construct()
    {
        $this->model = new TonnageModel();
    }
}
