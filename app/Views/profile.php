<?php $this->extend('layouts/app'); ?>

<?php $this->section('content'); ?>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8" x-data="{ activeTab: 'overview' }">
    <!-- Header Hero -->
    <div class="relative rounded-3xl overflow-hidden shadow-2xl mb-8 group">
        <!-- Background Gradient & Patterns -->
        <div class="absolute inset-0 bg-gradient-to-br from-primary-700 via-primary-600 to-indigo-800"></div>
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        
        <!-- Decorative blobs -->
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/10 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-1000"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-1000"></div>

        <div class="relative px-6 py-10 sm:p-12 flex flex-col md:flex-row items-center md:items-start gap-8">
            <!-- Profile Picture with Glow -->
            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-tr from-white/50 to-white/10 rounded-full blur opacity-40 group-hover:opacity-70 transition duration-500"></div>
                <div class="relative w-32 h-32 sm:w-40 sm:h-40 rounded-full bg-white/20 backdrop-blur-xl flex items-center justify-center border-4 border-white/30 shadow-2xl overflow-hidden">
                    <span class="text-5xl sm:text-7xl font-black text-white drop-shadow-lg">
                        <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
                    </span>
                </div>
                <div class="absolute bottom-2 right-2 w-8 h-8 sm:w-10 sm:h-10 bg-green-500 border-4 border-primary-600 rounded-full shadow-lg flex items-center justify-center" title="Online">
                    <div class="w-2 h-2 sm:w-3 sm:h-3 bg-white rounded-full animate-pulse"></div>
                </div>
            </div>

            <!-- Profile Identity -->
            <div class="flex-1 text-center md:text-left pt-2">
                <div class="flex flex-col md:flex-row md:items-center gap-3 mb-4">
                    <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight drop-shadow-sm">
                        <?= htmlspecialchars($user['name'] ?? 'User Name') ?>
                    </h1>
                    <span class="inline-flex items-center px-4 py-1.5 rounded-xl bg-white/20 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest border border-white/20 shadow-sm">
                        <i class="fas fa-shield-halved mr-2 opacity-70"></i>
                        <?= ucfirst(htmlspecialchars($user['role'] ?? 'user')) ?>
                    </span>
                </div>
                
                <div class="flex flex-wrap justify-center md:justify-start gap-4 sm:gap-6">
                    <div class="flex items-center text-white/80 gap-2">
                        <div class="p-2 bg-white/10 rounded-lg backdrop-blur-sm">
                            <i class="fas fa-envelope text-xs"></i>
                        </div>
                        <span class="text-sm font-medium"><?= htmlspecialchars($user['email'] ?? '-') ?></span>
                    </div>
                    <div class="flex items-center text-white/80 gap-2">
                        <div class="p-2 bg-white/10 rounded-lg backdrop-blur-sm">
                            <i class="fas fa-calendar-alt text-xs"></i>
                        </div>
                        <span class="text-sm font-medium italic opacity-90">Terdaftar sejak <?= date('M Y', strtotime($user['created_at'] ?? 'now')) ?></span>
                    </div>
                </div>

                <div class="mt-8 flex flex-wrap justify-center md:justify-start gap-3">
                    <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'bg-white text-primary-700 shadow-xl' : 'bg-white/10 text-white hover:bg-white/20'" class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all duration-300">
                        Overview
                    </button>
                    <button @click="activeTab = 'edit'" :class="activeTab === 'edit' ? 'bg-white text-primary-700 shadow-xl' : 'bg-white/10 text-white hover:bg-white/20'" class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all duration-300">
                        Ubah Profil
                    </button>
                    <button @click="activeTab = 'security'" :class="activeTab === 'security' ? 'bg-white text-primary-700 shadow-xl' : 'bg-white/10 text-white hover:bg-white/20'" class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all duration-300">
                        Keamanan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Content: Overview -->
    <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Account Statistics -->
        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-card group hover:shadow-2xl transition-all duration-500">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                        <i class="fas fa-clock-rotate-left text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Aktivitas Terakhir</p>
                        <p class="text-sm font-bold text-slate-800"><?= date('d M Y, H:i', strtotime($user['last_login'] ?? 'now')) ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-card group hover:shadow-2xl transition-all duration-500">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                        <i class="fas fa-shield-check text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Status Keanggotaan</p>
                        <p class="text-sm font-bold text-emerald-600 flex items-center">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                            Verified Account
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Bio/Description Card -->
            <div class="sm:col-span-2 bg-white rounded-3xl p-10 border border-slate-100 shadow-card">
                <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-primary-600 rounded-full"></span>
                    Informasi Akun
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Username Pengguna</p>
                        <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100 group hover:border-primary-200 transition-colors">
                            <i class="fas fa-at text-slate-400"></i>
                            <span class="text-sm font-black text-slate-700"><?= htmlspecialchars($user['username'] ?? '-') ?></span>
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Alamat Email</p>
                        <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100 group hover:border-primary-200 transition-colors">
                            <i class="fas fa-envelope text-slate-400"></i>
                            <span class="text-sm font-black text-slate-700"><?= htmlspecialchars($user['email'] ?? '-') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-slate-900 rounded-3xl p-8 text-white relative overflow-hidden shadow-2xl">
                <div class="absolute top-0 right-0 p-4 opacity-20">
                    <i class="fas fa-bolt text-7xl -rotate-12"></i>
                </div>
                <h3 class="text-xl font-black mb-4 relative z-10">Quick Actions</h3>
                <div class="space-y-3 relative z-10">
                    <a href="<?= base_url('dashboard') ?>" class="flex items-center justify-between p-4 bg-white/10 hover:bg-white/20 rounded-2xl transition-all group">
                        <span class="text-sm font-bold">Go to Dashboard</span>
                        <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="<?= base_url('auth/logout') ?>" class="flex items-center justify-between p-4 bg-rose-500/20 hover:bg-rose-500/40 rounded-2xl transition-all group text-rose-300">
                        <span class="text-sm font-bold">Logout System</span>
                        <i class="fas fa-power-off text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Content: Edit Profile -->
    <div x-show="activeTab === 'edit'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="max-w-2xl">
        <div class="bg-white rounded-3xl p-10 border border-slate-100 shadow-card">
            <h3 class="text-xl font-black text-slate-800 mb-8 flex items-center gap-4">
                <i class="fas fa-user-edit text-primary-600"></i>
                Perbarui Informasi Profil
            </h3>
            
            <form action="<?= base_url('profile/update') ?>" method="POST" class="space-y-6">
                <?= csrf_field() ?>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Nama Lengkap</label>
                        <div class="relative">
                            <i class="fas fa-id-card absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" name="name" value="<?= old('name', $user['name']) ?>" class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-primary-100 focus:border-primary-500 transition-all font-bold text-slate-700 outline-none" placeholder="Masukkan nama lengkap">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Username</label>
                            <div class="relative">
                                <i class="fas fa-at absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" name="username" value="<?= old('username', $user['username']) ?>" class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-primary-100 focus:border-primary-500 transition-all font-bold text-slate-700 outline-none" placeholder="Username">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Email</label>
                            <div class="relative">
                                <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="email" name="email" value="<?= old('email', $user['email']) ?>" class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-primary-100 focus:border-primary-500 transition-all font-bold text-slate-700 outline-none" placeholder="Email aktif">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex gap-3">
                    <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-black uppercase tracking-widest text-xs py-4 rounded-2xl shadow-lg shadow-primary-100 hover:shadow-primary-200 hover:-translate-y-0.5 active:translate-y-0 transition-all">
                        Simpan Perubahan
                    </button>
                    <button type="button" @click="activeTab = 'overview'" class="px-8 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black uppercase tracking-widest text-xs rounded-2xl transition-all">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tab Content: Security -->
    <div x-show="activeTab === 'security'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="max-w-2xl">
        <div class="bg-white rounded-3xl p-10 border border-slate-100 shadow-card">
            <h3 class="text-xl font-black text-slate-800 mb-2 flex items-center gap-4">
                <i class="fas fa-shield-alt text-rose-600"></i>
                Keamanan & Password
            </h3>
            <p class="text-sm text-slate-400 mb-8 px-1">Pastikan password Anda kuat dan unik untuk melindungi akun Anda.</p>
            
            <form action="<?= base_url('profile/change-password') ?>" method="POST" class="space-y-6">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Password Saat Ini</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="password" name="current_password" class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-rose-100 focus:border-rose-500 transition-all font-bold outline-none" placeholder="••••••••">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Password Baru</label>
                        <div class="relative">
                            <i class="fas fa-key absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="password" name="new_password" class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-primary-100 focus:border-primary-500 transition-all font-bold outline-none" placeholder="••••••••">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Konfirmasi Password Baru</label>
                        <div class="relative">
                            <i class="fas fa-check-double absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="password" name="confirm_password" class="w-full pl-12 pr-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-primary-100 focus:border-primary-500 transition-all font-bold outline-none" placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full bg-slate-900 hover:bg-black text-white font-black uppercase tracking-widest text-xs py-4 rounded-2xl shadow-xl hover:-translate-y-0.5 transition-all">
                        Perbarui Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>