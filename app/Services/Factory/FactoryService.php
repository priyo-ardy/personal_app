<?php

namespace App\Services\Factory;


use App\Repositories\Factory\FactoryRepository;
use App\Models\AppSetup\Factory\FactoryModel;
use App\Repositories\DataTableRepository;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Config\Database;
use App\Traits\ResponseTrait;
use App\Validation\Factory\FactoryValidation;

class FactoryService
{
    protected $db;
    protected $validation;
    protected $repository;

    public function __construct(FactoryRepository $repo)
    {
        $this->db = Database::connect();
        $this->validation = Services::validation();
        $this->repository = $repo;
    }

    public function loadTable(array $requestedData)
    {
        try {
            $model = new FactoryModel();
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
            log_message('error', '[FactoryService::loadTable] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function saveData(array $postData): bool
    {
        try {
            $this->validation->setRules(FactoryValidation::$save);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[FactoryService::saveData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $data = [
                'id' => generate_uuid(),
                'code' => $this->repository->generateCode('FCT-', 'code', 4),
                'name' => ucwords(trim($postData['data_name'])),
                'description' => trim($postData['data_remark']),
                'created_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[FactoryService::saveData] Failed to save new factory data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception("Failed to save new factory data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[FactoryService::saveData] New factory data was saved by {NIK}', ['NIK' => session()->get('user_name')]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[FactoryService::saveData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $id): array
    {
        try {
            $getData = $this->repository->find($id);
            if (!$getData) {
                log_message('error', '[FactoryService::getData] Data token $id by {NIK} from {ip} with error {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => 'Data not found']);
                throw new \Exception("Data not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            return [
                'token' => enkripsi($getData->id),
                'code' => $getData->code,
                'name' => $getData->name,
                'description' => $getData->description
            ];
        } catch (\Exception $e) {
            log_message('error', '[FactoryService::getData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function updateData(array $postData): bool
    {
        try {
            $this->validation->setRules(FactoryValidation::$update);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[FactoryService::updateData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = trim($postData['data_token']);

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
                log_message('error', '[FactoryService::updateData] Failed to update factory data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception("Failed to update factory data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[FactoryService::updateData] Factory data was updated by {NIK}', ['NIK' => session()->get('user_name')]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[FactoryService::updateData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function deleteData(array $id): bool
    {
        try {
            $this->db->transStart();
            $this->repository->massDelete($id);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[FactoryService::deleteData] Failed to delete factory data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception("Failed to delete factory data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[FactoryService::deleteData] Factory data was deleted by {NIK}', ['NIK' => session()->get('user_name')]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[FactoryService::deleteData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            $file_name = "factory_list_" . date("Ymd_his") . '.xlsx';

            $headers = [
                'Factory Code',
                'Factory Name',
                'Remark'
            ];

            $dataCallBack = function ($offset, $limit) {
                $column = 'code, name, description';
                return $this->repository->getChunkedData($offset, $limit, 'code', $column);
            };

            return export_to_excel($file_name, $headers, $dataCallBack);
        } catch (\Exception $e) {
            log_message('error', '[FactoryService::exportData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getAllData()
    {
        try {
            $getData = $this->repository->all('code', 'asc');
            if (!$getData) {
                log_message('error', '[FactoryService::getAllData] Data factory from {ip} with error {err}', ['ip' => $_SERVER['REMOTE_ADDR'], 'err' => 'Data not found']);
                throw new \Exception("Data not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            return $getData;
        } catch (\Exception $e) {
            log_message('error', '[FactoryService::getAllData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
