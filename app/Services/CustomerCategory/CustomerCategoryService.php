<?php

namespace App\Services\CustomerCategory;

use App\Repositories\CustomerCategory\CustomerCategoryRepository;
use App\Models\AppSetup\CustomerCategory\CustomerCategoryModel;
use App\Repositories\DataTableRepository;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Config\Services;
use App\Traits\ResponseTrait;
use App\Validation\CustomerCategory\CustomerCategoryValidation;

class CustomerCategoryService
{
    protected $db;
    protected $validation;
    protected $repository;

    public function __construct(CustomerCategoryRepository $repo)
    {
        $this->db = Database::connect();
        $this->validation = Services::validation();
        $this->repository = $repo;
    }

    public function loadTable(array $requestedData)
    {
        try {
            $model = new CustomerCategoryModel();
            $builder = $model->builder();

            $column_search = ['code', 'name', 'description'];
            $column_order = ['code', 'name', 'description'];
            $default_order = array('code' => 'asc');

            $data_table = new DataTableRepository($builder, $column_search, $column_order, $default_order, [], 'deleted_at');

            $result = $data_table->proses($requestedData);

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
            log_message('error', '[CustomerCategoryService::loadTable] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function saveData(array $postData)
    {
        try {
            $this->validation->setRules(CustomerCategoryValidation::$save);

            if ($this->validation->run($postData) === false) {
                $error_to_sting = implode("<br>", $this->validation->getErrors());
                log_message('error', '[CustomerCategoryService::saveData] Validation error : {err} from {ip}', ['err' => $error_to_sting, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_sting);
            }

            $id = uuid_v7();
            $code = $this->repository->generateCode('CTG-', 'code', 4);

            $data = [
                'id' => $id,
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
                $php_errormsg = $this->db->error();
                log_message('error', '[CustomerCategoryService::saveData] Failed to save data : {err} from {ip}', ['err' => $php_errormsg, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Failed to save data " . $php_errormsg, ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[CustomerCategoryService::saveData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $data = $this->repository->find($id);

            if (empty($data)) {
                log_message('error', '[CustomerCategoryService::getDta] Data not found with id {id} from {ip}', ['id' => $id, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Data not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            return [
                'token' => enkripsi($data->id),
                'code' => $data->code,
                'name' => $data->name,
                'description' => $data->description
            ];
        } catch (\Exception $e) {
            log_message('error', '[CustomerCategoryService::getDta] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function updateData(array $postData)
    {
        try {
            $this->validation->setRules(CustomerCategoryValidation::$update);

            if ($this->validation->run($postData) === false) {
                $error_to_sting = implode("<br>", $this->validation->getErrors());
                log_message('error', '[CustomerCategoryService::updateData] Validation error : {err} from {ip}', ['err' => $error_to_sting, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_sting);
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
                $php_errormsg = $this->db->error();
                log_message('error', '[CustomerCategoryService::updateData] Failed to update data : {err} from {ip}', ['err' => $php_errormsg, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Failed to update data " . $php_errormsg, ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[CustomerCategoryService::updateData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
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
                $php_errormsg = $this->db->error();
                log_message('error', '[CustomerCategoryService::deleteData] Failed to delete data : {err} from {ip}', ['err' => $php_errormsg, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Failed to delete data " . $php_errormsg, ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[CustomerCategoryService::deleteData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            $file_name = 'customer_category_list' . date('Ymd_his') . '.xlsx';

            $headers = [
                'Code',
                'Name',
                'Description'
            ];

            $dataCallBack = function ($offset, $limit) {
                $column = ['code', 'name', 'description'];
                return $this->repository->getChunkedData($offset, $limit, 'code', $column);
            };

            return export_to_excel($file_name, $headers, $dataCallBack);
        } catch (\Exception $e) {
            log_message('error', '[CustomerCategoryService::exportData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getAllData()
    {
        try {
            $get = $this->repository->all('code', 'asc');

            if (!$get) {
                log_message('error', '[CustomerCategoryService::getAllData] Data Customer Category from {ip} with error {err}', ['ip' => $_SERVER['REMOTE_ADDR'], 'err' => 'Data not found']);
                throw new \Exception("Data not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            return $get;
        } catch (\Exception $e) {
            log_message('error', '[CustomerCategoryService::getAllData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
