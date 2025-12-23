# Project Inj

Project Inj adalah sebuah aplikasi web yang menggunakan framework CodeIgniter 4. Aplikasi ini dirancang untuk membantu programmer dalam mengelola struktur folder pada proyek mereka.

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
5. Jalankan perintah `php spark migrate` untuk menjalankan migrasi database.
6. Jalankan perintah `php spark serve` untuk menjalankan server development.
7. Buka browser dan akses `http://localhost:8080` untuk melihat aplikasi.
8. Pada file `.env` tambahkan baris phone_salt dan email_salt untuk hashing email dan nomor telepon

## Kontribusi

Jika Anda ingin berkontribusi pada proyek ini, silakan ikuti langkah-langkah berikut:

1. Buat fork pada repository ini.
2. Buat branch baru untuk fitur atau perbaikan yang ingin Anda tambahkan.
3. Lakukan perubahan yang diperlukan pada branch Anda.
4. Buat pull request ke repository utama.

## Lisensi

Proyek ini dilisensikan di bawah MIT License. Silakan lihat [LICENSE](LICENSE) untuk informasi lebih lanjut.
