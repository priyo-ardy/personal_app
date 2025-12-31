<?php

namespace App\Repositories;

use App\Interfaces\CrudRepositoryInterface;

use CodeIgniter\Model;

abstract class CrudRepository implements CrudRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all($orderColumn = null, $orderDirection = null)
    {
        if ($orderColumn && $orderDirection) {
            return $this->model->orderBy($orderColumn, $orderDirection)->findAll();
        }

        return $this->model->findAll();
    }

    public function find(string $id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->insert($data);
    }

    public function update(string $id, array $data)
    {
        return $this->model->update($id, $data);
    }

    public function delete(string $id)
    {
        return $this->model->delete($id);
    }

    public function prevData(string $column_name, string $code)
    {
        return $this->model->where($column_name . '<', $code)->orderBy($column_name, 'DESC')->first();
    }

    public function nextData(string $column_name, string $code)
    {
        return $this->model->where($column_name . '>', $code)->orderBy($column_name, 'ASC')->first();
    }
}
