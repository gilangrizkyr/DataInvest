<?php $this->extend('layouts/app'); ?>

<?php $this->section('content'); ?>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8" x-data="{ activeSection: 'general' }">
    <!-- Header Section -->
    <div class="mb-10">
        <h1 class="text-3xl font-black text-slate-800 tracking-tight mb-2">Pengaturan Sistem</h1>
        <p class="text-slate-500 font-medium">Konfigurasi parameter aplikasi, aksesibilitas, dan monitoring sistem.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left Sidebar Navigation -->
        <div class="lg:col-span-3 space-y-3">
            <button @click="activeSection = 'general'" :class="activeSection === 'general' ? 'bg-primary-600 text-white shadow-lg shadow-primary-100' : 'bg-white text-slate-600 hover:bg-slate-50'" class="w-full flex items-center gap-4 px-6 py-4 rounded-2xl transition-all duration-300 group">
                <i class="fas fa-sliders text-sm" :class="activeSection === 'general' ? 'text-white' : 'text-slate-400 group-hover:text-primary-500'"></i>
                <span class="text-sm font-black uppercase tracking-widest">Umum</span>
            </button>
            <button @click="activeSection = 'security'" :class="activeSection === 'security' ? 'bg-primary-600 text-white shadow-lg shadow-primary-100' : 'bg-white text-slate-600 hover:bg-slate-50'" class="w-full flex items-center gap-4 px-6 py-4 rounded-2xl transition-all duration-300 group">
                <i class="fas fa-shield-halved text-sm" :class="activeSection === 'security' ? 'text-white' : 'text-slate-400 group-hover:text-primary-500'"></i>
                <span class="text-sm font-black uppercase tracking-widest">Keamanan</span>
            </button>
            <button @click="activeSection = 'appearance'" :class="activeSection === 'appearance' ? 'bg-primary-600 text-white shadow-lg shadow-primary-100' : 'bg-white text-slate-600 hover:bg-slate-50'" class="w-full flex items-center gap-4 px-6 py-4 rounded-2xl transition-all duration-300 group">
                <i class="fas fa-palette text-sm" :class="activeSection === 'appearance' ? 'text-white' : 'text-slate-400 group-hover:text-primary-500'"></i>
                <span class="text-sm font-black uppercase tracking-widest">Tampilan</span>
            </button>
            <button @click="activeSection = 'system'" :class="activeSection === 'system' ? 'bg-primary-600 text-white shadow-lg shadow-primary-100' : 'bg-white text-slate-600 hover:bg-slate-50'" class="w-full flex items-center gap-4 px-6 py-4 rounded-2xl transition-all duration-300 group">
                <i class="fas fa-server text-sm" :class="activeSection === 'system' ? 'text-white' : 'text-slate-400 group-hover:text-primary-500'"></i>
                <span class="text-sm font-black uppercase tracking-widest">Sistem</span>
            </button>
        </div>

        <!-- Right Content Area -->
        <div class="lg:col-span-9 space-y-8">
            <!-- General Settings -->
            <div x-show="activeSection === 'general'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="bg-white rounded-3xl p-10 border border-slate-100 shadow-card">
                <h3 class="text-xl font-black text-slate-800 mb-8 flex items-center gap-4">
                    <span class="p-3 bg-blue-50 text-blue-600 rounded-xl"><i class="fas fa-globe"></i></span>
                    Identitas & Lokalisasi
                </h3>
                
                <div class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Nama Aplikasi</label>
                            <input type="text" value="DataInvest - DPMPTSP Tanah Bumbu" class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-700 outline-none" readonly>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Versi Aplikasi</label>
                            <div class="flex items-center gap-3 px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl">
                                <span class="px-2 py-0.5 bg-blue-600 text-white rounded text-[10px] font-black uppercase tracking-widest">v5.2.0</span>
                                <span class="text-sm font-bold text-slate-500">Stable Build</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Bahasa Default</label>
                            <select class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-700 outline-none appearance-none cursor-not-allowed" disabled>
                                <option>Bahasa Indonesia</option>
                                <option>English (US)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">Mata Uang Default</label>
                            <select class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-700 outline-none appearance-none cursor-not-allowed" disabled>
                                <option>IDR (Rp)</option>
                                <option>USD ($)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Section -->
            <div x-show="activeSection === 'security'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="space-y-6">
                <div class="bg-white rounded-3xl p-10 border border-slate-100 shadow-card">
                    <h3 class="text-xl font-black text-slate-800 mb-8 flex items-center gap-4">
                        <span class="p-3 bg-rose-50 text-rose-600 rounded-xl"><i class="fas fa-shield-heart"></i></span>
                        Keamanan Aplikasi
                    </h3>
                    
                    <div class="space-y-6">
                        <div class="flex items-center justify-between p-6 bg-slate-50 rounded-3xl border border-slate-100">
                            <div>
                                <h4 class="font-black text-slate-800 mb-1">Two-Factor Authentication</h4>
                                <p class="text-xs text-slate-400 font-medium">Tambahkan lapisan keamanan ekstra pada akun admin.</p>
                            </div>
                            <div class="relative inline-block w-12 h-6 transition duration-200 ease-in-out bg-slate-200 rounded-full cursor-not-allowed opacity-50">
                                <span class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full"></span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-6 bg-slate-50 rounded-3xl border border-slate-100">
                            <div>
                                <h4 class="font-black text-slate-800 mb-1">Session Management</h4>
                                <p class="text-xs text-slate-400 font-medium">Putus otomatis sesi login yang tidak aktif selama 30 menit.</p>
                            </div>
                            <div class="relative inline-block w-12 h-6 transition duration-200 ease-in-out bg-emerald-500 rounded-full cursor-not-allowed">
                                <span class="absolute right-1 top-1 w-4 h-4 bg-white rounded-full"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Info Section -->
            <div x-show="activeSection === 'system'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-slate-900 rounded-3xl p-8 text-white relative overflow-hidden shadow-2xl">
                    <div class="absolute top-0 right-0 p-6 opacity-10">
                        <i class="fas fa-microchip text-7xl rotate-12"></i>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Environment Status</p>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-3 border-b border-white/10">
                            <span class="text-sm font-medium text-slate-300">PHP Version</span>
                            <span class="text-sm font-black"><?= phpversion() ?></span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b border-white/10">
                            <span class="text-sm font-medium text-slate-300">Framework</span>
                            <span class="text-sm font-black">CodeIgniter 4.3.5</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-slate-300">Database</span>
                            <span class="text-sm font-black">MySQL 8.0</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-card flex flex-col justify-between">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Memory Usage</p>
                        <h4 class="text-4xl font-black text-slate-800 tracking-tighter mb-2">12.4 <span class="text-xl text-slate-300">MB</span></h4>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-primary-600 h-full rounded-full" style="width: 35%"></div>
                        </div>
                    </div>
                    <p class="text-[10px] font-bold text-slate-400 italic">Optimized for high performance</p>
                </div>
            </div>
            
            <!-- Appearance Section -->
            <div x-show="activeSection === 'appearance'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="bg-white rounded-3xl p-10 border border-slate-100 shadow-card">
                <h3 class="text-xl font-black text-slate-800 mb-8 flex items-center gap-4">
                    <span class="p-3 bg-indigo-50 text-indigo-600 rounded-xl"><i class="fas fa-brush"></i></span>
                    Kustomisasi Visual
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="p-6 rounded-2xl border-2 border-primary-600 bg-primary-50 relative group cursor-pointer">
                        <div class="absolute top-3 right-3 text-primary-600"><i class="fas fa-check-circle"></i></div>
                        <div class="w-full h-20 bg-white rounded-lg mb-4 shadow-sm border border-slate-200"></div>
                        <p class="text-xs font-black text-center text-primary-700 uppercase tracking-widest">Light Mode</p>
                    </div>
                    <div class="p-6 rounded-2xl border-2 border-slate-100 bg-slate-50 opacity-60 grayscale hover:grayscale-0 transition-all cursor-not-allowed">
                        <div class="w-full h-20 bg-slate-800 rounded-lg mb-4 shadow-sm"></div>
                        <p class="text-xs font-black text-center text-slate-400 uppercase tracking-widest">Dark Mode</p>
                    </div>
                    <div class="p-6 rounded-2xl border-2 border-slate-100 bg-slate-50 opacity-60 grayscale hover:grayscale-0 transition-all cursor-not-allowed">
                        <div class="w-full h-20 bg-gradient-to-br from-slate-700 to-slate-900 rounded-lg mb-4 shadow-sm"></div>
                        <p class="text-xs font-black text-center text-slate-400 uppercase tracking-widest">Glassmorphism</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>
