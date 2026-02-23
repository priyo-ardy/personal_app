<?php

namespace App\Services\JobData;

use App\Repositories\JobData\JobDataRepository;
use App\Validation\JobData\JobDataValidation;
use App\Repositories\DataTableRepository;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Config\Services;
use App\Traits\KalkulasiTrait;

class JobDataService
{
    use KalkulasiTrait;
    protected $repository;
    protected $db;
    protected $validation;

    public function __construct(JobDataRepository $repo)
    {
        $this->repository = $repo;
        $this->db = Database::connect();
        $this->validation = Services::validation();
    }

    public function loadTable(array $postData)
    {
        try {
        } catch (\Exception $e) {
            log_message('error', '[JobDataService::loadTable] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function  saveData(array $postData)
    {
        try {
            $this->validation->setRules(JobDataValidation::save());

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[JobDataService::saveData] Validation error : {err} from {ip}', ['err' => implode('\n', $this->validation->getErrors()), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $remarkValue = "";
            if (!empty(trim($postData['data_remark']))) {
                $remarkValue = trim($postData['data_remark']);
            } elseif (trim($postData['data_relasi']) !== 'Tetap') {
                $remarkValue = $this->kalkulasi_durasi_kontrak(
                    trim($postData['effective_date']),
                    trim($postData['data_durasi']),
                    trim($postData['data_tipe_durasi'])
                );

                $remarkValue = $this->date_duration(trim($postData['effective_date']), $remarkValue);
            }

            $data = [
                'id' => uuid_v7(),
                'employee_id' => trim($postData['data_employee']),
                'action' => trim($postData['data_action']),
                'reason' => trim($postData['data_reason']),
                'position' => trim($postData['data_position']),
                'work_relationship' => trim($postData['data_relasi']),
                'effective_date' => trim($postData['effective_date']),
                'superior' => ($postData['data_superior']) ? trim($postData['data_superior']) : null,
                'no_contract' => trim($postData['data_contract']),
                'durasi_kontrak' => trim($postData['data_durasi']),
                'tipe_durasi' => trim($postData['data_tipe_durasi']),
                'akhir_kontrak' => trim($postData['data_akhir_kontrak']),
                'remark' => $remarkValue,
                'status' => '1',
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[JobDataService::saveData] Failed to save data : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Failed to save data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }
            log_message('info', '[JobDataService::saveData] Job data has been updated by {NIK}, query {query}', ['NIK' => session()->get('user_name'), 'query' => $this->db->getLastQuery()]);
            log_message('info', '[JobDataService::saveData] New job data has been registered to employee {employee} with id {id} from {ip}', ['employee' => $data['employee_id'], 'id' => $data['id'], 'ip' => $_SERVER['REMOTE_ADDR']]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[JobDataService::saveData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getJobDataInfo(string $id)
    {
        try {
            $result = $this->repository->find($id);

            if (!$result) {
                log_message('error', '[JobDataService::getJobDataInfo] Job data not found by {NIK} : {id}', ['NIK' => session()->get('user_name'), 'id' => $id]);
                throw new \Exception("Job data not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            $data = [
                'action' => $result->action,
                'reason' => $result->reason,
                'position' => $result->position,
                'effective_date' => $result->effective_date,
                'work_relationship' => $result->work_relationship,
                'no_contract' => $result->no_contract,
                'durasi_kontrak' => $result->durasi_kontrak,
                'akhir_kontrak' => $result->akhir_kontrak,
                'superior' => $result->superior,
                'remark' => $result->remark,
                'tipe_durasi' => $result->tipe_durasi,
                'position' => $result->position
            ];

            return $data;
        } catch (\Exception $e) {
            log_message('error', '[JobDataService::getJobDataInfo] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
