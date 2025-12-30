<?php

namespace App\Repositories;

use CodeIgniter\Database\BaseBuilder;

class DataTableRepository
{
    protected $builder;
    protected $column_search;
    protected $column_order; // Perhatikan nama properti ini
    protected $defaultOrder;
    protected $customSearch;

    public function __construct(BaseBuilder $builder, array $column_search, array $column_order, array $defaultOrder = [], array $customSearch = [])
    {
        $this->builder = $builder;
        $this->column_search = $column_search;
        $this->column_order = $column_order;
        $this->defaultOrder = $defaultOrder;
        $this->customSearch = $customSearch;
    }

    public function proses(array $requestData)
    {
        $draw           = $requestData['draw'] ?? 1;
        $length         = $requestData['length'] ?? 10;
        $start          = $requestData['start'] ?? 0;
        $searchValue    = $requestData['search']['value'] ?? null;

        // FIX 1: Typo 'columns' menjadi 'column'
        $orderIndex     = $requestData['order'][0]['column'] ?? 0;
        $orderDir       = $requestData['order'][0]['dir'] ?? 'asc';

        $totalRecord = (clone $this->builder)->countAllResults();

        if ($searchValue) {
            $this->applySearch($searchValue);
        }

        // Hitung filtered sebelum applyOrder/limit
        $filteredRecords = (clone $this->builder)->countAllResults(false);

        // Kirim requestData agar applyOrder bisa membaca index dan dir
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

        foreach ($this->column_search as $column) {
            if (array_key_exists($column, $this->customSearch)) {
                // Custom Search (Hash)
                $this->customSearch[$column]($this->builder, $searchValue);
            } else {
                // Logic Standar (Text)
                $this->builder->orLike($column, $searchValue);
            }
        }

        $this->builder->groupEnd();
    }

    private function applyOrder(array $requestData)
    {
        // FIX 1: Typo 'columns' menjadi 'column'
        $orderColumnIndex = $requestData['order'][0]['column'] ?? null;
        $orderDir         = $requestData['order'][0]['dir'] ?? null;

        $hasOrdered = false;

        // FIX 2: Ganti '$this->orderableColumns' menjadi '$this->column_order'
        if (!is_null($orderColumnIndex) && isset($this->column_order[$orderColumnIndex])) {
            $columnName = $this->column_order[$orderColumnIndex];

            if ($columnName) {
                $this->builder->orderBy($columnName, $orderDir);
                $hasOrdered = true;
            }
        }

        // Fallback ke default order
        if (!$hasOrdered && !empty($this->defaultOrder)) {
            foreach ($this->defaultOrder as $key => $value) {
                $this->builder->orderBy($key, $value);
            }
        }
    }
}
