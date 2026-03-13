<?php

namespace App\Repositories;

use CodeIgniter\Database\BaseBuilder;

class DataTableRepository
{
    protected $builder;
    protected $column_search;
    protected $column_order;
    protected $defaultOrder;
    protected $customSearch;

    public function __construct(BaseBuilder $builder, array $column_search, array $column_order, array $defaultOrder = [], array $customSearch = [], string $deletedAtColumn = 'deleted_at')
    {
        $this->builder = $builder;
        $this->column_search = $column_search;
        $this->column_order = $column_order;
        $this->defaultOrder = $defaultOrder;
        $this->customSearch = $customSearch;

        if (!empty($deletedAtColumn)) {
            $this->builder->where($deletedAtColumn, null);
        }
    }

    public function proses(array $requestData)
    {
        $draw           = $requestData['draw'] ?? 1;
        $length         = $requestData['length'] ?? 10;
        $start          = $requestData['start'] ?? 0;
        $searchValue    = $requestData['search']['value'] ?? null;

        $orderIndex     = $requestData['order'][0]['column'] ?? 0;
        $orderDir       = $requestData['order'][0]['dir'] ?? 'asc';

        // Hitung total record (Kondisi deleted_at sudah otomatis terbawa dari construct)
        $totalRecord = (clone $this->builder)->countAllResults();

        if ($searchValue) {
            $this->applySearch($searchValue);
        }

        // Hitung filtered record setelah pencarian
        // $filteredRecords = (clone $this->builder)->countAllResults(false);
        $filteredRecords = (clone $this->builder)->countAllResults( );

        $this->applyOrder($requestData);

        if ($length != -1) {
            $this->builder->limit($length, $start);
        }

        $data = $this->builder->get()->getResultObject();

        return [
            "draw"            => intval($draw),
            'recordsTotal'    => $totalRecord,
            'recordsFiltered' => $filteredRecords,
            'data'            => $data
        ];
    }

    private function applySearch($searchValue)
    {
        $this->builder->groupStart();

        foreach ($this->column_search as $key => $column) {
            // Perbaikan logika OR agar tidak menimpa WHERE deleted_at
            if ($key === 0) {
                // Kolom pertama pakai LIKE biasa agar terbungkus dalam groupStart
                if (array_key_exists($column, $this->customSearch)) {
                    $this->customSearch[$column]($this->builder, $searchValue);
                } else {
                    $this->builder->like($column, $searchValue, 'both', null, true);
                }
            } else {
                // Kolom selanjutnya pakai OR LIKE
                if (array_key_exists($column, $this->customSearch)) {
                    $this->customSearch[$column]($this->builder, $searchValue);
                } else {
                    $this->builder->orLike($column, $searchValue, 'both', null, true);
                }
            }
        }

        $this->builder->groupEnd();
    }

    private function applyOrder(array $requestData)
    {
        $orderColumnIndex = $requestData['order'][0]['column'] ?? null;
        $orderDir         = $requestData['order'][0]['dir'] ?? null;

        $hasOrdered = false;

        if (!is_null($orderColumnIndex) && isset($this->column_order[$orderColumnIndex])) {
            $columnName = $this->column_order[$orderColumnIndex];

            if ($columnName) {
                $this->builder->orderBy($columnName, $orderDir);
                $hasOrdered = true;
            }
        }

        if (!$hasOrdered && !empty($this->defaultOrder)) {
            foreach ($this->defaultOrder as $key => $value) {
                $this->builder->orderBy($key, $value);
            }
        }
    }
}
