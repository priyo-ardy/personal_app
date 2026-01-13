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
        // 1. Ambil data terakhir yang HANYA memiliki prefix tersebut
        // Tujuannya agar tidak terganggu oleh kode lain (misal ada kode 'ADM-001' dan 'DPT-001')
        $lastData = $this->model->select($column)
            ->like($column, $prefix, 'after') // Mencari yang berawalan $prefix
            ->orderBy($column, 'DESC')
            ->first();

        // 2. Logika Penomoran
        if ($lastData) {
            // Ambil string kode dari database
            $lastCodeString = $lastData->$column;

            // Buang prefix-nya, ambil angkanya saja
            // Contoh: 'DPT-0005' -> dibuang 'DPT-' (4 char) -> sisa '0005'
            $lastNumber = substr($lastCodeString, strlen($prefix));

            // Ubah jadi integer dan tambah 1
            $nextNumber = (int) $lastNumber + 1;
        } else {
            // Jika belum ada data sama sekali dengan prefix ini
            $nextNumber = 1;
        }

        // 3. Format ulang (Padding)
        // Contoh: 6 -> '0006'
        $paddedNumber = str_pad($nextNumber, $padding, '0', STR_PAD_LEFT);

        // Gabungkan: 'DPT-' . '0006'
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
}
