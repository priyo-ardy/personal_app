<?php

namespace App\Services\EquipmentType;


use App\Models\AppSetup\EquipmentType\EquipmentTypeModel;
use App\Repositories\EquipmentType\EquipmentTypeRepository;
use App\Validation\EquipmentType\EquipmentTypeValidation;
use App\Repositories\DataTableRepository;
use CodeIgniter\HTTP\ResponsableInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Config\Database;

class EquipmentTypeService
{
    protected $db;
    protected $validasi;
    protected $repository;

    public function __construct(EquipmentTypeRepository $repo)
    {
        $this->db = Database::connect();
        $this->validasi = Services::validation();
        $this->repository = $repo;
    }

    public function loadTable(array $requestedData)
    {
        try {
            $model = new EquipmentTypeModel();
            $builder = $model->builder();

            $column_search = ['code', 'name', 'description'];
            $column_order = [
                '0' => 'code',
                '1' => 'name',
                '2' => 'description'
            ];

            $defaultOrder = array('code' => 'asc');

            $data_table = new DataTableRepository($builder, $column_search, $column_order, $defaultOrder, [], 'deleted_at');

            $result = $data_table->proses($requestedData);

            $formattedData = [];

            foreach ($result['data'] as $row) {
                $formattedData[] = [
                    enkripsi($row->id),
                    '<a href="#" class="text-primary fw-bolder text-decoration-none" title="Click to edit" onclick="getData(`' . enkripsi($row->id) . '`)">' . $row->code . '</a>',
                    $row->name,
                    $row->description
                ];
            }

            $result['data'] = $formattedData;

            return $result;
        } catch (\Exception $e) {
            log_message('error', '[EquipmentTypeService::loadTable] failed to load data table by {NIK} from {ip} with error {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            throw $e;
        }
    }

    public function saveData(array $data)
    {
        try {
            $this->validasi->setRules(EquipmentTypeValidation::$save);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode('<br>', $this->validasi->getErrors());
                log_message('error', '[EquipmentTypeService::saveData] Failed to save a new equipment type data, validation failed with error {err} by {NIK} from {ip}', ['err' => $error_to_string, 'NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Validation failed $error_to_string", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = uuid_v7();

            $data = [
                'id' => $id,
                'code' => $this->repository->generateCode('EQT-', 'code', 4),
                'name' => ucwords(trim($data['data_name'])),
                'description' => trim($data['data_remark']),
                'created_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                log_message('error', '[EquipmentTypeservice::saveData] Failed to save a new equipment data with error {err} by {NIK} from {ip}', ['err' => $this->db->error(), 'NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Failed to save a new equipment type data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[EquipmentTypeService::saveData] New equipment type data saved successfully by {NIK} from {ip} with new token {token}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'token' => $id]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[EquipmentTypeService::saveData] Unexpected occured when saving equipement type data by {NIK} from {ip} with error {err} ', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            throw $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $get_data = $this->repository->find($id);
            if (!$get_data) {
                log_message('error', '[EquipmentTypeService::getData] Equipment type data not found by {NIK} from {ip}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Equipment type data not available", ResponseInterface::HTTP_NOT_FOUND);
            }

            return [
                'token' => enkripsi($get_data->id),
                'code' => $get_data->code,
                'name' => $get_data->name,
                'description' => $get_data->description
            ];
        } catch (\Exception $e) {
            log_message('error', '[EquipmentTypeService::getData] Unexpected occured when getting equipement type data by {NIK} from {ip} with error {err} ', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateData(array $data)
    {
        try {
            $this->validasi->setRules(EquipmentTypeValidation::$update);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode('<br>', $this->validasi->getErrors());
                log_message('error', '[EquipmentTypeService::updateData] Failed to update equipment type data, validation failed with error {err} by {NIK} from {ip}', ['err' => $error_to_string, 'NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Validation failed $error_to_string", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = dekripsi($data['data_token']);

            $data = [
                'name' => ucwords(trim($data['data_name'])),
                'description' => trim($data['data_remark']),
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->update($id, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                log_message('error', '[EquipmentTypeService::updateData] Failed update equipment data by {NIK} from {ip} with error {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $this->db->error()]);
                throw new \Exception("Failed to update equipment data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[EquipmentTypeService::updateData] Equipment data updated successfully by {NIK} from {ip} for data {token}', ['NIK' => session()->get('user_data'), 'ip' => $_SERVER['REMOTE_ADDR'], 'token' => $id]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[EquipmentTypeService::updateData] Unexpected occured when updated equipement type data by {NIK} from {ip} with error {err} ', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
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

                log_message('error', '[EquipmentTypeService::deleteData] Failed to delete equipment data by {NIK} from {ip} with error {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $this->db->error()]);
                throw new \Exception("Failed to delete equipment data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[EquipmentTypeService::deleteData] Equipment type data deleted successfully by {NIK} from {ip} with total data {total}', ['NIK' => session()->get('user_data'), 'ip' => $_SERVER['REMOTE_ADDR'], 'total' => count($data)]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[EquipmentTypeService::deleteData] Unexpected occured when deleted equipement type data by {NIK} from {ip} with error {err} ', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            $fileName = "section_list" . date("Ymd_his") . '.xlsx';

            $headers = [
                'Code',
                'Name',
                'Remark'
            ];

            $dataCallback = function ($offset, $limit) {
                $column = 'code, name, description';
                return $this->repository->getChunkedData($offset, $limit, 'code', $column);
            };

            return export_to_excel($fileName, $headers, $dataCallback);
        } catch (\Exception $e) {
            log_message('error', '[EquipmentTypeService::exportData] Unexpected occured when exported equipement type data by {NIK} from {ip} with error {err} ', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            throw $e;
        }
    }

    public function loadAllData()
    {
        try {
            return $this->repository->all('code', 'asc');
        } catch (\Exception $e) {
            log_message('error', '[EquipmentTypeService::loadAllData] Unexpected occured when load equipement type data by {NIK} from {ip} with error {err} ', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            throw $e;
        }
    }
}
