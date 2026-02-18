<?php

namespace App\Services\GroupLeader;

use App\Repositories\GroupLeader\GroupLeaderRepository;
use App\Models\AppSetup\GroupLeader\GroupLeaderModel;
use App\Models\AppSetup\GroupLeader\VwGroupLeaderModel;
use App\Validation\GroupLeader\GroupLeaderValidation;
use App\Repositories\DataTableRepository;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Config\Services;

class GroupLeaderService
{
    protected $repository;
    protected $db;
    protected $validation;

    public function __construct(GroupLeaderRepository $repo)
    {
        $this->repository = $repo;
        $this->db = Database::connect();
        $this->validation = Services::validation();
    }

    public function loadTable(array $requestedData)
    {
        try {
            $model = new VwGroupLeaderModel();
            $builder = $model->builder();

            $column_search = ['nik', 'nama_karyawan', 'remark'];
            $column_order = ['nik', 'nama_karyawan', 'remark'];
            $default_order = array('nik' => 'asc');

            $dataTable = new DataTableRepository($builder, $column_search, $column_order, $default_order, [], 'deleted_at');

            $result = $dataTable->proses($requestedData);
            $formattedData = [];

            foreach ($result['data'] as $row) {
                $formattedData[] = [
                    enkripsi($row->id),
                    $row->nik,
                    $row->nama_karyawan,
                    $row->remark
                ];
            }

            $result['data'] = $formattedData;

            return $result;
        } catch (\Exception $e) {
            log_message('error', '[GroupLeaderService::loadTable] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function  saveData(array $postData)
    {
        try {
            $this->validation->setRules(GroupLeaderValidation::$save);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[GroupLeaderService::saveData] Validation error : {err} from {ip}', ['err' => implode('\n', $this->validation->getErrors()), 'ip' => $_SERVER['REMOTE_ADDR']]);
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
                log_message('error', '[GroupLeaderService::saveData] Failed to save new group leader data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception($this->db->error()['message'], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[GroupLeaderService::saveData] New group leader data {id} was saved by {NIK}', ['id' => $data['id'], 'NIK' => session()->get('user_name')]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[GroupLeaderService::saveData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $get = $this->repository->find($id);

            $data = [
                'token' => $get->id,
                'employee' => $get->employee_id,
                'remark' => $get->remark
            ];

            return $data;
        } catch (\Exception $e) {
            log_message('error', '[GroupLeaderService::getData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function updateData(array $postData)
    {
        try {
            $this->validation->setRules(GroupLeaderValidation::$update);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[GroupLeaderService::update] Validation error : {err} from {ip}', ['err' => implode('\n', $this->validation->getErrors()), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = dekripsi(trim($postData['data_token']));

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
                log_message('error', '[GroupLeaderService::update] Failed to update group leader data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception($this->db->error()['message'], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[GroupLeaderService::update] Group leader data {id} was updated by {NIK}', ['id' => $id, 'NIK' => session()->get('user_name')]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[GroupLeaderService::update] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
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
                log_message('error', '[GroupLeaderService::deleteData] Failed to delete group leader data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to delete group leader data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[GroupLeaderService::deleteData] Group leader data was deleted by {NIK} with total data {total}', ['NIK' => session()->get('user_name'), 'total' => count($id)]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[GroupLeaderService::deleteData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getAllData()
    {
        try {
            $get = $this->repository->all('id', 'asc');

            return $get;
        } catch (\Exception $e) {
            log_message('error', '[GroupLeaderService::getAllData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            $file_name = 'group_leader_list_' . date('Ymd_his') . '.xlsx';

            $headers = [
                'NIK',
                'Name',
                'Remark'
            ];

            $dataCallBack = function ($offset, $limit) {
                $column = 'nik, nama_karyawan, remark';
                return $this->repository->chunkedData($offset, $limit, 'nik', $column);
            };

            return export_to_excel($file_name, $headers, $dataCallBack);
        } catch (\Exception $e) {
            log_message('error', '[GroupLeaderService::exportData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function get_employee_list()
    {
        try {
            $get = $this->repository->employee_list();

            $get_lists = $this->repository->employee_list();

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
            log_message('error', '[GroupLeaderService::get_employee_list] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
