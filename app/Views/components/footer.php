<footer class="bg-slate-900 text-slate-400 mt-20 pt-16 rounded-t-[40px] relative overflow-hidden shadow-2xl">
    <!-- Subtle Background Accents -->
    <div class="absolute top-0 left-1/4 w-64 h-64 bg-blue-500/5 rounded-full blur-[80px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-indigo-500/5 rounded-full blur-[100px] pointer-events-none">
    </div>

    <div class="container mx-auto px-8 lg:px-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-16 pb-12 border-b border-slate-800">
            <!-- Brand Column -->
            <div class="space-y-6">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-900/40">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                    <div>
                        <span class="text-2xl font-black text-white tracking-tighter block leading-none">DataInvest</span>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em]">Sistem Informasi Terpadu</span>
                    </div>
                </div>
                <p class="text-slate-400 text-sm leading-relaxed max-w-md">
                    Platform manajemen dan analisis data investasi terintegrasi milik DPMPTSP Kabupaten Tanah Bumbu. 
                    Dirancang untuk mendukung transparansi data dan percepatan pertumbuhan ekonomi daerah melalui visualisasi data yang cerdas.
                </p>
            </div>

            <!-- Contact & Info -->
            <div class="lg:pl-20">
                <h4 class="text-white font-black text-xs uppercase tracking-[0.2em] mb-8 flex items-center">
                    <span class="w-8 h-[2px] bg-blue-600 mr-3"></span> Hubungi Kami
                </h4>
                <div class="space-y-6">
                    <div class="flex items-start group">
                        <div class="w-10 h-10 bg-slate-800/50 rounded-xl flex items-center justify-center mr-4 text-blue-500 border border-slate-700/50 group-hover:border-blue-500/50 transition-colors">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Kantor Pusat</p>
                            <p class="text-xs text-slate-300 leading-relaxed max-w-[250px]">
                                Pd. Butun, Kec. Batulicin, Kabupaten Tanah Bumbu, Kalimantan Selatan 72273
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center group">
                        <div class="w-10 h-10 bg-slate-800/50 rounded-xl flex items-center justify-center mr-4 text-emerald-500 border border-slate-700/50 group-hover:border-emerald-500/50 transition-colors">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Telepon</p>
                            <p class="text-sm text-slate-300 font-bold">(0518) 70664</p>
                        </div>
                    </div>
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
                System Developed & Maintained by
                <i class="fas fa-code text-blue-500 mx-2"></i>
                <a href="https://github.com/gilangrizkyr" target="_blank"
                    class="text-white font-bold hover:text-blue-400 transition-all ml-1">GilangRizky</a>
            </div>
        </div>
    </div>
</footer>