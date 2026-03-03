<?php $this->extend('layouts/app'); ?>

<?php $this->section('content'); ?>

<!-- Hero Section: Professional FAQ Branding -->
<div class="relative overflow-hidden rounded-3xl bg-slate-900 mb-12 shadow-2xl">
    <!-- Abstract Background elements -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[100px] -mr-64 -mt-64">
    </div>
    <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-indigo-600/10 rounded-full blur-[80px] -ml-48 -mb-48">
    </div>

    <div class="relative z-10 px-8 py-16 md:px-16 md:py-20 flex flex-col items-center text-center">
        <div
            class="inline-flex items-center space-x-2 bg-blue-500/10 border border-blue-500/20 px-4 py-2 rounded-full mb-6">
            <i class="fas fa-question-circle text-blue-400 text-sm"></i>
            <span class="text-blue-400 text-xs font-bold uppercase tracking-wider">Pusat Bantuan & Edukasi</span>
        </div>

        <h1 class="text-3xl md:text-5xl font-black text-white mb-6 leading-tight max-w-4xl">
            Ada yang Bisa Kami <span
                class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Bantu?</span>
        </h1>

        <p class="text-lg text-slate-400 mb-8 max-w-2xl leading-relaxed">
            Temukan jawaban cepat atas pertanyaan umum seputar penggunaan platform DataInvest dan pengelolaan data
            investasi di Kabupaten Tanah Bumbu.
        </p>

        <!-- Glassmorphism Search Bar -->
        <div class="w-full max-w-2xl">
            <form action="<?= base_url('faq') ?>" method="get" class="relative group">
                <input type="text" name="search" placeholder="Ketik pertanyaan Anda di sini..."
                    class="w-full px-8 py-5 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:bg-white/10 transition-all backdrop-blur-md text-lg"
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button type="submit"
                    class="absolute right-3 top-1/2 -translate-y-1/2 w-12 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition flex items-center justify-center shadow-lg">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- FAQ Accordion Section -->
