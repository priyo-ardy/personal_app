<?php

namespace App\Controllers\AppSetup\City;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\CityService;
use App\Services\ProvinceService;
use App\Repositories\CityRepository;
use App\Repositories\ProvinceRepository;
use App\Traits\ResponseTrait;

class CityController extends BaseController
{
    use ResponseTrait;
    protected $citySerice;
    protected $provinceSerice;

    public function __construct()
    {
        $this->citySerice = new CityService(new CityRepository());
        $this->provinceSerice = new ProvinceService(new ProvinceRepository());
    }

    public function index()
    {
        $data = [
            'title' => "City Management",
            'province' => $this->provinceSerice->loadAllData(),
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/City/city.js' . '"></script>'
            ]
        ];

        return view('AppSetup/City/index', $data);
    }

    public function save()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[CityController::save] Invalid request method NIK : {NIK}, from IP {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->citySerice->saveData($data)) {
                return $this->success(ResponseInterface::HTTP_OK, 'Data saved successfully');
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[CityController::save] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function loadTable()
    {
        try {
            if ($this->request->isAJAX()) {
                $requestData = $this->request->getPost();

                $output = $this->citySerice->loadTable($requestData);

                return $this->response->setJSON($output);
            }

            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[CityController::loadTable] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function get($token)
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', "[CityController::get] Invalid request method NIK : {NIK}, from IP {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $id_city = dekripsi($token);

            $getData = $this->citySerice->getData($id_city);
            if (!$getData) {
                throw new \Exception("Data not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            return $this->success(ResponseInterface::HTTP_OK, 'Data found', $getData);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[CityController::get] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function update()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[CityController::update] Invalid request method NIK : {NIK}, from IP {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->citySerice->updateData($data)) {
                return $this->success(ResponseInterface::HTTP_OK, 'Data updated successfully');
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[CityController::update] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function delete()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[CityController::delete] Invalid request method NIK : {NIK}, from IP {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
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
            $id_city = [];

            for ($i = 0; $i < count($token); $i++) {
                $id_city[] = dekripsi($token[$i]);
            }

            if ($this->citySerice->deleteData($id_city)) {
                log_message('info', "Delete city data successfully with total data {total}", ['total' => count($token)]);
                return pesan(ResponseInterface::HTTP_OK, "City data deleted successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[CityController::delete] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function export()
    {
        try {
            $result = $this->citySerice->exportData();

            if ($result instanceof \Exception) {
                throw $result;
            }

            if ($result instanceof \CodeIgniter\HTTP\ResponseInterface) {
                return $result;
            }

            throw new \RuntimeException('Unexpected response from export service');
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[CityController::export] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function getCity(string $province)
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', "[CityController::getCityByProvince] Invalid request method NIK : {NIK}, from IP {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $get_data = $this->citySerice->getCityByProvince($province);

            if ($get_data) {
                return $this->success(ResponseInterface::HTTP_OK, 'Data found', $get_data);
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[CityController::getCityByProvince] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
