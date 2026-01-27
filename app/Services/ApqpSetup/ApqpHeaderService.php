<?php

namespace App\Services\ApqpSetup;

use App\Repositories\ApqpSetup\ApqpHeaderRepository;
use App\Repositories\DataTableRepository;
use App\Models\AppSetup\ApqpSetup\ApqpHeaderModel;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Config\Services;
use App\Traits\ResponseTrait;
use App\Validation\ApqpSetup\ApqpHeaderValidation;
use CodeIgniter\HTTP\Response;

class ApqpHeaderService
{
    protected $db;
    protected $validation;
    protected $repository;
    use ResponseTrait;

    public function __construct(ApqpHeaderRepository $repo)
    {
        $this->db = Database::connect();
        $this->validation = Services::validation();
        $this->repository = $repo;
    }

    public function loadTable(array $requestedData)
    {
        try {
            $model = new ApqpHeaderModel();
            $builder = $model->builder();

            $column_search = ['squence', 'name', 'description'];
            $column_order = ['0' => 'sequence', '1' => 'name', '2' => 'description'];
            $default_order = array('sequence' => 'asc');

            $data_table = new DataTableRepository($builder, $column_search, $column_order, $default_order, [], 'deleted_at');

            $result = $data_table->proses($requestedData);
            $formatedData = [];

            foreach ($result['data'] as $row) {
                $formatedData[] = [
                    enkripsi($row->id),
                    $row->sequence,
                    '<a href="#" class="text-primary fw-bolder text-decoration-none" title="Click to edit" onclick="getData(`' . enkripsi($row->id) . '`)">' . $row->name . '</a>',
                    '
                        <button type="button" class="btn btn-primary rounded-0 btn-sm d-block col-12" onclick="getApprover(`' . enkripsi($row->id) . '`)" title="View approver">
                            <i class="bi bi-diagram-3"></i>&ensp; Show Approver
                        </button>
                    ',
                    '
                        <button type="button" class="btn btn-primary rounded-0 btn-sm d-block col-12" onclick="getDocument(`' . enkripsi($row->id) . '`)" title="View approver">
                            <i class="bi bi-file-earmark-text"></i>&ensp; Show Document List
                        </button>
                    ',
                    $row->remark
                ];
            }

            $result['data'] = $formatedData;

            return $result;
        } catch (\Exception $e) {
            log_message('error', '[ApqpHeaderService::loadTable] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function saveData(array $postData)
    {
        try {
            $this->validation->setRules(ApqpHeaderValidation::$save);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[ApqpHeaderService::saveData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $data = [
                'id' => generate_uuid(),
                'sequence' => trim($postData['data_sequence']),
                'name' => trim($postData['data_name']),
                'description' => trim($postData['data_remark']),
                'created_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[ApqpHeaderService::saveData] Failed to save new apqp header data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception($this->db->error()['message'], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[ApqpHeaderService::saveData] Apqp Header data saved by {NIK}', ['NIK' => session()->get('user_name')]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[ApqpHeaderService::saveData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $get_data = $this->repository->find($id);

            if (!$get_data) {
                log_message('error', '[ApqpHeaderService::getData] Apqp Header with id {id} not found', ['id' => $id]);
                throw new \Exception("Apqp Header not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            return [
                'token' => enkripsi($get_data->id),
                'sequence' => $get_data->sequence,
                'name' => $get_data->name,
                'remark' => $get_data->remark
            ];
        } catch (\Exception $e) {
            log_message('error', '[ApqpHeaderService::getData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function updateData(array $postData)
    {
        try {
            $this->validation->setRules(ApqpHeaderValidation::$update);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[ApqpHeaderService::updateData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = dekripsi(trim($postData['data_token']));

            $data = [
                'sequence' => trim($postData['data_sequence']),
                'name' => trim($postData['data_name']),
                'remark' => trim($postData['data_remark']),
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->update($id, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[ApqpHeaderService::updateData] Failed to update apqp header data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception($this->db->error()['message'], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[ApqpHeaderService::updateData] Apqp Header data updated by {NIK}', ['NIK' => session()->get('user_name')]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[ApqpHeaderService::updateData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
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
                log_message('error', '[ApqpHeaderService::deleteData] Failed to delete apqp header data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception($this->db->error()['message'], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[ApqpHeaderService::deleteData] Apqp Header data was deleted by {NIK}', ['NIK' => session()->get('user_name')]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[ApqpHeaderService::deleteData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
