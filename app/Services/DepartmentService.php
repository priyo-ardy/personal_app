<?php

namespace App\Services;

use App\Models\AppSetup\Department\DepartmentModel;
use App\Repositories\DepartmentRepository;
use App\Validation\DepartmentValidation;
use App\Repositories\DataTableRepository;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Config\Database;

class DepartmentService
{
    protected $db;
    protected $validasi;
    protected $dataTable;
    protected $deptRepo;

    public function __construct(DepartmentRepository $deptRepo)
    {
        $this->db = Database::connect();
        $this->validasi = Services::validation();
        $this->deptRepo = $deptRepo;
    }

    function save(array $data)
    {
        try {
            $this->validasi->setRules(DepartmentValidation::$save);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message('error', "[DepartmentService::save] Failed to verify new department data by {NIK} : {err}", ['NIK' => session()->get('user_name'), 'err' => $error_to_string]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $dept_code = $this->deptRepo->getNewDeptCode();

            $data = [
                'id' => generate_uuid(),
                'code' => $dept_code,
                'name' => ucwords(trim($data['data_name'])),
                'effective_date' => date("Y-m-d", strtotime(trim($data['effective_date']))),
                'description' => trim($data['data_remark']),
                'created_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->deptRepo->save($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', "[DepartmentService::save] Failed to save new department data by {NIK} DB error: {err}", ['NIK' => session()->get('user_name'), 'err' => json_encode($this->db->error())]);
                throw new \Exception("Failed to save new department data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[DepartmentService::save] Successfully saved a new department data with new department code : {code} by {NIK}', ['code' => $dept_code, 'NIK' => session()->get('user_name')]);

            return true;
        } catch (\Exception $e) {
            log_message('error', "[DepartmentService::save] Failed to save new department data by {NIK} : {err}", ['NIK' => session()->get('user_name'), 'err' => $e->getMessage()]);
            return $e;
        }
    }

    function loadTable($requestedData)
    {
        $model = new DepartmentModel();
        $builder = $model->builder();

        $column_search = ['code', 'name', 'effective_date', 'description'];
        $column_order = [
            '0' => 'code',
            '1' => 'name',
            '2' => 'effective_date',
            '3' => 'description'
        ];

        $defaultOrder = array('code' => 'asc');

        $dataTable = new DataTableRepository($builder, $column_search, $column_order, $defaultOrder, [], 'deleted_at');

        $result = $dataTable->proses($requestedData);

        $formattedData = [];

        foreach ($result['data'] as $row) {
            $formattedData[] = [
                enkripsi($row->id),
                '<a href="#" class="text-primary fw-bolder text-decoration-none" title="Click to edit" onclick="getData(`' . enkripsi($row->id) . '`)">' . $row->code . '</a>',
                $row->name,
                date("l, d F Y", strtotime($row->effective_date)),
                $row->description
            ];
        }

        $result['data'] = $formattedData;

        return $result;
    }

    function getUserData(string $id_dept)
    {
        try {
            $getData = $this->deptRepo->getUserData($id_dept);
            if ($getData) {
                $data = [
                    'token' => enkripsi($getData->id),
                    'code' => $getData->code,
                    'name' => $getData->name,
                    'effective_date' => $getData->effective_date,
                    'remark' => $getData->description
                ];

                return $data;
            }

            return false;
        } catch (\Exception $e) {
            log_message('error', "[] Unexpexted error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return $e;
        }
    }

    function update($data)
    {
        try {
            $this->validasi->setRules(DepartmentValidation::$update);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode(', ', $this->validasi->getErrors());
                log_message('error', '[DepartmentService::update] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id_dept = dekripsi($data['data_token']);

            $data = [
                'name' => ucwords(trim($data['data_name'])),
                'effective_date' => date("Y-m-d", strtotime(trim($data['effective_date']))),
                'description' => trim($data['data_remark']),
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->deptRepo->updateData($id_dept, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[DepartmentService::update], Failed to update data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to update department data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', "[DepartmentService::update] Unexpexted error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return $e;
        }
    }

    function massDelete(array $dept_data)
    {
        try {
            $this->db->transStart();
            $this->deptRepo->massDelete($dept_data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                log_message('error', '[DepartmentService::massDelete] Failed to delete data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to delete department data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            log_message('error', '[DepartmentService::massDelete] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return $e;
        }
    }

    function exportData()
    {
        try {
            $fileName = "department_list" . date("Ymd_his") . '.xlsx';

            $headers = [
                'Department Code',
                'Department Name',
                'Effective Date',
                'Remark'
            ];

            $dataCallback = function ($offset, $limit) {
                $column = 'code, name, effective_date, description';
                return $this->deptRepo->chunkedData($offset, $limit, 'code', $column);
            };

            return export_to_excel($fileName, $headers, $dataCallback);
        } catch (\Exception $e) {
            log_message('error', '[DepartmentService::exportData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return $e;
        }
    }

    function loadData()
    {
        return $this->deptRepo->all('code', 'asc');
    }
}
