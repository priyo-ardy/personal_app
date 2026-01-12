<?php

namespace App\Services;

use App\Models\AppSetup\EmployeeCategory\EmployeeCategoryModel;
use App\Repositories\EmployeeCategoryRepository;
use App\Repositories\DataTableRepository;
use App\Validation\EmployeeCategoryValidation;
use CodeIgniter\HTTP\ResponsableInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Config\Database;

class EmployeeCategoryService
{
    protected $db;
    protected $validasi;
    protected $dataTable;
    protected $categoryRepo;

    public function __construct(EmployeeCategoryRepository $employeeCategoryRepo)
    {
        $this->db = Database::connect();
        $this->validasi = Services::validation();
        $this->categoryRepo = $employeeCategoryRepo;
    }

    function loadData()
    {
        return $this->categoryRepo->all('code', 'asc');
    }

    function loadTable($requestedData)
    {
        $model = new EmployeeCategoryModel();
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

    public function save(array $data)
    {
        try {
            $this->validasi->setRules(EmployeeCategoryValidation::$save);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message('error', '', []);
                throw new \Exception("Validation failed <br> $error_to_string", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $data = [
                'id' => generate_uuid(),
                'code' => $this->categoryRepo->getNewCode(),
                'name' => $data['data_name'],
                'effective_date' => $data['effective_date'],
                'description' => $data['data_remark'],
                'created_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->categoryRepo->save($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                log_message('error', '[EmployeeCategoryService::save] save failed by {NIK} from {ip} with error {err}', [
                    'NIK' => session()->get('user_name'),
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'err' => $this->db->error()
                ]);

                throw new \Exception("Failed to save new employee category data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[EmployeeCategoryService::save] save error by {NIK} form {ip}: {error}', [
                'NIK' => session()->get('user_name'),
                'ip' => $_SERVER['REMOTE_ADDR'],
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    function getData(string $id)
    {
        try {
            $getData = $this->categoryRepo->getById($id);
            if (!$getData) {
                throw new \Exception("Employee category data not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            return [
                'token' => enkripsi($getData->id),
                'code' => $getData->code,
                'name' => $getData->name,
                'effective_date' => $getData->effective_date,
                'description' => $getData->description
            ];
        } catch (\Exception $e) {
            log_message('error', '', []);

            throw $e;
        }
    }

    function update(array $data)
    {
        try {
            $this->validasi->setRules(EmployeeCategoryValidation::$update);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message(
                    'error',
                    '[EmployeeCategoryService::update] Validation failed by {NIK} from {ip} with error : {err}',
                    [
                        'NIK' => session()->get('user_name'),
                        'ip' => $_SERVER['REMOTE_ADDR'],
                        'err' => $error_to_string
                    ]
                );
                throw new \Exception("Validation failed <br> $error_to_string", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = dekripsi($data['data_token']);

            $data = [
                'name' => ucwords(trim($data['data_name'])),
                'effective_date' => trim($data['effective_date']),
                'description' => trim($data['data_remark']),
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->categoryRepo->updateData($id, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message(
                    'error',
                    '[EmployeeCategoryService::update] Failed to update employee category data by {NIK} from {ip} with error {err}',
                    [
                        'NIK' => session()->get('user_name'),
                        'ip' => $_SERVER['REMOTE_ADDR'],
                        'err' => $this->db->error()
                    ]
                );
            }

            return true;
        } catch (\Exception $e) {
            log_message(
                'error',
                '[EmployeeCategoryService::update] Failed to update employee category data by {NIK} from {ip} with error {err}',
                [
                    'NIK' => session()->get('user_name'),
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'err' => $e->getMessage()
                ]
            );

            throw $e;
        }
    }

    function delete($data)
    {
        try {
            $this->db->transStart();
            $this->categoryRepo->delete($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                log_message('error', '[EmployeeCategoryService::update] Failed to delete employee category data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to delete employee category data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            log_message('error', '[EmployeeCategoryService::update] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    function export()
    {
        try {
            $fileName = "employee_category_list" . date("Ymd_his") . '.xlsx';

            $headers = [
                'Category Code',
                'Category Name',
                'Effective Date',
                'Remark'
            ];

            $dataCallback = function ($offset, $limit) {
                $column = 'code, name, effective_date, description';
                return $this->categoryRepo->chunkedData($offset, $limit, 'code', $column);
            };

            return export_to_excel($fileName, $headers, $dataCallback);
        } catch (\Exception $e) {
            log_message('error', '[DepartmentService::exportData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
