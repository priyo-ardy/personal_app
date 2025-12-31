<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Validation\UsersValidation;
use App\Repositories\DataTableRepository;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;
use Config\Services;
use Config\Database;

use function PHPUnit\Framework\lessThanOrEqual;

class UsersService
{
    protected $db;
    protected $userRepo;
    protected $validasi;
    public function __construct(UserRepository $userRepo)
    {
        $this->db = Database::connect();
        $this->userRepo = $userRepo;
        $this->validasi = Services::validation();
    }

    public function save(array $data)
    {
        try {
            // Set validasi dari input user
            $this->validasi->setRules(UsersValidation::$saveUser);

            // Jalankan proses validasi, jika validasi gagal kembalikan error
            if ($this->validasi->run($data) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message('error', "Failed to verify ew user data : {err}", ['err' => $error_to_string]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            // Inisialisasi data baru
            $user_id = generate_uuid();
            $user_email = email_hash($data['data_email']);
            $user_phone = phone_hash($data['data_phone']);

            // Lakukan pengecekan user_email dan user_phone
            $check_email = $this->userRepo->findByEmail($user_email);
            $check_phone = $this->userRepo->findByPhone($user_phone);

            // Array untuk menampung apabila terdapat error
            $duplicate_errors = [];

            // Jika email duplikat masukkan kedalam array penampung error
            if ($check_email) {
                $duplicate_errors[] = "Email <strong class=\"text-danger\">$data[data_email]</strong> already exists";
            }

            // Jika nomor telepon duplikat masukkan kedalam array penampung error
            if ($check_phone) {
                $duplicate_errors[] = "Phone <strong class=\"text-danger\">$data[data_phone]</strong> already exists";
            }

            // Jika terdapat error data yang duplikat kembalikan error
            if (count($duplicate_errors) > 0) {
                $error_to_string = implode("<br>", $duplicate_errors);
                log_message('error', "Failed to create new user : {user}, with error {err}", ['user' => $data['data_username'], 'err' => $error_to_string]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_CONFLICT);
            }

            // Jika semua kriteria lolos inisialisasi data yang akan disimpan kedalam database
            $data = [
                'user_id' => $user_id,
                'user_name' => $data['data_username'],
                'full_name' => ucwords($data['data_fullname']),
                'user_password' => password_hash($data['data_password'], PASSWORD_DEFAULT),
                'user_email' => enkripsi($data['data_email']),
                'user_phone' => enkripsi($data['data_phone']),
                'email_hash' => email_hash($data['data_email']),
                'phone_hash' => phone_hash($data['data_phone']),
                'user_image' => 'default.png',
                'user_status' => 'active',
                'user_level' => $data['data_level'],
                'remark' => $data['data_remark'],
                'login_attempts' => 0,
                'last_login' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => session()->get('user_name'),
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => null,
                'deleted_at' => null
            ];

            // Proses penyimpanan data
            $this->db->transStart();
            $this->userRepo->create($data);
            $this->db->transComplete();

            // Jika aplikasi gagal menyimpan data, rollback data dan kembalikan errornya
            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', "Failed to create new user : {user}, error {err}", ['user' => $data['user_name'], 'err' => $this->db->getLastQuery()]);
                throw new \Exception('Failed to create new user', ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            // Jika berhasil meyimpan data, kembalikan nilai true
            return true;
        } catch (\Exception $e) {
            log_message('error', '[UsersService::createUser] failed to create new user with error {err} from {ip}]', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    function loadTable($requestData): array
    {
        // Load user model
        $model = new UserModel();
        // Buat database builder
        $builder = $model->builder();

        // Inisialisasi kolom pencarian dari table
        $column_search = ['user_name', 'full_name', 'email_hash', 'phone_hash', 'user_level', 'last_login', 'login_from', 'remark'];

        // Inisialisasi kolom order (untuk klik order pada header table)
        $column_order = [
            '0' => 'user_name',
            '1' => 'full_name',
            '2' => 'email_hash',
            '3' => 'phone_hash',
            '4' => 'user_level',
            '5' => 'last_login',
            '6' => 'login_from',
            '7' => 'remark'
        ];

        // Tentukan default order pada saat awal data diload
        $defaultOrder = array('user_name' => 'asc');

        // Inisialisasi pencarian custom di table karena alamat email dan nomor telepon kita hash, 
        // maka kita harus menyertakan bagian ini agar data nomor telepon dan alamat email terbaca
        $customSearch = [
            'email_hash' => function ($builder, $searchValue) {
                $email_hash = email_hash($searchValue);
                $builder->orWhere('email_hash', $email_hash);
            },
            'phone_hash' => function ($builder, $searchValue) {
                $phone_hash = phone_hash($searchValue);
                $builder->orWhere('phone_hash', $phone_hash);
            }
        ];

        // Kirim parameter ke repository datatable untuk dieksekusi
        $dataTable = new DataTableRepository($builder, $column_search, $column_order, $defaultOrder, $customSearch, 'deleted_at');

        // lakokan proses pengambilan data dari database
        $result = $dataTable->proses($requestData);

        // Buat array untuk menampun data dari database
        $formattedData = [];

        // Lakukan perulangan data dari database untuk dikirim ke front end
        foreach ($result['data'] as $row) {
            $formattedData[] = [
                enkripsi($row->user_id),
                '<a href="#" onclick="editData(`' . enkripsi($row->user_id) . '`)" class="text-primary fw-bolder text-decoration-none" title="Click to edit">' . $row->user_name . '</a>',
                $row->full_name,
                ($row->user_email) ? dekripsi($row->user_email) : '',
                ($row->user_phone) ? dekripsi($row->user_phone) : '',
                $row->user_level,
                $row->last_login,
                $row->login_from,
                $row->remark,
            ];
        }

        // masukkan seluruh data kedalam array
        $result['data'] = $formattedData;

        // kembalikan data
        return $result;
    }

    function exportData()
    {
        try {
            // Buat filename untuk file excel yang diexport
            $fileName = "user_list" . date("Ymd_his") . 'xlsx';

            // Inisialisasi untuk nama kolom pada file excel
            $headers = [
                'User Name',
                'Full Name',
                'Email Address',
                'Phone Number',
                'Last Login',
                'last Login From',
                'user_level',
                'user_status',
                'Remark',
            ];

            // Ambil data dari database
            $dataCallback = function ($offset, $limit) {
                $column = 'user_name, full_name, user_email, user_phone, last_login, login_from, user_level, user_status, remark';
                return $this->userRepo->getChunkedData($offset, $limit, 'user_name', $column);
            };

            // render data kedalam file excel dengan melakukan dekripsi pada kolom user_email dan user_phone
            return export_decrypted_data($fileName, $headers, ['user_email', 'user_phone'], $dataCallback);
        } catch (\Exception $e) {
            log_message('error', '[UsersService::exportData] failed to export users data with error {err} from ip {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            return $e;
        }
    }

    function getUserData($user_token)
    {
        try {
            // Dekripsi user_id dari data user token yang dikirim
            $user_id = dekripsi($user_token);

            // Cari data user berdasarkan user_id
            $get_user_by_id = $this->userRepo->findById($user_id);
            if (!$get_user_by_id) {
                // Jika data user tidak ditemukan kembalikan error
                throw new \Exception("User not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            // Jika data user ditemukan, kembalikan data user
            $data = [
                'token' => enkripsi($get_user_by_id->user_id),
                'user_name' => $get_user_by_id->user_name,
                'full_name' => $get_user_by_id->full_name,
                'user_email' => dekripsi($get_user_by_id->user_email),
                'user_phone' => dekripsi($get_user_by_id->user_phone),
                'user_level' => $get_user_by_id->user_level,
                'user_status' => $get_user_by_id->user_status,
                'remark' => $get_user_by_id->remark
            ];

            return $data;
        } catch (\Exception $e) {
            log_message('error', '[UsersService::getUserData] failed to getting user data with error {err} from {ip}]', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    public function update(array $data)
    {
        try {
            // 1. Validasi Input Dasar
            $this->validasi->setRules(UsersValidation::$updateUser);

            if ($this->validasi->run($data) === false) {
                $error_to_string = implode("<br>", $this->validasi->getErrors());
                log_message('error', "Failed to verify update user data : {err}", ['err' => $error_to_string]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            // 2. Ambil Data User Lama
            $user_id = dekripsi($data['data_token']);
            $existing_user = $this->userRepo->findById($user_id);

            if (!$existing_user) {
                log_message('error', "User not found with ID: " . $user_id);
                throw new \Exception("Failed to update user data, user not available", ResponseInterface::HTTP_NOT_FOUND);
            }

            // Siapkan array untuk menampung pesan error
            $error_message = [];

            // --- LOGIKA PENGECEKAN UNIK (Poin 1, 2, 3, 4) ---

            // 3. Cek Username (Jika berubah, cek apakah sudah dipakai orang lain)
            if ($data['data_username'] !== $existing_user->user_name) {
                $cek_username = $this->userRepo->findByUsername($data['data_username']);
                if ($cek_username) {
                    $error_message[] = "Username <strong class=\"text-danger\">{$data['data_username']}</strong> already exists";
                }
            }

            // 4. Cek Email (Jika hash email berubah, cek ketersediaan)
            // Menggunakan hash untuk perbandingan agar akurat
            $new_email_hash = email_hash($data['data_email']);
            if ($new_email_hash !== $existing_user->email_hash) {
                $check_email = $this->userRepo->findByEmail($new_email_hash);
                if ($check_email) {
                    $error_message[] = "Email <strong class=\"text-danger\">{$data['data_email']}</strong> already exists";
                }
            }

            // 5. Cek Telepon (Jika hash phone berubah, cek ketersediaan)
            $new_phone_hash = phone_hash($data['data_phone']);
            if ($new_phone_hash !== $existing_user->phone_hash) {
                $check_phone = $this->userRepo->findByPhone($new_phone_hash);
                if ($check_phone) {
                    $error_message[] = "Phone <strong class=\"text-danger\">{$data['data_phone']}</strong> already exists";
                }
            }

            // Jika ada error dari pengecekan di atas, lempar Exception
            if (count($error_message) > 0) {
                $error_to_string = implode("<br>", $error_message);
                log_message('error', "Failed to update user data duplicates found: {err}", ['err' => $error_to_string]);
                throw new \Exception($error_to_string, ResponseInterface::HTTP_BAD_REQUEST);
            }

            // --- PERSIAPAN DATA UPDATE ---

            $updateData = [
                'user_name'     => $data['data_username'],
                'full_name'     => ucwords($data['data_fullname']),
                'user_email'    => enkripsi($data['data_email']),
                'user_phone'    => enkripsi($data['data_phone']),
                'email_hash'    => $new_email_hash,
                'phone_hash'    => $new_phone_hash,
                'user_level'    => $data['data_level'],
                'remark'        => $data['data_remark'],
                'updated_at'    => date('Y-m-d H:i:s'),
                'updated_by'    => session()->get('user_name'),
            ];

            // Logika Password: Hanya update jika user mengisi password baru
            if (!empty($data['data_password'])) {
                $updateData['user_password'] = password_hash($data['data_password'], PASSWORD_DEFAULT);
            }

            // Logika Image: Jangan set default.png jika user tidak upload gambar baru
            // Asumsi: Jika tidak ada upload, pakai gambar lama. Jika kamu punya logika upload, letakkan di sini.
            // $updateData['user_image'] = ... (logika upload file) ...

            // --- EKSEKUSI DATABASE ---

            $this->db->transStart();

            // Pastikan method update di Repo menerima ID dan Array Data
            $this->userRepo->update($user_id, $updateData);

            $this->db->transComplete();

            // Jika gagal, kembalikan error
            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                log_message('error', "Failed to update user data DB error: {err}", ['err' => json_encode($this->db->error())]);
                throw new \Exception("Failed to update user data, internal server error", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', 'Successfully update user data {user_id}', ['user_id' => $user_id]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[UsersService::update] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    function disableData($json_data)
    {
        try {
            // Jika tidak ada data JSON, lempar Exception
            if (!is_array($json_data)) {
                throw new \Exception("Invalid JSON input request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            // Jika file json tidak ada object bernama token, lempar Exception
            if (empty($json_data['token'])) {
                throw new \Exception("Token is required", ResponseInterface::HTTP_BAD_REQUEST);
            }

            // Dekripsi user_id
            $user_id = dekripsi(trim($json_data['token']));

            // Cari data user
            $get_data = $this->userRepo->findById($user_id);

            // Jika data user tidak ditemukan, lempar Exception
            if (!$get_data) {
                throw new \Exception("User not found", ResponseInterface::HTTP_NOT_FOUND);
            }

            // Jika data user ditemukan, lakukan disable
            $this->db->transStart();
            $this->userRepo->delete($user_id);
            $this->db->transComplete();

            // Jika disable gagal, lempar Exception
            if ($this->db->transStatus() === false) {
                $this->db->transRollback(); // rollback transaksi jika proses gagal
                log_message('error', "Failed to disable user data DB error: {err}", ['err' => json_encode($this->db->error())]);
                throw new \Exception("Failed to disable user data, internal server error", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', '[UserService::disableData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    function prevData($json_data)
    {
        try {
            if (!is_array($json_data)) {
                throw new \Exception("Invalid JSON request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (empty($json_data['token'])) {
                throw new \Exception("Token is not available in JSON request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $user_name = trim($json_data['token']);

            $get_data = $this->userRepo->prevUser($user_name);
            if (!$get_data) {
                throw new \Exception("You are in the first data", ResponseInterface::HTTP_NOT_FOUND);
            }

            return [
                'token' => enkripsi($get_data->user_id)
            ];
        } catch (\Exception $e) {
            log_message('error', '[UserService::prevData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    function nextData($json_data)
    {
        try {
            if (!is_array($json_data)) {
                throw new \Exception("Invalid JSON request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (empty($json_data['token'])) {
                throw new \Exception("Token is not available in JSON request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $user_name = trim($json_data['token']);

            $get_data = $this->userRepo->nextUser($user_name);
            if (!$get_data) {
                throw new \Exception("You are in the last data", ResponseInterface::HTTP_NOT_FOUND);
            }

            return [
                'token' => enkripsi($get_data->user_id)
            ];
        } catch (\Exception $e) {
            log_message('error', '[UserService::nextData] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }

    function massDelete($json_data)
    {
        try {
            if (!is_array($json_data)) {
                throw new \Exception("Invalid JSON request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!isset($json_data['token'])) {
                throw new \Exception("Token is not available in JSON request", ResponseInterface::HTTP_BAD_REQUEST);
            }

            $user_id = $json_data['token'];

            $user_data = [];
            for ($i = 0; $i < count($user_id); $i++) {
                $user_data[] = dekripsi(trim($user_id[$i]));
            }

            $this->db->transStart();
            $this->userRepo->massDelete($user_data);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback(); // rollback transaksi jika proses gagal
                log_message('error', "Failed to disable user data DB error: {err}", ['err' => json_encode($this->db->error())]);
                throw new \Exception("Failed to disable user data, internal server error", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            }

            log_message('info', "Mass delete user data successfully with total data : {total} NIK : {NIK} form IP {ip}", ['total' => count($user_data), 'NIK' => session()->get('user_name'), $_SERVER['REMOTE_ADDR']]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[UserService::massDelete] Unexpected error occured : {err} from {ip}', ['err' => $e->getMessage(), 'ip' => $_SERVER['REMOTE_ADDR']]);
            throw $e;
        }
    }
}
