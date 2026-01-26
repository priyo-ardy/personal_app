<?php

namespace App\Repositories\Supplier;

use App\Repositories\CrudRepository;
use App\Models\AppSetup\Supplier\SupplierModel;

class SupplierRepository extends CrudRepository
{
    public function __construct()
    {
        $this->model = new SupplierModel();
    }
}
