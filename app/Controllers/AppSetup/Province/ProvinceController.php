<?php

namespace App\Controllers\AppSetup\Province;

use App\Controllers\BaseController;
use App\Traits\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\ProvinceService;
use App\Repositories\ProvinceRepository;
use App\Services\CountryService;
use App\Repositories\CountryRepository;

class ProvinceController extends BaseController
{
    use ResponseTrait;
    protected $provinceService;
    protected $countryService;

    public function __construct()
    {
        $this->provinceService = new ProvinceService(new ProvinceRepository());
        $this->countryService = new CountryService(new CountryRepository());
    }

    public function index()
    {
        $data = [
            'title' => "Province Management",
            'country' => $this->countryService->loadAllData(),
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/Province/province.js' . '"></script>'
            ]
        ];

        return view('AppSetup/Province/index', $data);
    }

    public function save()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[ProvinceController::save] Invalid request method NIK : {NIK}, from IP {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->provinceService->saveData($data)) {
                return $this->success(ResponseInterface::HTTP_OK, 'Data saved successfully');
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ProvinceController::save], Unexpected error occured NIK : {NIK}, from IP {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return pesan($code, $e->getMessage());
        }
    }

    public function loadTable()
    {
        try {
            if ($this->request->isAJAX()) {
                $requestData = $this->request->getPost();

                $output = $this->provinceService->loadTable($requestData);

                return $this->response->setJSON($output);
            }

            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ProvinceController::loadTable], Unexpected error occured NIK : {NIK}, from IP {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return pesan($code, $e->getMessage());
        }
    }

    public function get($token)
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', "[ProvinceController::get] Invalid request method NIK : {NIK}, from IP {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $id_province = dekripsi($token);
            $get_data = $this->provinceService->getData($id_province);
            if (!$get_data) {
                throw new \Exception("Data not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            return $this->success(ResponseInterface::HTTP_OK, 'Data found', $get_data);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ProvinceController::get], Unexpected error occured NIK : {NIK}, from IP {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return pesan($code, $e->getMessage());
        }
    }

    public function update()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[ProvinceController::update] Invalid request method NIK : {NIK}, from IP {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->provinceService->updateData($data)) {
                return $this->success(ResponseInterface::HTTP_OK, 'Data updated successfully');
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ProvinceController::update], Unexpected error occured NIK : {NIK}, from IP {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return pesan($code, $e->getMessage());
        }
    }

    public function delete()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[ProvinceController::delete] Invalid request method NIK : {NIK}, from IP {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                throw new \Exception("Invalid JSON request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['token'])) {
                throw new \Exception("Token is not available in JSON request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $token = $json_data['token'];
            $id_country = [];

            for ($i = 0; $i < count($token); $i++) {
                $id_country[] = dekripsi($token[$i]);
            }

            if ($this->provinceService->deleteData($id_country)) {
                log_message('info', "Delete country data successfully with total data {total}", ['total' => count($token)]);
                return pesan(ResponseInterface::HTTP_OK, "Country data deleted successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ProvinceController::delete], Unexpected error occured NIK : {NIK}, from IP {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return pesan($code, $e->getMessage());
        }
    }

    public function export()
    {
        try {
            $result = $this->provinceService->exportData();

            if ($result instanceof \Exception) {
                throw $result;
            }

            if ($result instanceof \CodeIgniter\HTTP\ResponseInterface) {
                return $result;
            }

            throw new \RuntimeException('Unexpected response from export service');
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[CountryController::export], Unexpected error occured NIK : {NIK}, from IP {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return pesan($code, $e->getMessage());
        }
    }

    public function seedData()
    {
        return pesan(ResponseInterface::HTTP_OK, 'Data seeded successfully', $this->provinceService->loadAllData());
    }
}
