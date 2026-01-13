<?php

namespace App\Repositories;

use App\Repositories\CrudRepository;
use App\Models\AppSetup\Province\ProvinceModel;
use App\Models\AppSetup\Province\VwProvinceModel;
use CodeIgniter\Model;

class ProvinceRepository extends CrudRepository
{
    protected $model;
    protected $crudRepo;

    public function __construct()
    {
        $this->model = new ProvinceModel();
    }

    public function chunkedData($limit, $offset, $order, $column)
    {
        $view = new VwProvinceModel();
        return $view->select($column)
            ->where('deleted_at', null)
            ->orderBy($order, 'ASC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
    }
}
