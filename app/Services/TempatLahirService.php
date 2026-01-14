<?php

namespace App\Services;


use App\Models\AppSetup\TempatLahir\TempatLahirModel;
use App\Repositories\TempatLahirRepository;
use App\Repositories\DataTableRepository;
use App\Validation\TempatLahirValidation;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Config\Database;

class TempatLahirService
{
    protected $db;
    protected $validasi;
    protected $dataTable;
    protected $tempatLahirRepo;

    public function __construct(TempatLahirRepository $tempatLahir)
    {
        $this->db = Database::connect();
        $this->validasi = Services::validation();
        $this->tempatLahirRepo = $tempatLahir;
    }

    public function saveData(array $data)
    {
        try {
            $this->validasi->setRules(TempatLahirValidation::$save);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message('error', '[TempatLahirService::save] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $data = [
                'id' => generate_uuid(),
                'code' => $this->tempatLahirRepo->generateCode('TTL-', 'code', 4),
                'name' => ucwords(trim($data['data_name'])),
                'description' => trim($data['data_remark']),
                'created_by' => session()->get('id'),
            ];

            $this->db->transStart();
            $this->tempatLahirRepo->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[TempatLahirService::save] Failed to save new birth place data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to save new birth place data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[TempatLahirService::save] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function loadTable($requestedData)
    {
        try {
            $model = new TempatLahirModel();
            $builder = $model->builder();

            $column_search = ['code', 'name', 'description'];
            $column_order = [
                '0' => 'code',
                '1' => 'name',
                '2' => 'description'
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
                    $row->description
                ];
            }

            $result['data'] = $formattedData;

            return $result;
        } catch (\Exception $e) {
            log_message('error', '[TempatLahirService::loadTable] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $getData = $this->tempatLahirRepo->find($id);
            if (!$getData) {
                log_message('error', '[TempatLahirService::getData] Data token $id by {NIK} from {ip} with error {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => 'Data not found']);
                throw new \Exception("Data not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            return [
                'token' => enkripsi($getData->id),
                'code' => $getData->code,
                'name' => $getData->name,
                'description' => $getData->description
            ];
        } catch (\Exception $e) {
            log_message('error', '[TempatLahirService::getData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function updateData(array $data)
    {
        try {
            $this->validasi->setRules(TempatLahirValidation::$update);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message('error', '[TempatLahirService::updateData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id_tempat_lahir = dekripsi($data['data_token']);

            $data = [
                'name' => ucwords(trim($data['data_name'])),
                'description' => trim($data['data_remark']),
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->tempatLahirRepo->update($id_tempat_lahir, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[TempatLahirService::updateData] Failed to update tempat_lahir data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to update birth place data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[TempatLahirService::updateData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function deleteData(array $id)
    {
        try {
            $this->db->transStart();
            $this->tempatLahirRepo->massDelete($id);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[TempatLahirService::deleteData] Failed to delete birth place data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to delete birth place data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[TempatLahirService::deleteData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            $fileName = "birth_place_list_" . date("Ymd_his") . '.xlsx';

            $headers = [
                'Code',
                'Name',
                'Remark'
            ];

            $dataCallback = function ($offset, $limit) {
                $column = 'code, name, description';
                return $this->tempatLahirRepo->getChunkedData($offset, $limit, 'code', $column);
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
            return $this->tempatLahirRepo->all('code', 'ASC');
        } catch (\Exception $e) {
            log_message('error', '[TempatLahirService::loadAllData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
