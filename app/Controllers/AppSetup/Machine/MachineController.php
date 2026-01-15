<?php

namespace App\Controllers\AppSetup\Machine;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Repositories\Machine\MachineRepository;
use App\Services\Machine\MachineService;
use App\Repositories\Workshop\WorkshopRepository;
use App\Services\Workshop\WorkshopService;
use App\Repositories\Tonnage\TonnageRepository;
use App\Services\Tonnage\TonnageService;
use App\Traits\ResponseTrait;
use Config\Services;

class MachineController extends BaseController
{
    use ResponseTrait;
    protected $machine;
    protected $workshop;
    protected $tonnage;

    public function __construct()
    {
        $this->machine = new MachineService(new MachineRepository());
        $this->workshop = new WorkshopService(new WorkshopRepository());
        $this->tonnage = new TonnageService(new TonnageRepository());
    }

    public function loadTable()
    {
        try {
            if ($this->request->isAJAX()) {
                $requestedData = $this->request->getPost();

                $output = $this->machine->loadTable($requestedData);

                return $this->response->setJSON($output);
            }
        } catch (\Exception $e) {
            $code = $e->getCode() ?? ResponseInterface::HTTP_INTERNAL_SERVER_ERROR;
            log_message('error', '[MachineController::loadTable] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return pesan($code, $e->getMessage());
        }
    }

    public function index()
    {
        $data = [
            'title' => "Machine Management",
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/Machine/machine.js' . '"></script>'
            ]
        ];

        return view('AppSetup/Machine/index', $data);
    }

    public function add()
    {
        $data = [
            'title' => "Add New Machine",
            'workshop' => $this->workshop->generateList(),
            'tonnage' => $this->tonnage->loadAllData(),
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/AppSetup/Machine/add.js' . '"></script>'
            ]
        ];

        return view('AppSetup/Machine/add', $data);
    }
}
