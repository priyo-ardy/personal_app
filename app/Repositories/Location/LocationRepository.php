<?php

namespace App\Repositories\Location;

use App\Models\AppSetup\Location\LocationModel;
use App\Models\AppSetup\Location\VwLcoationModel;
use App\Repositories\CrudRepository;

class LocationRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new LocationModel();
    }

    public function chunkedData($offset, $limit, $order, $column)
    {
        $view = new VwLcoationModel();

        return $view->select($column)
            ->where('deleted_at', null)
            ->orderBy($order, 'ASC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
    }
}
