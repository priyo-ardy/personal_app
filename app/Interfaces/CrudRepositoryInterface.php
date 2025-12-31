<?php

namespace App\Interfaces;

interface CrudRepositoryInterface
{
    public function all($orderColumn = null, $orderDirection = null);
    public function find(string $id);
    public function create(array $data);
    public function update(string $id, array $data);
    public function delete(string $id);
    public function prevData(string $column_name, string $code);
    public function nextData(string $column_name, string $code);
}
