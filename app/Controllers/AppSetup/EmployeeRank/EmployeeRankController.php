<?php

namespace App\Controllers\AppSetup\EmployeeRank;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\EmployeeRankService;
use App\Repositories\EmployeeRankRepository;
use App\Traits\ResponseTrait;

class EmployeeRankController extends BaseController
{
    use ResponseTrait;
    protected $rankService;

    public function __construct()
    {
        $this->rankService = new EmployeeRankService(new EmployeeRankRepository());
    }

    public function index()
    {
        $data = [
            'title' => 'Employee Rank Management',
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/EmployeeRank/rank.js' . '"></script>'
            ]
        ];

        return view('AppSetup/EmployeeRank/index', $data);
    }

    public function loadTable()
    {
        try {
            if ($this->request->isAJAX()) {
                $requestedData = $this->request->getPost();

                $output = $this->rankService->loadTable($requestedData);

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
                '[EmployeeRankController::save] Request method not allowed for user {NIK} from {ip}',
                ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]
            );

            throw new \Exception("Request not alowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->rankService->save($data)) {
                return $this->success(ResponseInterface::HTTP_OK, "New employee rank data saved successfully");
            }
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function get($token)
    {
        if ($this->request->getMethod() !== "GET") {
            log_message('error', "[EmployeeRankController::getData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $id = dekripsi($token);

            $get_data = $this->rankService->getData($id);
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
            log_message('error', "[EmployeeRankController::update] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->rankService->update($data)) {
                return $this->success(ResponseInterface::HTTP_OK, "Employee rank data updated successfully");
            }
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function delete()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', "[EmployeeRankController::delete] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
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
            $id_rank = [];

            for ($i = 0; $i < count($token); $i++) {
                $id_rank[] = dekripsi($token[$i]);
            }

            if ($this->rankService->delete($id_rank)) {
                return $this->success(ResponseInterface::HTTP_OK, "Deleted successfully");
            }
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function export()
    {
        try {
            $result = $this->rankService->export();

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

    public function seedData()
    {
        return $this->response->setJSON($this->rankService->loadData(), JSON_PRETTY_PRINT);
    }
}
