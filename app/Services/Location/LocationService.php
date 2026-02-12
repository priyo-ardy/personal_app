<?php

namespace App\Services\Location;

use App\Repositories\Location\LocationRepository;
use App\Validation\Location\LocationValidation;
use App\Models\AppSetup\Location\LocationModel;
use App\Models\AppSetup\Location\VwLcoationModel;
use App\Repositories\DataTableRepository;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Config\Services;
use App\Traits\ResponseTrait;

class LocationService
{
    protected $repository;
    protected $db;
    protected $validation;

    public function __construct(LocationRepository $repo)
    {
        $this->db = Database::connect();
        $this->validation = Services::validation();
        $this->repository = $repo;
    }

    public function loadTable(array $requestedData)
    {
        try {
            $model = new VwLcoationModel();
            $builder = $model->builder();

            $column_search = ['code', 'factory_name', 'name', 'address', 'description'];
            $column_order = ['0' => 'code', '1' => 'factory_name', '2' => 'name', '3' => 'address', '4' => 'description'];
            $default_order = array('code' => 'asc');

            $data_table = new DataTableRepository($builder, $column_search, $column_order, $default_order, [], 'deleted_at');

            $result = $data_table->proses($requestedData);
            $formatedData = [];

            foreach ($result['data'] as $row) {
                $formatedData[] = [
                    enkripsi($row->id),
                    '<a href="#" class="text-primary fw-bolder text-decoration-none" title="Click to edit" onclick="getData(`' . enkripsi($row->id) . '`)">' . $row->code . '</a>',
                    $row->factory_name,
                    $row->name,
                    $row->address,
                    $row->description,
                ];
            }

            $result['data'] = $formatedData;

            return $result;
        } catch (\Exception $e) {
            log_message('error', '[LocationService::loadTable] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function saveData(array $postData)
    {
        try {
            $this->validation->setRules(LocationValidation::$save);

            if (!$this->validation->run($postData)) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[LocationService::saveData] Validation error occured : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $data = [
                'id' => uuid_v7(),
                'code' => $this->repository->generateCode('LCT-', 'code', 4),
                'factory' => $postData['data_factory'],
                'name' => ucwords(trim($postData['data_name'])),
                'address' => $postData['data_address'],
                'description' => $postData['data_remark'],
                'created_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[LocationService::saveData] Failed to save new location data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception("Failed to save new location data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[LocationService::saveData] New location data was saved by {NIK}', ['NIK' => session()->get('user_name')]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[LocationService::saveData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $getData = $this->repository->find($id);

            if (!$getData) {
                log_message('error', '[LocationService::getDat] Data token $id by {NIK} from {ip} with error {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => 'Data not found']);
                throw new \Exception("Data not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            return [
                'token' => enkripsi($getData->id),
                'code' => $getData->code,
                'factory' => $getData->factory,
                'name' => $getData->name,
                'address' => $getData->address,
                'description' => $getData->description
            ];
        } catch (\Exception $e) {
            log_message('error', '[LocationService::getDat] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function updateData(array $postData)
    {
        try {
            $this->validation->setRules(LocationValidation::$update);

            if (!$this->validation->run($postData)) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[LocationService::updateData] Validation error occured : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = dekripsi(trim($postData['data_token']));

            $data = [
                'factory' => $postData['data_factory'],
                'name' => ucwords(trim($postData['data_name'])),
                'address' => $postData['data_address'],
                'description' => $postData['data_remark'],
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->update($id, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[LocationService::updateData] Failed to update location data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception("Failed to update location data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[LocationService::updateData] Location data was updated by {NIK}', ['NIK' => session()->get('user_name')]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[LocationService::updateData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
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
                log_message('error', '[LocationService::deleteData] Failed to delete location data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception("Failed to delete location data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[LocationService::deleteData] Location data was deleted by {NIK}', ['NIK' => session()->get('user_name')]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[LocationService::deleteData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function  exportData()
    {
        try {
            $file_name = "location_list_" . date("Ymd_his") . '.xlsx';

            $headers = [
                'Location Code',
                'Factory',
                'Location Name',
                'Address',
                'Remark'
            ];

            $dataCallBack = function ($offset, $limit) {
                $column = 'code, factory_name, name, address, description';
                return $this->repository->chunkedData($offset, $limit, 'code', $column);
            };

            return export_to_excel($file_name, $headers, $dataCallBack);
        } catch (\Exception $e) {
            log_message('error', '[LocationService::exportData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getAllData()
    {
        try {
            return $this->repository->all('code', 'asc');
        } catch (\Exception $e) {
            log_message('error', '[LocationService::getAllData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
