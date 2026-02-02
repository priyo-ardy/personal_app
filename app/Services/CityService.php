<?php

namespace App\Services;

use App\Models\AppSetup\City\CityModel;
use App\Models\AppSetup\City\VwCityModel;
use App\Repositories\CityRepository;
use App\Repositories\DataTableRepository;
use App\Traits\ResponseTrait;
use App\Validation\CityValidation;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Config\Database;

class CityService
{
    use ResponseTrait;
    protected $db;
    protected $validasi;
    protected $cityRepo;

    public function __construct(CityRepository $cityRepo)
    {
        $this->db = Database::connect();
        $this->validasi = Services::validation();
        $this->cityRepo = $cityRepo;
    }

    public function saveData(array $data)
    {
        try {
            $this->validasi->setRules(CityValidation::$save);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message('error', '[CityService::saveData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $data = [
                'id' => generate_uuid(),
                'code' => $this->cityRepo->generateCode('CTY-', 'code', 4),
                'province' => trim($data['data_province']),
                'name' => ucwords(trim($data['data_name'])),
                'description' => trim($data['data_remark']),
                'created_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->cityRepo->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[CityService::saveData] Failed to save new city data by {NIK} : {err}, last query : {last_query}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error(), $this->db->getLastQuery()]);
                throw new \Exception('Failed to save new city data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[CityService::saveData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function loadTable(array $requestData)
    {
        try {
            $model = new VwCityModel();
            $builder = $model->builder();

            $column_search = ['code', 'province_name', 'name', 'description'];
            $column_order = [
                '0' => 'code',
                '1' => 'province_name',
                '2' => 'name',
                '3' => 'description'
            ];

            $defaultOrder = ['code' => 'asc'];

            $dataTable = new DataTableRepository($builder, $column_search, $column_order, $defaultOrder, [], 'deleted_at');

            $result = $dataTable->proses($requestData);

            $formattedData = [];

            foreach ($result['data'] as $row) {
                $formattedData[] = [
                    enkripsi($row->id),
                    '<a href="#" class="text-primary fw-bolder text-decoration-none" title="Click to edit" onclick="getData(`' . enkripsi($row->id) . '`)">' . $row->code . '</a>',
                    $row->province_name,
                    $row->name,
                    $row->description
                ];
            }

            $result['data'] = $formattedData;

            return $result;
        } catch (\Exception $e) {
            log_message('error', '[CityService::loadTable] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $getData = $this->cityRepo->find($id);
            if (!$getData) {
                log_message('error', '[CityService::getData] Data token {id} by {NIK} from {ip} with error {err}', ['id' => $id, 'NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => 'Data not found']);
                throw new \Exception("Data not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            return [
                'token' => enkripsi($getData->id),
                'code' => $getData->code,
                'province' => $getData->province,
                'name' => $getData->name,
                'description' => $getData->description
            ];
        } catch (\Exception $e) {
            log_message('error', '[CityService::getData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function updateData(array $data)
    {
        try {
            $this->validasi->setRules(CityValidation::$update);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message('error', '[CityService::updateData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id_city = dekripsi($data['data_token']);

            $data = [
                'province' => trim($data['data_province']),
                'name' => ucwords(trim($data['data_name'])),
                'description' => trim($data['data_remark']),
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->cityRepo->update($id_city, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[CityService::updateData] Failed to update city data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to update city data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[CityService::updateData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function deleteData(array $id)
    {
        try {
            $this->db->transStart();
            $this->cityRepo->massDelete($id);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[CityService::deleteData] Failed to delete city data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to delete city data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[CityService::deleteData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            $fileName = 'city_list_' . date('Ymd_his') . '.xlsx';

            $headers = [
                'City Code',
                'Province Name',
                'City Name',
                'Remark'
            ];

            $dataCallback = function ($offset, $limit) {
                $column = 'code, province_name, name, description';
                return $this->cityRepo->chunkedData($offset, $limit, 'code', $column);
            };

            return export_to_excel($fileName, $headers, $dataCallback);
        } catch (\Exception $e) {
            log_message('error', '[CityService::exportData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function loadAllData()
    {
        try {
            return $this->cityRepo->all('code', 'ASC');
        } catch (\Exception $e) {
            log_message('error', '[CityService::loadAllData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getCityByProvince(string $province)
    {
        try {
            $get_city_list = $this->cityRepo->getCityList($province);
            if (!$get_city_list) {
                log_message('error', '[CityService::getCityByProvince] Data not found by {NIK} from {ip}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception('Data not found', ResponseInterface::HTTP_NOT_FOUND);
            }

            $list = [];

            foreach ($get_city_list as $row) {
                $list[] = [
                    'id' => $row['id'],
                    'name' => $row['name']
                ];
            }

            return $list;
        } catch (\Exception $e) {
            log_message('error', '[CityService::getCityByProvince] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
