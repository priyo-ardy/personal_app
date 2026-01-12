<?php

namespace App\Services;

use App\Models\AppSetup\Position\PositionModel;
use App\Repositories\PositionRepository;
use App\Validation\PositionValidation;
use App\Repositories\DataTableRepository;
use App\Models\AppSetup\Position\VwPositionModel;
use App\Traits\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Config\Services;

class PositionService
{
    use ResponseTrait;
    protected $db;
    protected $validasi;
    protected $positionRepo;

    public function __construct(PositionRepository $positionRepo)
    {
        $this->db = Database::connect();
        $this->validasi = Services::validation();
        $this->positionRepo = $positionRepo;
    }

    public function loadData()
    {
        return $this->positionRepo->all('code', 'asc');
    }

    public function loadDataList()
    {
        try {
            $lists = $this->positionRepo->generatePositionList();

            $data = [];

            foreach ($lists as $row) {
                $data[] = [
                    'token' => $row->id,
                    'code' => $row->code,
                    'name' => $row->name
                ];
            }

            return $data;
        } catch (\Exception $e) {
            log_message('error', '[PositionService::loadDataList] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function generateCode()
    {
        return $this->positionRepo->generateNewCode();
    }

    public function save(array $data)
    {
        try {
            $this->validasi->setRules(PositionValidation::$save);

            if ($this->validasi->run($data) == false) {
                $error_to_string = implode('<br>', $this->validasi->getErrors());
                log_message('error', '[PositionService::save] Failed to verify new position data by {NIK} from {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $error_to_string]);
                throw new \Exception("Validation failed $error_to_string", ResponseInterface::HTTP_BAD_REQUEST);;
            }

            $code = $this->positionRepo->generateNewCode();

            $data = [
                'id' => generate_uuid(),
                'code' => $code,
                'name' => $data['data_name'],
                'description' => $data['data_remark'],
                'nbhx_position' => $data['nbhx_position'],
                'dept' => $data['data_dept'],
                'section' => $data['data_section'],
                'report_to' => $data['data_report_to'],
                'grade' => $data['data_grade'],
                'rank' => $data['data_rank'],
                'emp_status' => $data['data_status'],
                'category' => $data['data_category'],
                'nbhx_category' => $data['nbhx_category'],
                'effective_date' => $data['effective_date'],
                'hitung_absen' => $data['data_absen'],
                'hitung_lembur' => $data['data_lembur'],
                'created_by' => session()->get('user_name'),
            ];

            $this->db->transStart();
            $this->positionRepo->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', '[PositionService::save] Failed to save new position data by {NIK} from {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $this->db->error()]);
                throw new \Exception('Failed to save new position data', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[PositionService::save] Unexpected error occured NIK : {NIK}, from IP {ip} : {err}', ['NIK' => session()->get('user_name'), 'ip' => $_SERVER['REMOTE_ADDR'], 'err' => $e->getMessage()]);
            throw $e;
        }
    }

    public function loadTable($requestedData)
    {
        try {
            $model = new VwPositionModel();
            $builder = $model->builder();

            $column_search = ['code', 'name', 'nbhx_position_name', 'dept_name', 'section_name', 'report_to_position', 'grade_name', 'rank_name', 'status_name', 'category_name', 'nbhx_category_name', 'nama_hitung_absen', 'nama_hitung_lembur', 'description'];
            $column_order = [
                '0' => 'code',
                '1' => 'name',
                '2' => 'nbhx_position_name',
                '3' => 'dept_name',
                '4' => 'section_name',
                '5' => 'report_to_position',
                '6' => 'grade_name',
                '7' => 'rank_name',
                '8' => 'status_name',
                '9' => 'category_name',
                '10' => 'nbhx_category_name',
                '11' => 'effective_date',
                '12' => 'nama_hitung_absen',
                '13' => 'nama_hitung_lembur',
                '14' => 'description',
            ];

            $order = ['code' => 'asc'];

            $dataTable = new DataTableRepository($builder, $column_search, $column_order, $order, [], 'deleted_at');
            $result = $dataTable->proses($requestedData);
            $data = [];
            foreach ($result['data'] as $row) {
                $data[] = [
                    enkripsi($row->id),
                    '<a href="#" class="text-primary fw-bolder text-decoration-none" onclick="getData(`' . enkripsi($row->id) . '`)">' . $row->code . '</a>',
                    $row->name,
                    $row->nbhx_position_name,
                    $row->dept_name,
                    $row->section_name,
                    $row->report_to_position,
                    $row->grade_name,
                    $row->rank_name,
                    $row->status_name,
                    $row->category_name,
                    $row->nbhx_category_name,
                    $row->effective_date,
                    $row->nama_hitung_absen,
                    $row->nama_hitung_lembur,
                    $row->description,
                ];
            }

            $result['data'] = $data;
            return $result;
        } catch (\Exception $e) {
            log_message('error', '[NbhxPositionService::loadTable] Unexpexted error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $token)
    {
        try {
            $getData = $this->positionRepo->findById($token);
            if (empty($getData)) {
                throw new \Exception("Data not found " . $token, ResponseInterface::HTTP_NOT_FOUND);
            }

            $data = [
                'token' => enkripsi($getData->id),
            ];

            return $data;
        } catch (\Exception $e) {
            log_message('error', '[PositionService::getData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function showData(string $token)
    {
        try {
            $getData = $this->positionRepo->findById($token);
            if (empty($getData)) {
                throw new \Exception("Data not found " . $token, ResponseInterface::HTTP_NOT_FOUND);
            }

            return $getData;
        } catch (\Exception $e) {
            log_message('error', '[PositionService::showData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
