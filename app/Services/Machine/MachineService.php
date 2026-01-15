<?php

namespace App\Services\Machine;

use App\Models\AppSetup\Machine\MachineModel;
use App\Repositories\Machine\MachineRepository;
use App\Validation\Machine\MachineValidation;
use CodeIgniter\HTTP\ResponsableInterface;
use App\Models\AppSetup\Machine\VwMachine;
use App\Repositories\DataTableRepository;
use Config\Services;
use Config\Database;
use App\Traits\ResponseTrait;

class MachineService
{
    protected $db;
    protected $validasi;
    protected $repository;

    public function __construct(MachineRepository $repo)
    {
        $this->db = Database::connect();
        $this->validasi = Services::validation();
        $this->repository = $repo;
    }

    public function loadTable(array $requestedData)
    {
        try {
            $model = new VwMachine();
            $builder = $model->builder();

            $column_search = ['code', 'name', 'specification', 'workshop_name', 'brand', 'serial_no', 'tonnage_name', 'rate', 'mfg_date', 'puchase_date', 'description'];
            $column_order = [
                'code',
                'name',
                'specification',
                'workshop_name',
                'brand',
                'serial_no',
                'tonnage_name',
                'rate',
                'mfg_date',
                'puchase_date',
                'description'
            ];
            $defaultOrder = array('code' => 'asc');

            $data_table = new DataTableRepository($builder, $column_search, $column_order, $defaultOrder, [], 'deleted_at');

            $result = $data_table->proses($requestedData);

            $formattedData = [];

            foreach ($result['data'] as $row) {
                $formattedData[] = [
                    enkripsi($row->id),
                    '<a href="#" class="text-primary fw-bolder text-decoration-none" title="Click to edit" onclick="getData(`' . enkripsi($row->id) . '`)">' . $row->code . '</a>',
                    $row->name,
                    $row->specification,
                    $row->workshop_name,
                    $row->brand,
                    $row->serial_no,
                    $row->tonnage_name,
                    $row->rate,
                    $row->mfg_date,
                    $row->puchase_date,
                    $row->description
                ];
            }

            $result['data'] = $formattedData;

            return $result;
        } catch (\Exception $e) {
            log_message('error', '[MachineService::loadTable] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function saveData(array $data)
    {
        try {
        } catch (\Exception $e) {
            log_message('error', '[MachineService::saveData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
        }
    }

    public function getData(string $id)
    {
        try {
        } catch (\Exception $e) {
            log_message('error', '[MachineService::getData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
        }
    }

    public function updateData(array $data)
    {
        try {
        } catch (\Exception $e) {
            log_message('error', '[MachineService::updateData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
        }
    }

    public function  deleteData(string $id)
    {
        try {
        } catch (\Exception $e) {
            log_message('error', '[MachineService::deleteData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function massDelete(array $data)
    {
        try {
        } catch (\Exception $e) {
            log_message('error', '[MachineService::massDelete] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
        }
    }

    public function exportData()
    {
        try {
        } catch (\Exception $e) {
            log_message('error', '[MachineService::exportData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
        }
    }

    public function loadAllData()
    {
        try {
        } catch (\Exception $e) {
            log_message('error', '[MachineService::loadAllData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
        }
    }
}
