<?php

namespace App\Services\AbsenceStatus;

use App\Repositories\AbsenceStatus\AbsenceStatusRepository;
use App\Validation\AbsenceStatus\AbsenceStatusValidation;
use App\Models\AppSetup\AbsenceStatus\AbsenceStatusModel;
use App\Repositories\DataTableRepository;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Config\Services;
use Ramsey\Uuid\Uuid;

class AbsenceStatusService
{
    protected $repository;
    protected $db;
    protected $validation;

    public function __construct(AbsenceStatusRepository $repo)
    {
        $this->repository = $repo;
        $this->db = Database::connect();
        $this->validation = Services::validation();
    }

    public function loadTable(array $postData): array
    {
        try {
            $model = new AbsenceStatusModel();
            $builder = $model->builder();

            $column_search = ['code', 'name', 'description'];
            $column_order = ['code', 'name', 'description'];
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
            log_message('error', '[AbsenceStatusService::loadTable] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function saveData(array $postData): bool
    {
        try {
            $postData['data_code'] = strtoupper(trim($postData['data_code']));
            $this->validation->setRules(AbsenceStatusValidation::$save);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[AbsenceStatusService::saveData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $data = [
                'id' => Uuid::uuid7()->toString(),
                'code' => strtoupper(trim($postData['data_code'])),
                'name' => ucwords(trim($postData['data_name'])),
                'description' => trim($postData['data_remark']),
                'created_by' => session()->get('user_name'),
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[AbsenceStatusService::saveData] Failed to save new Absence Status data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to save new Absence Status data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[AbsenceStatusService::saveData] New Absence Status data was saved by {NIK} with new id {id}', ['NIK' => session()->get('user_name'), 'id' => $data['id']]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[AbsenceStatusService::saveData] Unexpexted error occured : {err} from {ip} on line {line}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR'], 'line' => $e->getLine()]);
            throw $e;
        }
    }

    public function getData(string $id): array
    {
        try {
            $get_data = $this->repository->find($id);

            if (!$get_data) {
                log_message('error', '[AbsenceStatusService::getData] Absence Status with id {id} not found', ['id' => $id]);
                throw new \Exception("Absence Status not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            $data = [
                'token' => enkripsi($get_data->id),
                'code' => $get_data->code,
                'name' => $get_data->name,
                'description' => $get_data->description
            ];

            return $data;
        } catch (\Exception $e) {
            log_message('error', '[AbsenceStatusService::getData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getDataByCode(string $code): array
    {
        try {
            $get_data = $this->repository->findDataByCode($code);

            if (!$get_data) {
                log_message('error', '[AbsenceStatusService::getDataByCode] Absence Status with code {code} not found', ['code' => $code]);
                throw new \Exception("Absence Status not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            $data = [
                'token' => enkripsi($get_data->id),
                'code' => $get_data->code,
                'name' => $get_data->name,
                'description' => $get_data->description
            ];

            return $data;
        } catch (\Exception $e) {
            log_message('error', '[AbsenceStatusService::getDataByCode] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function updateData(array $postData): bool
    {
        try {
            $this->validation->setRules(AbsenceStatusValidation::$update);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[AbsenceStatusService::updateData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $token = $postData['data_token'];
            $id = dekripsi($token);

            $find = $this->repository->find($id);
            if (!$find) {
                log_message('error', '[AbsenceStatusService::updateData] Absence Status with id {id} not found', ['id' => $id]);
                throw new \Exception("Absence Status not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            $newCode = strtoupper(trim($postData['data_code']));
            $newName = ucwords(trim($postData['data_name']));
            $newDescription = trim($postData['data_remark']);

            if ($find->code !== $newCode) {
                $check_code = $this->repository->findDataByCode($newCode);
                if ($check_code) {
                    log_message('error', '[AbsenceStatusService::updateData] Absence Status code {code} already exist', ['code' => strtoupper($newCode)]);
                    throw new \Exception("Absence Status code already exist", ResponseInterface::HTTP_BAD_REQUEST);
                }
            }

            $data = [
                'code' => $newCode,
                'name' => $newName,
                'description' => $newDescription,
                'updated_by' => session()->get('user_name'),
            ];

            $this->db->transStart();
            $this->repository->update($id, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[AbsenceStatusService::updateData] Failed to update Absence Status data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to update Absence Status data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[AbsenceStatusService::updateData] Absence Status data was updated by {NIK} with id {id}', ['NIK' => session()->get('user_name'), 'id' => $id]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[AbsenceStatusService::updateData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
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
                log_message('error', '[AbsenceStatusService::deleteData] Failed to delete Absence Status data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to delete Absence Status data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[AbsenceStatusService::deleteData] Absence Status data was deleted by {NIK} with total deleted data {total}', ['NIK' => session()->get('user_name'), 'total' => count($id)]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[AbsenceStatusService::deleteData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            $file_name = "absence_status_list" . date("Ymd_his") . '.xlsx';

            $headers = [
                'Absence Status Code',
                'Absence Status Name',
                'Description'
            ];

            $dataCallBack = function ($offset, $limit) {
                $column = 'code, name, description';
                return $this->repository->getChunkedData($offset, $limit, 'code', $column);
            };

            return export_to_excel($file_name, $headers, $dataCallBack);
        } catch (\Exception $e) {
            log_message('error', '[AbsenceStatusService::exportData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getAllData()
    {
        try {
            return $this->repository->all('code', 'asc');
        } catch (\Exception $e) {
            log_message('error', '[AbsenceStatusService::getAllData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
