<?php

namespace App\Services\Tonnage;


use App\Models\AppSetup\Tonnage\TonnageModel;
use App\Repositories\Tonnage\TonnageRepository;
use App\Repositories\DataTableRepository;
use App\Validation\Tonnage\TonnageValidation;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Config\Database;

class TonnageService
{
    protected $db;
    protected $validasi;
    protected $repository;

    public function __construct(TonnageRepository $tonnage)
    {
        $this->db = Database::connect();
        $this->validasi = Services::validation();
        $this->repository = $tonnage;
    }

    public function loadTable($requestedData)
    {
        try {
            $model = new TonnageModel();
            $builder = $model->builder();

            $column_search = ['code', 'name', 'debugging', 'description'];
            $column_order = [
                '0' => 'code',
                '1' => 'name',
                '2' => 'debugging',
                '3' => 'description'
            ];

            $defaultOrder = ['code' => 'asc'];

            $dataTable = new DataTableRepository($builder, $column_search, $column_order, $defaultOrder, [], 'deleted_at');

            $result = $dataTable->proses($requestedData);

            $formattedData = [];

            foreach ($result['data'] as $row) {
                $formattedData[] = [
                    enkripsi($row->id),
                    '<a href="#" class="text-primary fw-bolder text-decoration-none" title="Click to edit" onclick="getData(`' . enkripsi($row->id) . '`)">' . $row->code . '</a>',
                    $row->name,
                    $row->debugging . " Kg",
                    $row->description
                ];
            }

            $result['data'] = $formattedData;

            return $result;
        } catch (\Exception $e) {
            log_message('error', '[TonnageService::loadTable] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function saveData(array $data)
    {
        try {
            $this->validasi->setRules(TonnageValidation::$save);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message('error', '[TonnageService::save] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $data = [
                'id' => uuid_v7(),
                'code' => $this->repository->generateCode('TNG-', 'code', 4),
                'name' => ucwords(trim($data['data_name'])),
                'debugging' => (float)$data['data_debugging'],
                'description' => trim($data['data_remark']),
                'created_by' => session()->get('user_name'),
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[TonnageService::save] Failed to save new machine tonnage data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to save new machine tonnage data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[TonnageService::save] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $getData = $this->repository->find($id);
            if (!$getData) {
                log_message('error', '[TonnageService::getData] Data token $id by {NIK} from {ip} with error {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => 'Data not found']);
                throw new \Exception("Data not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            return [
                'token' => enkripsi($getData->id),
                'code' => $getData->code,
                'name' => $getData->name,
                'debugging' => $getData->debugging,
                'description' => $getData->description
            ];
        } catch (\Exception $e) {
            log_message('error', '[TonnageService::getData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function updateData(array $data)
    {
        try {
            $this->validasi->setRules(TonnageValidation::$update);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message('error', '[TonnageService::updateData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = dekripsi($data['data_token']);

            $data = [
                'name' => ucwords(trim($data['data_name'])),
                'debugging' => (float)$data['data_debugging'],
                'description' => trim($data['data_remark']),
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->update($id, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[TonnageService::updateData] Failed to update machine tonnage data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to update machine tonnage data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[TonnageService::updateData] Updated machine tonnage data by {NIK} : {id}', ['NIK' => session()->get('user_name'), 'id' => $id]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[TonnageService::updateData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function deleteData(array $id)
    {
        try {
            $this->db->transStart();
            $this->repository->massDelete($id);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[TonnageService::deleteData] Failed to delete machine tonnage data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to delete machine tonnage data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[TonnageService::deleteData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            $fileName = "machine_tonnage_list_" . date("Ymd_his") . '.xlsx';

            $headers = [
                'Code',
                'Name',
                'Debugging',
                'Remark'
            ];

            $dataCallback = function ($offset, $limit) {
                $column = 'code, name, debugging, description';
                return $this->repository->getChunkedData($offset, $limit, 'code', $column);
            };

            return export_to_excel($fileName, $headers, $dataCallback);
        } catch (\Exception $e) {
            log_message('error', '[TonnageService::exportData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    function loadAllData()
    {
        try {
            return $this->repository->all('code', 'ASC');
        } catch (\Exception $e) {
            log_message('error', '[TonnageService::loadAllData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
