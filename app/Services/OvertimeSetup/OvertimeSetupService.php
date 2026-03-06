<?php

namespace App\Services\OvertimeSetup;

use App\Repositories\OvertimeSetup\OvertimeSetupRepository;
use App\Validation\OvertimeSetup\OvertimeSetupValidation;
use CodeIgniter\HTTP\ResponsableInterface;
use App\Models\AppSetup\OvertimeSetup\OvertimeSetupModel;
use App\Repositories\DataTableRepository;
use Config\Database;
use Config\Services;

class OvertimeSetupService
{
    protected $repository;
    protected $db;
    protected $validation;

    public function __construct(OvertimeSetupRepository $repo)
    {
        $this->repository = $repo;
        $this->db = Database::connect();
        $this->validation = Services::validation();
    }

    public function loadTable(array $postData)
    {
        try {
            $model = new OvertimeSetupModel();
            $builder = $model->builder();

            $column_search = ['code', 'name', 'description'];
            $column_order = ['code', 'name', 'description'];
            $default_order = array('code' => 'asc');

            $dataTable = new DataTableRepository($builder, $column_search, $column_order, $default_order, [], 'deleted_at');

            $result = $dataTable->proses($postData);
            $formattedData = [];

            foreach ($result['data'] as $row) {
                $formattedData[] = [
                    enkripsi($row->id),
                    '<a href="#" class="text-primary fw-bolder text-decoration-none" title="Click to edit" onclick="getData(`' . enkripsi($row->id) . '`)">' . $row->code . '</a>',
                    $row->name,
                    $row->description
                ];
            }

            $result['data'] = $formattedData;

            return $result;
        } catch (\Exception $e) {
            log_message('error', '[OvertimeSetupService::loadTable] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function saveData(array $postData) {}

    public function getData(string $id) {}

    public function updateData(array $postData) {}

    public function deleteData(array $id) {}

    public function exportData() {}

    public function getAllData() {}
}
