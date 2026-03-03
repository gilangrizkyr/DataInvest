<?php $this->extend('layouts/app'); ?>

<?php $this->section('content'); ?>

<?= view('components/section_header', [
    'title' => 'Profil Saya',
    'description' => 'Informasi akun dan pengaturan profil',
    'icon' => 'fas fa-user-circle'
]) ?>

<div class="max-w-4xl mx-auto">
    <div class="card overflow-hidden">
        <div class="bg-gradient-to-r from-primary-600 to-indigo-600 px-8 py-12 text-white text-center">
            <div
                class="w-24 h-24 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white/30">
                <i class="fas fa-user text-4xl"></i>
            </div>
            <h2 class="text-2xl font-bold">
                <?= htmlspecialchars($user['name'] ?? 'User') ?>
            </h2>
            <p class="text-white/80">
                <?= htmlspecialchars($user['email'] ?? '-') ?>
            </p>
        </div>

        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Username</label>
                        <p class="text-lg font-semibold text-gray-900">
                            <?= htmlspecialchars($user['username'] ?? '-') ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Role</label>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-100 text-primary-800">
                            <i class="fas fa-shield-alt mr-2"></i>
                            <?= ucfirst(htmlspecialchars($user['role'] ?? 'user')) ?>
                        </span>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Status Akun</label>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-2"></i>
                            Aktif
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Terakhir Login</label>
                        <p class="text-gray-900">
                            <?= date('d F Y, H:i') ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-10 pt-8 border-t border-gray-100 flex justify-end">
                <button type="button" class="btn btn-secondary mr-3"
                    onclick="Swal.fire('Fitur Pengembang', 'Fungsi edit profil sedang dalam tahap pengembangan.', 'info')">
                    <i class="fas fa-edit mr-2"></i>Edit Profil
                </button>
                <button type="button" class="btn btn-primary"
                    onclick="Swal.fire('Ganti Password', 'Silakan hubungi administrator untuk mereset password Anda.', 'info')">
                    <i class="fas fa-key mr-2"></i>Ganti Password
                </button>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>