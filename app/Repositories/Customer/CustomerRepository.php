<?php

namespace App\Repositories\Customer;

use App\Models\AppSetup\Customer\CustomerModel;
use App\Models\AppSetup\Customer\VwCustomerModel;
use App\Repositories\CrudRepository;

class CustomerRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new CustomerModel();
    }

    public function chunkedData($offset, $limit, $order, $column)
    {
        $model = new VwCustomerModel();

        return $model->select($column)
            ->where('deleted_at', null)
            ->orderBy($order, 'ASC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
    }
}
