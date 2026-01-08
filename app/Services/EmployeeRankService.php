<?php

namespace App\Services;

use App\Repositories\EmployeeRankRepository;
use App\Validation\EmployeeRankValidation;
use App\Repositories\DataTableRepository;
use App\Models\AppSetup\EmployeeRank\EmployeeRankModel;
use App\Traits\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Config\Database;

class EmployeeRankService
{
    use ResponseTrait;

    protected $db;
    protected $model;
    protected $validasi;
    protected $dataTable;
    protected $rankRepo;

    public function __construct(EmployeeRankRepository $rankRepo)
    {
        $this->db = Database::connect();
        $this->validasi = Services::validation();
        $this->rankRepo = $rankRepo;
        $this->model = new EmployeeRankModel();
    }

    public function loadData()
    {
        return $this->rankRepo->all('code', 'asc');
    }

    public function save(array $data)
    {
        try {
            $this->validasi->setRules(EmployeeRankValidation::$save);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message(
                    'error',
                    '[EmployeeRankService::save] Validation failed by {NIK} from {ip} with error : {err}',
                    [
                        'NIK' => session()->get('user_name'),
                        'ip' => $_SERVER['REMOTE_ADDR'],
                        'err' => $error_to_string
                    ]
                );
                throw new \Exception("Validation failed <br>$error_to_string", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $data = [
                'id' => generate_uuid(),
                'code' => $this->rankRepo->getNewCode(),
                'name' => ucwords(trim($data['data_name'])),
                'effective_date' => trim($data['effective_date']),
                'description' => trim($data['data_remark']),
                'created_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->rankRepo->saveData($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                log_message(
                    'error',
                    '[EmployeeRankService::save] save failed by {NIK} from {ip} with error {err}',
                    [
                        'NIK' => session()->get('user_name'),
                        'ip' => $_SERVER['REMOTE_ADDR'],
                        'err' => $this->db->error()
                    ]
                );

                throw new \Exception("Failed to save employee rank", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message(
                'error',
                '[EmployeeRankService::save] save failed by {NIK} from {ip} with error {err}',
                [
                    'NIK' => session()->get('user_name'),
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'err' => $e->getMessage()
                ]
            );

            return $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $result = $this->rankRepo->findData($id);
            if (!$result) {
                log_message(
                    'error',
                    '[EmployeeRankService::getData] getData failed by {NIK} from {ip} with error {err}',
                    [
                        'NIK' => session()->get('user_name'),
                        'ip' => $_SERVER['REMOTE_ADDR'],
                        'err' => 'Data not found'
                    ]
                );
                throw new \Exception('Data not found', ResponseInterface::HTTP_NOT_FOUND);
            }

            $data = [
                'token' => enkripsi($result->id),
                'code' => $result->code,
                'name' => $result->name,
                'effective_date' => $result->effective_date,
                'description' => $result->description
            ];

            return $data;
        } catch (\Exception $e) {
            log_message(
                'error',
                '[EmployeeRankService::getData] getData failed by {NIK} from {ip} with error {err}',
                [
                    'NIK' => session()->get('user_name'),
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'err' => $e->getMessage()
                ]
            );

            return $e;
        }
    }

    public function update(array $data)
    {
        try {
            $this->validasi->setRules(EmployeeRankValidation::$update);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message(
                    'error',
                    '[EmployeeRankService::updateData] Validation failed by {NIK} from {ip} with error : {err}',
                    [
                        'NIK' => session()->get('user_name'),
                        'ip' => $_SERVER['REMOTE_ADDR'],
                        'err' => $error_to_string
                    ]
                );
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
            $this->rankRepo->updateData($id, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                log_message(
                    'error',
                    '[EmployeeRankService::updateData] updateData failed by {NIK} from {ip} with error {err}',
                    [
                        'NIK' => session()->get('user_name'),
                        'ip' => $_SERVER['REMOTE_ADDR'],
                        'err' => $this->db->error()
                    ]
                );

                throw new \Exception("Failed to update employee rank", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message(
                'error',
                '[EmployeeRankService::updateData] updateData failed by {NIK} from {ip} with error {err}',
                [
                    'NIK' => session()->get('user_name'),
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'err' => $e->getMessage()
                ]
            );

            return $e;
        }
    }

    public function delete($data)
    {
        try {
            $this->db->transStart();
            $this->rankRepo->deleteData($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                log_message(
                    'error',
                    '[EmployeeRankService::delete] delete failed by {NIK} from {ip} with error {err}',
                    [
                        'NIK' => session()->get('user_name'),
                        'ip' => $_SERVER['REMOTE_ADDR'],
                        'err' => $this->db->error()
                    ]
                );

                throw new \Exception("Failed to delete employee rank", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message(
                'error',
                '[EmployeeRankService::delete] delete failed by {NIK} from {ip} with error {err}',
                [
                    'NIK' => session()->get('user_name'),
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'err' => $e->getMessage()
                ]
            );

            return $e;
        }
    }

    public function loadTable($requestedData)
    {
        try {
            $model = new EmployeeRankModel();
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
            log_message('error', '[EmployeeRankService::loadTable] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return $e;
        }
    }

    public function export()
    {
        try {
            $fileName = "employee_rank_list" . date("Ymd_his") . '.xlsx';

            $headers = [
                'Rank Code',
                'Rank Name',
                'Effective Date',
                'Remark'
            ];

            $dataCallback = function ($offset, $limit) {
                $column = 'code, name, effective_date, description';
                return $this->rankRepo->chunkedData($offset, $limit, 'code', $column);
            };

            return export_to_excel($fileName, $headers, $dataCallback);
        } catch (\Exception $e) {
            log_message('error', '[EmployeeRankService::export] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return $e;
        }
    }
}
