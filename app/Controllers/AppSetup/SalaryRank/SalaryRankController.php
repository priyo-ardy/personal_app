<?php

namespace App\Controllers\AppSetup\SalaryRank;

use App\Controllers\BaseController;
use App\Repositories\SalaryRankRepository;
use App\Traits\ResponseTrait;
use App\Services\SalaryRankService;
use CodeIgniter\HTTP\ResponseInterface;

class SalaryRankController extends BaseController
{
    use ResponseTrait;
    protected $salaryService;

    public function __construct()
    {
        $this->salaryService = new SalaryRankService(new SalaryRankRepository());
    }

    public function index()
    {
        $data = [
            'title' => 'Salary Rank Management',
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/export.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/SalaryRank/salary_rank.js' . '"></script>'
            ]
        ];

        return view('AppSetup/SalaryRank/index', $data);
    }

    public function loadTable()
    {
        try {
            if ($this->request->isAJAX()) {
                $requestedData = $this->request->getPost();

                $output = $this->salaryService->loadTable($requestedData);

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
                'Request method not allowed for save new employee salary rank process : {method} from {ip} by {NIK}',
                ['method' => $this->request->getMethod(), 'ip' => $_SERVER['REMOTE_ADDR'], 'NIK' => session()->get('user_name')]
            );

            throw new \Exception('Request not allowed', ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->salaryService->save($data)) {
                return $this->success(ResponseInterface::HTTP_OK, "New salary rank data saved successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR; // jika tidak ada kode error di exception, kembalikan error 500
            log_message('error', "[SalaryRankController::save] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]); // simpan log
            return pesan($code, $e->getMessage());
        }
    }

    public function get($token)
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message(
                'error',
                'Request method not allowed for getting employee salary rank process : {method} from {ip} by {NIK}',
                ['method' => $this->request->getMethod(), 'ip' => $_SERVER['REMOTE_ADDR'], 'NIK' => session()->get('user_name')]
            );

            throw new \Exception('Request not allowed', ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $id = dekripsi($token);

            $data = $this->salaryService->getData($id);

            if (empty($data)) {
                throw new \Exception("Data not found " . $id, ResponseInterface::HTTP_NOT_FOUND);
            }

            return $this->success(ResponseInterface::HTTP_OK, "Data retrieved successfully", $data);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR; // jika tidak ada kode error di exception, kembalikan error 500
            log_message('error', "[SalaryRankController::get] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]); // simpan log
            return pesan($code, $e->getMessage());
        }
    }

    public function update()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message(
                'error',
                'Request method not allowed for update employee salary rank process : {method} from {ip} by {NIK}',
                ['method' => $this->request->getMethod(), 'ip' => $_SERVER['REMOTE_ADDR'], 'NIK' => session()->get('user_name')]
            );

            throw new \Exception('Request not allowed', ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = $this->request->getPost();

            if ($this->salaryService->updateData($data)) {
                return $this->success(ResponseInterface::HTTP_OK, "Data updated successfully");
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR; // jika tidak ada kode error di exception, kembalikan error 500
            log_message('error', "[SalaryRankController::update] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]); // simpan log
            return pesan($code, $e->getMessage());
        }
    }

    function export()
    {
        try {
            return $this->salaryService->exportToExcel();

            // If the result is an exception, throw it
            // if ($result instanceof \Exception) {
            //     throw $result;
            // }

            // If the result is a ResponseInterface, return it directly
            // if ($result instanceof \CodeIgniter\HTTP\ResponseInterface) {
            //     return $result;
            // }
            // return $this->success(ResponseInterface::HTTP_OK, "Data retrieved successfully", $result);
            // If we get here, something unexpected happened
            // throw new \RuntimeException('Unexpected response from export service');
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[SalaryRankController::exportData], Unexpected error occurred NIK : {NIK}, from IP {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            return pesan($code, $e->getMessage());
        }
    }

    public function seedData()
    {
        return $this->response->setJSON($this->salaryService->loadData(), JSON_PRETTY_PRINT);
    }
}
