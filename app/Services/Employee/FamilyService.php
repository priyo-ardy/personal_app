<?php

namespace App\Services\Employee;

use App\Repositories\Employee\FamilyRepository;
use App\Models\MasterData\Employee\FamilyModel;
use App\Validation\Employee\FamilyValidation;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Config\Services;
use App\Traits\ResponseTrait;


class FamilyService
{
    protected $db;
    protected $validation;
    protected $repository;
    use ResponseTrait;

    public function __construct(FamilyRepository $repo)
    {
        $this->db = Database::connect();
        $this->validation = Services::validation();
        $this->repository = $repo;
    }

    public function saveData(array $postData)
    {
        try {
            $this->validation->setRules(FamilyValidation::$save);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[FamilyService::saveData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $employee_id = dekripsi(trim($postData['data_token']));
            $saveData = [];
            $row = 1;

            for ($i = 0; $i < count($postData['data_relation']); $i++) {
                $saveData[] = [
                    'id' => uuid_v7(),
                    'employee_id' => $employee_id,
                    'row_no' => $row,
                    'relation' => $postData['data_relation'][$i],
                    'name' => $postData['data_name'][$i],
                    'ocupation' => $postData['data_ocupation'][$i],
                    'remark' => $postData['data_remark'][$i],
                    'created_by' => session()->get('user_name')
                ];

                $row++;
            }

            $this->db->transStart();
            $this->repository->massSave($saveData);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[FamilyService::saveData] Failed to save data : {err} from {ip}', ['err' => $this->db->error(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Failed to save data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[FamilyService::saveData] Family data was saved by {NIK}', ['NIK' => session()->get('user_name')]);
            return [
                'status' => true,
                'token' => enkripsi($employee_id)
            ];
        } catch (\Exception $e) {
            log_message('error', '[FamilyService::saveData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
