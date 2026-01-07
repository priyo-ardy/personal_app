<?php

namespace App\Controllers\AppSetup\NbhxPosition;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\NbhxPositionService;
use App\Repositories\NbhxPositionRepository;
use App\Traits\ResponseTrait;

class NbhxPositionController extends BaseController
{
    use ResponseTrait;
    protected $positionService;

    public function __construct()
    {
        $this->positionService = new NbhxPositionService(new NbhxPositionRepository());
    }

    public function index()
    {
        $data = [
            'title' => 'NBHX Position Category Management',
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/NbhxPosition/nbhx_position.js' . '"></script>'
            ]
        ];

        return view('AppSetup\NbhxPosition\index', $data);
    }

    public function loadTable()
    {
        try {
            if ($this->request->isAJAX()) {
                $requestedData = $this->request->getPost();

                $output = $this->positionService->loadTable($requestedData);

                return $this->response->setJSON($output);
            }
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function save()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message(
                'error',
                '[NbhxPositionController::save] Request method not allowed for user {NIK} from {ip}',
                ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]
            );

            throw new \Exception("Request not alowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->positionService->save($data)) {
                return $this->success(ResponseInterface::HTTP_OK, "New NBHX position category data saved successfully");
            }
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function get($token)
    {
        if ($this->request->getMethod() !== "GET") {
            log_message('error', "[NbhxPositionController::getData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $id = dekripsi($token);

            $get_data = $this->positionService->getData($id);
            if ($get_data instanceof \Exception) {
                throw $get_data;
            }

            return $this->success(ResponseInterface::HTTP_OK, "Data retrieved successfully", $get_data);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function update()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', "[NbhxPositionController::update] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->positionService->update($data)) {
                return $this->success(ResponseInterface::HTTP_OK, "NBHX position category data updated successfully");
            }
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function delete()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', "[NbhxPositionController::delete] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                throw new \Exception("Invalid JSON request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['token'])) {
                throw new \Exception("Token is required", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $token = $json_data['token'];
            $id_position = [];

            for ($i = 0; $i < count($token); $i++) {
                $id_position[] = dekripsi($token[$i]);
            }

            if ($this->positionService->delete($id_position)) {
                return $this->success(ResponseInterface::HTTP_OK, "Deleted successfully");
            }
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function export()
    {
        try {
            $result = $this->positionService->export();

            // If the result is an exception, throw it
            if ($result instanceof \Exception) {
                throw $result;
            }

            // If the result is a ResponseInterface, return it directly
            if ($result instanceof \CodeIgniter\HTTP\ResponseInterface) {
                return $result;
            }

            // If we get here, something unexpected happened
            throw new \RuntimeException('Unexpected response from export service');
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
