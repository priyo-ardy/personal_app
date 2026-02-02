<?php

namespace App\Repositories;

use App\Repositories\CrudRepository;
use App\Models\AppSetup\City\CityModel;
use App\Models\AppSetup\City\VwCityModel;

class CityRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new CityModel();
    }

    public function getCityList($province_id)
    {
        return $this->model->where('province', $province_id)
            ->orderBy('name', 'asc')
            ->get()
            ->getResultArray();
    }

    public function chunkedData($offset, $limit, $order, $column)
    {
        $view = new VwCityModel();
        return $view->select($column)
            ->where('deleted_at', null)
            ->orderBy($order, 'ASC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
    }
}
