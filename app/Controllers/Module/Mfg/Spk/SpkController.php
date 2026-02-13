<?php

namespace App\Controllers\Module\Mfg\Spk;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\Location\LocationService;
use App\Repositories\Location\LocationRepository;
use App\Services\DepartmentService;
use App\Repositories\DepartmentRepository;
use App\Services\EquipmentType\EquipmentTypeService;
use App\Repositories\EquipmentType\EquipmentTypeRepository;


class SpkController extends BaseController
{
    protected $location;
    protected $dept;
    protected $equipment;

    public function __construct()
    {
        $this->location = new LocationService(new LocationRepository());
        $this->dept = new DepartmentService(new DepartmentRepository());
        $this->equipment = new EquipmentTypeService(new EquipmentTypeRepository());
    }

    public function index()
    {
        $data = [
            'title' => 'List of SPK',
            'footer' => [
                '<script src="' . base_url() . 'js/App/datatable.js' . '"></script>',
                '<script src="' . base_url() . 'js/Module/Mfg/Spk/spk.js' . '"></script>'
            ]
        ];

        return view('Module/Mfg/Spk/index', $data);
    }

    public function add()
    {
        $data = [
            'title' => "Register New SPK",
            'location' => $this->location->getAllData(),
            'dept' => $this->dept->loadData(),
            'equipment' => $this->equipment->loadAllData(),
            'footer' => [
                '<script src="' . base_url() . 'js/App/validasi.js' . '"></script>',
                '<script src="' . base_url() . 'js/Module/Mfg/Spk/add.js' . '"></script>'
            ]
        ];

        return view('Module/Mfg/Spk/add', $data);
    }
}
