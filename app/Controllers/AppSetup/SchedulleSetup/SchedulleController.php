<?php

namespace App\Controllers\AppSetup\SchedulleSetup;

use App\Controllers\BaseController;
use App\Services\ShiftSetup\ShiftService;
use App\Repositories\ShiftSetup\ShiftRepository;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\SchedulleSetup\SchedulleService;
use App\Repositories\SchedulleSetup\SchedulleRepository;

class SchedulleController extends BaseController
{
    protected $shift;
    protected $schedulle;

    public function __construct()
    {
        $this->shift = new ShiftService(new ShiftRepository());
        $this->schedulle = new SchedulleService(new SchedulleRepository());
    }

    public function index()
    {
        $data = [
            'title' => 'Schedulle Setup',
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/SchedulleSetup/schedulle.js' . '"></script>',
            ]
        ];

        return view('AppSetup/SchedulleSetup/index', $data);
    }

    public function add()
    {
        $data = [
            'title' => 'Add New Schedulle',
            'shift' => $this->shift->getAllData(),
            'footer' => [
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/SchedulleSetup/add.js' . '"></script>',
            ]
        ];

        return view('AppSetup/SchedulleSetup/add', $data);
    }

    public function save()
    {
        if ($this->request->getMethod() !== 'POST') {
            log_message('error', '[SchedulleController::save] Method not allowed from {ip}', ['ip' => $_SERVER['REMOTE_ADDR']]);
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        try {
            $postData = $this->request->getPost();

            $this->schedulle->saveData($postData);

            return pesan(ResponseInterface::HTTP_OK, "Data saved successfully");
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[SchedulleController::save] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function loadTable()
    {
        try {
            if ($this->request->isAJAX()) {
                $postData = $this->request->getPost();

                $output = $this->schedulle->loadTable($postData);

                return $this->response->setJSON($output, JSON_PRETTY_PRINT);
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[SchedulleController::loadTable] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }
}
