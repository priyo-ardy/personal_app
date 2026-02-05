<?php

namespace App\Services\Employee;

use App\Repositories\Employee\EmployeeEducationRepository;
use App\Validation\Employee\EducationValidation;
use App\Models\MasterData\Employee\EducationModel;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Config\Services;

class EmployeeEducationService
{
    protected $repository;
    protected $db;
    protected $validation;

    public function __construct(EmployeeEducationRepository $repo)
    {
        $this->db = Database::connect();
        $this->validation = Services::validation();
        $this->repository = $repo;
    }

    public function saveData(array $postData)
    {
        try {
            $this->validation->setRules(EducationValidation::$save);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[EmployeeEducationService::saveData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $employee_id = dekripsi(trim($postData['data_token']));
            $data = [];
            $baris = 1;

            for ($i = 0; $i < count($postData['data_degree']); $i++) {
                $data[] = [
                    'id' => uuid_v7(),
                    'employee_id' => $employee_id,
                    'row_no' => $baris,
                    'degree' => trim($postData['data_degree'][$i]),
                    'major' => trim($postData['data_jurusan'][$i]),
                    'school_name' => trim($postData['data_sekolah'][$i]),
                    'year_graduated' => trim($postData['data_tahun_lulus'][$i]),
                    'remark' => trim($postData['data_remark'][$i]),
                    'created_by' => session()->get('user_name')
                ];

                $baris++;
            }

            $this->db->transStart();
            $this->repository->massSave($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[EmployeeEducationService::saveData] Failed to save data : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Failed to save data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[EmployeeEducationService::saveData] Successfully saved data by {NIK} from {ip}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return [
                'token' => enkripsi($employee_id)
            ];
        } catch (\Exception $e) {
            log_message('error', '[EmployeeEducationService::saveData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
