<?php
// Navigation bar component
$user = session()->get('user');
?>
<nav class="bg-white shadow-md sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
    <div class="container mx-auto px-4">
        <div class="flex justify-between h-16">
            <!-- Logo & Brand -->
            <div class="flex items-center">
                <div class="flex-shrink-0 flex items-center">
                    <a href="<?= base_url() ?>" class="flex items-center">
                        <img src="<?= base_url('logo-beraksi.png') ?>" alt="Logo Beraksi"
                            class="h-14 w-auto drop-shadow-md">
                    </a>
                </div>
                <!-- Aesthetic Divider -->
                <div class="hidden md:block h-10 w-px bg-slate-200 mx-5"></div>
                <!-- Redesigned Brand Text -->
                <div class="hidden md:flex flex-col justify-center">
                    <span
                        class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] leading-none mb-1.5">Pemerintah
                        Kabupaten</span>
                    <span class="text-xs font-black text-slate-800 tracking-tight leading-none uppercase">DPMPTSP Tanah
                        Bumbu</span>
                </div>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex md:items-center md:space-x-2">
                <?php if ($user): ?>
                    <a href="<?= base_url('dashboard') ?>"
                        class="px-4 py-2 text-gray-700 hover:text-primary-600 transition">
                        <i class="fas fa-dashboard mr-1"></i>Dashboard
                    </a>
                    <a href="<?= base_url('faq') ?>" class="px-4 py-2 text-gray-700 hover:text-primary-600 transition">
                        <i class="fas fa-question-circle mr-1"></i>FAQ
                    </a>

                    <?php if ($user['role'] === 'superadmin'): ?>
                        <a href="<?= base_url('user-management') ?>"
                            class="px-4 py-2 text-gray-700 hover:text-primary-600 transition">
                            <i class="fas fa-users mr-1"></i>Users
                        </a>
                        <a href="<?= base_url('security-monitoring') ?>"
                            class="px-4 py-2 text-gray-700 hover:text-primary-600 transition">
                            <i class="fas fa-shield-alt mr-1"></i>Security
                        </a>
                    <?php endif; ?>

                    <!-- User Menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                            <div
                                class="w-8 h-8 rounded-full bg-primary-500 flex items-center justify-center text-white text-sm font-medium">
                                <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
                            </div>
                            <span class="hidden sm:inline text-sm"><?= htmlspecialchars($user['name'] ?? 'User') ?></span>
                            <i class="fas fa-chevron-down text-xs ml-2"></i>
                        </button>

                        <!-- Dropdown -->
                        <div @click.away="open = false" x-show="open"
                            class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg py-2" x-cloak>
                            <a href="<?= base_url('profile') ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-user mr-2"></i>Profile
                            </a>
                            <a href="<?= base_url('settings') ?>" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-cog mr-2"></i>Settings
                            </a>
                            <hr class="my-2">
                            <a href="<?= base_url('auth/logout') ?>" class="block px-4 py-2 text-red-600 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?= base_url('auth/login') ?>"
                        class="px-4 py-2 text-gray-700 hover:text-primary-600 transition">
                        <i class="fas fa-sign-in-alt mr-1"></i>Login
                    </a>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none transition">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div x-show="mobileMenuOpen" class="md:hidden border-t border-gray-200 bg-gray-50">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <?php if ($user): ?>
                    <a href="<?= base_url('dashboard') ?>"
                        class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition">
                        <i class="fas fa-dashboard mr-2"></i>Dashboard
                    </a>
                    <a href="<?= base_url('faq') ?>"
                        class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition">
                        <i class="fas fa-question-circle mr-2"></i>FAQ
                    </a>

                    <?php if ($user['role'] === 'superadmin'): ?>
                        <a href="<?= base_url('user-management') ?>"
                            class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition">
                            <i class="fas fa-users mr-2"></i>Users
                        </a>
                        <a href="<?= base_url('security-monitoring') ?>"
                            class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition">
                            <i class="fas fa-shield-alt mr-2"></i>Security
                        </a>
                    <?php endif; ?>

                    <hr class="my-2">
                    <a href="<?= base_url('profile') ?>"
                        class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition">
                        <i class="fas fa-user mr-2"></i>Profile
                    </a>
                    <a href="<?= base_url('auth/logout') ?>"
                        class="block px-3 py-2 rounded-md text-red-600 hover:bg-red-50 transition">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('auth/login') ?>"
                        class="block px-3 py-2 rounded-md text-primary-600 hover:bg-gray-100 transition font-medium">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>