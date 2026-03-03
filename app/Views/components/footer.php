<footer class="bg-slate-900 text-slate-400 mt-20 pt-16 rounded-t-[40px] relative overflow-hidden shadow-2xl">
    <!-- Subtle Background Accents -->
    <div class="absolute top-0 left-1/4 w-64 h-64 bg-blue-500/5 rounded-full blur-[80px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-indigo-500/5 rounded-full blur-[100px] pointer-events-none">
    </div>

    <div class="container mx-auto px-8 lg:px-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 pb-12 border-b border-slate-800">
            <!-- Brand Column -->
            <div class="lg:col-span-1">
                <div class="flex items-center space-x-3 mb-6">
                    <div
                        class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-900/40">
                        <i class="fas fa-chart-line text-white"></i>
                    </div>
                    <span class="text-2xl font-black text-white tracking-tighter">DataInvest</span>
                </div>
                <p class="text-slate-400 text-sm leading-relaxed mb-6">
                    Platform manajemen dan analisis data investasi terintegrasi milik DPMPTSP Kabupaten Tanah Bumbu.
                    Membangun masa depan ekonomi yang transparan.
                </p>
                <!-- <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 bg-slate-800 hover:bg-blue-600 text-white rounded-xl flex items-center justify-center transition-all duration-300">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-slate-800 hover:bg-blue-400 text-white rounded-xl flex items-center justify-center transition-all duration-300">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-slate-800 hover:bg-pink-600 text-white rounded-xl flex items-center justify-center transition-all duration-300">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div> -->
            </div>

            <!-- Quick Access -->
            <div>
                <h4 class="text-white font-bold text-lg mb-6 flex items-center">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2"></span> Akses Cepat
                </h4>
                <ul class="space-y-4">
                    <li>
                        <a href="<?= base_url('dashboard') ?>"
                            class="hover:text-blue-400 transition-colors flex items-center group text-sm">
                            <i
                                class="fas fa-chevron-right text-[10px] mr-2 text-slate-600 group-hover:text-blue-400 transition-colors"></i>
                            Dashboard Realisasi
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('faq') ?>"
                            class="hover:text-blue-400 transition-colors flex items-center group text-sm">
                            <i
                                class="fas fa-chevron-right text-[10px] mr-2 text-slate-600 group-hover:text-blue-400 transition-colors"></i>
                            Pusat Jawaban (FAQ)
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('auth/login') ?>"
                            class="hover:text-blue-400 transition-colors flex items-center group text-sm">
                            <i
                                class="fas fa-chevron-right text-[10px] mr-2 text-slate-600 group-hover:text-blue-400 transition-colors"></i>
                            Login Administrator
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Services -->
            <div>
                <h4 class="text-white font-bold text-lg mb-6 flex items-center">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></span> Layanan Kami
                </h4>
                <ul class="space-y-4 text-sm text-slate-400">
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-emerald-500 mr-2.5"></i> Pemetaan Sektor
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-emerald-500 mr-2.5"></i> Analisis Wilayah
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-emerald-500 mr-2.5"></i> Pelaporan LKPM
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-emerald-500 mr-2.5"></i> Monitoring Investasi
                    </li>
                </ul>
            </div>

            <!-- Contact & Help -->
            <div>
                <h4 class="text-white font-bold text-lg mb-6 flex items-center">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-2"></span> Hubungi Kami
                </h4>
                <div class="space-y-5">
                    <div class="flex items-start">
                        <div
                            class="w-8 h-8 bg-slate-800 rounded-lg flex items-center justify-center mr-3 mt-1 text-amber-500 shadow-inner">
                            <i class="fas fa-map-marker-alt text-sm"></i>
                        </div>
                        <p class="text-xs leading-relaxed">
                            Pd. Butun, Kec. Batulicin, Kabupaten Tanah Bumbu, Kalimantan Selatan 72273
                        </p>
                    </div>
                    <div class="flex items-center">
                        <div
                            class="w-8 h-8 bg-slate-800 rounded-lg flex items-center justify-center mr-3 text-blue-500 shadow-inner">
                            <i class="fas fa-phone-alt text-sm"></i>
                        </div>
                        <p class="text-sm">(0518) 70664</p>
                    </div>
                    <!-- <div class="flex items-center">
                        <div class="w-8 h-8 bg-slate-800 rounded-lg flex items-center justify-center mr-3 text-indigo-500 shadow-inner">
                            <i class="fas fa-envelope text-sm"></i>
                        </div>
                        <p class="text-sm">dpmptsp@tanahbumbu.go.id</p>
                    </div> -->
                </div>
            </div>
        </div>

        <!-- Copyright Row -->
        <div class="py-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-[13px] text-slate-500">
                &copy; <?= date('Y') ?> <span class="text-white font-bold">DataInvest</span>. Dinas Penanaman Modal dan
                Pelayanan Terpadu Satu Pintu | Tanah Bumbu
            </div>
            <div class="text-[13px] text-slate-500 flex items-center">
                Made with <i class="fas fa-heart text-rose-500 mx-2 animate-pulse"></i> by
                <a href="https://github.com/gilangrizkyr" target="_blank"
                    class="text-blue-400 font-bold hover:underline ml-2 transition-all">GilangRizky</a>
            </div>
        </div>
    </div>
</footer>