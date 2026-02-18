<?php

namespace App\Services\JobDataAction;

use App\Repositories\JobDataAction\JobDataActionRepository;
use App\Models\AppSetup\JobData\JobDataModel;
use App\Models\AppSetup\JobDataAction\JobDataActionModel;
use App\Repositories\DataTableRepository;
use App\Validation\JobDataAction\JobDataActionValidation;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Config\Database;

class JobDataActionService
{
    protected $db;
    protected $validation;
    protected $repository;

    public function __construct(JobDataActionRepository $repo)
    {
        $this->db = Database::connect();
        $this->validation = Services::validation();
        $this->repository = $repo;
    }

    public function loadTable(array $postData)
    {
        try {
            $model = new JobDataActionModel();
            $builder = $model->builder();

            $column_search = ['code', 'name', 'description'];
            $column_order = ['0' => 'code', '1' => 'name', '2' => 'description'];
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
            log_message('error', '[JobDataActionService::loadTable] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function saveData(array $postData)
    {
        try {
            $this->validation->setRules(JobDataActionValidation::$save);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[JobDataActionService::saveData] Validation error : {err} from {ip}', ['err' => implode('\n', $this->validation->getErrors()), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $data = [
                'id' => uuid_v7(),
                'code' => $this->repository->generateCode('JDA-', 'code', 3),
                'name' => ucwords(trim($postData['data_name'])),
                'description' => trim($postData['data_remark']),
                'created_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[JobDataActionService::saveData] Failed to save new job data action data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception($this->db->error()['message'], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[JobDataActionService::saveData] New job data action data {id} was saved by {NIK}', ['id' => $data['id'], 'NIK' => session()->get('user_name')]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[JobDataActionService::saveData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $get_data = $this->repository->find($id);

            $data = [
                'token' => enkripsi($get_data->id),
                'code' => $get_data->code,
                'name' => $get_data->name,
                'description' => $get_data->description
            ];

            return $data;
        } catch (\Exception $e) {
            log_message('error', '[JobDataActionService::getData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function updateData(array $postData)
    {
        try {
            $this->validation->setRules(JobDataActionValidation::$update);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[JobDataActionService::updateData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
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
                log_message('error', '[JobDataActionService::updateData] Failed to update job data action data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception($this->db->error()['message'], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[JobDataActionService::updateData] Job data action data {id} was updated by {NIK}', ['id' => $id, 'NIK' => session()->get('user_name')]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[JobDataActionService::updateData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
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
                log_message('error', '[JobDataActionService::deleteData] Failed to delete job data action data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception($this->db->error()['message'], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[JobDataActionService::deleteData] Job data action data was deleted by {NIK} with total data {total}', ['NIK' => session()->get('user_name'), 'total' => count($id)]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[JobDataActionService::deleteData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            $file_name = 'job_data_action_list_' . date('Ymd_his') . '.xlsx';

            $headers = [
                'Code',
                'Name',
                'Remark'
            ];

            $dataCallBack = function ($offset, $limit) {
                $column = 'code, name, description';
                return $this->repository->getChunkedData($offset, $limit, 'code', $column);
            };

            return export_to_excel($file_name, $headers, $dataCallBack);
        } catch (\Exception $e) {
            log_message('error', '[JobDataActionService::exportData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getAllData()
    {
        try {
            return $this->repository->all('code', 'asc');
        } catch (\Exception $e) {
            log_message('error', '[JobDataActionService::getAllData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function generateList()
    {
        try {
            $get = $this->repository->all('code', 'asc');
            $list = [];
            foreach ($get as $row) {
                $list[] = [
                    'id' => $row->id,
                    'code' => $row->code,
                    'name' => $row->name,
                    'description' => $row->description
                ];
            }

            return $list;
        } catch (\Exception $e) {
            log_message('error', '[JobDataActionService::generateList] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
