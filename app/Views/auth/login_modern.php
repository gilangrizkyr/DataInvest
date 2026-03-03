<?php $this->extend('layouts/auth'); ?>

<?php $this->section('content'); ?>

<div
    class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary-600 to-primary-900 py-12 px-4 sm:px-6 lg:px-8 relative">

    <!-- Navigation Back Button -->
    <a href="<?= base_url('/') ?>"
        class="absolute top-6 left-6 flex items-center space-x-2 px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-xl backdrop-blur-md border border-white/10 transition-all duration-300 group shadow-lg">
        <i class="fas fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
        <span class="text-sm font-bold">Kembali ke Beranda</span>
    </a>

    <div class="w-full max-w-md">
        <!-- Brand Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 mb-8">
            <!-- Logo & Title -->
            <div class="text-center mb-8">
                <div class="inline-flex mb-4">
                    <img src="<?= base_url('logo-dpmptsp.png') ?>" alt="Logo DPMPTSP"
                        class="h-20 w-auto drop-shadow-lg">
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">DataInvest</h1>
                <p class="text-gray-600">DPMPTSP Tanah Bumbu</p>
            </div>

            <!-- Display General Error/Success Messages -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                    <p class="text-red-700 text-sm">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <?= htmlspecialchars(session()->getFlashdata('error')) ?>
                    </p>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                    <p class="text-green-700 text-sm">
                        <i class="fas fa-check-circle mr-2"></i>
                        <?= htmlspecialchars(session()->getFlashdata('success')) ?>
                    </p>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form action="<?= base_url('auth/process-login') ?>" method="POST" id="loginForm">
                <?= csrf_field() ?>

                <!-- Username/Email Input -->
                <div class="form-group mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        Username atau Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" id="username" name="username"
                            class="form-control pl-10 w-full <?= (isset($errors) && isset($errors['username'])) ? 'border-red-500' : '' ?>"
                            placeholder="username atau email" required value="<?= old('username') ?>">
                    </div>
                    <?php if (isset($errors) && isset($errors['username'])): ?>
                        <p class="text-red-600 text-sm mt-2">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            <?= htmlspecialchars($errors['username']) ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Password Input -->
                <div class="form-group mb-2">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" id="password" name="password"
                            class="form-control pl-10 w-full pr-10 <?= (isset($errors) && isset($errors['password'])) ? 'border-red-500' : '' ?>"
                            placeholder="••••••••" required>
                        <button type="button"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                            onclick="togglePasswordVisibility()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    <?php if (isset($errors) && isset($errors['password'])): ?>
                        <p class="text-red-600 text-sm mt-2">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            <?= htmlspecialchars($errors['password']) ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between mb-6 text-sm">
                    <label class="flex items-center text-gray-700 cursor-pointer hover:text-gray-900">
                        <input type="checkbox" name="remember" class="mr-2 form-check-input" value="1">
                        <span>Ingat saya</span>
                    </label>
                    <a href="<?= base_url('auth/forgot-password') ?>"
                        class="text-primary-600 hover:text-primary-700 font-medium">
                        Lupa Kata Sandi?
                    </a>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full btn btn-primary py-3 text-lg font-medium mb-4">
                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                </button>

                <!-- Divider -->
                <div class="relative mb-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Atau</span>
                    </div>
                </div>

                <!-- Help Text -->
                <p class="text-center text-gray-600 text-sm">
                    Belum punya akun?
                    <br>
                    <span class="text-gray-500">Hubungi administrator untuk pendaftaran</span>
                </p>
            </form>
        </div>

        <!-- Security Notice -->
        <div class="bg-white/10 backdrop-blur-lg rounded-lg p-4 text-white text-center text-sm">
            <i class="fas fa-shield-alt mr-2"></i>
            Portal ini hanya untuk pengguna resmi. Semua aktivitas dicatat dan dipantau.
        </div>
    </div>
</div>

<style>
    @keyframes fadeOut {
        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }
</style>

<script>
    // Toggle tampilan password (show/hide)
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }

    // Animasi loading saat form di-submit
    document.getElementById('loginForm').addEventListener('submit', function (e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
        }
    });
</script>

<?php $this->endSection(); ?>