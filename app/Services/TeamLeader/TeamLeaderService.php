<?php

namespace App\Services\TeamLeader;

use App\Validation\TeamLeader\TeamLeaderValidation;
use App\Repositories\TeamLeader\TeamLeaderRepository;
use App\Models\AppSetup\TeamLeader\VwTeamLeaderModel;
use App\Repositories\DataTableRepository;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Config\Services;

class TeamLeaderService
{
    protected $repository;
    protected $db;
    protected $validation;

    public function __construct(TeamLeaderRepository $repo)
    {
        $this->db = Database::connect();
        $this->validation = Services::validation();
        $this->repository = $repo;
    }

    public function loadTable(array $requestedData)
    {
        try {
            $model = new VwTeamLeaderModel();
            $builder = $model->builder();

            $column_search = ['nik', 'name', 'remark'];
            $column_order = ['nik', 'name', 'remark'];
            $default_order = ['nik' => 'asc'];

            $dataTable = new DataTableRepository($builder, $column_search, $column_order, $default_order, [], 'deleted_at');

            $result = $dataTable->proses($requestedData);

            $formattedData = [];

            foreach ($result['data'] as $row) {
                $formattedData[] = [
                    enkripsi($row->id),
                    $row->nik,
                    $row->name,
                    $row->remark
                ];
            }

            $result['data'] = $formattedData;

            return $result;
        } catch (\Exception $e) {
            log_message('error', '[TeamLeaderService::loadTable] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception($e->getMessage(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function generateEmployeeList()
    {
        try {
            $get_lists = $this->repository->getEmployeeList();

            $data = [];

            foreach ($get_lists as $row) {
                $data[] = [
                    'token' => $row->id,
                    'nik' => $row->nik,
                    'name' => $row->name
                ];
            }

            return $data;
        } catch (\Exception $e) {
            log_message('error', '[TeamLeaderService::generateEmployeeList] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function saveData(array $postData)
    {
        try {
            $this->validation->setRules(TeamLeaderValidation::$save);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[TeamLeaderService::saveData] Validation error : {err} from {ip}', ['err' => implode('\n', $this->validation->getErrors()), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $data = [
                'id' => uuid_v7(),
                'employee_id' => trim($postData['data_employee']),
                'remark' => trim($postData['data_remark']),
                'created_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[TeamLeaderService::saveData] Failed to save new team leader data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception($this->db->error()['message'], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[TeamLeaderService::saveData] New team leader data {id} was saved by {NIK}', ['id' => $data['id'], 'NIK' => session()->get('user_name')]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[TeamLeaderService::saveData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception($e->getMessage(), ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getData(string $id)
    {
        try {
            $get = $this->repository->find($id);
            if (!$get) {
                log_message('error', '[TeamLeaderService::getData] Team leader data not found by {NIK} : {id}', ['NIK' => session()->get('user_name'), 'id' => $id]);
                throw new \Exception("Team leader data not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            $data = [
                'token' => enkripsi($get->id),
                'id' => $get->id,
                'employee' => $get->employee_id,
                'remark' => $get->remark
            ];

            return $data;
        } catch (\Exception $e) {
            log_message('error', '[TeamLeaderService::getData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function updateData(array $postData)
    {
        try {
            $this->validation->setRules(TeamLeaderValidation::$update);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[TeamLeaderService::updateData] Validation error : {err} from {ip}', ['err' => implode('\n', $this->validation->getErrors()), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = dekripsi($postData['data_token']);

            $data = [
                'employee_id' => trim($postData['data_employee']),
                'remark' => trim($postData['data_remark']),
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->update($id, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[TeamLeaderService::updateData] Failed to update team leader data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception($this->db->error()['message'], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[TeamLeaderService::updateData] Team leader data {id} was updated by {NIK}', ['id' => $id, 'NIK' => session()->get('user_name')]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[TeamLeaderService::updateData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function deleteData(array $id)
    {
        try {
            $this->db->transStart();
            $this->repository->permanentDelete($id);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[TeamLeaderService::deleteData] Failed to delete team leader data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to delete team leader data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[TeamLeaderService::deleteData] Team leader data was deleted by {NIK} with total data {total}', ['NIK' => session()->get('user_name'), 'total' => count($id)]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[TeamLeaderService::deleteData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            $file_name = 'team_leader_list_' . date('Ymd_his') . '.xlsx';

            $headers = [
                'NIK',
                'Name',
                'Remark'
            ];

            $dataCallBack = function ($offset, $limit) {
                $column = 'nik, name, remark';
                return $this->repository->chunkedData($offset, $limit, 'nik', $column);
            };


            return export_to_excel($file_name, $headers, $dataCallBack);
        } catch (\Exception $e) {
            log_message('error', '[TeamLeaderService::exportData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getAllData()
    {
        try {
            return $this->repository->all('id', 'asc');
        } catch (\Exception $e) {
            log_message('error', '[TeamLeaderService::getAllData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
