<?php

namespace App\Repositories\JobData;

use App\Repositories\CrudRepository;
use App\Models\AppSetup\JobData\JobDataModel;
use App\Models\AppSetup\JobData\LatestJobDataModel;

class JobDataRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new JobDataModel();
    }

    public function chunkedData($offset, $limit, $order, $column)
    {
        $view = new LatestJobDataModel();
        return $view->select($column)
            ->where('deleted_at', null)
            ->orderBy($order, 'ASC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
    }
}
