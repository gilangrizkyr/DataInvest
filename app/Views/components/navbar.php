<?php
// Navigation bar component
$user = session()->get('user');
?>
<nav 
    x-data="{ mobileMenuOpen: false, scrolled: false }" 
    @scroll.window="scrolled = (window.pageYOffset > 20)"
    :class="{
        'bg-white/80 backdrop-blur-md shadow-lg py-1': scrolled,
        'bg-white shadow-md py-2': !scrolled
    }"
    class="sticky top-0 z-50 transition-all duration-500 ease-in-out border-b border-white/10"
>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo & Brand -->
            <div class="flex items-center">
                <div class="flex-shrink-0 flex items-center group">
                    <a href="<?= base_url() ?>" class="flex items-center transition-transform duration-300 group-hover:scale-105">
                        <img src="<?= base_url('logo-beraksi.png') ?>" alt="Logo Beraksi"
                            class="h-12 w-auto drop-shadow-lg filter brightness-110">
                    </a>
                </div>
                <!-- Aesthetic Divider -->
                <div class="hidden lg:block h-8 w-px bg-slate-200/60 mx-6"></div>
                <!-- Redesigned Brand Text -->
                <div class="hidden md:flex flex-col justify-center">
                    <span
                        class="text-[8px] font-black text-slate-400 uppercase tracking-[0.25em] leading-none mb-1.5 opacity-80">Pemerintah
                        Kabupaten</span>
                    <span class="text-[12px] font-black text-slate-800 tracking-wide leading-none uppercase group-hover:text-primary-600 transition-colors">DPMPTSP Tanah
                        Bumbu</span>
                </div>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex md:items-center md:space-x-1">
                <?php if ($user): ?>
                    <a href="<?= base_url('dashboard') ?>"
                        class="px-4 py-2 text-[13px] font-bold text-slate-600 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-chart-pie text-xs opacity-70"></i><span>Dashboard</span>
                    </a>
                    <a href="<?= base_url('faq') ?>" class="px-4 py-2 text-[13px] font-bold text-slate-600 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-circle-question text-xs opacity-70"></i><span>Bantuan</span>
                    </a>

                    <?php if ($user['role'] === 'superadmin'): ?>
                        <div class="h-4 w-px bg-slate-200 mx-2"></div>
                        <a href="<?= base_url('user-management') ?>"
                            class="px-4 py-2 text-[13px] font-bold text-slate-600 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-all duration-300 flex items-center gap-2">
                            <i class="fas fa-user-gear text-xs opacity-70"></i><span>Akses</span>
                        </a>
                        <a href="<?= base_url('security-monitoring') ?>"
                            class="px-4 py-2 text-[13px] font-bold text-slate-600 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-all duration-300 flex items-center gap-2">
                            <i class="fas fa-shield-halved text-xs opacity-70"></i><span>Sistem</span>
                        </a>
                    <?php endif; ?>

                    <div class="h-4 w-px bg-slate-200 mx-2"></div>

                    <!-- User Menu -->
                    <div class="relative ml-2" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center space-x-3 p-1.5 pr-4 rounded-2xl bg-slate-50 border border-slate-100 hover:border-primary-200 hover:bg-white hover:shadow-md transition-all duration-300">
                            <div
                                class="w-9 h-9 rounded-xl bg-gradient-to-tr from-primary-600 to-primary-400 flex items-center justify-center text-white text-sm font-black shadow-primary-glow">
                                <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
                            </div>
                            <div class="flex flex-col items-start leading-tight">
                                <span class="text-[12px] font-black text-slate-800"><?= htmlspecialchars($user['name'] ?? 'User') ?></span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter"><?= $user['role'] ?></span>
                            </div>
                            <i class="fas fa-chevron-down text-[10px] ml-2 text-slate-400 transition-transform" :class="{'rotate-180': open}"></i>
                        </button>

                        <!-- Dropdown -->
                        <div @click.away="open = false" 
                             x-show="open"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                             class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-2xl border border-slate-100 p-2 overflow-hidden z-[60]" x-cloak>
                            <div class="px-4 py-3 mb-1 border-b border-slate-50">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Akun Terhubung</p>
                                <p class="text-xs font-bold text-slate-700 truncate"><?= htmlspecialchars($user['email'] ?? '') ?></p>
                            </div>
                            <a href="<?= base_url('profile') ?>" class="flex items-center gap-3 px-4 py-2.5 text-[13px] font-bold text-slate-600 hover:bg-primary-50 hover:text-primary-600 rounded-xl transition-all">
                                <i class="fas fa-user-circle opacity-70"></i>Profil Saya
                            </a>
                            <?php if (($user['role'] ?? '') === 'superadmin'): ?>
                                <a href="<?= base_url('settings') ?>" class="flex items-center gap-3 px-4 py-2.5 text-[13px] font-bold text-slate-600 hover:bg-primary-50 hover:text-primary-600 rounded-xl transition-all">
                                    <i class="fas fa-sliders opacity-70"></i>Pengaturan
                                </a>
                            <?php endif; ?>
                            <div class="my-2 border-t border-slate-50"></div>
                            <a href="<?= base_url('auth/logout') ?>" class="flex items-center gap-3 px-4 py-2.5 text-[13px] font-bold text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                                <i class="fas fa-power-off opacity-70"></i>Keluar Sesi
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?= base_url('auth/login') ?>"
                        class="w-10 h-10 flex items-center justify-center border border-slate-200 text-slate-500 hover:text-primary-600 hover:bg-primary-50 hover:border-primary-200 rounded-xl transition-all duration-300 group"
                        title="Masuk ke Sistem">
                        <i class="fas fa-right-to-bracket text-xs opacity-70 group-hover:opacity-100 transition-opacity"></i>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="inline-flex items-center justify-center p-2.5 rounded-xl text-slate-600 bg-slate-50 border border-slate-100 hover:bg-white hover:shadow-sm focus:outline-none transition-all">
                    <i class="fas" :class="mobileMenuOpen ? 'fa-times' : 'fa-bars-staggered'"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="md:hidden border-t border-slate-100 py-4 space-y-2" x-cloak>
            <?php if ($user): ?>
                <a href="<?= base_url('dashboard') ?>"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl text-[14px] font-bold text-slate-700 hover:bg-primary-50 hover:text-primary-600 transition-all">
                    <i class="fas fa-chart-pie w-5 opacity-70"></i>Dashboard
                </a>
                <a href="<?= base_url('faq') ?>"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl text-[14px] font-bold text-slate-700 hover:bg-primary-50 hover:text-primary-600 transition-all">
                    <i class="fas fa-circle-question w-5 opacity-70"></i>Pusat Bantuan
                </a>

                <?php if ($user['role'] === 'superadmin'): ?>
                    <a href="<?= base_url('user-management') ?>"
                        class="flex items-center gap-4 px-4 py-3 rounded-xl text-[14px] font-bold text-slate-700 hover:bg-primary-50 hover:text-primary-600 transition-all">
                        <i class="fas fa-user-gear w-5 opacity-70"></i>Manajemen User
                    </a>
                    <a href="<?= base_url('security-monitoring') ?>"
                        class="flex items-center gap-4 px-4 py-3 rounded-xl text-[14px] font-bold text-slate-700 hover:bg-primary-50 hover:text-primary-600 transition-all">
                        <i class="fas fa-shield-halved w-5 opacity-70"></i>Keamanan
                    </a>
                <?php endif; ?>

                <div class="mx-4 my-4 border-t border-slate-100"></div>
                
                <div class="px-4 py-2 flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-primary-600 flex items-center justify-center text-white font-black">
                        <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
                    </div>
                    <div class="flex flex-col leading-tight">
                        <span class="text-sm font-black text-slate-800"><?= htmlspecialchars($user['name'] ?? 'User') ?></span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase"><?= $user['role'] ?></span>
                    </div>
                </div>

                <a href="<?= base_url('profile') ?>"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl text-[14px] font-bold text-slate-700 hover:bg-primary-50 transition-all">
                    <i class="fas fa-user-circle w-5 opacity-70"></i>Edit Profil
                </a>
                <?php if (($user['role'] ?? '') === 'superadmin'): ?>
                    <a href="<?= base_url('settings') ?>"
                        class="flex items-center gap-4 px-4 py-3 rounded-xl text-[14px] font-bold text-slate-700 hover:bg-primary-50 transition-all">
                        <i class="fas fa-sliders w-5 opacity-70"></i>Pengaturan Sistem
                    </a>
                <?php endif; ?>
                <a href="<?= base_url('auth/logout') ?>"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl text-[14px] font-bold text-rose-600 bg-rose-50 transition-all">
                    <i class="fas fa-power-off w-5 opacity-70"></i>Keluar Aplikasi
                </a>
            <?php else: ?>
                <a href="<?= base_url('auth/login') ?>"
                    class="flex items-center justify-center gap-3 px-4 py-4 rounded-xl text-[12px] font-black text-slate-500 bg-slate-50 border border-slate-100 hover:bg-white hover:text-primary-600 hover:border-primary-200 transition-all">
                    <i class="fas fa-right-to-bracket opacity-70"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>