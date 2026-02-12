<?php

namespace App\Services\Workshop;

use App\Models\AppSetup\Workshop\WorkshopModel;
use App\Repositories\Workshop\WorkshopRepository;
use App\Repositories\DataTableRepository;
use CodeIgniter\HTTP\ResponseInterface;
use App\Validation\Workshop\WorkshopValidation;
use Config\Database;
use Config\Services;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;

class WorkshopService
{
    protected $db;
    protected $validasi;
    protected $dataTable;
    protected $workshopRepo;

    public function __construct(WorkshopRepository $workshop)
    {
        $this->db = Database::connect();
        $this->validasi = Services::validation();
        $this->workshopRepo = $workshop;
    }

    public function loadTable(array $requestedData)
    {
        $model = new WorkshopModel();
        $builder = $model->builder();

        $column_search = ['code', 'name', 'description'];
        $column_order = [
            '0' => 'code',
            '1' => 'name',
            '2' => 'description'
        ];
        $defaultSearch = ['code' => 'asc'];

        $dataTable = new DataTableRepository($builder, $column_search, $column_order, $defaultSearch, [], 'deleted_at');

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

    public function saveData(array $data)
    {
        try {
            $this->validasi->setRules(WorkshopValidation::$save);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message('error', "[WorkshopService::saveData] Failed to verify new workshop data by {NIK} : {err}", ['NIK' => session()->get('user_name'), 'err' => $error_to_string]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $data = [
                'id' => uuid_v7(),
                'code' => $this->workshopRepo->generateCode('WRH-', 'code', 4),
                'name' => ucwords(trim($data['data_name'])),
                'description' => trim($data['data_remark']),
                'created_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->workshopRepo->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                log_message('error', '[WorkshopService::saveData] Failed to save new workshop data by {NIK} with error: {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to save new workshop data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[WorkshopService::saveData] New workshop data was saved by {NIK}', ['NIK' => session()->get('user_name')]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[WorkshopService::saveData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function getData(string $id)
    {
        try {
            $getData = $this->workshopRepo->find($id);
            if (!$getData) {
                log_message('error', '[WorkshopService::getData] Data token {id} by {NIK} from {ip} with error {err}', ['id' => $id, 'NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => 'Data not found']);
                throw new \Exception("Data not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            return [
                'token' => enkripsi($getData->id),
                'code' => $getData->code,
                'name' => $getData->name,
                'description' => $getData->description
            ];
        } catch (\Exception $e) {
            log_message('error', '[WorkshopService::getData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function updateData(array $data)
    {
        try {
            $this->validasi->setRules(WorkshopValidation::$update);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message('error', "[WorkshopService::updateData] Failed to verify workshop data by {NIK} : {err}", ['NIK' => session()->get('user_name'), 'err' => $error_to_string]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = dekripsi($data['data_token']);

            $data = [
                'name' => ucwords(trim($data['data_name'])),
                'description' => trim($data['data_remark']),
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->workshopRepo->update($id, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                log_message('error', '[WorkshopService::updateData] Failed to update workshop data by {NIK} with error: {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to update workshop data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[WorkshopService::updateData] Workshop data was updated by {NIK}', ['NIK' => session()->get('user_name')]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[WorkshopService::updateData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function deleteData(array $data)
    {
        try {
            $this->db->transStart();
            $this->workshopRepo->massDelete($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();

                log_message('error', '[WorkshopService::deleteData] Failed to delete workshop data by {NIK} with error: {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to delete workshop data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[WorkshopService::deleteData] Workshop data was deleted by {NIK}', ['NIK' => session()->get('user_name')]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[WorkshopService::deleteData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function exportData()
    {
        try {
            try {
                $fileName = 'workshop_list_' . date('Ymd_his') . '.xlsx';

                $headers = [
                    'Code',
                    'Name',
                    'Remark'
                ];

                $dataCallback = function ($offset, $limit) {
                    $column = 'code, name, description';
                    return $this->workshopRepo->getChunkedData($offset, $limit, 'code', $column);
                };

                return export_to_excel($fileName, $headers, $dataCallback);
            } catch (\Exception $e) {
                log_message('error', '[WorkshopService::exportData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw $e;
            }
        } catch (\Exception $e) {
            log_message('error', '[WorkshopService::exportData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }

    public function generateList()
    {
        try {
            return $this->workshopRepo->all('name', 'asc');
        } catch (\Exception $e) {
            log_message('error', '[WorkshopService::generateList] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }
}
