<?php

namespace App\Repositories\ShoesSize;

use App\Models\AppSetup\ShoesSize\ShoesSizeModel;
use App\Repositories\CrudRepository;

class ShoesSizeRepository extends CrudRepository
{
    public function __construct()
    {
        $this->model = new ShoesSizeModel();
    }
}
