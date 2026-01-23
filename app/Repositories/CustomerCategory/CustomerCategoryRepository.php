<?php

namespace App\Repositories\CustomerCategory;

use App\Repositories\CrudRepository;
use App\Models\AppSetup\CustomerCategory\CustomerCategoryModel;

class CustomerCategoryRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new CustomerCategoryModel();
    }
}
