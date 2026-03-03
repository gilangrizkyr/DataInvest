<?php $this->extend('layouts/app'); ?>

<?php $this->section('content'); ?>

<?= view('components/section_header', [
    'title' => 'Pengaturan Sistem',
    'description' => 'Konfigurasi parameter aplikasi dan data master',
    'icon' => 'fas fa-cog'
]) ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-1">
        <div class="card overflow-hidden">
            <div class="p-4 bg-gray-50 border-b border-gray-100">
                <h3 class="font-bold flex items-center">
                    <i class="fas fa-list-ul mr-2 text-primary-500"></i>Kategori Pengaturan
                </h3>
            </div>
            <nav class="flex flex-col">
                <a href="#"
                    class="px-6 py-4 text-sm font-medium border-l-4 border-primary-500 bg-primary-50 text-primary-700">
                    <i class="fas fa-globe mr-3"></i>Umum
                </a>
                <a href="#"
                    class="px-6 py-4 text-sm font-medium border-l-4 border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                    <i class="fas fa-shield-alt mr-3"></i>Keamanan
                </a>
                <a href="#"
                    class="px-6 py-4 text-sm font-medium border-l-4 border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                    <i class="fas fa-database mr-3"></i>Backup Data
                </a>
            </nav>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="card p-8">
            <h3 class="text-lg font-bold mb-6">Pengaturan Umum</h3>
            <div class="space-y-6">
                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Aplikasi</label>
                    <input type="text" class="form-input w-full" value="DataInvest - DPMPTSP" disabled>
                </div>
                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Administrator</label>
                    <input type="email" class="form-input w-full" value="admin@datainvest.go.id" disabled>
                </div>
                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kurs Mata Uang Default (IDR/USD)</label>
                    <select class="form-select w-full" disabled>
                        <option>IDR (Indonesian Rupiah)</option>
                        <option>USD (US Dollar)</option>
                    </select>
                </div>
                <div class="flex justify-end pt-4">
                    <button type="button" class="btn btn-primary opacity-60 cursor-not-allowed" disabled>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>