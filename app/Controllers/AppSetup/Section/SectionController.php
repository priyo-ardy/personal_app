<?php

namespace App\Controllers\AppSetup\Section;

use App\Controllers\BaseController;
use App\Models\AppSetup\Section\SectionModel;
use App\Repositories\DepartmentRepository;
use App\Repositories\SectionRepository;
use App\Services\SectionService;
use CodeIgniter\HTTP\ResponseInterface;

class SectionController extends BaseController
{
    protected $deptRepo;
    protected $sectionService;
    public function __construct()
    {
        $this->deptRepo = new DepartmentRepository();
        $this->sectionService = new SectionService(new SectionRepository());
    }
    public function index()
    {
        $data = [
            'title' => "Section Management",
            'dept' => $this->deptRepo->all('name', 'asc'),
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/Section/section.js' . '"></script>'
            ]
        ];

        return view('AppSetup/Section/index', $data);
    }

    public function save()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[SectionController::save] Request method not allowed for user {NIK} from {ip}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->sectionService->save($data)) {
                return pesan(ResponseInterface::HTTP_OK, 'New section has been added successfully');
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[SectionController::save] Unexpected error occured for user {NIK} from {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return pesan($code, $e->getMessage());
        }
    }

    function loadTable()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[SectionController::loadTable] Request method not allowed for user {NIK} from {ip}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            if ($this->request->isAJAX()) {
                $requestData = $this->request->getPost();

                $output = $this->sectionService->loadTable($requestData);

                return $this->response->setJSON($output);
            }

            throw new \Exception("Bad Request", ResponseInterface::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[SectionController::loadTable] Unexpected error occured for user {NIK} from {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return pesan($code, $e->getMessage());
        }
    }

    function get($id)
    {
        try {
            $id_section = dekripsi($id);

            $get_data = $this->sectionService->get($id_section);

            return pesan(ResponseInterface::HTTP_OK, "Data found", $get_data);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[SectionController::get] Unexpected error occured for user {NIK} from {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return pesan($code, $e->getMessage());
        }
    }

    function update()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[SectionController::update] Request method not allowed for user {NIK} from {ip}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->sectionService->update($data)) {
                return pesan(ResponseInterface::HTTP_OK, "Section data updated successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[SectionController::update] Unexpected error occured for user {NIK} from {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return pesan($code, $e->getMessage());
        }
    }

    function delete()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[SectionController::delete] Request method not allowed for user {NIK} from {ip}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $json_data = $this->request->getJSON(true);

            if (!is_array($json_data)) {
                throw new \Exception('Invalid JSON request', ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['token'])) {
                throw new \Exception('Token is not available in JSON request', ResponseInterface::HTTP_BAD_REQUEST);
            }

            $token = $json_data['token'];

            $id_section = [];

            for ($i = 0; $i < count($token); $i++) {
                $id_section[] = dekripsi($token[$i]);
            }

            if ($this->sectionService->delete($id_section)) {
                return pesan(ResponseInterface::HTTP_OK, "Section data deleted successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[SectionController::delete] Unexpected error occured for user {NIK} from {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return pesan($code, $e->getMessage());
        }
    }

    function export()
    {
        try {
            $result = $this->sectionService->exportData();

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
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[DepartmentController::exportData], Unexpected error occurred NIK : {NIK}, from IP {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return pesan($code, $e->getMessage());
        }
    }

    function seedData()
    {
        $data = $this->sectionService->loadData();

        return $this->response->setJSON($data, JSON_PRETTY_PRINT);
    }
}
