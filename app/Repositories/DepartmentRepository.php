<?php

namespace App\Repositories;

use App\Interfaces\DepartmentInterface;
use App\Repositories\CrudRepository;
use App\Models\AppSetup\Department\DepartmentModel;
use App\Models\AppSetup\Department\VwDepartmentModel;
use App\Models\AppSetup\Section\VwSectionModel;
use CodeIgniter\Model;

class DepartmentRepository extends CrudRepository implements DepartmentInterface
{
    protected $model;
    protected $crudRepo;

    public function __construct()
    {
        $this->model = new DepartmentModel();
    }

    public function getNewDeptCode()
    {
        return $this->generateCode('DPT-', 'code', 4);
    }

    public function getDepartmentList()
    {
        return $this->all('code', 'ASC');
    }

    public function save(array $data)
    {
        return $this->create($data);
    }

    public function getUserData(string $id_dept)
    {
        return $this->find($id_dept);
    }

    public function updateData($id, $data)
    {
        return $this->update($id, $data);
    }

    public function massDelete($dept_data)
    {
        return $this->model->update($dept_data, ['deleted_at' => date('Y-m-d H:i:sP')]);
    }

    public function chunkedData($offset, $limit, $order, $column)
    {
        return $this->getChunkedData($offset, $limit, $order, $column);
    }
}
