<?php

namespace App\Services\Employee;

use App\Repositories\Employee\EmployeeRepository;
use App\Repositories\DataTableRepository;
use App\Services\UploadImage\UploadImageService;
use App\Validation\Employee\EmployeeValidation;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Config\Services;
use App\Traits\ResponseTrait;
use Faker\Core\Uuid;

class EmployeeService
{
    protected $db;
    protected $validation;
    protected $repository;
    protected $uploadService;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->validation = Services::validation();
        $this->repository = new EmployeeRepository();
        $this->uploadService = new UploadImageService();
    }

    public function newNik(string $category)
    {
        try {
            $nik = $this->repository->getNewNik($category);
            return $nik;
        } catch (\Exception $e) {
            log_message('error', "[EmployeeService::newNik] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function saveData(array $postData, $uploadFile = null)
    {
        try {
            $this->validation->setRules(EmployeeValidation::$save);

            if ($this->validation->run($postData) === false) {
                $error_to_string = implode("<br>", $this->validation->getErrors());
                log_message('error', '[EmployeeService::saveData] Validation error : {err} from {ip}', ['err' => implode('\n', $this->validation->getErrors()), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            $imageFile = null;
            $error_message = [];

            // No ktp
            if ($postData['data_ktp'] !== '') {
                $check_ktp = $this->repository->checkKtp(ktp_hash(trim($postData['data_ktp'])));
                if ($check_ktp) {
                    $error_message[] = 'ID Number ' . $postData['data_ktp'] . ' already registered';
                }
            }

            // Email
            if ($postData['data_email'] !== '') {
                $check_email = $this->repository->checkEmail(email_hash(trim($postData['data_email'])));
                if ($check_email) {
                    $error_message[] = 'Email ' . $postData['data_email'] . ' already registered';
                }
            }

            // Nomor telepon
            if ($postData['data_tlp_1'] !== '') {
                $check_phone = $this->repository->checkPhone(phone_hash(trim($postData['data_tlp_1'])));
                if ($check_phone) {
                    $error_message[] = 'Phone Number ' . $postData['data_tlp_1'] . ' already registered';
                }
            }

            // nomor bpjs kesehatan
            if ($postData['data_bpjs_kesehatan'] !== '') {
                $bpjs_keshatan = $this->repository->checkBpjsKesehatan(bpjs_kesehatan_hash(trim($postData['data_bpjs_kesehatan'])));
                if ($bpjs_keshatan) {
                    $error_message[] = 'BPJS Kesehatan ' . $postData['data_bpjs_kesehatan'] . ' already registered';
                }
            }

            // Nomor BPJS ketenagakerjaan
            if ($postData['data_bpjs_tk'] !== '') {
                $bpjs_ketenagakerjaan = $this->repository->checkBpsjTenagaKerja(bpjs_ketenagakerjaan_hash(trim($postData['data_bpjs_tk'])));
                if ($bpjs_ketenagakerjaan) {
                    $error_message[] = 'BPJS Ketenagakerjaan ' . $postData['data_bpjs_tk'] . ' already registered';
                }
            }

            // Nomor npwp
            if ($postData['data_npwp'] !== '') {
                $check_npwp = $this->repository->checkNpwp(npwp_hash(trim($postData['data_npwp'])));
                if ($check_npwp) {
                    $error_message[] = 'NPWP ' . $postData['data_npwp'] . ' already registered';
                }
            }

            // Nomor rekening
            if ($postData['data_rekening'] !== '') {
                $check_rekening = $this->repository->checkRekening(bank_account_no_hash(trim($postData['data_rekening'])));
                if ($check_rekening) {
                    $error_message[] = 'Bank Account Number ' . $postData['data_rekening'] . ' already registered';
                }
            }

            if (count($error_message) > 0) {
                $error_to_string = implode("<br>", $error_message);
                log_message('error', '[EmployeeService::saveData] Validation error : {err} from {ip}', ['err' => implode('\n', $error_message), 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            // Upload employee photo
            if ($uploadFile && $uploadFile->isValid()) {
                try {
                    $uploadResult = $this->uploadService->upload_single_image('employee', $uploadFile);
                    $imageFile = $uploadResult['file_name'];
                } catch (\Exception $e) {
                    log_message('error', "[EmployeeService::saveData] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
                    throw $e;
                }
            }

            $data = [
                'id' => uuid_v7(),
                'category' => $postData['data_category'],
                'lokasi_kerja' => trim($postData['data_lokasi']),
                'nik' => trim($postData['data_nik']),
                'name' => strtoupper(trim($postData['data_name'])),
                'no_ktp' => enkripsi(trim($postData['data_ktp'])),
                'no_ktp_hash' => ktp_hash(trim($postData['data_ktp'])),
                'tempat_lahir' => $postData['data_tempat_lahir'],
                'tgl_lahir' => $postData['data_tgl_lahir'],
                'jenis_kelamin' => $postData['data_gender'],
                'golongan_darah' => $postData['data_gol_darah'] ?? null,
                'agama' => $postData['data_agama'],
                'tinggi_badan' => $postData['data_tinggi'] ?? 0,
                'berat_badan' => $postData['data_berat'] ?? 0,
                'alamat_email' => enkripsi(trim($postData['data_email'])),
                'alamat_email_hash' => email_hash(trim($postData['data_email'])),
                'tlp_1' => enkripsi(trim($postData['data_tlp_1'])),
                'tlp_1_hash' => phone_hash(trim($postData['data_tlp_1'])),
                'tlp_2' => ($postData['data_tlp_2']) ? enkripsi(trim($postData['data_tlp_2'])) : null,
                'tlp_2_hash' => ($postData['data_tlp_2']) ? phone_hash(trim($postData['data_tlp_2'])) : null,
                'tgl_masuk_kerja' => $postData['data_join'],
                'provinsi_sekarang' => $postData['data_provinsi_sekarang'],
                'kota_sekarang' => $postData['data_kota_sekarang'],
                'alamat_sekarang' => trim($postData['data_alamat_sekarang']),
                'provinsi_ktp' => $postData['data_provinsi_ktp'],
                'kota_ktp' => $postData['data_kota_ktp'],
                'alamat_ktp' => trim($postData['data_alamat_ktp']),
                'provinsi_orang_tua' => $postData['data_provinsi_orang_tua'],
                'kota_orang_tua' => $postData['data_kota_orang_tua'],
                'alamat_orang_tua' => trim($postData['data_alamat_orang_tua']),
                'photo' => ($imageFile !== null) ? $imageFile : null,
                'bpjs_keshatan' => ($postData['data_bpjs_kesehatan']) ? enkripsi(trim($postData['data_bpjs_kesehatan'])) : null,
                'bpjs_kesehatan_hash' => ($postData['data_bpjs_kesehatan']) ? bpjs_kesehatan_hash(trim($postData['data_bpjs_kesehatan'])) : null,
                'bpjs_tenaga_kerja' => ($postData['data_bpjs_tk']) ? enkripsi(trim($postData['data_bpjs_tk'])) : null,
                'bpjs_tenaga_kerja_hash' => ($postData['data_bpjs_tk']) ? bpjs_ketenagakerjaan_hash(trim($postData['data_bpjs_tk'])) : null,
                'npwp' => ($postData['data_npwp']) ? enkripsi(trim($postData['data_npwp'])) : null,
                'npwp_hash' => ($postData['data_npwp']) ? npwp_hash(trim($postData['data_npwp'])) : null,
                'no_rekening' => ($postData['data_rekening']) ? enkripsi(trim($postData['data_rekening'])) : null,
                'no_rekening_hash' => ($postData['data_rekening']) ? bank_account_no_hash(trim($postData['data_rekening'])) : null,
                'nama_bank' => $postData['data_nama_bank'] ?? null,
                'nama_rekening' => $postData['data_nama_rekening'] ?? null,
                'relasi_kontak_darurat' => $postData['data_relasi_emergency'],
                'nama_kontak_darurat' => ucwords(trim($postData['data_nama_emergency'])),
                'alamat_kontak_darurat' => trim($postData['data_alamat_emergency']),
                'tlp_kontak_darurat' => enkripsi(trim($postData['data_tlp_emergency'])),
                'tlp_kontak_darurat_hash' => phone_hash(trim($postData['data_tlp_emergency'])),
                'jenis_seragam' => $postData['data_jenis_seragam'] ?? null,
                'ukuran_seragam' => $postData['data_ukuran_seragam'] ?? null,
                'ukuran_sepatu' => $postData['data_ukuran_sepatu'] ?? null,
                'aksesoris' => $postData['data_aksesoris'] ?? null,
                'created_by' => session()->get('user_name'),
            ];

            $this->db->transStart();
            $this->repository->create($data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                if ($imageFile !== null) {
                    unlink(FCPATH . "uploads/employee/$imageFile");
                }
                log_message('error', "[EmployeeService::saveData] Failed to save data : {err} from {ip}", ['err' => $this->db->error()['message'], 'ip' => $_SERVER['REMOTE_ADDR']]);
                throw new \Exception("Failed to save data", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', "[EmployeeService::saveData] Employee with NIK {nik} has been saved", ['nik' => $data['nik']]);
            return [
                'token' => enkripsi($data['id']),
            ];
        } catch (\Exception $e) {
            log_message('error', "[EmployeeService::saveData] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function getData(string $employee_id)
    {
        try {
            $data = $this->repository->find($employee_id);

            return $data;
        } catch (\Exception $e) {
            log_message('error', "[EmployeeService::getData] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function generateList()
    {
        try {
            $data = $this->repository->all('nik', 'asc');
            return $data;
        } catch (\Exception $e) {
            log_message('error', "[EmployeeService::generateList] Unexpected error occured : {err} from {ip}", ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
