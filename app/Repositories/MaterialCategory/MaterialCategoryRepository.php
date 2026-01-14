<?php

namespace App\Repositories\MaterialCategory;

use App\Models\AppSetup\MaterialCategory\MaterialCategoryModel;
use App\Repositories\CrudRepository;
use CodeIgniter\Model;

class MaterialCategoryRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new MaterialCategoryModel();
    }
}
