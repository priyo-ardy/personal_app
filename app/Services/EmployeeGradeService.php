<?php

namespace App\Services;

use App\Repositories\EmployeeGradeRepository;
use App\Validation\EmployeeGradeValidation;
use App\Repositories\DataTableRepository;
use App\Models\AppSetup\EmployeeGrade\EmployeeGradeModel;
use App\Traits\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Config\Database;

class EmployeeGradeService
{
    protected $db;
    protected $validasi;
    protected $dataTable;
    protected $employeeGradeRepo;
    use ResponseTrait;

    public function __construct(EmployeeGradeRepository $employeeGradeRepo)
    {
        $this->db = Database::connect();
        $this->validasi = Services::validation();
        $this->employeeGradeRepo = $employeeGradeRepo;
    }

    public function loadData()
    {
        return $this->employeeGradeRepo->all('code', 'asc');
    }

    function save(array $data)
    {
        try {
            $this->validasi->setRules(EmployeeGradeValidation::$save);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode('<br>', $this->validasi->getErrors());
                log_message('error', '[EmployeeGradeService::save] Failed to make validation for user {NIK} from {ip}, err : {err} ', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $error_to_string]);
                throw new \Exception("Validation failed <br>$error_to_string", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = uuid_v7();
            $code = $this->employeeGradeRepo->getNewEmployeeGradeCode();

            $data = [
                'id' => $id,
                'code' => $code,
                'name' => ucwords(trim($data['data_name'])),
                'effective_date' => trim($data['effective_date']),
                'description' => trim($data['data_remark']),
                'created_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->employeeGradeRepo->saveData($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                log_message('error', "[EmployeeGradeService::save] Failed to save employee grade for user {NIK} from {ip} with error : {err}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $this->db->error()]);
                throw new \Exception("Failed to save employee grade", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    function getData(string $id)
    {
        try {
            $get_data = $this->employeeGradeRepo->find($id);

            if (!$get_data) {
                log_message('error', '[EmployeeGradeService::getData] Failed to get employee grade data by {NIK} from {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => 'Data not found']);
                throw new \Exception('Data not found', ResponseInterface::HTTP_NOT_FOUND);
            }

            return [
                'token' => enkripsi($get_data->id),
                'code' => $get_data->code,
                'name' => $get_data->name,
                'effective_date' => $get_data->effective_date,
                'description' => $get_data->description,
            ];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    function update(array $data)
    {
        try {
            $this->validasi->setRules(EmployeeGradeValidation::$update);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode('<br>', $this->validasi->getErrors());
                log_message('error', '[EmployeeGradeService::update] Failed to make validation for user {NIK} from {ip}, err : {err} ', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $error_to_string]);
                throw new \Exception("Validation failed <br>$error_to_string", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = dekripsi($data['data_token']);

            $data = [
                'name' => ucwords(trim($data['data_name'])),
                'effective_date' => trim($data['effective_date']),
                'description' => trim($data['data_remark']),
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->employeeGradeRepo->updateData($id, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                log_message('error', "[EmployeeGradeService::update] Failed to update employee grade for user {NIK} from {ip} with error : {err}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $this->db->error()]);
                throw new \Exception("Failed to update employee grade", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    function delete(array $data)
    {
        try {
            $this->db->transStart();
            $this->employeeGradeRepo->deleteData($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                log_message('error', "[EmployeeGradeService::delete] Failed to delete employee grade for user {NIK} from {ip} with error : {err}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $this->db->error()]);
                throw new \Exception("Failed to delete employee grade", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    function loadTable($requestedData)
    {
        try {
            $model = new EmployeeGradeModel();
            $builder = $model->builder();

            $column_search = ['code', 'name', 'effective_date', 'description'];
            $column_order = [
                '0' => 'code',
                '1' => 'name',
                '2' => 'effective_date',
                '3' => 'description'
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
                    $row->description
                ];
            }

            $result['data'] = $data;
            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    function exportData()
    {
        try {
            $fileName = "employee_grade_list" . date("Ymd_his") . '.xlsx';

            $headers = [
                'Grade Code',
                'Grade Name',
                'Effective Date',
                'Remark'
            ];

            $dataCallback = function ($offset, $limit) {
                $column = 'code, name, effective_date, description';
                return $this->employeeGradeRepo->chunkedData($offset, $limit, 'code', $column);
            };

            return export_to_excel($fileName, $headers, $dataCallback);
        } catch (\Exception $e) {
            log_message('error', '[EmployeeGradeService::exportData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