<div class="max-w-4xl mx-auto space-y-10 mb-20" x-data="{ expanded: null }">

    <!-- Category: Panduan Pengguna -->
    <div class="space-y-4">
        <div class="flex items-center space-x-3 mb-6 px-2">
            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                <i class="fas fa-rocket"></i>
            </div>
            <h2 class="text-2xl font-black text-slate-900">Memulai Penggunaan</h2>
        </div>

        <!-- FAQ Item 1 -->
        <div class="bg-white border border-slate-200 rounded-[24px] overflow-hidden transition-all duration-300 hover:shadow-md"
            :class="expanded === 1 ? 'border-blue-500 ring-1 ring-blue-500/20' : ''">
            <button @click="expanded = expanded === 1 ? null : 1"
                class="w-full px-8 py-6 text-left flex justify-between items-center group">
                <div class="flex items-center space-x-4">
                    <div class="w-2 h-2 rounded-full bg-blue-500 transition-transform duration-300"
                        :class="expanded === 1 ? 'scale-150' : ''"></div>
                    <span class="font-bold text-slate-800 text-lg group-hover:text-blue-600 transition-colors">Bagaimana
                        cara mengunggah data realisasi baru?</span>
                </div>
                <i class="fas fa-chevron-down text-slate-400 transition-transform duration-500"
                    :class="expanded === 1 ? 'rotate-180 text-blue-600' : ''"></i>
            </button>
            <div x-show="expanded === 1" x-collapse>
                <div class="px-12 pb-8 pt-2 text-slate-600 leading-relaxed border-t border-slate-50">
                    <ol class="space-y-3">
                        <li class="flex items-start">
                            <span
                                class="w-6 h-6 rounded-full bg-blue-50 text-blue-600 text-xs font-bold flex items-center justify-center mr-3 mt-0.5">1</span>
                            Masuk ke Dashboard Administrator.
                        </li>
                        <li class="flex items-start">
                            <span
                                class="w-6 h-6 rounded-full bg-blue-50 text-blue-600 text-xs font-bold flex items-center justify-center mr-3 mt-0.5">2</span>
                            Klik tombol "Upload" pada area "Upload Data Baru".
                        </li>
                        <li class="flex items-start">
                            <span
                                class="w-6 h-6 rounded-full bg-blue-50 text-blue-600 text-xs font-bold flex items-center justify-center mr-3 mt-0.5">3</span>
                            Pilih file Excel (.xlsx) yang sesuai dengan format resmi DataInvest.
                        </li>
                        <li class="flex items-start">
                            <span
                                class="w-6 h-6 rounded-full bg-blue-50 text-blue-600 text-xs font-bold flex items-center justify-center mr-3 mt-0.5">4</span>
                            Lengkapi informasi metadata (Periode Kwartal, Tahun, dan Kurs USD).
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- FAQ Item 2 -->
        <div class="bg-white border border-slate-200 rounded-[24px] overflow-hidden transition-all duration-300 hover:shadow-md"
            :class="expanded === 2 ? 'border-emerald-500 ring-1 ring-emerald-500/20' : ''">
            <button @click="expanded = expanded === 2 ? null : 2"
                class="w-full px-8 py-6 text-left flex justify-between items-center group">
                <div class="flex items-center space-x-4">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 transition-transform duration-300"
                        :class="expanded === 2 ? 'scale-150' : ''"></div>
                    <span class="font-bold text-slate-800 text-lg group-hover:text-emerald-600 transition-colors">Apa
                        format file Excel yang didukung?</span>
                </div>
                <i class="fas fa-chevron-down text-slate-400 transition-transform duration-500"
                    :class="expanded === 2 ? 'rotate-180 text-emerald-600' : ''"></i>
            </button>
            <div x-show="expanded === 2" x-collapse>
                <div class="px-12 pb-8 pt-2 text-slate-600 leading-relaxed border-t border-slate-50">
                    <p class="mb-4">Sistem DataInvest mendukung format <strong>.xlsx</strong> (Excel 2007+) dengan
                        ketentuan:</p>
                    <ul class="space-y-2 list-disc list-inside">
                        <li>Memiliki tepat 31 kolom sesuai template.</li>
                        <li>Urutan kolom tidak boleh diubah.</li>
                        <li>Tidak diperkenankan mengunggah data Duplikat (Tahun & Kwartal yang sama).</li>
                    </ul>
                    <div
                        class="mt-6 p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between">
                        <span class="text-sm font-semibold text-slate-500 italic">Gunakan template resmi untuk akurasi
                            data:</span>
                        <a href="<?= base_url('assets/templates/data_template.xlsx') ?>"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-bold shadow-md shadow-emerald-200 hover:bg-emerald-700 transition">
                            <i class="fas fa-download mr-2"></i>Download Template
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category: Manajemen Data -->
    <div class="space-y-4 pt-4">
        <div class="flex items-center space-x-3 mb-6 px-2">
            <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shadow-sm">
                <i class="fas fa-database"></i>
            </div>
            <h2 class="text-2xl font-black text-slate-900">Manajemen & Analisis</h2>
        </div>

        <!-- FAQ Item 3 -->
        <div class="bg-white border border-slate-200 rounded-[24px] overflow-hidden transition-all duration-300 hover:shadow-md"
            :class="expanded === 3 ? 'border-indigo-500 ring-1 ring-indigo-500/20' : ''">
            <button @click="expanded = expanded === 3 ? null : 3"
                class="w-full px-8 py-6 text-left flex justify-between items-center group">
                <div class="flex items-center space-x-4">
                    <div class="w-2 h-2 rounded-full bg-indigo-500 transition-transform duration-300"
                        :class="expanded === 3 ? 'scale-150' : ''"></div>
                    <span
                        class="font-bold text-slate-800 text-lg group-hover:text-indigo-600 transition-colors">Bagaimana
                        cara mengedit informasi Metadata (Kurs/Kwartal)?</span>
                </div>
                <i class="fas fa-chevron-down text-slate-400 transition-transform duration-500"
                    :class="expanded === 3 ? 'rotate-180 text-indigo-600' : ''"></i>
            </button>
            <div x-show="expanded === 3" x-collapse>
                <div class="px-12 pb-8 pt-2 text-slate-600 leading-relaxed border-t border-slate-50">
                    <p>Informasi Metadata dapat diperbarui tanpa mengunggah ulang file melalui tabel riwayat di
                        Dashboard Admin. Cukup klik tombol <strong>Edit</strong> berwarna biru pada baris data yang
                        diinginkan.</p>
                </div>
            </div>
        </div>

        <!-- FAQ Item 4 -->
        <div class="bg-white border border-slate-200 rounded-[24px] overflow-hidden transition-all duration-300 hover:shadow-md"
            :class="expanded === 4 ? 'border-rose-500 ring-1 ring-rose-500/20' : ''">
            <button @click="expanded = expanded === 4 ? null : 4"
                class="w-full px-8 py-6 text-left flex justify-between items-center group">
                <div class="flex items-center space-x-4">
                    <div class="w-2 h-2 rounded-full bg-rose-500 transition-transform duration-300"
                        :class="expanded === 4 ? 'scale-150' : ''"></div>
                    <span class="font-bold text-slate-800 text-lg group-hover:text-rose-600 transition-colors">Apa yang
                        terjadi jika saya menghapus riwayat upload?</span>
                </div>
                <i class="fas fa-chevron-down text-slate-400 transition-transform duration-500"
                    :class="expanded === 4 ? 'rotate-180 text-rose-600' : ''"></i>
            </button>
            <div x-show="expanded === 4" x-collapse>
                <div class="px-12 pb-8 pt-2 text-slate-600 leading-relaxed border-t border-slate-50">
                    <p class="text-rose-700 font-semibold mb-2"><i class="fas fa-exclamation-triangle mr-2"></i>Tindakan
                        ini tidak dapat dibatalkan.</p>
                    <p>Menghapus riwayat upload akan secara permanen menghapus seluruh data proyek yang terkait dengan
                        file tersebut dari Database. Pastikan Anda memiliki cadangan data sebelum melakukan penghapusan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Support CTA -->
