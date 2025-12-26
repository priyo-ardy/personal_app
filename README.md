# Codeigniter 4 Personal Project

Project ini adalah sebuah aplikasi web yang menggunakan framework CodeIgniter 4, bootstrap 5, vanilla.js. Aplikasi ini dirancang untuk menjadi personal portofolio dalam pengembangan aplikasi berbasis web.

## Struktur Folder

Berikut adalah struktur folder pada proyek ini:

### App

Folder `App` berisi berbagai komponen penting pada proyek ini, termasuk:

- **Commands**: Folder ini berisi komponen yang digunakan untuk menjalankan perintah CLI. Contohnya, dapat digunakan untuk membuat model atau migrasi.
- **Interfaces**: Folder ini berisi antarmuka yang digunakan untuk mengatur interaksi antara layanan dan repository.
- **Jobs**: Folder ini berisi komponen yang digunakan untuk menjalankan tugas-tugas di latar belakang.
- **Repositories**: Folder ini berisi komponen yang digunakan untuk mengelola data pada database.
- **Services**: Folder ini berisi komponen yang digunakan untuk mengelola layanan pada aplikasi.
- **Validation**: Folder ini berisi komponen yang digunakan untuk melakukan validasi data.

### Filter

Folder `Filter` berisi komponen yang digunakan untuk melakukan filter pada permintaan HTTP.

- **RateLimiterFilter**: Komponen ini digunakan untuk melakukan rate limiting pada permintaan HTTP.

## Cara Menggunakan Project Inj

Untuk menggunakan proyek ini, Anda dapat mengikuti langkah-langkah berikut:

1. Clone repository ini ke dalam direktori lokal Anda.
2. Buka terminal atau command prompt, lalu arahkan ke direktori proyek.
3. Jalankan perintah `composer install` untuk menginstal dependensi proyek.
4. Konfigurasi database pada file `.env` sesuai dengan konfigurasi database Anda.
5. Pada file `.env` tambahkan baris `phone_salt` dan `email_salt` untuk `hashing email dan nomor telepon user`
6. Jalankan perintah `php spark migrate` untuk menjalankan migrasi database.
7. Jalankan perintah `php spark db:seed RestoreData` untuk mengisi data awal seperti data login dan master data lainnya
8. Jalankan perintah `php spark serve` untuk menjalankan server development.
9. Buka browser dan akses `http://localhost:8080` untuk melihat aplikasi.

## Konfigurasi Email

Untuk melakukan konfigurasi akun email, kamu harus menambahkan data berikut ini kedalam file `.env` (Jangan melakukan hard code pada file `app/config/Email.php`)

`email.fromEmail` diisi dengan alamat pengirim email
`email.fromName` diisi dengan nama pengirim email
`email.SMTPHost` diisi dengan host pengirim email
`email.SMTPUser` diisi dengan username akun pengirim email
`email.SMTPPass` diisi dengan password akun pengirim email
`email.SMTPPort` diisi dengan port akun pengirim email
`email.SMTPCrypto` diisi dengan tipe enkripsi

## Pengiriman Email

Untuk mengirim email (yang terdapat pada antrian) silahkan eksekusi `php spark email:pending` perintah ini akan mengeksekusi semua email yang statusnya pending maupun failed

## Kontribusi

Jika Anda ingin berkontribusi pada proyek ini, silakan ikuti langkah-langkah berikut:

1. Buat fork pada repository ini.
2. Buat branch baru untuk fitur atau perbaikan yang ingin Anda tambahkan.
3. Lakukan perubahan yang diperlukan pada branch Anda.
4. Buat pull request ke repository utama.

## Lisensi

Proyek ini dilisensikan di bawah MIT License. Silakan lihat [LICENSE](LICENSE) untuk informasi lebih lanjut.
