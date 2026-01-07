<?php

namespace App\Services;

use App\Repositories\SalaryRankRepository;
use App\Validation\SalaryRankValidation;
use App\Repositories\DataTableRepository;
use App\Models\AppSetup\SalaryRank\SalaryRankModel;
use App\Traits\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Config\Database;

class SalaryRankService
{
    use ResponseTrait;
    protected $db;
    protected $validasi;
    protected $dataTable;
    protected $salaryRepo;

    public function __construct(SalaryRankRepository $salaryRank)
    {
        $this->db = Database::connect();
        $this->validasi = Services::validation();
        $this->salaryRepo = $salaryRank;
    }

    public function save(array $data)
    {
        try {
            $this->validasi->setRules(SalaryRankValidation::$save);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message('error', '[SalaryRankService::save] Validation failed by {NIK} from {ip} with error : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $error_to_string]);
                throw new \Exception("Validation failed <br>$error_to_string", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $data = [
                'id' => generate_uuid(),
                'code' => strtoupper(trim($data['data_code'])),
                'name' => trim($data['data_name']),
                'effective_date' => trim($data['effective_date']),
                'salary' => trim($data['data_salary']),
                'description' => trim($data['data_remark']),
                'created_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->salaryRepo->saveData($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                log_message('error', '[SalaryRankService::save] Failed to save salary rank data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception("Failed to save salary rank data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[SalaryRankService::save] Unexpected error occured for user {NIK} from {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return $e;
        }
    }
}
