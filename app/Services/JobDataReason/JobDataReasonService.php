<?php

namespace App\Services\JobDataReason;

use App\Repositories\JobDataAction\JobDataActionRepository;
use App\Repositories\JobDataReason\JobDataReasonRepository;
use App\Models\AppSetup\JobDataReason\VwJobDataReasonModel;
use App\Validation\JobDataReason\JobDataReasonValidation;
use App\Repositories\DataTableRepository;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Config\Services;


class JobDataReasonService
{
    protected $db;
    protected $validation;
    protected $repository;
    protected $action;

    public function __construct(JobDataReasonRepository $repo)
    {
        $this->db = Database::connect();
        $this->validation = Services::validation();
        $this->repository = $repo;
        $this->action = new JobDataActionRepository();
    }

    public function loadTable(array $postData)
    {
        try {
            $model = new VwJobDataReasonModel();
            $builder = $model->builder();

            $column_search = ['code', 'action_name', 'name', 'description'];
            $column_order = ['0' => 'code', '1' => 'action_name', '2' => 'name', '3' => 'description'];
            $default_order = array('code' => 'asc');

            $data_table = new DataTableRepository($builder, $column_search, $column_order, $default_order, [], 'deleted_at');

            $result = $data_table->proses($postData);
            $formattedData = [];

            foreach ($result['data'] as $row) {
                $formattedData[] = [
                    enkripsi($row->id),
                    '<a href="#" class="text-primary fw-bolder text-decoration-none" title="Click to edit" onclick="getData(`' . enkripsi($row->id) . '`)">' . $row->code . '</a>',
                    $row->action_name,
                    $row->name,
                    $row->description
                ];
            }

            $result['data'] = $formattedData;

            return $result;
        } catch (\Exception $e) {
            log_message('error', '[JobDataReasonService::loadTable] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function saveData(array $postData)
    {
        try {
            $this->validation->setRules(JobDataReasonValidation::$save);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[JobDataReasonService::saveData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $data = [
                'id' => uuid_v7(),
                'code' => $this->repository->generateCode('JDR', 'code', 3),
                'action' => trim($postData['data_action']),
                'name' => ucwords(trim($postData['data_name'])),
                'description' => trim($postData['data_remark']),
                'created_by' => session()->get('user_name'),
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[JobDataReasonService::saveData] Transaction error : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Failed to save new job data reason data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[JobDataReasonService::saveData] Job data reason data has been saved successfully with id {id} from {ip}', ['id' => $data['id'], 'ip' => $_SERVER['REMOTE_ADDR']]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[JobDataReasonService::saveData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $get = $this->repository->find($id);

            if (!$get) {
                log_message('error', '[JobDataReasonService::getData] Job data reason data not found with id {id} from {ip}', ['id' => $id, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Job data reason data not found', ResponseInterface::HTTP_NOT_FOUND);
            }

            $data = [
                'token' => enkripsi($get->id),
                'code' => $get->code,
                'action' => $get->action,
                'name' => $get->name,
                'description' => $get->description
            ];

            return $data;
        } catch (\Exception $e) {
            log_message('error', '[JobDataReasonService::getData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function updateData(array $postData)
    {
        try {
            $this->validation->setRules(JobDataReasonValidation::$update);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[JobDataReasonService::updateData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = dekripsi(trim($postData['data_token']));

            $data = [
                'action' => trim($postData['data_action']),
                'name' => ucwords(trim($postData['data_name'])),
                'description' => trim($postData['data_remark']),
                'updated_by' => session()->get('user_name'),
            ];

            $this->db->transStart();
            $this->repository->update($id, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[JobDataReasonService::updateData] Transaction error : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Failed to update job data reason data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[JobDataReasonService::updateData] Job data reason data has been updated successfully with id {id} from {ip}', ['id' => $id, 'ip' => $_SERVER['REMOTE_ADDR']]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[JobDataReasonService::updateData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
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
                log_message('error', '[JobDataReasonService::delete] Transaction error : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Failed to delete job data reason data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[JobDataReasonService::delete] Job data reason data has been deleted successfully with total data {total} from {ip}', ['id' => $id, 'ip' => $_SERVER['REMOTE_ADDR'], 'total' => count($id)]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[JobDataReasonService::delete] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            $file_name = 'job_data_reason_list_' . date('Ymd_his') . '.xlsx';

            $headers = [
                'Code',
                'Action',
                'Name',
                'Remark'
            ];

            $dataCallBack = function ($offset, $limit) {
                $column = 'code, action_name, name, description';
                return $this->repository->chunkedData($offset, $limit, 'code', $column);
            };

            return export_to_excel($file_name, $headers, $dataCallBack);
        } catch (\Exception $e) {
            log_message('error', '[JobDataReasonService::exportData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getAllData()
    {
        try {
            $get = $this->repository->all('code', 'asc');

            return $get;
        } catch (\Exception $e) {
            log_message('error', '[JobDataReasonService::getAllData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getDataByAction(string $action)
    {
        try {
            $get_list = $this->repository->getListByAction($action);

            $list = [];

            foreach ($get_list as $row) {
                $list[] = [
                    'token' => $row->id,
                    'name' => $row->name
                ];
            }

            return $list;
        } catch (\Exception $e) {
            log_message('error', '[JobDataReasonService::getDataByAction] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
