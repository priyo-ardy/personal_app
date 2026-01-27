<?php

namespace App\Controllers\AppSetup\ApqpSetup;

use App\Controllers\BaseController;
use App\Repositories\ApqpSetup\ApqpHeaderRepository;
use App\Services\ApqpSetup\ApqpHeaderService;
use CodeIgniter\HTTP\ResponseInterface;
use App\Traits\ResponseTrait;

class ApqpHeaderController extends BaseController
{
    use ResponseTrait;
    protected $header;

    public function __construct()
    {
        $this->header = new ApqpHeaderService(new ApqpHeaderRepository());
    }

    public function loadTable()
    {
        try {
            if ($this->request->isAJAX()) {
                $requestedData = $this->request->getPost();

                $output = $this->header->loadTable($requestedData);

                return $this->response->setJSON($output);
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ApqpHeaderController::laodTable] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($e->getMessage(), $code);
        }
    }

    public function index()
    {
        $data = [
            'title' => "Apqp Header Management",
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/ApqpSetup/apqp_header.js' . '"></script>'
            ]
        ];

        return view('AppSetup/ApqpSetup/index', $data);
    }

    public function save()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[ApqpHeaderController::save] Method not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Method not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $postData = $this->request->getPost();

            if ($this->header->saveData($postData)) {
                return pesan(ResponseInterface::HTTP_OK, "Data saved successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ApqpHeaderController::save] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function get(string $token)
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', '[ApqpHeaderController::get] Method not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Method not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $id = dekripsi($token);

            $get_data = $this->header->getData($id);

            return $this->success(ResponseInterface::HTTP_OK, "Data found", $get_data);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ApqpHeaderController::get] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function update()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[ApqpHeaderController::update] Method not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Method not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $postData = $this->request->getPost();

            if ($this->header->updateData($postData)) {
                return $this->success(ResponseInterface::HTTP_OK, "Data updated successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ApqpHeaderController::update] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($e->getMessage(), $code);
        }
    }

    public function delete()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[ApqpHeaderController::delete] Method not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Method not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                throw new \Exception("Invalid JSON data", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['token'])) {
                throw new \Exception("Invalid JSON data", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $token = $json_data['token'];
            $id = [];

            for ($i = 0; $i < count($token); $i++) {
                $id[] = dekripsi($token[$i]);
            }

            if ($this->header->deleteData($id)) {
                return $this->success(ResponseInterface::HTTP_OK, "Data deleted successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[ApqpHeaderController::delete] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
