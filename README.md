# 📊 DataInvest - Sistem Statistik Terpadu v2

**DataInvest** adalah aplikasi manajemen data investasi yang dirancang khusus untuk DPMPTSP (Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu) Kabupaten Tanah Bumbu. Aplikasi ini mempermudah pengelolaan, analisis, dan visualisasi data investasi PMA (Penanaman Modal Asing) dan PMDN (Penanaman Modal Dalam Negeri).

---

## 🚀 Fitur Utama

-   **Dashboard Modern**: Visualisasi statistik investasi yang intuitif dengan fitur filter periode, tahun, dan mata uang.
-   **Manajemen Upload Fleksibel**: Mendukung impor data dari Excel (.xlsx, .xls, .csv) dengan sistem pemetaan kolom otomatis yang cerdas.
-   **Keamanan Berlapis**: Dilengkapi dengan deteksi ancaman real-time (SQLi, XSS, Path Traversal) dan manajemen hak akses berbasis peran (Role-Based Access Control).
-   **Statistik Otomatis**: Menghitung data agregat per sektor dan per kecamatan secara otomatis setelah proses upload.
-   **Ekspor Laporan**: Mendukung ekspor data dan grafik ke format Excel dan PDF yang siap cetak.
-   **Multi-Bahasa**: Antarmuka dalam Bahasa Indonesia dan Bahasa Inggris.

---

## 🛠️ Aturan & Logika Aplikasi (Business Rules)

Aplikasi ini dibangun dengan beberapa aturan utama yang harus dipahami oleh pengembang:

### 1. Keamanan & Akses (Filters)
-   **AuthFilter**: Memastikan setiap pengguna yang mengakses dashboard harus login terlebih dahulu.
-   **RoleFilter**: Membatasi akses fitur tertentu berdasarkan peran. Fitur *User Management* hanya dapat diakses oleh `superadmin`.
-   **ThreatDetection**: Filter global yang memantau setiap request untuk mendeteksi upaya serangan. Upaya serangan akan diblokir (403) dan dicatat dalam `security_logs`.

### 2. Logika Pemrosesan Data (Upload & Parser)
-   **Fleksibilitas Kolom**: Parser Excel dirancang sangat fleksibel. Sistem akan berusaha memproses data meskipun nama kolom tidak persis sama dengan template, menggunakan sistem *alternative mapping*.
-   **Pencegahan Duplikasi**: Sistem melarang upload data untuk Quarter dan Tahun yang sama jika data tersebut sudah berstatus 'completed' atau 'processing'.
-   **Format Waktu & Angka**: Angka investasi dibersihkan secara otomatis dari simbol mata uang (Rp) dan pemisah ribuan sebelum disimpan ke database. Format Quarter secara internal dinormalisasi menjadi `Q1`, `Q2`, `Q3`, atau `Q4`.

### 3. Otomasi Statistik
-   Setelah data Excel berhasil diproses, aplikasi akan menjalankan perhitungan statistik secara otomatis untuk memperbarui tabel:
    -   `upload_statistics` (Agregat global per upload)
    -   `district_statistics` (Data per kecamatan)
    -   `sector_statistics` (Data per sektor/KBLI)

---

## 💻 Tech Stack

-   **Framework**: [CodeIgniter 4](https://codeigniter.com/) (PHP 8.1+)
-   **Database**: MySQL / MariaDB
-   **Frontend**: Bootstrap 5, Chart.js, Three.js (Modern Glassmorphic UI)
-   **Library**:
    -   `PhpSpreadsheet`: Untuk pemrosesan file Excel.
    -   `TCPDF`: Untuk pembuatan laporan PDF.
-   **Infrastruktur**: Docker & Docker Compose.

---

## 🔌 API Endpoints

Aplikasi ini menyediakan beberapa endpoint API untuk integrasi data dan monitoring:

-   **GET `/api/public/data`**: Mengambil data statistik publik untuk ditampilkan di landing page.
-   **GET `/api/security/threats`**: Mengambil log ancaman keamanan terbaru (Memerlukan role minimal `staff`).
-   **GET `/api/security/export`**: Mengekspor log keamanan dalam format tertentu.

---

## 🗄️ Struktur Database Utama

-   **`users`**: Menyimpan data akun pengguna, peran (`superadmin`, `admin`, `staff`), dan status.
-   **`uploads`**: Informasi file Excel yang diunggah, metadata (Quarter/Year), dan status pemrosesan.
-   **`projects`**: Data detail dari setiap baris di file Excel (PMA/PMDN).
-   **`upload_statistics`**: Hasil kalkulasi agregat per file upload.
-   **`district_statistics`**: Agregasi data investasi per kecamatan.
-   **`sector_statistics`**: Agregasi data investasi per sektor usaha (KBLI).
-   **`security_logs`**: Catatan upaya serangan yang terdeteksi oleh sistem.

---

## ⚙️ Instalasi & Setup

### Menggunakan Docker (Direkomendasikan)
1. Salin file `.env`:
   ```bash
   cp .env.example .env
   ```
2. Jalankan docker-compose:
   ```bash
   docker-compose up -d
   ```
3. Akses aplikasi di `http://localhost:8081`.

### Instalasi Manual (Local)
1. Pastikan PHP 8.1+ dan Composer terinstal.
2. Install dependensi:
   ```bash
   composer install
   ```
3. Konfigurasi database di file `.env`.
4. Jalankan migrasi dan seeder:
   ```bash
   php spark migrate
   php spark db:seed UserSeeder
   ```
5. Jalankan server:
   ```bash
   php spark serve
   ```

---

## 📁 Struktur Direktori

-   `app/Controllers`: Logika alur aplikasi (Auth, Dashboard, UserManagement, dll).
-   `app/Filters`: Logika keamanan dan pembatasan akses.
-   `app/Models`: Interaksi database dan parser Excel yang kompleks.
-   `app/Services`: Logika bisnis tambahan (Pemrosesan upload, kalkulasi statistik).
-   `app/Views`: Template antarmuka pengguna.
-   `public/`: Asset publik (CSS, JS, Images).
-   `writable/uploads`: Lokasi penyimpanan file Excel sementara sebelum diproses.

---

## 📝 Catatan Tambahan
-   Log aplikasi dapat ditemukan di `writable/logs`.
-   Pastikan direktori `writable` memiliki izin tulis (write permission).
-   Gunakan `php spark` untuk utilitas baris perintah CodeIgniter.

---

**Developed for DPMPTSP Kabupaten Tanah Bumbu.**
