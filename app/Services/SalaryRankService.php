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

    public function loadData()
    {
        return $this->salaryRepo->all('code', 'asc');
    }

    public function loadTable($requestedData)
    {
        try {
            $model = new SalaryRankModel();
            $builder = $model->builder();

            $column_search = ['code', 'name', 'effective_date', 'from_salary', 'to_salary', 'description'];
            $column_order = [
                '0' => 'code',
                '1' => 'name',
                '2' => 'effective_date',
                '3' => 'from_salary',
                '4' => 'to_salary',
                '5' => 'description'
            ];

            $order = ['code' => 'asc'];

            $dataTable = new DataTableRepository($builder, $column_search, $column_order, $order, [], 'deleted_at');
            $result = $dataTable->proses($requestedData);
            $data = [];
            foreach ($result['data'] as $row) {
                $data[] = [
                    enkripsi($row->id),
                    '<a href="#" class="text-primary fw-bolder text-decoration-none" onclick="getData(`' . enkripsi($row->id) . '`)">' . $row->code . '</a>',
                    $row->name,
                    date("d F Y", strtotime($row->effective_date)),
                    number_to_currency($row->from_salary, 'IDR', 'id_ID', 2) . ' - ' . number_to_currency($row->to_salary, 'IDR', 'id_ID', 2),
                    $row->description
                ];
            }

            $result['data'] = $data;
            return $result;
        } catch (\Exception $e) {
            log_message('error', '[NbhxPositionService::loadTable] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function save(array $postData)
    {
        try {
            $this->validasi->setRules(SalaryRankValidation::$save);

            if ($this->validasi->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message('error', '[SalaryRankService::save] Validation failed by {NIK} from {ip} with error : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $error_to_string]);
                throw new \Exception("Validation failed <br>$error_to_string", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if ($postData['data_salary_from'] > $postData['data_salary_to']) {
                throw new \Exception("Salary range is not valid", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $data = [
                'id' => uuid_v7(),
                'code' => $this->salaryRepo->getNewCode(),
                'name' => trim($postData['data_name']),
                'effective_date' => trim($postData['effective_date']),
                'from_salary' => trim($postData['data_salary_from']),
                'to_salary' => trim($postData['data_salary_to']),
                'description' => trim($postData['data_remark']),
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
            throw $e;
        }
    }

    public function getData(string $id)
    {
        $get_data = $this->salaryRepo->findData($id);

        if (!$get_data) {
            throw new \Exception("Data not found", ResponseInterface::HTTP_NOT_FOUND);
        }

        $data = [
            'token' => enkripsi($get_data->id),
            'code' => $get_data->code,
            'name' => $get_data->name,
            'effective_date' => $get_data->effective_date,
            'from_salary' => $get_data->from_salary,
            'to_salary' => $get_data->to_salary,
            'description' => $get_data->description
        ];

        return $data;
    }

    public function updateData(array $postData)
    {
        try {
            $this->validasi->setRules(SalaryRankValidation::$update);

            if ($this->validasi->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message('error', '[SalaryRankService::updateData] Validation failed by {NIK} from {ip} with error : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $error_to_string]);
                throw new \Exception("Validation failed <br>$error_to_string", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if ($postData['data_salary_from'] > $postData['data_salary_to']) {
                throw new \Exception("Salary range is not valid", ResponseInterface::HTTP_BAD_REQUEST);
            }


            $token = $postData['data_token'];
            $id = dekripsi($token);

            $data = [
                'name' => trim($postData['data_name']),
                'effective_date' => trim($postData['effective_date']),
                'from_salary' => trim($postData['data_salary_from']),
                'to_salary' => trim($postData['data_salary_to']),
                'description' => trim($postData['data_remark']),
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->salaryRepo->update($id, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                log_message('error', '[SalaryRankService::updateData] Failed to update salary rank data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception("Failed to update salary rank data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[SalaryRankService::updateData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportToExcel()
    {
        try {
            $fileName = "nbhx_position_category_list" . date("Ymd_his") . '.xlsx';

            $headers = [
                "Code",
                "Name",
                "Effective Date",
                "Remark"
            ];

            $dataCallback = function ($offset, $limit) {
                $column = 'code, name, effective_date, description';
                return $this->salaryRepo->chunkedData($offset, $limit, 'code', $column);
            };

            return export_to_excel($fileName, $headers, $dataCallback);
        } catch (\Exception $e) {
            log_message('error', '[SalaryRankService::export] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
