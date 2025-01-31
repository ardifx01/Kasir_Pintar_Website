1. **Clone Repository:** Gunakan Git untuk mengkloning repositori dari GitHub ke komputer lokal Anda.  Perintahnya biasanya seperti ini: `git clone https://github.com/NeoCode29/project-kasir-pintar-website.git`

2. **Instalasi Dependencies:**  Navigasi ke direktori proyek yang telah dikloning. Gunakan Composer untuk menginstal semua dependensi yang dibutuhkan.  Ketik perintah: `composer install`

3. **Konfigurasi .env:** Salin file `.env.example` menjadi `.env` (`cp .env.example .env`).  Ubah nilai-nilai di dalam file `.env` sesuai dengan konfigurasi lingkungan Anda (database, nama aplikasi, kunci aplikasi, dll).  Pastikan untuk menghasilkan kunci aplikasi baru menggunakan `php artisan key:generate` jika belum ada.

4. **Setup Database:** Setup database baru di server database Anda (misalnya MySQL, PostgreSQL) sesuai dengan spesifikasi yang ada di file `.env`.

5. **Jalankan Migrasi:** Setelah konfigurasi database selesai, jalankan migrasi database Laravel untuk membuat tabel yang diperlukan.  Perintahnya adalah: `php artisan migrate`

6. **Isi Data (Optional):** Jika ada file seeder, Anda dapat menjalankan perintah `php artisan db:seed --class=CategoryProductSeeder` untuk mengisi database dengan data contoh.

7. **Konfigurasi Sanctum:** Ikuti instruksi dokumentasi Sanctum untuk setting konfigurasi autentikasi.  Ini biasanya melibatkan menjalankan perintah migrasi dan  melakukan konfigurasi di file `config/auth.php`.

9. **Konfigurasi Blade UI Icon:** Ikuti instruksi instalasi dan konfigurasi Blade UI Icon sesuai dokumentasinya. Ini biasanya melibatkan instalasi package dan konfigurasi di template Blade Anda.

10. **Jalankan Aplikasi:**  Jalankan server development Laravel dengan perintah: `php artisan serve`.  Akses aplikasi melalui browser Anda.

11. **Konfigurasi Server (Produksi):** Untuk deployment ke server produksi, konfigurasi tambahan mungkin diperlukan, termasuk konfigurasi web server (Apache, Nginx) dan pengaturan database yang sesuai dengan lingkungan produksi.
