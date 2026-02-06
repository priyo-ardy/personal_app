<?php

namespace App\Services\Employee;

use App\Repositories\Employee\EmployeeJobDataRepository;
use App\Models\MasterData\Employee\EmployeeJobDataModel;
use App\Validation\Employee\EmployeeJobDataValidation;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Config\Services;

class EmployeeJobDataService
{
    protected $db;
    protected $validation;
    protected $repository;

    public function __construct(EmployeeJobDataRepository $repo)
    {
        $this->db = Database::connect();
        $this->validation = Services::validation();
        $this->repository = $repo;
    }

    public function saveData(array $postData)
    {
        try {
            $this->validation->setRules(EmployeeJobDataValidation::save());

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[EmployeeJobDataService::saveData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $data = [
                'id' => uuid_v7(),
                'employee_id' => dekripsi(trim($postData['data_token'])),
                'action' => trim($postData['data_action']),
                'reason' => trim($postData['data_reason']),
                'effective_date' => trim($postData['effective_date']),
                'work_relationship' => trim($postData['data_hubungan_kerja']),
                'no_contract' => trim($postData['data_contract']),
                'durasi_kontrak' => (!empty($postData['data_durasi'])) ? trim($postData['data_durasi']) : 0,
                'tipe_durasi' => (!empty($postData['data_tipe_durasi'])) ? trim($postData['data_tipe_durasi']) : null,
                'akhir_kontrak' => (!empty($postData['data_akhir_kontrak'])) ? trim($postData['data_akhir_kontrak']) : null,
                'superior' => (!empty($postData['data_superior'])) ? trim($postData['data_superior']) : null,
                'status' => 1,
                'remark' => trim($postData['data_remark']),
                'created_by' => session()->get('user_name'),
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                log_message('error', '[EmployeeJobDataService::saveData] Transaction error from {ip} with error {err}', ['ip' => $_SERVER['REMOTE_ADDR'], 'err' => $this->db->getLastQuery()]);
                throw new \Exception("Transaction error", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[EmployeeJobDataService::saveData] Employee job data has been saved with new job data id {id}', ['ip' => $_SERVER['REMOTE_ADDR'], 'id' => $data['id']]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[EmployeeJobDataService::saveData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
