<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Sistem Statistik Terpadu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .faq-item {
            transition: all 0.3s ease;
        }

        .faq-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .faq-answer.active {
            max-height: 1000px;
        }

        .rotate-icon {
            transition: transform 0.3s ease;
        }

        .rotate-icon.active {
            transform: rotate(180deg);
        }

        .search-highlight {
            background-color: #fef08a;
            padding: 2px 4px;
            border-radius: 2px;
        }
    </style>
</head>

<body class="min-h-screen gradient-bg">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-blue-800 to-blue-900 shadow-lg mb-8">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-question-circle text-3xl text-blue-300"></i>
                    <div>
                        <h1 class="text-2xl font-bold text-white">FAQ - Frequently Asked Questions</h1>
                        <p class="text-blue-100 text-sm">Sistem Statistik Terpadu PMA & PMDN</p>
                    </div>
                </div>
                <a href="/dashboard" class="px-5 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto max-w-6xl px-6 pb-12">

        <!-- Search Box -->
        <div class="bg-white rounded-xl shadow-xl p-6 mb-8">
            <div class="relative">
                <input type="text" id="faq-search" placeholder="Cari pertanyaan..."
                    class="w-full px-6 py-4 pl-14 text-lg border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none">
                <i class="fas fa-search absolute left-5 top-5 text-gray-400 text-xl"></i>
            </div>
            <p class="text-sm text-gray-500 mt-2">
                <i class="fas fa-info-circle mr-1"></i>
                Ketik kata kunci untuk mencari pertanyaan yang Anda butuhkan
            </p>
        </div>

        <!-- Quick Links -->
        <div class="bg-white rounded-xl shadow-xl p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-link mr-2 text-blue-600"></i>
                Navigasi Cepat
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <button onclick="scrollToSection('umum')" class="px-4 py-3 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition">
                    <i class="fas fa-home mr-2"></i>Umum
                </button>
                <button onclick="scrollToSection('login')" class="px-4 py-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </button>
                <button onclick="scrollToSection('upload')" class="px-4 py-3 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition">
                    <i class="fas fa-upload mr-2"></i>Upload Data
                </button>
                <button onclick="scrollToSection('dashboard')" class="px-4 py-3 bg-orange-50 text-orange-700 rounded-lg hover:bg-orange-100 transition">
                    <i class="fas fa-chart-bar mr-2"></i>Dashboard
                </button>
            </div>
        </div>

        <!-- FAQ Sections -->

        <!-- 1. UMUM -->
        <div id="umum" class="bg-white rounded-xl shadow-xl p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center border-b pb-4">
                <i class="fas fa-info-circle mr-3 text-blue-600"></i>
                Umum
            </h2>

            <!-- FAQ Item -->
            <div class="faq-item mb-4 border border-gray-200 rounded-lg">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                    <span class="font-semibold text-gray-800">
                        <i class="fas fa-question-circle mr-2 text-blue-600"></i>
                        Apa itu Sistem Statistik Terpadu?
                    </span>
                    <i class="fas fa-chevron-down rotate-icon text-gray-400"></i>
                </button>
                <div class="faq-answer px-6 pb-4">
                    <p class="text-gray-600 leading-relaxed">
                        Sistem Statistik Terpadu adalah aplikasi untuk mengelola, menganalisis, dan memvisualisasikan data investasi
                        PMA (Penanaman Modal Asing) dan PMDN (Penanaman Modal Dalam Negeri) di Kabupaten Tanah Bumbu.
                        Aplikasi ini memudahkan admin DPMPTSP dalam monitoring dan pelaporan data investasi.
                    </p>
                </div>
            </div>

            <div class="faq-item mb-4 border border-gray-200 rounded-lg">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                    <span class="font-semibold text-gray-800">
                        <i class="fas fa-question-circle mr-2 text-blue-600"></i>
                        Siapa yang dapat menggunakan aplikasi ini?
                    </span>
                    <i class="fas fa-chevron-down rotate-icon text-gray-400"></i>
                </button>
                <div class="faq-answer px-6 pb-4">
                    <p class="text-gray-600 leading-relaxed">
                        Aplikasi ini hanya dapat dikelola oleh <strong>Admin DPMPTSP</strong> yang telah memiliki akun.
                        Untuk membuat akun baru, silakan hubungi Tim IT DPMPTSP Kabupaten Tanah Bumbu.
                    </p>
                </div>
            </div>

            <div class="faq-item mb-4 border border-gray-200 rounded-lg">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                    <span class="font-semibold text-gray-800">
                        <i class="fas fa-question-circle mr-2 text-blue-600"></i>
                        Fitur apa saja yang tersedia?
                    </span>
                    <i class="fas fa-chevron-down rotate-icon text-gray-400"></i>
                </button>
                <div class="faq-answer px-6 pb-4">
                    <ul class="text-gray-600 space-y-2 ml-4">
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Upload data investasi dari file Excel</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Dashboard dengan visualisasi chart interaktif</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Filter data berdasarkan periode (Quarter & Tahun)</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Konversi mata uang (IDR/USD)</li>
                        <!-- <li><i class="fas fa-check text-green-600 mr-2"></i>Download hasil analisis ke Excel</li> -->
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Multi-bahasa (Indonesia & English)</li>
                        <!-- <li><i class="fas fa-check text-green-600 mr-2"></i>Security monitoring (untuk Superadmin)</li> -->
                        <!-- <li><i class="fas fa-check text-green-600 mr-2"></i>User management (untuk Superadmin)</li> -->
                    </ul>
                </div>
            </div>
        </div>

        <!-- 2. LOGIN & AKSES -->
        <div id="login" class="bg-white rounded-xl shadow-xl p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center border-b pb-4">
                <i class="fas fa-sign-in-alt mr-3 text-green-600"></i>
                Login & Akses
            </h2>

            <div class="faq-item mb-4 border border-gray-200 rounded-lg">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                    <span class="font-semibold text-gray-800">
                        <i class="fas fa-question-circle mr-2 text-green-600"></i>
                        Bagaimana cara login ke aplikasi?
                    </span>
                    <i class="fas fa-chevron-down rotate-icon text-gray-400"></i>
                </button>
                <div class="faq-answer px-6 pb-4">
                    <ol class="text-gray-600 space-y-2 ml-4 list-decimal">
                        <li>Buka browser dan akses URL aplikasi</li>
                        <li>Masukkan <strong>Email</strong> <strong>/</strong> <strong>Username</strong> dan <strong>Password</strong> Anda</li>
                        <li>Klik tombol <strong>"Login"</strong></li>
                        <li>Anda akan diarahkan ke Dashboard</li>
                    </ol>
                </div>
            </div>

            <div class="faq-item mb-4 border border-gray-200 rounded-lg">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                    <span class="font-semibold text-gray-800">
                        <i class="fas fa-question-circle mr-2 text-green-600"></i>
                        Lupa password, bagaimana cara reset?
                    </span>
                    <i class="fas fa-chevron-down rotate-icon text-gray-400"></i>
                </button>
                <div class="faq-answer px-6 pb-4">
                    <p class="text-gray-600 leading-relaxed">
                        Jika Anda lupa password, silakan <strong>hubungi Tim IT DPMPTSP</strong> untuk melakukan reset password.
                        Tim IT akan membantu Anda mendapatkan password baru.
                    </p>
                    <div class="mt-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                        <p class="text-sm text-yellow-800">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Penting:</strong> Untuk keamanan, jangan menyebarluaskan password yang telah di berikan.
                        </p>
                    </div>
                </div>
            </div>

            <div class="faq-item mb-4 border border-gray-200 rounded-lg">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                    <span class="font-semibold text-gray-800">
                        <i class="fas fa-question-circle mr-2 text-green-600"></i>
                        Bagaimana cara membuat akun baru?
                    </span>
                    <i class="fas fa-chevron-down rotate-icon text-gray-400"></i>
                </button>
                <div class="faq-answer px-6 pb-4">
                    <p class="text-gray-600 leading-relaxed">
                        Pembuatan akun baru hanya dapat dilakukan oleh <strong>Tim IT DPMPTSP</strong>.
                        Silakan hubungi Tim IT dan berikan informasi berikut:
                    </p>
                    <ul class="text-gray-600 space-y-1 ml-4 mt-2">
                        <li><i class="fas fa-caret-right text-blue-600 mr-2"></i>Nama lengkap</li>
                        <li><i class="fas fa-caret-right text-blue-600 mr-2"></i>Email</li>
                        <li><i class="fas fa-caret-right text-blue-600 mr-2"></i>Role yang dibutuhkan (Admin/User Biasa)</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- 3. UPLOAD DATA -->
        <div id="upload" class="bg-white rounded-xl shadow-xl p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center border-b pb-4">
                <i class="fas fa-upload mr-3 text-purple-600"></i>
                Upload Data Excel
            </h2>

            <div class="faq-item mb-4 border border-gray-200 rounded-lg">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                    <span class="font-semibold text-gray-800">
                        <i class="fas fa-question-circle mr-2 text-purple-600"></i>
                        Format Excel seperti apa yang diterima?
                    </span>
                    <i class="fas fa-chevron-down rotate-icon text-gray-400"></i>
                </button>
                <div class="faq-answer px-6 pb-4">
                    <p class="text-gray-600 mb-3">File Excel harus memiliki <strong>kolom-kolom wajib</strong> berikut dengan urutan yang sama:</p>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-3 py-2 text-left">No</th>
                                    <th class="border px-3 py-2 text-left">Nama Kolom</th>
                                    <th class="border px-3 py-2 text-left">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600">
                                <tr>
                                    <td class="border px-3 py-2">1</td>
                                    <td class="border px-3 py-2 font-semibold">No</td>
                                    <td class="border px-3 py-2">Nomor urut</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">2</td>
                                    <td class="border px-3 py-2 font-semibold">ID Laporan</td>
                                    <td class="border px-3 py-2">ID unik laporan</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">3</td>
                                    <td class="border px-3 py-2 font-semibold">ID Proyek</td>
                                    <td class="border px-3 py-2">ID unik proyek</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">4</td>
                                    <td class="border px-3 py-2 font-semibold">Periode Tahap</td>
                                    <td class="border px-3 py-2">Periode proyek</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">5</td>
                                    <td class="border px-3 py-2 font-semibold">Sektor Utama</td>
                                    <td class="border px-3 py-2">Sektor utama investasi</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">6</td>
                                    <td class="border px-3 py-2 font-semibold">23 Sektor</td>
                                    <td class="border px-3 py-2">Klasifikasi 23 sektor</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">7</td>
                                    <td class="border px-3 py-2 font-semibold">Jenis Badan Usaha</td>
                                    <td class="border px-3 py-2">PT, CV, dll</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">8</td>
                                    <td class="border px-3 py-2 font-semibold">Nama Perusahaan</td>
                                    <td class="border px-3 py-2">Nama lengkap perusahaan</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">9</td>
                                    <td class="border px-3 py-2 font-semibold">Kecamatan</td>
                                    <td class="border px-3 py-2">Lokasi kecamatan</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">10</td>
                                    <td class="border px-3 py-2 font-semibold">Email</td>
                                    <td class="border px-3 py-2">Email perusahaan</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">11</td>
                                    <td class="border px-3 py-2 font-semibold">Alamat</td>
                                    <td class="border px-3 py-2">Alamat lengkap</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">12</td>
                                    <td class="border px-3 py-2 font-semibold">Cetak Lokasi</td>
                                    <td class="border px-3 py-2">Lokasi cetak</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">13</td>
                                    <td class="border px-3 py-2 font-semibold">Sektor</td>
                                    <td class="border px-3 py-2">Detail sektor</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">14</td>
                                    <td class="border px-3 py-2 font-semibold">Deskripsi KBLI</td>
                                    <td class="border px-3 py-2">Kode KBLI</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">15</td>
                                    <td class="border px-3 py-2 font-semibold">Wilayah</td>
                                    <td class="border px-3 py-2">Wilayah proyek</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">16</td>
                                    <td class="border px-3 py-2 font-semibold">Provinsi</td>
                                    <td class="border px-3 py-2">Provinsi</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">17</td>
                                    <td class="border px-3 py-2 font-semibold">Kabkot</td>
                                    <td class="border px-3 py-2">Kabupaten/Kota</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">18</td>
                                    <td class="border px-3 py-2 font-semibold">No Izin</td>
                                    <td class="border px-3 py-2">Nomor izin usaha</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">19</td>
                                    <td class="border px-3 py-2 font-semibold">Tambahan Investasi</td>
                                    <td class="border px-3 py-2">Nilai tambahan investasi</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">20</td>
                                    <td class="border px-3 py-2 font-semibold">Total Investasi</td>
                                    <td class="border px-3 py-2">Nilai total investasi</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">21</td>
                                    <td class="border px-3 py-2 font-semibold">Negara</td>
                                    <td class="border px-3 py-2">Negara asal (untuk PMA)</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">22</td>
                                    <td class="border px-3 py-2 font-semibold">Rencana Total Investasi</td>
                                    <td class="border px-3 py-2">Rencana investasi</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">23</td>
                                    <td class="border px-3 py-2 font-semibold">Proyek</td>
                                    <td class="border px-3 py-2">Nama proyek</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">24</td>
                                    <td class="border px-3 py-2 font-semibold">TKI</td>
                                    <td class="border px-3 py-2">Jumlah TKI</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">25</td>
                                    <td class="border px-3 py-2 font-semibold">TKA</td>
                                    <td class="border px-3 py-2">Jumlah TKA</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">26</td>
                                    <td class="border px-3 py-2 font-semibold">Nama Petugas</td>
                                    <td class="border px-3 py-2">Petugas input</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">27</td>
                                    <td class="border px-3 py-2 font-semibold">Rencana Modal Tetap</td>
                                    <td class="border px-3 py-2">Rencana modal</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">28</td>
                                    <td class="border px-3 py-2 font-semibold">Keterangan Masalah</td>
                                    <td class="border px-3 py-2">Masalah (jika ada)</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">29</td>
                                    <td class="border px-3 py-2 font-semibold">Penjelasan Modal Tetap</td>
                                    <td class="border px-3 py-2">Detail modal tetap</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">30</td>
                                    <td class="border px-3 py-2 font-semibold">No Telp</td>
                                    <td class="border px-3 py-2">Nomor telepon</td>
                                </tr>
                                <tr>
                                    <td class="border px-3 py-2">31</td>
                                    <td class="border px-3 py-2 font-semibold">PMA/PMDN</td>
                                    <td class="border px-3 py-2">Jenis investasi (PMA atau PMDN)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 p-4 bg-red-50 border-l-4 border-red-400 rounded">
                        <p class="text-sm text-red-800">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <strong>Perhatian:</strong> Kolom harus lengkap dan urutan harus sama persis. Jika ada kolom yang hilang, upload akan gagal.
                        </p>
                    </div>
                </div>
            </div>

            <div class="faq-item mb-4 border border-gray-200 rounded-lg">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                    <span class="font-semibold text-gray-800">
                        <i class="fas fa-question-circle mr-2 text-purple-600"></i>
                        Bagaimana cara upload data?
                    </span>
                    <i class="fas fa-chevron-down rotate-icon text-gray-400"></i>
                </button>
                <div class="faq-answer px-6 pb-4">
                    <ol class="text-gray-600 space-y-2 ml-4 list-decimal">
                        <li>Login ke aplikasi</li>
                        <li>Di Dashboard, cari box <strong>"Upload File Excel"</strong></li>
                        <li>Klik area upload atau drag & drop file Excel (.xlsx atau .xls)</li>
                        <li>Klik tombol <strong>"Upload dan Proses"</strong></li>
                        <li>Anda akan diarahkan ke halaman <strong>Metadata</strong></li>
                        <li>Isi form metadata:
                            <ul class="ml-6 mt-2 space-y-1">
                                <li><i class="fas fa-caret-right text-blue-600 mr-2"></i><strong>Nama Upload:</strong> Nama identifikasi upload</li>
                                <li><i class="fas fa-caret-right text-blue-600 mr-2"></i><strong>Quarter:</strong> Q1, Q2, Q3, atau Q4</li>
                                <li><i class="fas fa-caret-right text-blue-600 mr-2"></i><strong>Tahun:</strong> Tahun data</li>
                                <li><i class="fas fa-caret-right text-blue-600 mr-2"></i><strong>Nilai USD:</strong> Kurs USD pada periode tersebut</li>
                            </ul>
                        </li>
                        <li>Klik <strong>"Proses Data"</strong></li>
                        <li>Tunggu hingga proses selesai</li>
                        <li>Data akan muncul di Dashboard</li>
                    </ol>
                </div>
            </div>

            <div class="faq-item mb-4 border border-gray-200 rounded-lg">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                    <span class="font-semibold text-gray-800">
                        <i class="fas fa-question-circle mr-2 text-purple-600"></i>
                        Apakah bisa upload data quarter yang sama di tahun yang sama?
                    </span>
                    <i class="fas fa-chevron-down rotate-icon text-gray-400"></i>
                </button>
                <div class="faq-answer px-6 pb-4">
                    <p class="text-gray-600 leading-relaxed">
                        <strong>TIDAK BISA.</strong> Sistem akan menolak upload jika kombinasi <strong>Quarter dan Tahun</strong> sudah ada.
                        Misalnya, jika Anda sudah upload data Q1 2024, maka tidak bisa upload Q1 2024 lagi.
                    </p>
                    <div class="mt-4 p-4 bg-blue-50 border-l-4 border-blue-400 rounded">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Solusi:</strong> Jika ingin update data, hapus upload yang lama terlebih dahulu, lalu upload yang baru.
                        </p>
                    </div>
                </div>
            </div>

            <div class="faq-item mb-4 border border-gray-200 rounded-lg">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                    <span class="font-semibold text-gray-800">
                        <i class="fas fa-question-circle mr-2 text-purple-600"></i>
                        Upload gagal, apa yang harus dilakukan?
                    </span>
                    <i class="fas fa-chevron-down rotate-icon text-gray-400"></i>
                </button>
                <div class="faq-answer px-6 pb-4">
                    <p class="text-gray-600 mb-3">Periksa hal-hal berikut:</p>
                    <ul class="text-gray-600 space-y-2 ml-4">
                        <li><i class="fas fa-check-circle text-green-600 mr-2"></i>Format file adalah .xlsx atau .xls</li>
                        <li><i class="fas fa-check-circle text-green-600 mr-2"></i>Semua 31 kolom wajib ada</li>
                        <li><i class="fas fa-check-circle text-green-600 mr-2"></i>Urutan kolom sesuai (lihat tabel di atas)</li>
                        <li><i class="fas fa-check-circle text-green-600 mr-2"></i>Nama kolom sesuai (case sensitive)</li>
                        <li><i class="fas fa-check-circle text-green-600 mr-2"></i>Tidak ada Quarter + Tahun yang duplikat</li>
                        <li><i class="fas fa-check-circle text-green-600 mr-2"></i>File tidak corrupt</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- 4. DASHBOARD & CHART -->
        <div id="dashboard" class="bg-white rounded-xl shadow-xl p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center border-b pb-4">
                <i class="fas fa-chart-bar mr-3 text-orange-600"></i>
                Dashboard & Chart
            </h2>

            <div class="faq-item mb-4 border border-gray-200 rounded-lg">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                    <span class="font-semibold text-gray-800">
                        <i class="fas fa-question-circle mr-2 text-orange-600"></i>
                        Bagaimana cara melihat data tertentu saja?
                    </span>
                    <i class="fas fa-chevron-down rotate-icon text-gray-400"></i>
                </button>
                <div class="faq-answer px-6 pb-4">
                    <p class="text-gray-600 mb-3">Gunakan filter di tabel <strong>"Manajemen Upload"</strong>:</p>
                    <ul class="text-gray-600 space-y-2 ml-4">
                        <li><i class="fas fa-eye text-blue-600 mr-2"></i>Klik icon <strong>mata (eye)</strong> di kolom Actions untuk melihat chart upload tersebut</li>
                        <li><i class="fas fa-dollar-sign text-yellow-600 mr-2"></i>Gunakan dropdown <strong>Currency</strong> untuk ubah mata uang (IDR/USD)</li>
                    </ul>
                </div>
            </div>

            <div class="faq-item mb-4 border border-gray-200 rounded-lg">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                    <span class="font-semibold text-gray-800">
                        <i class="fas fa-question-circle mr-2 text-orange-600"></i>
                        Bagaimana cara mengubah tipe chart?
                    </span>
                    <i class="fas fa-chevron-down rotate-icon text-gray-400"></i>
                </button>
                <div class="faq-answer px-6 pb-4">
                    <ol class="text-gray-600 space-y-2 ml-4 list-decimal">
                        <li>Scroll ke chart yang ingin diubah</li>
                        <li>Di pojok kanan atas chart, ada dropdown type</li>
                        <li>Pilih type yang diinginkan (Bar, Line, Pie, dll)</li>
                        <li>Chart akan otomatis berubah</li>
                    </ol>
                    <p class="mt-3 text-gray-600">
                        <strong>Chart yang support type switching:</strong>
                    </p>
                    <ul class="text-gray-600 space-y-1 ml-4 mt-2">
                        <li><i class="fas fa-caret-right text-blue-600 mr-2"></i>PMA/PMDN Ratio</li>
                        <li><i class="fas fa-caret-right text-blue-600 mr-2"></i>Proyek per Kecamatan</li>
                        <li><i class="fas fa-caret-right text-blue-600 mr-2"></i>Investasi per Lokasi</li>
                        <li><i class="fas fa-caret-right text-blue-600 mr-2"></i>Proyek per Sektor</li>
                        <li><i class="fas fa-caret-right text-blue-600 mr-2"></i>TKI & TKA (PMA dan PMDN)</li>
                        <li><i class="fas fa-caret-right text-blue-600 mr-2"></i>Peringkat Proyek</li>
                        <li><i class="fas fa-caret-right text-blue-600 mr-2"></i>Proyek per Negara</li>
                        <li><i class="fas fa-caret-right text-blue-600 mr-2"></i>Tambahan Investasi Triuwulan</li>
                    </ul>
                </div>
            </div>

            <div class="faq-item mb-4 border border-gray-200 rounded-lg">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                    <span class="font-semibold text-gray-800">
                        <i class="fas fa-question-circle mr-2 text-orange-600"></i>
                        Bagaimana cara filter chart Tambahan Investasi Triuwulan berdasarkan tahun?
                    </span>
                    <i class="fas fa-chevron-down rotate-icon text-gray-400"></i>
                </button>
                <div class="faq-answer px-6 pb-4">
                    <ol class="text-gray-600 space-y-2 ml-4 list-decimal">
                        <li>Scroll ke chart <strong>"Tambahan Investasi Triuwulan"</strong></li>
                        <li>Di atas chart, ada dropdown <strong>"Tahun"</strong></li>
                        <li>Pilih tahun yang diinginkan (atau "Semua Tahun")</li>
                        <li>Chart akan otomatis update menampilkan data tahun tersebut</li>
                    </ol>
                </div>
            </div>

            <div class="faq-item mb-4 border border-gray-200 rounded-lg">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                    <span class="font-semibold text-gray-800">
                        <i class="fas fa-question-circle mr-2 text-orange-600"></i>
                        Bagaimana cara menyembunyikan chart tertentu?
                    </span>
                    <i class="fas fa-chevron-down rotate-icon text-gray-400"></i>
                </button>
                <div class="faq-answer px-6 pb-4">
                    <ol class="text-gray-600 space-y-2 ml-4 list-decimal">
                        <li>Lihat box <strong>"Opsi Chart"</strong> di sebelah kiri</li>
                        <li>Uncheck checkbox chart yang ingin disembunyikan</li>
                        <li>Chart akan langsung hilang dari tampilan</li>
                        <li>Check kembali untuk menampilkan</li>
                    </ol>
                </div>
            </div>
            <!-- 
            <div class="faq-item mb-4 border border-gray-200 rounded-lg">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                    <span class="font-semibold text-gray-800">
                        <i class="fas fa-question-circle mr-2 text-orange-600"></i>
                        Bagaimana cara download hasil analisis?
                    </span>
                    <i class="fas fa-chevron-down rotate-icon text-gray-400"></i>
                </button>
                <div class="faq-answer px-6 pb-4">
                    <ol class="text-gray-600 space-y-2 ml-4 list-decimal">
                        <li>Scroll ke bagian bawah Dashboard</li>
                        <li>Klik tombol hijau <strong>"Download Excel"</strong></li>
                        <li>File Excel akan otomatis terdownload</li>
                        <li>File berisi 3 sheet:
                            <ul class="ml-6 mt-2">
                                <li><i class="fas fa-file-excel text-green-600 mr-2"></i>Sheet 1: Raw Data</li>
                                <li><i class="fas fa-file-excel text-green-600 mr-2"></i>Sheet 2: Ranking Proyek</li>
                                <li><i class="fas fa-file-excel text-green-600 mr-2"></i>Sheet 3: Statistik Summary</li>
                            </ul>
                        </li>
                    </ol>
                </div>
            </div> -->

            <div class="faq-item mb-4 border border-gray-200 rounded-lg">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                    <span class="font-semibold text-gray-800">
                        <i class="fas fa-question-circle mr-2 text-orange-600"></i>
                        Bagaimana cara mengubah bahasa?
                    </span>
                    <i class="fas fa-chevron-down rotate-icon text-gray-400"></i>
                </button>
                <div class="faq-answer px-6 pb-4">
                    <ol class="text-gray-600 space-y-2 ml-4 list-decimal">
                        <li>Di navbar atas, cari dropdown <strong>"Bahasa:"</strong></li>
                        <li>Pilih bahasa yang diinginkan (Indonesia atau English)</li>
                        <li>Halaman akan otomatis reload dengan bahasa yang dipilih</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- 5. MANAJEMEN DATA -->
        <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center border-b pb-4">
                <i class="fas fa-cog mr-3 text-indigo-600"></i>
                Manajemen Data
            </h2>

            <div class="faq-item mb-4 border border-gray-200 rounded-lg">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                    <span class="font-semibold text-gray-800">
                        <i class="fas fa-question-circle mr-2 text-indigo-600"></i>
                        Bagaimana cara edit metadata upload?
                    </span>
                    <i class="fas fa-chevron-down rotate-icon text-gray-400"></i>
                </button>
                <div class="faq-answer px-6 pb-4">
                    <ol class="text-gray-600 space-y-2 ml-4 list-decimal">
                        <li>Di tabel <strong>"Manajemen Upload"</strong>, cari data yang ingin diedit</li>
                        <li>Klik icon <strong>pensil (edit)</strong> di kolom Actions</li>
                        <li>Edit metadata (Nama Upload, Quarter, Tahun, Nilai USD)</li>
                        <li>Klik <strong>"Update Metadata"</strong></li>
                    </ol>
                    <div class="mt-3 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                        <p class="text-sm text-yellow-800">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Catatan:</strong> Edit metadata tidak mengubah data proyek, hanya mengubah informasi upload.
                        </p>
                    </div>
                </div>
            </div>

            <div class="faq-item mb-4 border border-gray-200 rounded-lg">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                    <span class="font-semibold text-gray-800">
                        <i class="fas fa-question-circle mr-2 text-indigo-600"></i>
                        Bagaimana cara menghapus upload?
                    </span>
                    <i class="fas fa-chevron-down rotate-icon text-gray-400"></i>
                </button>
                <div class="faq-answer px-6 pb-4">
                    <ol class="text-gray-600 space-y-2 ml-4 list-decimal">
                        <li>Di tabel <strong>"Manajemen Upload"</strong>, cari data yang ingin dihapus</li>
                        <li>Klik icon <strong>trash (hapus)</strong> merah di kolom Actions</li>
                        <li>Konfirmasi penghapusan</li>
                        <li>Data akan terhapus permanent</li>
                    </ol>
                    <div class="mt-3 p-4 bg-red-50 border-l-4 border-red-400 rounded">
                        <p class="text-sm text-red-800">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <strong>Peringatan:</strong> Menghapus upload akan menghapus SEMUA data proyek yang terkait. Tindakan ini tidak dapat dibatalkan!
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. TROUBLESHOOTING -->
        <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center border-b pb-4">
                <i class="fas fa-tools mr-3 text-red-600"></i>
                Troubleshooting
            </h2>

            <div class="faq-item mb-4 border border-gray-200 rounded-lg">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                    <span class="font-semibold text-gray-800">
                        <i class="fas fa-question-circle mr-2 text-red-600"></i>
                        Chart tidak muncul atau error
                    </span>
                    <i class="fas fa-chevron-down rotate-icon text-gray-400"></i>
                </button>
                <div class="faq-answer px-6 pb-4">
                    <p class="text-gray-600 mb-3">Coba solusi berikut:</p>
                    <ul class="text-gray-600 space-y-2 ml-4">
                        <li><i class="fas fa-sync text-blue-600 mr-2"></i>Refresh halaman (Ctrl + R)</li>
                        <li><i class="fas fa-broom text-blue-600 mr-2"></i>Clear cache browser (Ctrl + Shift + Delete)</li>
                        <li><i class="fas fa-check text-blue-600 mr-2"></i>Pastikan ada data yang sudah diupload</li>
                        <li><i class="fas fa-wifi text-blue-600 mr-2"></i>Periksa koneksi internet</li>
                        <li><i class="fas fa-browser text-blue-600 mr-2"></i>Gunakan browser terbaru (Chrome, Firefox, Edge)</li>
                    </ul>
                </div>
            </div>

            <div class="faq-item mb-4 border border-gray-200 rounded-lg">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                    <span class="font-semibold text-gray-800">
                        <i class="fas fa-question-circle mr-2 text-red-600"></i>
                        Aplikasi lambat atau loading lama
                    </span>
                    <i class="fas fa-chevron-down rotate-icon text-gray-400"></i>
                </button>
                <div class="faq-answer px-6 pb-4">
                    <p class="text-gray-600 mb-3">Kemungkinan penyebab:</p>
                    <ul class="text-gray-600 space-y-2 ml-4">
                        <li><i class="fas fa-database text-red-600 mr-2"></i>Data terlalu banyak - coba filter berdasarkan upload tertentu</li>
                        <li><i class="fas fa-signal text-red-600 mr-2"></i>Koneksi internet lambat</li>
                        <li><i class="fas fa-server text-red-600 mr-2"></i>Server sedang sibuk - coba beberapa saat lagi</li>
                        <li><i class="fas fa-memory text-red-600 mr-2"></i>Browser kehabisan memory - close tab yang tidak perlu</li>
                    </ul>
                </div>
            </div>

            <div class="faq-item mb-4 border border-gray-200 rounded-lg">
                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                    <span class="font-semibold text-gray-800">
                        <i class="fas fa-question-circle mr-2 text-red-600"></i>
                        Tidak bisa login atau session expired
                    </span>
                    <i class="fas fa-chevron-down rotate-icon text-gray-400"></i>
                </button>
                <div class="faq-answer px-6 pb-4">
                    <ol class="text-gray-600 space-y-2 ml-4 list-decimal">
                        <li>Clear cookies dan cache browser</li>
                        <li>Pastikan email dan password benar</li>
                        <li>Jika lupa password, hubungi Tim IT</li>
                        <li>Jika masih tidak bisa, logout dan login kembali</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- 7. TIPS & BEST PRACTICES -->
        <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center border-b pb-4">
                <i class="fas fa-lightbulb mr-3 text-yellow-600"></i>
                Tips & Best Practices
            </h2>

            <div class="space-y-4">
                <div class="p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                    <h3 class="font-bold text-blue-900 mb-2">
                        <i class="fas fa-check-circle mr-2"></i>
                        Upload Data
                    </h3>
                    <ul class="text-blue-800 text-sm space-y-1 ml-4">
                        <li>• Selalu backup file Excel sebelum upload</li>
                        <li>• Gunakan nama upload yang deskriptif (misal: "Q1 2024 - Upload Januari")</li>
                        <li>• Verifikasi data di Excel sebelum upload</li>
                        <li>• Upload data per quarter untuk analisis yang lebih baik</li>
                    </ul>
                </div>

                <div class="p-4 bg-green-50 rounded-lg border-l-4 border-green-500">
                    <h3 class="font-bold text-green-900 mb-2">
                        <i class="fas fa-check-circle mr-2"></i>
                        Penggunaan Dashboard
                    </h3>
                    <ul class="text-green-800 text-sm space-y-1 ml-4">
                        <li>• Gunakan filter untuk melihat data spesifik</li>
                        <li>• Ubah tipe chart sesuai kebutuhan visualisasi</li>
                        <!-- <li>• Download hasil analisis secara berkala sebagai backup</li> -->
                        <li>• Sembunyikan chart yang tidak diperlukan untuk performa lebih baik</li>
                    </ul>
                </div>

                <div class="p-4 bg-purple-50 rounded-lg border-l-4 border-purple-500">
                    <h3 class="font-bold text-purple-900 mb-2">
                        <i class="fas fa-check-circle mr-2"></i>
                        Keamanan
                    </h3>
                    <ul class="text-purple-800 text-sm space-y-1 ml-4">
                        <li>• Jangan share password dengan orang lain</li>
                        <li>• Logout setelah selesai menggunakan aplikasi</li>
                        <!-- <li>• Gunakan password yang kuat</li> -->
                        <li>• Jangan mengakses dari komputer publik</li>
                    </ul>
                </div>

                <div class="p-4 bg-orange-50 rounded-lg border-l-4 border-orange-500">
                    <h3 class="font-bold text-orange-900 mb-2">
                        <i class="fas fa-check-circle mr-2"></i>
                        Performa
                    </h3>
                    <ul class="text-orange-800 text-sm space-y-1 ml-4">
                        <li>• Gunakan browser modern (Chrome, Firefox, Edge)</li>
                        <li>• Clear cache secara berkala</li>
                        <li>• Hindari membuka terlalu banyak chart sekaligus</li>
                        <li>• Filter data untuk menampilkan subset yang lebih kecil</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Contact Support -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl shadow-xl p-8 text-white">
            <div class="text-center">
                <i class="fas fa-headset text-5xl mb-4"></i>
                <h2 class="text-2xl font-bold mb-2">Butuh Bantuan Lebih Lanjut?</h2>
                <p class="text-blue-100 mb-6">
                    Jika pertanyaan Anda tidak terjawab di FAQ ini, silakan hubungi Tim IT DPMPTSP Tanah Bumbu
                </p>
                <!-- <div class="flex justify-center gap-4 flex-wrap">
                    <div class="bg-white/10 backdrop-blur rounded-lg px-6 py-3">
                        <i class="fas fa-envelope mr-2"></i>
                        Email: it@dpmptsp-tanahbumbu.go.id
                    </div>
                    <div class="bg-white/10 backdrop-blur rounded-lg px-6 py-3">
                        <i class="fas fa-phone mr-2"></i>
                        Telp: (0518) 12345
                    </div>
                </div> -->
            </div>
        </div>

    </div>

    <footer class="bg-gray-800 border-t border-gray-700 shadow-inner mt-12">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-center items-center text-sm text-white/80">
            © <?= date('Y') ?> DPMPTSP - Kabupaten Tanah Bumbu
        </div>
    </footer>

    <script>
        // FAQ Accordion
        document.querySelectorAll('.faq-question').forEach(button => {
            button.addEventListener('click', () => {
                const answer = button.nextElementSibling;
                const icon = button.querySelector('.rotate-icon');

                answer.classList.toggle('active');
                icon.classList.toggle('active');
            });
        });

        // Search FAQ
        const searchInput = document.getElementById('faq-search');
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const faqItems = document.querySelectorAll('.faq-item');

            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question span').textContent.toLowerCase();
                const answer = item.querySelector('.faq-answer').textContent.toLowerCase();

                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    item.style.display = 'block';
                    // Highlight search term
                    if (searchTerm.length > 2) {
                        item.querySelector('.faq-answer').classList.add('active');
                        item.querySelector('.rotate-icon').classList.add('active');
                    }
                } else {
                    item.style.display = 'none';
                }
            });

            // If search is empty, show all
            if (searchTerm === '') {
                faqItems.forEach(item => {
                    item.style.display = 'block';
                    item.querySelector('.faq-answer').classList.remove('active');
                    item.querySelector('.rotate-icon').classList.remove('active');
                });
            }
        });

        // Scroll to section
        function scrollToSection(sectionId) {
            const section = document.getElementById(sectionId);
            if (section) {
                section.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
    </script>
</body>

</html>