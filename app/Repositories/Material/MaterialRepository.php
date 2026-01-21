<?php

namespace App\Repositories\Material;

use App\Repositories\CrudRepository;
use App\Models\AppSetup\Material\MaterialModel;

class MaterialRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new MaterialModel();
    }

    public function checkCode($workshop, $code)
    {
        return $this->model->where('workshop', $workshop)->where('code', $code)->first();
    }
}
