<?php

namespace App\Controllers\AppSetup\Position;

use App\Controllers\BaseController;
use App\Repositories\NbhxPositionRepository;
use App\Repositories\PositionRepository;
use App\Repositories\DepartmentRepository;
use App\Services\SectionService;
use App\Repositories\SectionRepository;
use App\Repositories\DataTableRepository;
use App\Repositories\EmployeeGradeRepository;
use App\Repositories\EmployeeRankRepository;
use App\Repositories\EmployeeCategoryRepository;
use App\Repositories\ClassNbhxRepository;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\NbhxPositionService;
use App\Services\DepartmentService;
use App\Services\EmployeeGradeService;
use App\Services\EmployeeRankService;
use App\Services\EmployeeCategoryService;
use App\Services\ClassNbhxService;
use App\Services\PositionService;
use App\Traits\ResponseTrait;

class PositionController extends BaseController
{
    use ResponseTrait;
    protected $nbhxPositionService;
    protected $departmentService;
    protected $gradeService;
    protected $rankService;
    protected $categoryService;
    protected $classService;
    protected $positionService;
    protected $sectionService;

    public function __construct()
    {
        $this->nbhxPositionService = new NbhxPositionService(new NbhxPositionRepository());
        $this->departmentService = new DepartmentService(new DepartmentRepository());
        $this->gradeService = new EmployeeGradeService(new EmployeeGradeRepository());
        $this->rankService = new EmployeeRankService(new EmployeeRankRepository());
        $this->categoryService = new EmployeeCategoryService(new EmployeeCategoryRepository());
        $this->classService = new ClassNbhxService(new ClassNbhxRepository());
        $this->positionService = new PositionService(new PositionRepository());
        $this->sectionService = new SectionService(new SectionRepository());
    }
    public function index()
    {
        $data = [
            'title' => "Employee Position Management",
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/Position/position.js' . '"></script>'
            ]
        ];

        return view('AppSetup/Position/index', $data);
    }

    public function add()
    {
        $data = [
            'title' => "Add New Position",
            'nbhx_position' => $this->nbhxPositionService->loadData(),
            'dept' => $this->departmentService->loadData(),
            'grade' => $this->gradeService->loadData(),
            'rank' => $this->rankService->loadData(),
            'emp_category' => $this->categoryService->loadData(),
            'class_nbhx' => $this->classService->loadData(),
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/Position/add.js' . '"></script>'
            ]
        ];

        return view('AppSetup/Position/add', $data);
    }

    public function dataList()
    {
        try {
            $generate_data = $this->positionService->loadDataList();

            return $this->success(ResponseInterface::HTTP_OK, "Data found", $generate_data);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[PositionController::dataList] Unexpected error occured for user {NIK} from {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return pesan($code, $e->getMessage());
        }
    }


    public function saveData()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[PositionController::saveData] Invalid request method NIK : {NIK}, from IP {ip}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->positionService->save($data)) {
                return pesan(ResponseInterface::HTTP_OK, 'New position data has been added successfully');
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[PositionController::saveData] Unexpected error occured NIK : {NIK}, from IP {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return pesan($code, $e->getMessage());
        }
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

    public function getData($token)
    {
        if ($this->request->getMethod() !== "GET") {
            log_message('error', "[PositionController::getData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $id = dekripsi($token);
            $data = $this->positionService->getData($id);

            return $this->success(ResponseInterface::HTTP_OK, "Data retrieved successfully", $data);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[PositionController::getData] Unexpected error occured for user {NIK} from {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return pesan($code, $e->getMessage());
        }
    }

    public function showData($token)
    {
        if ($this->request->getMethod() !== "GET") {
            log_message('error', "[PositionController::showData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $id = dekripsi($token);

            $list = $this->positionService->showData($id);

            $data = [
                'title' => "Edit Position Data | " . $list->name,
                'data' => $list,
                'nbhx_position' => $this->nbhxPositionService->loadData(),
                'dept' => $this->departmentService->loadData(),
                'section' => $this->sectionService->getListByDept($list->dept),
                'grade' => $this->gradeService->loadData(),
                'rank' => $this->rankService->loadData(),
                'position' => $this->positionService->loadDataList(),
                'emp_category' => $this->categoryService->loadData(),
                'class_nbhx' => $this->classService->loadData(),
                'footer' => [
                    '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                    '<script src="' . base_url() . 'js/AppSetup/Position/edit.js' . '"></script>'
                ]
            ];

            return view('AppSetup/Position/edit', $data);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[PositionController::showData] Unexpected error occured for user {NIK} from {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return pesan($code, $e->getMessage());
        }
    }

    public function updateData()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', "[PositionController::updateData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $postData = $this->request->getPost();

            if ($this->positionService->updateData($postData)) {
                return $this->success(ResponseInterface::HTTP_OK, "Position data updated successfully");
            }
        } catch (\Exception $e) {
            log_message('error', '[PositionController::updateData] Unexpected error occured for user {NIK} from {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            $this->exceptionResponse($e);
        }
    }

    public function deleteData()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', "[PositionController::deleteData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
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

            $token = trim($json_data['token']);
            $id = dekripsi($token);

            if ($this->positionService->deleteData($id)) {
                return $this->success(ResponseInterface::HTTP_OK, "Position data deleted successfully");
            }
        } catch (\Exception $e) {
            log_message('error', '[PositionController::deleteData] Unexpected error occured for user {NIK} from {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return $this->exceptionResponse($e);
        }
    }

    public function massDelete()
    {
        if ($this->request->getMethod() !== "POST") {
            log_message('error', "[PositionController::massDelete] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
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
            $id_position = [];

            for ($i = 0; $i < count($token); $i++) {
                $id_position[] = dekripsi($token[$i]);
            }

            if ($this->positionService->massDelete($id_position)) {
                return $this->success(ResponseInterface::HTTP_OK, "Position data deleted successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[PositionController::massDelete] Unexpected error occured for user {NIK} from {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);

            return pesan($code, $e->getMessage());
        }
    }

    public function exportData()
    {
        try {
            try {
                $result = $this->positionService->exportData();

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
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[PositionController::export] Unexpected error occured for user {NIK} from {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);

            return pesan($code, $e->getMessage());
        }
    }

    public function prevData()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[PositionController::prevData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                throw new \Exception("Invalid JSON request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['code'])) {
                throw new \Exception("Code is not available in JSON request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $code = trim($json_data['code']);

            $prev = $this->positionService->getPrevData($code);
            if (!$prev) {
                throw new \Exception("You are in the first data", ResponseInterface::HTTP_BAD_REQUEST);
            }


            return $this->success(ResponseInterface::HTTP_OK, "Data retrieved successfully", $prev);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[PositionController::prevData] Unexpected error occured for user {NIK} from {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return pesan($code, $e->getMessage());
        }
    }

    public function nextData()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[PositionController::nextData] Request method not allowed for user {NIK} from {ip}", ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                throw new \Exception("Invalid JSON request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['code'])) {
                throw new \Exception("Code is not available in JSON request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $code = trim($json_data['code']);

            $next = $this->positionService->getNextData($code);
            if (!$next) {
                throw new \Exception("You are in the first data", ResponseInterface::HTTP_BAD_REQUEST);
            }


            return $this->success(ResponseInterface::HTTP_OK, "Data retrieved successfully", $next);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[PositionController::prevData] Unexpected error occured for user {NIK} from {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return pesan($code, $e->getMessage());
        }
    }
}
