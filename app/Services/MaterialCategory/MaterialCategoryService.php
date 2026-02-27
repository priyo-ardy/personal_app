<?php

namespace App\Services\MaterialCategory;

use App\Models\AppSetup\MaterialCategory\MaterialCategoryModel;
use App\Repositories\MaterialCategory\MaterialCategoryRepository;
use App\Validation\MaterialCategory\MaterialCategoryValidation;
use App\Repositories\DataTableRepository;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Config\Database;


class MaterialCategoryService
{
    protected $db;
    protected $validasi;
    protected $repository;

    public function __construct(MaterialCategoryRepository $category)
    {
        $this->db = Database::connect();
        $this->validasi = Services::validation();
        $this->repository = $category;
    }

    public function loadTable(array $requestedData)
    {
        try {
            $model = new MaterialCategoryModel();
            $builder = $model->builder();

            $column_search = ['code', 'name', 'description'];
            $column_order = [
                '0' => 'code',
                '1' => 'name',
                '2' => 'prefix',
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
                    $row->prefix,
                    $row->description
                ];
            }

            $result['data'] = $formattedData;

            return $result;
        } catch (\Exception $e) {
            log_message('error', '[MaterialCategoryService::loadTable] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function saveData(array $data)
    {
        try {
            $this->validasi->setRules(MaterialCategoryValidation::$save);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message('error', '[MaterialCategory::save] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $data = [
                'id' => uuid_v7(),
                'code' => $this->repository->generateCode('MCTG-', 'code', 4),
                'name' => ucwords(trim($data['data_name'])),
                'prefix' => trim($data['data_prefix']),
                'description' => trim($data['data_remark']),
                'created_by' => session()->get('user_name'),
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[MaterialCategory::save] Failed to save new material category data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to save new material category data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[MaterialCategoryService::saveData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $getData = $this->repository->find($id);
            if (!$getData) {
                log_message('error', '[MaterialCategoryService::getData] Data token $id by {NIK} from {ip} with error {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => 'Data not found']);
                throw new \Exception("Data not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            return [
                'token' => enkripsi($getData->id),
                'code' => $getData->code,
                'name' => $getData->name,
                'prefix' => $getData->prefix,
                'description' => $getData->description
            ];
        } catch (\Exception $e) {
            log_message('error', '[MaterialCategoryService::getData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function updateData(array $data)
    {
        try {
            $this->validasi->setRules(MaterialCategoryValidation::$update);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message('error', '[MaterialCategory::updateData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = dekripsi($data['data_token']);

            $data = [
                'name' => ucwords(trim($data['data_name'])),
                'description' => trim($data['data_remark']),
                'prefix' => trim($data['data_prefix']),
                'updated_by' => session()->get('user_name')
            ];

            $this->db->transStart();
            $this->repository->update($id, $data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[MaterialCategory::updateData] Failed to update material category data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to update material category data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[TonnageService::updateData] Updated material category data by {NIK} : {id}', ['NIK' => session()->get('user_name'), 'id' => $id]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[MaterialCategoryService::updateData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
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
                log_message('error', '[MaterialCategory::deleteData] Failed to delete material category data by {NIK} : {err}', ['NIK' => session()->get('user_name'), 'err' => $this->db->error()]);
                throw new \Exception('Failed to delete material category data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', '[MaterialCategoryService::deleteData] Deleted material category data success with total data {total} by {NIK}', ['total' => count($data), 'NIK' => session()->get('user_name')]);
            return true;
        } catch (\Exception $e) {
            log_message('error', '[MaterialCategoryService::deleteData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function exportData()
    {
        try {
            $fileName = "material_category_list_" . date("Ymd_his") . '.xlsx';

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
            log_message('error', '[MaterialCategoryService::exportData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function loadAllData()
    {
        try {
            return $this->repository->all('code', 'ASC');
        } catch (\Exception $e) {
            log_message('error', '[MaterialCategoryService::loadAllData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
