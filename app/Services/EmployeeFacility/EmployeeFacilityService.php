<?php

namespace App\Services\EmployeeFacility;

use App\Repositories\EmployeeFacility\EmployeeFacilityRepository;
use App\Validation\EmployeeFacility\EmployeeFacilityValidation;
use App\Repositories\DataTableRepository;
use App\Models\AppSetup\EmployeeFacility\EmployeeFacilityModel;
use App\Traits\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Config\Database;

class EmployeeFacilityService
{
    use ResponseTrait;
    protected $db;
    protected $validation;
    protected $repository;

    public function __construct(EmployeeFacilityRepository $repo)
    {
        $this->db = Database::connect();
        $this->validation = Services::validation();
        $this->repository = $repo;
    }

    public function loadTable(array $requestedData)
    {
        try {
            $model = new EmployeeFacilityModel();
            $builder = $model->builder();

            $column_search = ['code', 'name', 'description'];
            $column_order = ['0' => 'code', '1' => 'name', '2' => 'description'];
            $default_order = array('code' => 'asc');

            $data_table = new DataTableRepository($builder, $column_search, $column_order, $default_order, [], 'deleted_at');

            $result = $data_table->proses($requestedData);
            $formatedData = [];

            foreach ($result['data'] as $row) {
                $formatedData[] = [
                    enkripsi($row->id),
                    '<a href="#" class="text-primary fw-bolder text-decoration-none" title="Click to edit" onclick="getData(`' . enkripsi($row->id) . '`)">' . $row->code . '</a>',
                    $row->name,
                    $row->description,
                ];
            }

            $result['data'] = $formatedData;

            return $result;
        } catch (\Exception $e) {
            log_message('error', '[EmployeeFacilityService::loadTable] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function saveData(array $postData)
    {
        try {
            $this->validation->setRules(EmployeeFacilityValidation::$save);

            if (!$this->validation->run($postData)) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[EmployeeFacilityService::saveData] Validation error occured : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $code = $this->repository->generateCode('EF-', 'code', 4);

            $data = [
                'id' => uuid_v7(),
                'code' => $code,
                'name' => ucwords(trim($postData['data_name'])),
                'description' => trim($postData['data_remark']),
                'created_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[EmployeeFacilityService::saveData] Transaction error occured from {ip} with error {err}', ['ip' => $_SERVER['REMOTE_ADDR'], 'err' => $this->db->error()['message']]);
                throw new \Exception('Transaction error occured', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[EmployeeFacilityService::saveData] Employee facility data saved from {ip} with new code {code}', ['ip' => $_SERVER['REMOTE_ADDR'], 'code' => $code]);
        } catch (\Exception $e) {
            log_message('error', '[EmployeeFacilityService::saveData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $get_data = $this->repository->find($id);

            if (!$get_data) {
                log_message('error', '[EmployeeFacilityService::getData] Employee facility data with id {id} not found from {ip}', ['id' => $id, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Employee facility data not found', ResponseInterface::HTTP_NOT_FOUND);
            }

            return [
                'token' => enkripsi($get_data->id),
                'code' => $get_data->code,
                'name' => $get_data->name,
                'description' => $get_data->description
            ];
        } catch (\Exception $e) {
            log_message('error', '[EmployeeFacilityService::getData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function updateData(array $postData)
    {
        try {
            $this->validation->setRules(EmployeeFacilityValidation::$update);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[EmployeeFacilityService::updateData] Validation error : {err} from {ip}', ['err' => implode('\n', $this->validation->getErrors()), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = dekripsi(trim($postData['data_token']));

            $data = [
                'name' => ucwords(trim($postData['data_name'])),
                'description' => trim($postData['data_remark']),
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->update($id, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[EmployeeFacilityService::updateData] Failed to update employee facility data for {id} from {ip} with error {err}', ['id' => $id, 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $this->db->getLastQuery()]);
                throw new \Exception('Failed to update employee facility data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[EmployeeFacilityService::updateData] Employee facility data updated for {id} from {ip}', ['id' => $id, 'ip' => $_SERVER['REMOTE_ADDR']]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[EmployeeFacilityService::updateData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
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
                log_message('error', '[EmployeeFacilityService::deleteData] Failed to delete employee facility data : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Failed to delete employee facility data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[EmployeeFacilityService::deleteData] Employee facility data deleted from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[EmployeeFacilityService::deleteData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            $file_name = "employee_facility_list_" . date("Ymd_his") . '.xlsx';

            $headers = [
                'Facility Code',
                'Facility Name',
                'Remark'
            ];

            $dataCallBack = function ($offset, $limit) {
                $column = 'code, name, description';
                return $this->repository->getChunkedData($offset, $limit, 'code', $column);
            };

            return export_to_excel($file_name, $headers, $dataCallBack);
        } catch (\Exception $e) {
            log_message('error', '[EmployeeFacilityService::exportData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getAllData()
    {
        try {
            return $this->repository->all('code', 'asc');
        } catch (\Exception $e) {
            log_message('error', '[EmployeeFacilityService::getAllData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
