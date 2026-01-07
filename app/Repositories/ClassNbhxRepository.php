<?php

namespace App\Repositories;

use App\Models\AppSetup\ClassNBHX\ClassNbhxModel;;

use App\Repositories\CrudRepository;

class ClassNbhxRepository extends CrudRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new ClassNbhxModel();
    }

    public function getNewCode()
    {
        return $this->generateCode('CNBHX-', 'code', 4);
    }

    public function saveData(array $data)
    {
        return $this->create($data);
    }

    public function updateData(string $id, array $data)
    {
        return $this->update($id, $data);
    }

    public function deleteData($data)
    {
        return $this->model->update($data, ['deleted_at' => date('Y-m-d H:i:sP')]);
    }

    public function findData(string $id)
    {
        return $this->find($id);
    }

    public function getAll($orderColumn = 'code', $orderDirection = 'ASC')
    {
        return $this->all($orderColumn, $orderDirection);
    }

    public function chunkedData($offset, $limit, $order, $column)
    {
        return $this->getChunkedData($offset, $limit, $order, $column);
    }
}
