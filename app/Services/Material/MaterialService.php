<?php

namespace App\Services\Material;

use App\Repositories\Material\MaterialRepository;
use App\Validation\Material\MaterialValidation;
use App\Models\AppSetup\Material\MaterialModel;
use App\Models\AppSetup\Material\VwMaterialModel;
use App\Repositories\DataTableRepository;
use App\Repositories\MaterialCategory\MaterialCategoryRepository;
use App\Repositories\Workshop\WorkshopRepository;
use App\Repositories\UoM\UomRepository;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\UploadImage\UploadImageService;
use Config\Services;
use Config\Database;
use Exception;

class MaterialService
{
    protected $db;
    protected $validation;
    protected $repository;
    protected $category;
    protected $workshop;
    protected $uom;
    protected $upload;

    public function __construct(MaterialRepository $repo)
    {
        $this->db = Database::connect();
        $this->validation = Services::validation();
        $this->repository = $repo;
        $this->category = new MaterialCategoryRepository();
        $this->workshop = new WorkshopRepository();
        $this->uom = new UomRepository();
        $this->upload = new UploadImageService();
    }

    public function loadTable(array $requestedData)
    {
        try {
            $model = new VwMaterialModel();
            $builder = $model->builder();

            $column_search = [
                'code',
                'name',
                'specification',
                'category_name',
                'cust_part_no',
                'cust_part_name',
                'color',
                'workshop_name',
                'property',
                'uom_name',
                'shift_capacity',
                'spq',
                'qty_per_bag',
                'net_weight',
                'gross_weight',
                'cavity',
                'description'
            ];
            $column_order = [
                '0' => 'code',
                '1' => 'name',
                '2' => 'specification',
                '3' => 'category_name',
                '4' => 'cust_part_no',
                '5' => 'cust_part_name',
                '6' => 'color',
                '7' => 'workshop_name',
                '8' => 'property',
                '9' => 'uom_name',
                '10' => 'shift_capacity',
                '11' => 'spq',
                '12' => 'qty_per_bag',
                '13' => 'net_weight',
                '14' => 'gross_weight',
                '15' => 'cavity',
                '16' => 'description'
            ];
            $default_order = array('code' => 'asc');

            $data_table = new DataTableRepository($builder, $column_search, $column_order, $default_order, [], 'deleted_at');

            $result = $data_table->proses($requestedData);
            $formattedData = [];

            foreach ($result['data'] as $row) {
                $formattedData[] = [
                    enkripsi($row->id),
                    '<a href="#" class="text-primary fw-bolder text-decoration-none" title="Click to edit" onclick="getData(`' . enkripsi($row->id) . '`)">' . $row->code . '</a>',
                    $row->name,
                    $row->specification,
                    $row->category_name,
                    $row->cust_part_no,
                    $row->cust_part_name,
                    $row->color,
                    $row->workshop_name,
                    $row->property,
                    $row->uom_name,
                    $row->shift_capacity,
                    $row->spq,
                    $row->qty_per_bag,
                    $row->net_weight,
                    $row->gross_weight,
                    $row->cavity,
                    $row->description,
                ];
            }

            $result['data'] = $formattedData;

            return $result;
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[MaterialService::loadData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function saveData(array $requestData, $uploadFiles = null)
    {
        try {
            $uploadService = new UploadImageService();
            $imageFile = null;

            $this->validation->setRules(MaterialValidation::$save);

            if ($this->validation->run($requestData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[MaterialService::saveData] Validation error : {err} from {ip}', ['err' => $error_to_string, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }
            $getCategory = $this->category->find($requestData['data_category']);
            $categoryName = $getCategory->name ?? null;
            $check_code = $this->repository->checkCode($requestData['data_workshop'], $requestData['data_code']);
            if ($check_code) {
                log_message('error', '[MaterialService::saveData] Material code {code} already exist from {ip}', ['code' => $requestData['data_code'], 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Material code <strong class='text-danger fw-bolder'>" . $requestData['data_code'] . "</strong> already exist  in material category <strong class='text-danger fw-bolder'>" . $categoryName . "</strong>", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if ($uploadFiles && $uploadFiles->isValid() && !$uploadFiles->hasMoved()) {
                try {
                    $uploadResult = $uploadService->upload_single_image('material', $uploadFiles);
                    $imageFile = $uploadResult['file_name'];
                } catch (\Exception $e) {
                    log_message('error', '[MaterialService::saveData] Error when upload image : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                    throw $e;
                }
            }

            $data = [
                'id' => generate_uuid(),
                'code' => strtoupper(trim($requestData['data_code'])),
                'name' => ucwords(trim($requestData['data_name'])),
                'specification' => trim($requestData['data_specification']),
                'category' => $requestData['data_category'],
                'cust_part_no' => trim($requestData['data_cust_part_no']),
                'cust_part_name' => strtoupper(trim($requestData['data_cust_part_name'])),
                'color' => ucwords(trim($requestData['data_color'])),
                'workshop' => $requestData['data_workshop'],
                'property' => $requestData['data_property'],
                'uom' => $requestData['data_uom'],
                'shift_capacity' => ($requestData['data_shift_capacity']) ? trim($requestData['data_shift_capacity']) : 0,
                'spq' => ($requestData['data_spq']) ? trim($requestData['data_spq']) : 0,
                'qty_per_bag' => ($requestData['data_qty_per_bag']) ? trim($requestData['data_qty_per_bag']) : 0,
                'net_weight' => ($requestData['data_net_weight']) ? trim($requestData['data_net_weight']) : 0,
                'gross_weight' => ($requestData['data_gross_weight']) ? trim($requestData['data_gross_weight']) : 0,
                'cavity' => ($requestData['data_cavity']) ? trim($requestData['data_cavity']) : 0,
                'image' => ($imageFile !== null) ? $imageFile : null,
                'description' => trim($requestData['data_description']),
                'created_by' => session()->get('user_name'),
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                $php_errormsg = $this->db->error();

                if ($imageFile !== null) {
                    unlink(FCPATH . "uploads/material/$imageFile");
                }
                log_message('error', '[MaterialService::saveData] Failed to save data : {err} from {ip}', ['err' => $php_errormsg, 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Failed to save data " . $php_errormsg, ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }
            return true;
        } catch (\Exception $e) {
            log_message('error', '[MaterialService::saveData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $id)
    {
        try {
            $get_data = $this->repository->find($id);

            if (!$get_data) {
                log_message('error', '[MaterialService::getData] Data with id {id} not found', ['id' => $id]);
                throw new \Exception("Data with id $id not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            return [
                'token' => enkripsi($get_data->id),
                'code' => $get_data->code,
                'name' => $get_data->name,
                'specification' => $get_data->specification,
                'category' => $get_data->category,
                'cust_part_no' => $get_data->cust_part_no,
                'cust_part_name' => $get_data->cust_part_name,
                'color' => $get_data->color,
                'workshop' => $get_data->workshop,
                'property' => $get_data->property,
                'uom' => $get_data->uom,
                'shift_capacity' => $get_data->shift_capacity,
                'spq' => $get_data->spq,
                'qty_per_bag' => $get_data->qty_per_bag,
                'net_weight' => $get_data->net_weight,
                'gross_weight' => $get_data->gross_weight,
                'cavity' => $get_data->cavity,
                'image' => $get_data->image,
                'description' => $get_data->description,
            ];
        } catch (\Exception $e) {
            log_message('error', '[MaterialService::getData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
