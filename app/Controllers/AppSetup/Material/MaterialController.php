<?php

namespace App\Controllers\AppSetup\Material;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\Material\MaterialService;
use App\Repositories\Material\MaterialRepository;
use App\Models\AppSetup\Material\VwMaterialModel;
use App\Services\MaterialCategory\MaterialCategoryService;
use App\Repositories\MaterialCategory\MaterialCategoryRepository;
use App\Services\Workshop\WorkshopService;
use App\Repositories\Workshop\WorkshopRepository;
use App\Services\UoM\UomService;
use App\Repositories\UoM\UomRepository;
use App\Traits\ResponseTrait;

class MaterialController extends BaseController
{
    use ResponseTrait;
    protected $material;
    protected $category;
    protected $workshop;
    protected $uom;

    public function __construct()
    {
        $this->material = new MaterialService(new MaterialRepository());
        $this->category = new MaterialCategoryService(new MaterialCategoryRepository());
        $this->workshop = new WorkshopService(new WorkshopRepository());
        $this->uom = new UomService(new UomRepository());
    }
    public function index()
    {
        $data = [
            'title' => "Material Management",
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/Material/material.js' . '"></script>'
            ]
        ];

        return view('AppSetup/Material/index', $data);
    }

    public function add()
    {
        $data = [
            'title' => "Material Management",
            'category' => $this->category->loadAllData(),
            'workshop' => $this->workshop->generateList(),
            'uom' => $this->uom->getAllData(),
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/Material/add.js' . '"></script>'
            ]
        ];

        return view('AppSetup/Material/add', $data);
    }

    public function loadTable()
    {
        try {
            if ($this->request->isAJAX()) {
                $requestedData = $this->request->getPost();

                $output = $this->material->loadTable($requestedData);

                return $this->response->setJSON($output);
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[MaterialController::loadTable] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function save()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[MaterialController::save] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();
            $uploadFile = $this->request->getFile('fupload');

            $save = $this->material->saveData($data, $uploadFile);
            // if ($this->material->saveData($data, $uploadFile)) {
            //     return $this->success(ResponseInterface::HTTP_OK, "Data saved successfully");
            // }

            return $this->success(ResponseInterface::HTTP_OK, "Data saved successfully", $save);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[MaterialController::save] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function get($token)
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', "[MaterialController::get] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $id = dekripsi($token);

            $get_data = $this->material->getData($id);

            return $this->success(ResponseInterface::HTTP_OK, "Data found", $get_data);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[MaterialController::get] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function show($token)
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', "[MaterialController::show] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $material = $this->material->getData(dekripsi($token));

            $data = [
                'title' => "Edit Material Data | " . $material['code'],
                'material' => $material,
                'category' => $this->category->loadAllData(),
                'workshop' => $this->workshop->generateList(),
                'uom' => $this->uom->getAllData(),
                'footer' => [
                    '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                    '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                    '<script src="' . base_url() . 'js/AppSetup/Material/show.js' . '"></script>'
                ]
            ];

            return view('AppSetup/Material/show', $data);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[MaterialController::show] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function update()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[MaterialController::update] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->material->updateData($data)) {
                return $this->success(ResponseInterface::HTTP_OK, "Data updated successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[MaterialController::update] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
