<?php

namespace App\Controllers\AppSetup\EmployeeGrade;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\EmployeeGradeService;
use App\Repositories\EmployeeGradeRepository;
use App\Traits\ResponseTrait;
use Exception;

class EmployeeGradeController extends BaseController
{
    use ResponseTrait;
    protected $gradeService;

    public function __construct()
    {
        $this->gradeService = new EmployeeGradeService(new EmployeeGradeRepository());
    }

    public function index()
    {
        $data = [
            'title' => "Employee Grade Management",
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/EmployeeGrade/grade.js' . '"></script>'
            ]
        ];

        return view('AppSetup/EmployeeGrade/index', $data);
    }

    function loadTable()
    {
        try {
            if ($this->request->isAJAX()) {
                $requestData = $this->request->getPost();

                $output = $this->gradeService->loadTable($requestData);

                return $this->response->setJSON($output);
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    function saveData()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', "[EmployeeGradeController::saveData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->gradeService->save($data)) {
                return $this->success(ResponseInterface::HTTP_OK, "Data saved successfully");
            }
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    function getData($token)
    {
        if ($this->request->getMethod() !== "GET") {
            log_message('error', "[EmployeeGradeController::getData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $id = dekripsi($token);

            $get_data = $this->gradeService->getData($id);

            return $this->success(ResponseInterface::HTTP_OK, "Data retrieved successfully", $get_data);
        } catch (Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    function updateData()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', "[EmployeeGradeController::updateData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->gradeService->update($data)) {
                return $this->success(ResponseInterface::HTTP_OK, "Updated successfully");
            }
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    function deleteData()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', "[EmployeeGradeController::deleteData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
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
            $id_grade = [];

            for ($i = 0; $i < count($token); $i++) {
                $id_grade[] = dekripsi($token[$i]);
            }

            if ($this->gradeService->delete($id_grade)) {
                return $this->success(ResponseInterface::HTTP_OK, "Deleted successfully");
            }
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    function exportData()
    {
        try {
            $result = $this->gradeService->exportData();

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
