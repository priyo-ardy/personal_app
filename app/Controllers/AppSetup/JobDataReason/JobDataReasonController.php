<?php

namespace App\Controllers\AppSetup\JobDataReason;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\JobDataReason\JobDataReasonService;
use App\Repositories\JobDataReason\JobDataReasonRepository;

class JobDataReasonController extends BaseController
{

    protected $reason;

    public function __construct()
    {
        $this->reason = new JobDataReasonService(new JobDataReasonRepository());
    }

    public function index()
    {
        //
    }

    public function getByAction(string $token)
    {
        if ($this->request->getMethod() !== 'GET') {
            log_message('error', '[JobDataReasonController::getByAction] Unexpected request method : {method} from {ip}', ['method' => $this->request->getMethod(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw new \Exception("Request not allowed", ResponseInterface::HTTP_METHOD_NOT_ALLOWED);
        }

        try {

            $get = $this->reason->getDataByAction($token);

            return pesan(ResponseInterface::HTTP_OK, "Data found", $get);
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[JobDataReasonController::getByAction] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
