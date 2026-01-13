<?php

namespace App\Services;

use App\Models\AppSetup\Country\CountryModel;
use App\Repositories\CountryRepository;
use App\Validation\CountryValidation;
use App\Repositories\DataTableRepository;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Config\Database;

class CountryService
{
    protected $db;
    protected $validasi;
    protected $dataTable;
    protected $countryRepo;

    public function __construct(CountryRepository $countryRepo)
    {
        $this->db = Database::connect();
        $this->validasi = Services::validation();
        $this->countryRepo = $countryRepo;
    }

    public function saveData(array $data)
    {
        try {
            $this->validasi->setRules(CountryValidation::$save);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message('error', '[CountryService::save] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $data = [
                'id' => generate_uuid(),
                'code' => $this->countryRepo->getNewCode(),
                'name' => ucwords(trim($data['data_name'])),
                'description' => trim($data['data_remark']),
                'created_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->countryRepo->saveData($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[CountryService::save] Failed to save new country data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to save new country data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[CountryService::save] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    function loadTable($requestedData)
    {
        $model = new CountryModel();
        $builder = $model->builder();

        $column_search = ['code', 'name', 'description'];
        $column_order = [
            '0' => 'code',
            '1' => 'name',
            '2' => 'description'
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
                $row->description
            ];
        }

        $result['data'] = $formattedData;

        return $result;
    }

    public function getData(string $id)
    {
        try {
            $getData = $this->countryRepo->findData($id);
            if (!$getData) {
                log_message('error', "[CountryService::getData] Data token $id by {NIK} from {ip} with error {err}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => 'Data not found']);
                throw new \Exception("Data not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            return [
                'token' => enkripsi($getData->id),
                'code' => $getData->code,
                'name' => $getData->name,
                'description' => $getData->description
            ];
        } catch (\Exception $e) {
            log_message('error', "[CountryService::getData] Unexpexted error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function update(array $data)
    {
        try {
            $this->validasi->setRules(CountryValidation::$update);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message('error', '[CountryService::update] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id_country = dekripsi($data['data_token']);

            $data = [
                'name' => ucwords(trim($data['data_name'])),
                'description' => trim($data['data_remark']),
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->countryRepo->updateData($id_country, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[CountryService::update] Failed to update country data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to update country data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[CountryService::update] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function delete(array $data)
    {
        try {
            $this->db->transStart();
            $this->countryRepo->deleteData($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[CountryService::delete] Failed to delete country data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to delete country data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[CountryService::delete] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            $fileName = "country_list" . date("Ymd_his") . '.xlsx';

            $headers = [
                'Country Code',
                'Country Name',
                'Remark'
            ];

            $dataCallback = function ($offset, $limit) {
                $column = 'code, name, description';
                return $this->countryRepo->chunkedData($offset, $limit, 'code', $column);
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
            return $this->countryRepo->all('code', 'ASC');
        } catch (\Exception $e) {
            log_message('error', '[CountryService::loadAllData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
