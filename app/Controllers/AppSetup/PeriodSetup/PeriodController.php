<?php

namespace App\Controllers\AppSetup\PeriodSetup;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\PeriodSetup\PeriodService;
use App\Repositories\PeriodSetup\PeriodRepository;

class PeriodController extends BaseController
{
    protected $period;

    public function __construct()
    {
        $this->period = new PeriodService(new PeriodRepository());
    }

    public function index()
    {
        $data = [
            'title' => "Period Setup",
            'all_period' => $this->period->getAllPeriod(),
            'my_period' => $this->period->getMyPeriod(session()->get('user_name')),
            'footer' => [
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/PeriodSetup/period.js' . '"></script>'
            ]
        ];

        return view('AppSetup/PeriodSetup/index', $data);
    }

    public function saveDefault()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', "[PeriodController::saveDefault] Request method not valid, request method : {method}", ['method' => $this->request->getMethod()]);
            throw new \Exception("Request method not valid", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $postData = $this->request->getPost();

            $check_period = $this->period->getData($postData['data_user_name']);

            if (!$check_period) {
                $this->period->saveData($postData);
            } else {
                $this->period->updateData($postData);
            }

            return pesan(ResponseInterface::HTTP_OK, "Data saved successfully");
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', "[PeriodController::saveDefault] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
