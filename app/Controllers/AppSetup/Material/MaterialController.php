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
use App\Services\ProcessRoute\ProcessRouteService;
use App\Repositories\ProcessRoute\ProcessRouteRepository;
use App\Traits\ResponseTrait;

class MaterialController extends BaseController
{
    use ResponseTrait;
    protected $material;
    protected $category;
    protected $workshop;
    protected $uom;
    protected $route;

    public function __construct()
    {
        $this->material = new MaterialService(new MaterialRepository());
        $this->category = new MaterialCategoryService(new MaterialCategoryRepository());
        $this->workshop = new WorkshopService(new WorkshopRepository());
        $this->uom = new UomService(new UomRepository());
        $this->route = new ProcessRouteService(new ProcessRouteRepository());
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
            'route' => $this->route->getAllData(),
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
                'route' => $this->route->getAllData(),
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
            $uploadFile = $this->request->getFile('fupload');

            if ($this->material->updateData($data, $uploadFile)) {
                return $this->success(ResponseInterface::HTTP_OK, "Data updated successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[MaterialController::update] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function delete()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[MaterialController::delete] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                throw new \Exception("Request not valid", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $id = dekripsi($json_data['token']);

            if ($this->material->deleteData($id)) {
                return $this->success(ResponseInterface::HTTP_OK, "Data deleted successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[MaterialController::delete] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function massDelete()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[MaterialController::massDelete] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                throw new \Exception("Request not valid", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['token'])) {
                throw new \Exception("Request not valid", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $token = $json_data['token'];
            $id = [];

            foreach ($token as $key => $value) {
                $id[] = dekripsi($value);
            }

            if ($this->material->massDeleteData($id)) {
                return $this->success(ResponseInterface::HTTP_OK, "Data deleted successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[MaterialController::massDelete] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function export()
    {
        try {
            $result = $this->material->exportData();

            if ($result instanceof \Exception) {
                throw $result;
            }

            if ($result instanceof \CodeIgniter\HTTP\ResponseInterface) {
                return $result;
            }

            throw new \Exception('Unexpected response from export service');
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[MaterialController::export] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function prev()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[MaterialController::prev] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                throw new \Exception("Request not valid", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['category']) || !isset($json_data['code'])) {
                throw new \Exception("Request not valid", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $category = trim($json_data['category']);
            $code = trim($json_data['code']);

            $prev = $this->material->prevData($category, $code);
            if (!$prev) {
                throw new \Exception("You are in the first data", ResponseInterface::HTTP_BAD_REQUEST);
            }

            return $this->success(ResponseInterface::HTTP_OK, "Data found", $prev);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[MaterialController::prev] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function next()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[MaterialController::next] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                throw new \Exception("Request not valid", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['category']) || !isset($json_data['code'])) {
                throw new \Exception("Request not valid", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $category = trim($json_data['category']);
            $code = trim($json_data['code']);

            $next = $this->material->nextData($category, $code);
            if (!$next) {
                throw new \Exception("You are in the first data", ResponseInterface::HTTP_BAD_REQUEST);
            }

            return $this->success(ResponseInterface::HTTP_OK, "Data found", $next);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[MaterialController::next] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function seedData()
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', "[MaterialController::seedData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $get = $this->material->getAllData();
            return $this->success(ResponseInterface::HTTP_OK, "Data has been seeded", $get);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[MaterialController::seedData] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function getMaterialList()
    {
        try {
            $get = $this->material->getMaterialByCategory('019c50cc-e708-794a-8d29-ceb41ce5b71b');

            $data = [];
            foreach ($get as $row) {
                $data[] = [
                    'token' => $row->id,
                    'code' => $row->code,
                    'name' => $row->name,
                    'model' => $row->specification,
                    'mold_no' => $row->mold_no
                ];
            }

            return pesan(ResponseInterface::HTTP_OK, "Data found", $data);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[MaterialController::getMaterialList] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
