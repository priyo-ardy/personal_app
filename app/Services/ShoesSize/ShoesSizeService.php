<?php

namespace App\Services\ShoesSize;

use App\Repositories\ShoesSize\ShoesSizeRepository;
use App\Models\AppSetup\ShoesSize\ShoesSizeModel;
use App\Repositories\DataTableRepository;
use CodeIgniter\HTTP\ResponseInterface;
use App\Traits\ResponseTrait;
use App\Validation\ShoesSize\ShoesSizeValidation;
use Config\Database;
use Config\Services;

class ShoesSizeService
{
    protected $db;
    protected $validation;
    protected $repository;

    public function  __construct(ShoesSizeRepository $repo)
    {
        $this->db = Database::connect();
        $this->validation = Services::validation();
        $this->repository = $repo;
    }

    public function loadTable(array $requestedData)
    {
        try {
            $model = new ShoesSizeModel();
            $builder = $model->builder();

            $column_search = ['code', 'name', 'description'];
            $column_order = [
                '0' => 'code',
                '1' => 'name',
                '2' => 'description'
            ];
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
            log_message('error', '[ShoesSizeService::loadTable] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function saveData(array $postData)
    {
        try {
            $this->validation->setRules(ShoesSizeValidation::$save);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[ShoesSizeService::saveData] Validation error : {err} from {ip}', ['err' => implode('\n', $this->validation->getErrors()), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = uuid_v7();
            $code = $this->repository->generateCode('SS-', 'code', 4);

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
                $php_errormsg = $this->db->error()['message'];
                log_message('error', '[ShoesSizeService::saveData] Failed to save data : {err} from {ip}', ['err' => $php_errormsg, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Failed to save data " . $php_errormsg, ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[ShoesSizeService::saveData] Shoes size has been saved successfully with new id : {id} by {NIK}', ['id' => $id, 'NIK' => session()->get('user_name')]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[ShoesSizeService::saveData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $get_data = $this->repository->find($id);

            if (!$get_data) {
                log_message('error', '[ShoesSizeService::getData] Shoes size with id {id} not found', ['id' => $id]);
                throw new \Exception("Shoes size not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            return [
                'token' => enkripsi($get_data->id),
                'code' => $get_data->code,
                'name' => $get_data->name,
                'description' => $get_data->description
            ];
        } catch (\Exception $e) {
            log_message('error', '[ShoesSizeService::getData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function updateData(array $postData)
    {
        try {
            $this->validation->setRules(ShoessizeValidation::$update);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[ShoesSizeService::updateData] Validation error : {err} from {ip}', ['err' => implode('\n', $this->validation->getErrors()), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = dekripsi($postData['data_token']);
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
                $php_errormsg = $this->db->error()['message'];
                log_message('error', '[ShoesSizeService::updateData] Failed to update data : {err} from {ip}', ['err' => $php_errormsg, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Failed to update data " . $php_errormsg, ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[ShoesSizeService::updateData] Shoes size has been updated successfully with id : {id} by {NIK}', ['id' => $id, 'NIK' => session()->get('user_name')]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[ShoesSizeService::updateData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
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
                $php_errormsg = $this->db->error()['message'];
                log_message('error', '[ShoesSizeService::deleteData] Failed to delete data : {err} from {ip} for id : {id}', ['err' => $php_errormsg, 'ip' => $_SERVER['REMOTE_ADDR'], 'id' => implode(',', $id)]);
                throw new \Exception("Failed to delete selected Shoes size data " . $php_errormsg, ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[ShoesSizeService::deleteData] Shoes size has been deleted successfully with id : {id} by {NIK}', ['id' => implode(',', $id), 'NIK' => session()->get('user_name')]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[ShoesSizeService::deleteData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            $file_name = "shoes_size_list_" . date("Ymd_his") . '.xlsx';

            $headers = [
                'Shoes Size Code',
                'Shoes Size Name',
                'Description'
            ];

            $dataCallBack = function ($offset, $limit) {
                $column = 'code, name, description';
                return $this->repository->getChunkedData($offset, $limit, 'code', $column);
            };

            return export_to_excel($file_name, $headers, $dataCallBack);
        } catch (\Exception $e) {
            log_message('error', '[ShoesSizeService::exportData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getAllData()
    {
        try {
            return $this->repository->all('code', 'asc');
        } catch (\Exception $e) {
            log_message('error', '[ShoesSizeService::getAllData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
