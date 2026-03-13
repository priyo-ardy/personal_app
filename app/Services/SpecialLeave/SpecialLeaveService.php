<?php

namespace App\Services\SpecialLeave;

use App\Models\AppSetup\SpecialLeave\SpecialLeaveModel;
use App\Repositories\SpecialLeave\SpecialLeaveRepository;
use App\Validation\SpecialLeave\SpecialLeaveValidation;
use App\Repositories\DataTableRepository;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Config\Services;
use Ramsey\Uuid\Uuid;

class SpecialLeaveService
{
    protected $repository;
    protected $db;
    protected $validation;

    public function __construct(SpecialLeaveRepository $repo)
    {
        $this->repository = $repo;
        $this->db = Database::connect();
        $this->validation = Services::validation();
    }

    public function loadTable(array $postData)
    {
        try {
            $model = new SpecialLeaveModel();
            $builder = $model->builder();

            $column_search = ['code', 'name',  'dokumen', 'remark'];
            $column_order = ['code', 'name', 'jml_hari', 'dokumen', 'upload_dokumen', 'remark'];
            $defaultOrder = array('code' => 'asc');

            $data_table = new DataTableRepository($builder, $column_search, $column_order, $defaultOrder, [], 'deleted_at');

            $result = $data_table->proses($postData);
            $formattedData = [];

            foreach ($result['data'] as $row) {
                $formattedData[] = [
                    enkripsi($row->id),
                    '<a href="#" class="text-primary fw-bolder text-decoration-none" title="Click to edit" onclick="getData(`' . enkripsi($row->id) . '`)">' . $row->code . '</a>',
                    $row->name,
                    $row->jml_hari,
                    $row->dokumen,
                    $row->upload_dokumen,
                    $row->remark
                ];
            }

            $result['data'] = $formattedData;

            return $result;
        } catch (\Exception $e) {
            log_message('error', '[SpecialLeaveService::loadTable] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function saveData(array $postData)
    {
        try {
            $this->validation->setRules(SpecialLeaveValidation::$save);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[SpecialLeaveService::saveData] Validation error occured : {err} from {ip}', ['err' => implode('\n', $this->validation->getErrors()), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $data = [
                'id' => Uuid::uuid7()->toString(),
                'code' => $this->repository->generateCode('SPL-', 'code', 6),
                'name' => ucwords(trim($postData['data_name'])),
                'jml_hari' => trim($postData['data_hari']),
                'dokumen' => trim($postData['data_dokumen']),
                'upload_dokumen' => trim($postData['data_upload_dokumen']),
                'remark' => trim($postData['data_remark']),
                'created_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[SpecialLeaveService::saveData] Transaction failed : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Transaction failed", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[SpecialLeaveService::saveData] Data saved successfully, id : {id}', ['id' => $data['id']]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[SpecialLeaveService::saveData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $get = $this->repository->find($id);

            if (!$get) {
                log_message('error', '[SpecialLeaveService::getData] Special leave data not found with id {id} from {ip}', ['id' => $id, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Special leave data not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            $data = [
                'token' => enkripsi($get->id),
                'code' => $get->code,
                'name' => $get->name,
                'jml_hari' => $get->jml_hari,
                'dokumen' => $get->dokumen,
                'upload_dokumen' => ($get->upload_dokumen == 't') ? 'true' : 'false',
                'remark' => $get->remark
            ];

            return $data;
        } catch (\Exception $e) {
            log_message('error', '[SpecialLeaveService::getData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function updateData(array $postData)
    {
        try {
            $this->validation->setRules(SpecialLeaveValidation::$update);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[SpecialLeaveService::updateData] Validation error : {err} from {ip}', ['err' => implode('\n', $this->validation->getErrors()), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = dekripsi(trim($postData['data_token']));

            $data = [
                'name' => ucwords(trim($postData['data_name'])),
                'jml_hari' => trim($postData['data_hari']),
                'dokumen' => trim($postData['data_dokumen']),
                'upload_dokumen' => trim($postData['data_upload_dokumen']),
                'remark' => trim($postData['data_remark']),
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->update($id, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[SpecialLeaveService::updateData] Transaction failed : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Transaction failed", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[SpecialLeaveService::updateData] Data updated successfully, id : {id}', ['id' => $id]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[SpecialLeaveService::updateData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
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
                log_message('error', '[SpecialLeaveService::deleteData] Transaction failed : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Transaction failed", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[SpecialLeaveService::deleteData] Data deleted successfully, id : {id}', ['id' => json_encode($id)]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[SpecialLeaveService::deleteData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            $file_name = "special_leave_list_" . date("Ymd_his") . '.xlsx';

            $headers = [
                'Code',
                'Name',
                'Leave Granted',
                'Supporting Document',
                'Upload Document',
                'Remark'
            ];

            $dataCallBack = function ($offset, $limit) {
                $columns = [
                    'code',
                    'name',
                    'jml_hari',
                    'dokumen',
                    'upload_dokumen',
                    'remark',
                ];

                return $this->repository->getChunkedData($offset, $limit, 'code', $columns);
            };

            return export_to_excel($file_name, $headers, $dataCallBack);
        } catch (\Exception $e) {
            log_message('error', '[SpecialLeaveService::exportData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getAllData()
    {
        try {
            $get = $this->repository->all('code', 'asc');

            return $get;
        } catch (\Exception $e) {
            log_message('error', '[SpecialLeaveService::getAllData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
