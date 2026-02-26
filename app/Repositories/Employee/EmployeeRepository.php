<?php

namespace App\Repositories\Employee;

use App\Models\MasterData\Employee\EmployeeModel;
use App\Models\MasterData\Employee\EmployeeActiveModel;

use App\Repositories\CrudRepository;

class EmployeeRepository extends CrudRepository
{
    public function __construct()
    {
        $this->model = new EmployeeModel();
    }

    public function getNewNik(string $category)
    {
        // 1. Cari data terakhir yang diawali dengan category tersebut
        // Menggunakan LIKE agar lebih akurat mencari prefix
        $lastData = $this->model->where("nik LIKE '$category%'")
            ->orderBy('nik', 'DESC')
            ->limit(1)
            ->first();

        if ($lastData) {
            $lastCodeString = $lastData->nik;

            // 2. Ambil angka setelah karakter category
            // Contoh: Jika nik = "A0005" dan category = "A", ambil mulai dari indeks 1
            $lastNumberStr = substr($lastCodeString, strlen($category));

            // 3. Pastikan dikonversi ke integer sebelum ditambah 1
            $nextNumber = (int)$lastNumberStr + 1;
        } else {
            // Jika belum ada data dengan category tersebut
            $nextNumber = 1;
        }

        // 4. Gabungkan kembali dengan padding 4 digit
        $paddedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return $category . $paddedNumber;
    }

    public function checkKtp(string $no_ktp_hash)
    {
        return $this->model->where('no_ktp_hash', $no_ktp_hash)->first();
    }

    public function checkEmail(string $email_hash)
    {
        return $this->model->where('alamat_email_hash', $email_hash)->first();
    }

    public function checkPhone(string $phone_hash)
    {
        return $this->model->where('tlp_1_hash', $phone_hash)->first();
    }

    public function checkBpjsKesehatan(string $bpjs_keshatan_hash)
    {
        return $this->model->where('bpjs_kesehatan_hash', $bpjs_keshatan_hash)->first();
    }

    public function checkBpsjTenagaKerja(string $bpjs_tk_hash)
    {
        return $this->model->where('bpjs_tenaga_kerja_hash', $bpjs_tk_hash)->first();
    }

    public function checkNpwp(string $npwp_hash)
    {
        return $this->model->where('npwp_hash', $npwp_hash)->first();
    }

    public function checkRekening(string $rekening_hash)
    {
        return $this->model->where('no_rekening_hash', $rekening_hash)->first();
    }

    public function generateList()
    {
        return $this->model->findAll();
    }

    public function employeeListUnregisteredJobData()
    {
        $db = \Config\Database::connect();

        $sub_query = $db->table('m_job_data')
            ->select('employee_id')
            ->getCompiledSelect();

        $builder = $db->table('m_karyawan');

        $builder->select('id, nik, name');
        $builder->where("id NOT IN ($sub_query)", null, false);
        $builder->orderBy('nik', 'ASC');

        return $builder->get()->getResultObject();
    }

    public function employeeListRegisteredJobData()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('m_job_data j');

        // 1. Ambil kolom yang dibutuhkan
        $builder->select('DISTINCT ON (k.nik) j.id as job_id, k.id as employee_id, k.nik, k.name');

        // 2. Hubungkan ke tabel karyawan
        $builder->join('m_karyawan k', 'k.id = j.employee_id');

        // 3. FILTER KRUSIAL: Status aktif DAN Tanggal Efektif sudah lewat atau hari ini
        $builder->where('j.status', '1');
        $builder->where('j.effective_date <=', date('Y-m-d')); // Menggunakan tanggal hari ini dari server PHP

        // 4. PENGURUTAN: Ambil yang terbaru dari grup yang lolos filter di atas
        $builder->orderBy('k.nik');
        $builder->orderBy('j.effective_date', 'DESC');
        $builder->orderBy('j.created_at', 'DESC'); // Backup jika ada 2 record di tanggal yang sama

        $result = $builder->get()->getResultArray();

        return $result;
    }

    public function getEmployeeActive()
    {
        $model = new EmployeeActiveModel();

        return $model->orderBy('nik', 'ASC')->findAll();
    }

    public function getemployeeByDate(string $date)
    {
        $cacheKey = 'employees_list_' . $date;

        if ($found = cache($cacheKey)) {
            return $found;
        }

        $query = "
        SELECT 
            JD.id AS job_data_id,
            K.id AS employee_id,
            K.NIK,
            K.name AS employee_name,
            JD.position,
            P.name AS position_name,
            D.name AS dept_name,
            S.name AS section_name,
            NP.name AS nbhx_position_name,
            P1.name AS report_to_position,
            G.name AS grade_name,
            ER.name AS rank_name,
            C.name AS category_name,
            CN.name AS nbhx_category_name,
            JD.effective_date AS on_job_position,
            JDA.name AS action_name,
            JDR.name AS reason_name,
            K1.name as superior_name,
            K.tgl_masuk_kerja,
            JD.work_relationship,
            JD.no_contract,
            JD.durasi_kontrak,
            JD.tipe_durasi,
            JD.akhir_kontrak,
            JD.remark,
            K.deleted_at
        FROM m_karyawan AS K
        LEFT JOIN (
            -- Mengambil satu baris terbaru per karyawan sekaligus
            SELECT DISTINCT ON (employee_id) *
            FROM m_job_data
            WHERE effective_date <= ?
            ORDER BY employee_id, effective_date DESC, id DESC
        ) AS JD ON JD.employee_id = K.id
        LEFT JOIN m_position AS P ON JD.position = P.id
        LEFT JOIN m_department AS D ON P.dept = D.id
        LEFT JOIN m_section AS S ON P.section = S.id
        LEFT JOIN m_nbhx_position AS NP ON P.nbhx_position = NP.id
        LEFT JOIN m_position AS P1 ON P.report_to = P1.id
        LEFT JOIN m_grade AS G ON P.grade = G.id
        LEFT JOIN m_employee_rank AS ER ON P.rank = ER.id
        LEFT JOIN m_employee_category AS C ON P.category = C.id
        LEFT JOIN m_class_nbhx AS CN ON P.nbhx_category = CN.id
        LEFT JOIN m_job_data_action AS JDA ON JD.action = JDA.id
        LEFT JOIN m_job_data_reason AS JDR ON JD.reason = JDR.id
        LEFT JOIN m_karyawan AS K1 ON JD.superior = K1.id
        WHERE 
            (JDA.code IS NULL OR JDA.code <> 'JDA-005') AND
            JD.deleted_at IS NULL
        ORDER BY K.NIK ASC
    ";

        $result = $this->model->query($query, [$date])->getResultObject();

        // Simpan cache (1 jam)
        cache()->save($cacheKey, $result, 3600);

        return $result;
    }
}
