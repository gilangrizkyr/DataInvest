<?php $this->extend('layouts/app'); ?>

<?php $this->section('content'); ?>

<?= view('components/section_header', [
    'title' => 'Audit Logs',
    'description' => 'Rekaman aktivitas sistem dan perubahan data',
    'icon' => 'fas fa-history'
]) ?>

<div class="card p-12 text-center">
    <div class="max-w-md mx-auto">
        <div class="w-20 h-20 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-tools text-3xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Halaman Dalam Pengembangan</h2>
        <p class="text-gray-600 mb-8">
            Fitur Audit Logs sedang dikembangkan untuk memastikan semua aktivitas tercatat dengan aman sesuai standar
            kepatuhan data.
        </p>
        <a href="<?= base_url('dashboard') ?>" class="btn btn-primary">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
        </a>
    </div>
</div>

<?php $this->endSection(); ?>