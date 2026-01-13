<?php

namespace App\Services;

use App\Models\AppSetup\Province\ProvinceModel;
use App\Models\AppSetup\Province\VwProvinceModel;
use App\Repositories\ProvinceRepository;
use App\Validation\ProvinceValidation;
use App\Repositories\DataTableRepository;
use CodeIgniter\Config\Services as ConfigServices;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Config\Database;


class ProvinceService
{
    protected $db;
    protected $validasi;
    protected $dataTable;
    protected $provinceRepo;

    public function __construct(ProvinceRepository $provinceRepo)
    {
        $this->db = Database::connect();
        $this->validasi = Services::validation();
        $this->provinceRepo = $provinceRepo;
    }

    public function saveData(array $data)
    {
        try {
            $this->validasi->setRules(ProvinceValidation::$save);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message('error', '[ProvinceService::save] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $data = [
                'id' => generate_uuid(),
                'code' => $this->provinceRepo->generateCode('PRV-', 'code', 4),
                'country' => trim($data['data_country']),
                'name' => ucwords(trim($data['data_name'])),
                'description' => trim($data['data_remark']),
                'created_by' => session()->get('id'),
            ];

            $this->db->transStart();
            $this->provinceRepo->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[ProvinceService::save] Failed to save new province data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to save new province data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[ProvinceService::save] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function loadTable($requestedData)
    {
        try {
            $model = new VwProvinceModel();
            $builder = $model->builder();

            $column_search = ['code', 'country_name', 'name', 'description'];
            $column_order = [
                '0' => 'code',
                '1' => 'country_name',
                '2' => 'name',
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
                    $row->country_name,
                    $row->name,
                    $row->description
                ];
            }

            $result['data'] = $formattedData;

            return $result;
        } catch (\Exception $e) {
            log_message('error', '[ProvinceService::loadTable] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $getData = $this->provinceRepo->find($id);
            if (!$getData) {
                log_message('error', '[ProvinceService::getData] Data token $id by {NIK} from {ip} with error {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => 'Data not found']);
                throw new \Exception("Data not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            return [
                'token' => enkripsi($getData->id),
                'code' => $getData->code,
                'country' => $getData->country,
                'name' => $getData->name,
                'description' => $getData->description
            ];
        } catch (\Exception $e) {
            log_message('error', '[ProvinceService::getData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function updateData(array $data)
    {
        try {
            $this->validasi->setRules(ProvinceValidation::$update);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message('error', '[ProvinceService::updateData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id_province = dekripsi($data['data_token']);

            $data = [
                'country' => trim($data['data_country']),
                'name' => ucwords(trim($data['data_name'])),
                'description' => trim($data['data_remark']),
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->provinceRepo->update($id_province, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[ProvinceService::updateData] Failed to update province data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to update province data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[ProvinceService::updateData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function deleteData(array $id)
    {
        try {
            $this->db->transStart();
            $this->provinceRepo->massDelete($id);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[ProvinceService::deleteData] Failed to delete province data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to delete province data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[ProvinceService::deleteData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            $fileName = "country_list" . date("Ymd_his") . '.xlsx';

            $headers = [
                'Province Code',
                'Country Name',
                'Province Name',
                'Remark'
            ];

            $dataCallback = function ($offset, $limit) {
                $column = 'code, country_name, name, description';
                return $this->provinceRepo->chunkedData($offset, $limit, 'code', $column);
            };

            return export_to_excel($fileName, $headers, $dataCallback);
        } catch (\Exception $e) {
            log_message('error', '[DepartmentService::exportData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    function loadAllData()
    {
        try {
            return $this->provinceRepo->all('code', 'ASC');
        } catch (\Exception $e) {
            log_message('error', '[ProvinceService::loadAllData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
