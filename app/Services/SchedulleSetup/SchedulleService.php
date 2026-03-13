<?php

namespace App\Services\SchedulleSetup;

use App\Repositories\SchedulleSetup\SchedulleRepository;
use App\Validation\SchedulleSetup\SchedulleValidation;
use App\Repositories\DataTableRepository;
use App\Models\AppSetup\SchedulleSetup\SchedulleModel;
use CodeIgniter\HTTP\ResponseInterface;
use Ramsey\Uuid\Uuid;
use Config\Services;
use Config\Database;

class SchedulleService
{
    protected $repository;
    protected $db;
    protected $validation;

    public function __construct(SchedulleRepository $repository)
    {
        $this->repository = $repository;
        $this->db = Database::connect();
        $this->validation = Services::validation();
    }

    public function saveData(array $postData)
    {
        try {
            $this->validation->setRules(SchedulleValidation::$save);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[SchedulleService::saveData] Validation error : {err} from {ip}', ['err' => implode('\n', $this->validation->getErrors()), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $shifts = $postData['shift'];
            $shift = [];

            for ($i = 0; $i < count($shifts); $i++) {
                $shift[] = $shifts[$i];
            }

            $data = [
                'id' => Uuid::uuid7()->toString(),
                'code' => $this->repository->generateCode('SH-', 'code', 6),
                'name' => trim($postData['data_name']),
                'total_day' => trim($postData['data_hari']),
                'effective_date' => trim($postData['effective_date']),
                'remark' => trim($postData['data_remark']),
                'shift' => json_encode($shift),
                'created_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[SchedulleService::saveData] Failed to save data : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Failed to save data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[SchedulleService::saveData] Data saved successfully, id : {id}', ['id' => $data['id']]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[SchedulleService::saveData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function loadTable(array $postData)
    {
        try {
            $model = new SchedulleModel();
            $builder = $model->builder();

            $column_search = ['code', 'name', 'total_day', 'effective_date', 'remark'];
            $column_order = ['code', 'name', 'total_day', 'effective_date', 'remark'];
            $default_order = array('code' => 'asc');

            $dataTable = new DataTableRepository($builder, $column_search, $column_order, $default_order, [], 'deleted_at');

            $result = $dataTable->proses($postData);
            $formattedData = [];

            foreach ($result['data'] as $row) {
                $formattedData[] = [
                    enkripsi($row->id),
                    '<a href="#" class="text-primary fw-bolder text-decoration-none" title="Click to edit" onclick="getData(`' . enkripsi($row->id) . '`)">' . $row->code . '</a>',
                    $row->name,
                    $row->total_day . " Days",
                    $row->effective_date,
                    '<button type="button" class="btn btn-sm rounded-0 btn-primary" onclick="showShift(`' . enkripsi($row->id) . '`)">Show Shift</button>',
                    $row->remark
                ];
            }

            $result['data'] = $formattedData;

            return $result;
        } catch (\Exception $e) {
            log_message('error', '[SchedulleService::loadTable] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            //code...
        } catch (\Exception $e) {
            log_message('error', '[SchedulleService::exportData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
