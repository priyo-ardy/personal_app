<?php

namespace App\Services\ProcessRoute;

use App\Repositories\ProcessRoute\ProcessRouteRepository;
use App\Models\AppSetup\ProcessRoute\ProcessRouteModel;
use App\Validation\ProcessRoute\ProcessRouteValidation;
use App\Repositories\DataTableRepository;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Config\Database;

class ProcessRouteService
{
    protected $db;
    protected $validation;
    protected $repository;

    public function __construct(ProcessRouteRepository $repo)
    {
        $this->db = Database::connect();
        $this->validation = Services::validation();
        $this->repository = $repo;
    }

    public function loadTable(array $requestedData)
    {
        try {
            $model = new ProcessRouteModel();
            $builder = $model->builder();

            $column_search = ['code', 'name', 'process_name', 'description'];
            $column_order = [
                '0' => 'code',
                '1' => 'name',
                '2' => 'process_name',
                '3' => 'description'
            ];
            $default_order = array('code' => 'asc');

            $dataTable = new DataTableRepository($builder, $column_search, $column_order, $default_order, [], 'deleted_at');
            $result = $dataTable->proses($requestedData);
            $formattedData = [];

            foreach ($result['data'] as $row) {
                $formattedData[] = [
                    enkripsi($row->id),
                    '<a href="#" class="text-primary fw-bolder text-decoration-none" title="Click to edit" onclick="getData(`' . enkripsi($row->id) . '`)">' . $row->code . '</a>',
                    $row->name,
                    $row->process_name,
                    $row->description
                ];
            }

            $result['data'] = $formattedData;

            return $result;
        } catch (\Exception $e) {
            log_message('error', '[ProcessRouteService::loadTable] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function saveData(array $data)
    {
        try {
            $this->validation->setRules(ProcessRouteValidation::$save);

            if ($this->validation->run($data) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[ProcessRouteService::saveData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $data = [
                'id' => uuid_v7(),
                'code' => $this->repository->generateCode('PRT-', 'code', 4),
                'name' => ucwords(trim($data['data_name'])),
                'process_name' => trim($data['data_route']),
                'description' => trim($data['data_remark']),
                'created_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                $php_errormsg = $this->db->error();
                log_message('error', '[ProcessRouteService::saveData] Failed to save data : {err} from {ip}', ['err' => $php_errormsg, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Failed to save data " . $php_errormsg, ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[ProcessRouteService::saveData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $get_data = $this->repository->find($id);
            if (!$get_data) {
                log_message('error', '[ProcessRouteService::getData] Process Route with id {id} not found', ['id' => $id]);
                throw new \Exception("Process Route with id $id not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            return [
                'token' => enkripsi($get_data->id),
                'code' => $get_data->code,
                'name' => $get_data->name,
                'process_name' => $get_data->process_name,
                'description' => $get_data->description
            ];
        } catch (\Exception $e) {
            log_message('error', '[ProcessRouteService::getData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function updateData(array $data)
    {
        try {

            $this->validation->setRules(ProcessRouteValidation::$update);

            if ($this->validation->run($data) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[ProcessRouteService::updateData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = dekripsi($data['data_token']);

            $data = [
                'name' => ucwords(trim($data['data_name'])),
                'process_name' => trim($data['data_route']),
                'description' => trim($data['data_remark']),
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->update($id, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                $php_errormsg = $this->db->error();
                log_message('error', '[ProcessRouteService::updateData] Failed to update data : {err} from {ip}', ['err' => $php_errormsg, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Failed to update data " . $php_errormsg, ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[ProcessRouteService::updateData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function deleteData(array $data)
    {
        try {
            $this->db->transStart();
            $this->repository->massDelete($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                $php_errormsg = $this->db->error();
                log_message('error', '[ProcessRouteService::deleteData] Failed to delete data : {err} from {ip}', ['err' => $php_errormsg, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Failed to delete data " . $php_errormsg, ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[ProcessRouteService::deleteData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            $file_name = "process_route_list_" . date("Ymd_his") . '.xlsx';

            $headers = [
                'Process Route Code',
                'Process Route Name',
                'Process Name',
                'Description'
            ];

            $dataCallBack = function ($offset, $limit) {
                $column = 'code, name, process_name, description';
                return $this->repository->getChunkedData($offset, $limit, 'code', $column);
            };

            return export_to_excel($file_name, $headers, $dataCallBack);
        } catch (\Exception $e) {
            log_message('error', '[ProcessRouteService::exportData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getAllData()
    {
        try {
            $data = $this->repository->all('code', 'asc');

            if (!$data) {
                log_message('error', '[ProcessRouteService::getAllData] Data Process Route from {ip} with error {err}', ['ip' => $_SERVER['REMOTE_ADDR'], 'err' => 'Data not found']);
                throw new \Exception("Data not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            return $data;
        } catch (\Exception $e) {
            log_message('error', '[ProcessRouteService::getAllData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
