<?php

namespace App\Services\UoM;

use App\Models\AppSetup\UoM\UomModel;
use App\Repositories\DataTableRepository;
use App\Repositories\UoM\UomRepository;
use App\Validation\UoM\UomValidation;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Config\Database;

class UomService
{
    protected $db;
    protected $validation;
    protected $repository;

    public function __construct(UomRepository $repo)
    {
        $this->db = Database::connect();
        $this->validation = Services::validation();
        $this->repository = $repo;
    }

    public function loadTable(array $requestedData)
    {
        try {
            $model = new UomModel();
            $builder = $model->builder();

            $column_search = ['code', 'name', 'symbol', 'description'];
            $coumn_order = [
                '0' => 'code',
                '1' => 'name',
                '2' => 'symbol',
                '3' => 'description'
            ];
            $default_order = array('code' => 'asc');

            $dataTable = new DataTableRepository($builder, $column_search, $coumn_order, $default_order, [], 'deleted_at');

            $result = $dataTable->proses($requestedData);
            $formattedData = [];

            foreach ($result['data'] as $row) {
                $formattedData[] = [
                    enkripsi($row->id),
                    '<a href="#" class="text-primary fw-bolder text-decoration-none" title="Click to edit" onclick="getData(`' . enkripsi($row->id) . '`)">' . $row->code . '</a>',
                    $row->name,
                    $row->symbol,
                    $row->description
                ];
            }

            $result['data'] = $formattedData;

            return $result;
        } catch (\Exception $e) {
            log_message('error', '[UomService::loadTable] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function saveData(array $data)
    {
        try {
            $this->validation->setRules(UomValidation::$save);

            if ($this->validation->run($data) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[UomService::saveData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string);
            }

            $data = [
                'id' => generate_uuid(),
                'code' => $this->repository->generateCode('UOM-', 'code', 4),
                'name' => ucwords(trim($data['data_name'])),
                'symbol' => trim($data['data_symbol']),
                'description' => $data['data_remark'],
                'created_by' => session()->get('id'),
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[UomService::saveData] Failed to save new UoM data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception("Failed to save new UoM data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[UomService::saveData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $get_data = $this->repository->find($id);
            if (!$get_data) {
                log_message('error', '[UomService::getData] Data token {id} by {NIK} from {ip} with error {err}', ['id' => $id, 'NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => 'Data not found']);
                throw new \Exception("Data not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            return [
                'token' => enkripsi($get_data->id),
                'code' => $get_data->code,
                'name' => $get_data->name,
                'symbol' => $get_data->symbol,
                'description' => $get_data->description
            ];
        } catch (\Exception $e) {
            log_message('error', '[UomService::getData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }


    public function updateData(array $data)
    {
        try {
            $this->validation->setRules(UomValidation::$update);

            if ($this->validation->run($data) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[UomService::updateData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string);
            }

            $id = dekripsi($data['data_token']);

            $data = [
                'name' => ucwords(trim($data['data_name'])),
                'symbol' => trim($data['data_symbol']),
                'description' => $data['data_remark'],
                'updated_by' => session()->get('id')
            ];

            $this->db->transStart();
            $this->repository->update($id, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[UomService::updateData] Failed to update UoM data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception("Failed to update UoM data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[UomService::updateData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function deleteData(array $data)
    {
        try {
            $this->db->transStart();
            $this->repository->massDelete($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[UomService::deleteData] Failed to delete UoM data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception("Failed to delete UoM data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[UomService::deleteData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            $file_name = "uom_list_" . date("Ymd_his") . '.xlsx';

            $headers = [
                'UoM Code',
                'UoM Name',
                'UoM Symbol',
                'Remark'
            ];

            $dataCallBack = function ($offset, $limit) {
                $column = 'code, name, symbol, description';
                return $this->repository->getChunkedData($offset, $limit, 'code', $column);
            };

            return export_to_excel($file_name, $headers, $dataCallBack);
        } catch (\Exception $e) {
            log_message('error', '[UomService::exportData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getAllData()
    {
        try {
            $getData = $this->repository->all('code', 'asc');
            if (!$getData) {
                log_message('error', '[UomService::getAllData] Data UoM from {ip} with error {err}', ['ip' => $_SERVER['REMOTE_ADDR'], 'err' => 'Data not found']);
                throw new \Exception("Data not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            return $getData;
        } catch (\Exception $e) {
            log_message('error', '[UomService::getAllData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