<div class="bg-slate-900 rounded-[40px] p-10 md:p-16 relative overflow-hidden shadow-2xl mb-20 group">
    <div class="absolute top-0 right-0 w-[300px] h-[300px] bg-blue-500/10 rounded-full blur-[80px] -mr-32 -mt-32"></div>

    <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-10">
        <div class="text-center lg:text-left max-w-xl">
            <h3 class="text-3xl font-black text-white mb-4">Masih Butuh Bantuan?</h3>
            <p class="text-slate-400 text-lg">Tim IT dan Support kami siap membantu Anda menyelesaikan kendala teknis
                dalam pengelolaan data investasi.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="mailto:support@datainvest.tanahbumbu.go.id"
                class="px-10 py-5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold transition transform hover:scale-105 flex items-center justify-center shadow-xl shadow-blue-900/40">
                <i class="fas fa-envelope mr-3 text-xl"></i>Hubungi Tim Support
            </a>
            <button onclick="alert('Layanan Telepon Segera Hadir')"
                class="px-10 py-5 bg-slate-800 hover:bg-slate-700 text-white rounded-2xl font-bold transition border border-slate-700 flex items-center justify-center">
                <i class="fas fa-phone-alt mr-3 text-xl"></i>Emergency Hotline
            </button>
        </div>
    </div>
</div>

<!-- Extra Scripts & Styles for Smooth Transitions -->
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    [x-cloak] {
        display: none !important;
    }

    .faq-category-title {
        letter-spacing: -0.025em;
    }
</style>

<?php $this->endSection(); ?>