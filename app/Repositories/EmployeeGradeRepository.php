<?php

namespace App\Repositories;

use App\Interfaces\EmployeeGradeInterface;
use App\Models\AppSetup\EmployeeGrade\EmployeeGradeModel;
use App\Repositories\CrudRepository;


class EmployeeGradeRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new EmployeeGradeModel();
    }

    public function getNewEmployeeGradeCode()
    {
        return $this->generateCode('EGR-', 'code', 4);
    }

    public function saveData(array $data)
    {
        return $this->create($data);
    }

    public function updateData($id, array $data)
    {
        return $this->update($id, $data);
    }

    public function deleteData($data)
    {
        return $this->model->update($data, ['deleted_at' => date('Y-m-d H:i:sP')]);
    }

    public function findData($id)
    {
        return $this->find($id);
    }

    public function findAll()
    {
        return $this->findAll();
    }

    public function chunkedData($offset, $limit, $order, $column)
    {
        return $this->getChunkedData($offset, $limit, $order, $column);
    }
}
