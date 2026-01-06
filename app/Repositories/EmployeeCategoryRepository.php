<?php

namespace App\Repositories;

use App\Models\AppSetup\EmployeeCategory\EmployeeCategoryModel;


class EmployeeCategoryRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new EmployeeCategoryModel();
    }

    function getNewCode()
    {
        return $this->generateCode("ECT", 'code', 4);
    }

    public function getAll($column = 'code', $order = 'ASC')
    {
        return $this->all($column, $order);
    }

    public function getById(string $id)
    {
        return $this->find($id);
    }

    public function save(array $data)
    {
        return $this->create($data);
    }

    public function updateData(string $id, array $data)
    {
        return $this->update($id, $data);
    }

    public function delete($data)
    {
        return $this->model->update($data, ['deleted_at' => date('Y-m-d H:i:sP')]);
    }

    public function chunkedData($offset, $limit, $order, $column)
    {
        return $this->getChunkedData($offset, $limit, $order, $column);
    }
}
