<?php

namespace App\Services\PeriodSetup;

use App\Repositories\PeriodSetup\PeriodRepository;
use App\Validation\PeriodSetup\PeriodValidation;
use CodeIgniter\HTTP\ResponseInterface;
use Ramsey\Uuid\Uuid;
use Config\Database;
use Config\Services;

class PeriodService
{
    protected $repository;
    protected $db;
    protected $validation;

    public function __construct(PeriodRepository $repo)
    {
        $this->db = Database::connect();
        $this->validation = Services::validation();
        $this->repository = $repo;
    }

    public function saveData(array $postData)
    {
        try {
            $this->validation->setRules(PeriodValidation::$save);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[PeriodService::saveData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            if ($postData['data_tgl_awal'] > $postData['data_tgl_akhir']) {
                log_message('error', '[PeriodService::saveData] Validation error : {err} from {ip}', ['err' => 'Start date cannot be greater than end date', 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Start date cannot be greater than end date', ResponseInterface::HTTP_BAD_REQUEST);
            }

            $data = [
                'id' => Uuid::uuid7()->toString(),
                'tgl_awal' => $postData['data_tgl_awal'],
                'tgl_akhir' => $postData['data_tgl_akhir'],
                'user_name' => $postData['data_user_name'],
                'created_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[PeriodService::saveData] Failed to save new period data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->getLastQuery()]);
                throw new \Exception($this->db->error()['message'], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[PeriodService::saveData] Success to save new period data by {NIK}', ['NIK' => session()->get('user_name')]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[PeriodService::saveData] Failed to save new period data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $e->getMessage()]);
            throw $e;
        }
    }

    public function getData(string $user_name)
    {
        try {
            $get_data = $this->repository->findDataByUser($user_name);

            return $get_data;
        } catch (\Exception $e) {
            log_message('error', '[PeriodService::getData] Failed to get period data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $e->getMessage()]);
            throw $e;
        }
    }

    public function getAllPeriod()
    {
        try {
            $get_data = $this->repository->findDataByUser('all');

            return $get_data;
        } catch (\Exception $e) {
            log_message('error', '[PeriodService::getAllPeriod] Failed to get period data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $e->getMessage()]);
            throw $e;
        }
    }

    public function getMyPeriod(string $user_name)
    {
        try {
            $get_data = $this->repository->findDataByUser($user_name);

            return $get_data;
        } catch (\Exception $e) {
            log_message('error', '[PeriodService::getMyPeriod] Failed to get period data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateData(array $postData)
    {
        try {
            $this->validation->setRules(PeriodValidation::$update);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[PeriodService::updateData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $user_name = $postData['data_user_name'];

            if ($postData['data_tgl_awal'] > $postData['data_tgl_akhir']) {
                log_message('error', '[PeriodService::updateData] Validation error : {err} from {ip}', ['err' => 'Start date cannot be greater than end date', 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Start date cannot be greater than end date', ResponseInterface::HTTP_BAD_REQUEST);
            }

            $data = [
                'tgl_awal' => $postData['data_tgl_awal'],
                'tgl_akhir' => $postData['data_tgl_akhir'],
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->updateByUser($user_name, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[PeriodService::updateData] Failed to update period data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception($this->db->error()['message'], ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[PeriodService::updateData] Success to update period data by {NIK}', ['NIK' => session()->get('user_name')]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[PeriodService::updateData] Failed to update period data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $e->getMessage()]);
            throw $e;
        }
    }
}
