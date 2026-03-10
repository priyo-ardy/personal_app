<?php

namespace App\Repositories\OvertimeSetup;

use App\Models\AppSetup\OvertimeSetup\OvertimeSetupModel;
use App\Models\AppSetup\OvertimeSetup\VwOvertimeSetupModel;
use App\Repositories\CrudRepository;

class OvertimeSetupRepository extends CrudRepository
{
    public function __construct()
    {
        $this->model = new OvertimeSetupModel();
    }

    public function chunkedData($offset, $limit, $order, $column)
    {
        $view = new VwOvertimeSetupModel();

        return $view->select($column)
            ->where('deleted_at', null)
            ->orderBy($order, 'ASC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
    }
}
