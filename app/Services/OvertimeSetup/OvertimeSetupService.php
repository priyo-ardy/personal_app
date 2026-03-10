<?php

namespace App\Services\OvertimeSetup;

use App\Repositories\OvertimeSetup\OvertimeSetupRepository;
use App\Validation\OvertimeSetup\OvertimeSetupValidation;
use App\Models\AppSetup\OvertimeSetup\OvertimeSetupModel;
use App\Models\AppSetup\OvertimeSetup\VwOvertimeSetupModel;
use App\Repositories\DataTableRepository;
use CodeIgniter\HTTP\ResponseInterface;
use Ramsey\Uuid\Uuid;
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
            $model = new VwOvertimeSetupModel();
            $builder = $model->builder();

            $column_search = ['code', 'name', 'description', 'nama_type'];
            $column_order = ['code', 'name', 'description', 'nama_type'];
            $default_order = array('code' => 'asc');

            $dataTable = new DataTableRepository($builder, $column_search, $column_order, $default_order, [], 'deleted_at');

            $result = $dataTable->proses($postData);
            $formattedData = [];

            foreach ($result['data'] as $row) {
                $formattedData[] = [
                    enkripsi($row->id),
                    '<a href="#" class="text-primary fw-bolder text-decoration-none" title="Click to edit" onclick="getData(`' . enkripsi($row->id) . '`)">' . $row->code . '</a>',
                    $row->name,
                    '<button type="button" class="btn btn-sm rounded-0 col-12 btn-primary" onclick="showRate(`' . enkripsi($row->id) . '`)">Show Rate</button>',
                    $row->nama_type,
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

    public function saveData(array $postData)
    {
        try {
            $this->validation->setRules(OvertimeSetupValidation::$save);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[OvertimeSetupService::saveData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $rate = $postData['rate'];
            $data_rate = [];

            for ($i = 0; $i < count($rate); $i++) {
                $data_rate[] = $rate[$i];
            }

            $code = $this->repository->generateCode('OVT-', 'code', 4);

            $data = [
                'id' => Uuid::uuid7()->toString(),
                'code' => $code,
                'name' => ucwords(trim($postData['data_name'])),
                'rate' => json_encode($data_rate),
                'day_type' => trim($postData['data_type']),
                'total_row' => trim($postData['data_rate']),
                'description' => trim($postData['data_remark']),
                'created_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[OvertimeSetupService::saveData] Failed to create data : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Failed to create data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[OvertimeSetupService::saveData] Overtime setup data was created by {NIK}', ['NIK' => session()->get('user_name')]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[OvertimeSetupService::saveData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function  getRateList(string $id)
    {
        try {
            $get_rate = $this->repository->find($id);

            if (!$get_rate) {
                log_message('error', '[OvertimeSetupService::getRateList] Overtime setup data with id {id} not found', ['id' => $id]);
                throw new \Exception('Overtime setup data not found', ResponseInterface::HTTP_NOT_FOUND);
            }

            $data = [
                'token' => enkripsi($get_rate->id),
                'rate' => $get_rate->rate,
            ];

            return $data;
        } catch (\Exception $e) {
            log_message('error', '[OvertimeSetupService::getRateList] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function updateOvertimeRate(array $postData)
    {
        try {
            $this->validation->setRules(OvertimeSetupValidation::$update_rate);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[OvertimeSetupService::updateOvertimeRate] Validation error : {err} from {ip}', ['err' => implode('\n', $this->validation->getErrors()), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }


            $id = dekripsi(trim($postData['rate_token']));
            $post_rate = $postData['rate'];
            $rate = [];

            for ($i = 0; $i < count($post_rate); $i++) {
                $rate[] = $post_rate[$i];
            }

            $data = [
                'rate' => json_encode($rate),
                'total_row' => count($rate),
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->update($id, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[OvertimeSetupService::updateOvertimeRate] Failed to update data : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Failed to update data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[OvertimeSetupService::updateOvertimeRate] Overtime rate data was updated by {NIK} for overtime id {id}', ['NIK' => session()->get('user_name'), 'id' => $id]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[OvertimeSetupService::updateOvertimeRate] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $get = $this->repository->find($id);

            if (!$get) {
                log_message('error', '[OvertimeSetupService::getData] Overtime setup data with id {id} not found', ['id' => $id]);
                throw new \Exception('Overtime setup data not found', ResponseInterface::HTTP_NOT_FOUND);
            }

            $data = [
                'token' => enkripsi($get->id),
                'code' => $get->code,
                'name' => $get->name,
                'day_type' => $get->day_type,
                'total_row' => $get->total_row,
                'description' => $get->description,
            ];

            return $data;
        } catch (\Exception $e) {
            log_message('error', '[OvertimeSetupService::getData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function updateData(array $postData)
    {
        try {
            $this->validation->setRules(OvertimeSetupValidation::$update);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[OvertimeSetupService::updateData] Validation error : {err} from {ip}', ['err' => implode('\n', $this->validation->getErrors()), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = dekripsi(trim($postData['data_token']));

            $data = [
                'name' => ucwords(trim($postData['data_name'])),
                'day_type' => trim($postData['data_type']),
                'description' => trim($postData['data_remark']),
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->update($id, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[OvertimeSetupService::updateData] Failed to update data : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Failed to update data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[OvertimeSetupService::updateData] Overtime setup data was updated by {NIK} for id {id}', ['NIK' => session()->get('user_name'), 'id' => $id]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[OvertimeSetupService::updateData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function deleteData(array $id)
    {
        try {
            $this->db->transStart();
            $this->repository->massDelete($id);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[OvertimeSetupService::deleteData] Failed to delete data : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Failed to delete data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[OvertimeSetupService::deleteData] Overtime setup data was deleted by {NIK} for with total deleted data {id}', ['NIK' => session()->get('user_name'), 'id' => count($id)]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[OvertimeSetupService::deleteData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            $file_name = "overtime_setup_list_" . date("Ymd_his") . '.xlsx';

            $headers = [
                'Code',
                'Name',
                'Rate',
                'Type of Working Day',
                'Remark'
            ];

            $dataCallBack = function ($offset, $limit) {
                $column = 'code, name, rate, nama_type, description';
                return $this->repository->chunkedData($offset, $limit, 'code', $column);
            };

            return export_to_excel($file_name, $headers, $dataCallBack);
        } catch (\Exception $e) {
            log_message('error', '[OvertimeSetupService::exportData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getAllData()
    {
        try {
            $get = $this->repository->all('code', 'asc');

            return $get;
        } catch (\Exception $e) {
            log_message('error', '[OvertimeSetupService::getAllData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
