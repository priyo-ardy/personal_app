<?php

namespace App\Repositories;

use App\Interfaces\CrudRepositoryInterface;
use App\Models\AppSetup\Section\VwSectionModel;

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

    public function generateCode(string $prefix, string $column = 'code', int $padding = 4)
    {
        $lastData = $this->model->select($column)
            ->like($column, $prefix, 'after') // Mencari yang berawalan $prefix
            ->orderBy($column, 'DESC')
            ->first();

        if ($lastData) {
            $lastCodeString = $lastData->$column;

            $lastNumber = substr($lastCodeString, strlen($prefix));

            $nextNumber = (int) $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $paddedNumber = str_pad($nextNumber, $padding, '0', STR_PAD_LEFT);

        return $prefix . $paddedNumber;
    }

    public function getChunkedData($offset, $limit, $order, $column)
    {
        return $this->model->select($column)
            ->where('deleted_at', null)
            ->orderBy($order, 'ASC')
            ->limit($limit, $offset)
            ->get()
            ->getResultArray();
    }

    public function massDelete(array $id)
    {
        return $this->model->update($id, ['deleted_at' => date('Y-m-d H:i:sP')]);
    }

    public function massSave(array $data)
    {
        return $this->model->insertBatch($data);
    }
}
