<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php $errors = session()->getFlashdata('errors'); ?>

<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-10 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-amber-600 text-white rounded-[2.5rem] shadow-2xl shadow-amber-100 mb-6 transform hover:-rotate-12 transition-transform duration-500">
            <i class="fas fa-user-pen text-3xl"></i>
        </div>
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">Edit Detail Pengguna</h1>
        <p class="text-slate-500 font-medium mt-2">Perbarui informasi profil, akses, atau status akun pengguna.</p>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-card border border-slate-100 overflow-hidden">
        <div class="p-10">
            <form action="<?= base_url("user-management/update/{$user['id']}") ?>" method="POST" class="space-y-8">
                <?= csrf_field() ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Nama Lengkap -->
                    <div class="group space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] group-focus-within:text-amber-600 transition-colors">Nama Lengkap</label>
                        <div class="relative">
                            <i class="fas fa-id-card absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-amber-500 transition-colors"></i>
                            <input type="text" name="name" value="<?= old('name', $user['name']) ?>" required class="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-700 focus:ring-4 focus:ring-amber-100 focus:border-amber-500 outline-none transition-all placeholder:text-slate-300">
                        </div>
                        <?php if (isset($errors['name'])): ?>
                            <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest px-2 italic"><i class="fas fa-circle-exclamation mr-1"></i><?= $errors['name'] ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Username -->
                    <div class="group space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] group-focus-within:text-amber-600 transition-colors">ID Username</label>
                        <div class="relative">
                            <i class="fas fa-at absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-amber-500 transition-colors"></i>
                            <input type="text" name="username" value="<?= old('username', $user['username']) ?>" required class="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-700 focus:ring-4 focus:ring-amber-100 focus:border-amber-500 outline-none transition-all placeholder:text-slate-300">
                        </div>
                        <?php if (isset($errors['username'])): ?>
                            <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest px-2 italic"><i class="fas fa-circle-exclamation mr-1"></i><?= $errors['username'] ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Email -->
                    <div class="group space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] group-focus-within:text-amber-600 transition-colors">Email Terdaftar</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-amber-500 transition-colors"></i>
                            <input type="email" name="email" value="<?= old('email', $user['email']) ?>" required class="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-700 focus:ring-4 focus:ring-amber-100 focus:border-amber-500 outline-none transition-all placeholder:text-slate-300">
                        </div>
                        <?php if (isset($errors['email'])): ?>
                            <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest px-2 italic"><i class="fas fa-circle-exclamation mr-1"></i><?= $errors['email'] ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Password -->
                    <div class="group space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] group-focus-within:text-amber-600 transition-colors">Atur Ulang Sandi</label>
                        <div class="relative">
                            <i class="fas fa-key absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-amber-500 transition-colors"></i>
                            <input type="password" name="password" class="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-700 focus:ring-4 focus:ring-amber-100 focus:border-amber-500 outline-none transition-all placeholder:text-slate-300" placeholder="Kosongkan jika tidak diubah">
                        </div>
                        <?php if (isset($errors['password'])): ?>
                            <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest px-2 italic"><i class="fas fa-circle-exclamation mr-1"></i><?= $errors['password'] ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Role & Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:col-span-2">
                        <div class="group space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] group-focus-within:text-amber-600 transition-colors">Hak Akses</label>
                            <div class="relative">
                                <i class="fas fa-user-tag absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-amber-500 transition-colors"></i>
                                <select name="role" class="w-full pl-14 pr-10 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-700 focus:ring-4 focus:ring-amber-100 focus:border-amber-500 outline-none transition-all appearance-none cursor-pointer">
                                    <option value="admin" <?= (old('role') ?? $user['role']) === 'admin' ? 'selected' : '' ?>>Administrator Unit</option>
                                    <option value="user" <?= (old('role') ?? $user['role']) === 'user' ? 'selected' : '' ?>>User Standar</option>
                                    <option value="superadmin" <?= (old('role') ?? $user['role']) === 'superadmin' ? 'selected' : '' ?>>Super Administrator</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                            </div>
                        </div>

                        <div class="group space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] group-focus-within:text-amber-600 transition-colors">Status Akun</label>
                            <div class="relative">
                                <i class="fas fa-power-off absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-amber-500 transition-colors"></i>
                                <select name="status" class="w-full pl-14 pr-10 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-700 focus:ring-4 focus:ring-amber-100 focus:border-amber-500 outline-none transition-all appearance-none cursor-pointer">
                                    <option value="active" <?= (old('status') ?? $user['status']) === 'active' ? 'selected' : '' ?>>🟢 Akun Aktif</option>
                                    <option value="inactive" <?= (old('status') ?? $user['status']) === 'inactive' ? 'selected' : '' ?>>🔴 Akun Nonaktif</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="flex items-center gap-4 pt-6">
                    <a href="<?= base_url('user-management') ?>" class="flex-1 py-4 px-6 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-black uppercase tracking-widest rounded-2xl transition-all text-center">Batalkan</a>
                    <button type="submit" class="flex-[2] py-4 px-6 bg-slate-900 hover:bg-black text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-xl hover:-translate-y-1 active:translate-y-0 transition-all">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>