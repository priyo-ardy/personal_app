<?php

namespace App\Repositories\JobDataReason;

use App\Models\AppSetup\JobDataReason\JobDataReasonModel;
use App\Models\AppSetup\JobDataReason\VwJobDataReasonModel;
use App\Repositories\CrudRepository;

class JobDataReasonRepository extends CrudRepository
{
    protected $model;
    public function __construct()
    {
        $this->model = new JobDataReasonModel();
    }

    public function getListByAction(string $action)
    {
        return $this->model->where('action', $action)->orderBy('code', 'ASC')->findAll();
    }

    public function chunkedData($offset, $limit, $order, $column)
    {
        $view = new VwJobDataReasonModel();

        return $view->select($column)
            ->where('deleted_at', null)
            ->orderBy($order, 'ASC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
    }
}
