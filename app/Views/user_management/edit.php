<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php $errors = session()->getFlashdata('errors'); ?>

<!-- Section Header -->
<?= view('components/section_header', [
    'title' => 'Edit Pengguna',
    'description' => 'Perbarui informasi akun, role, atau status akses pengguna di bawah ini.',
    'icon' => 'fas fa-user-edit'
]) ?>

<div class="max-w-2xl mx-auto">
    <div class="card p-8">
        <form action="<?= base_url("user-management/update/{$user['id']}") ?>" method="POST">
            <?= csrf_field() ?>

            <div class="space-y-6">
                <!-- Nama Lengkap -->
                <div class="group">
                    <label for="name"
                        class="text-xs font-black text-slate-500 uppercase tracking-widest mb-3 block group-focus-within:text-blue-500 transition-colors">Nama
                        Lengkap</label>
                    <div class="relative">
                        <i
                            class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors"></i>
                        <input type="text" name="name" id="name" value="<?= old('name', $user['name']) ?>" required
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl py-3.5 pl-12 pr-4 text-sm font-bold text-slate-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all <?= isset($errors['name']) ? 'border-red-500 bg-red-50' : '' ?>"
                            placeholder="Masukkan nama lengkap...">
                    </div>
                    <?php if (isset($errors['name'])): ?>
                        <p class="mt-2 text-[10px] font-bold text-red-500 uppercase tracking-tighter"><?= $errors['name'] ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Username -->
                <div class="group">
                    <label for="username"
                        class="text-xs font-black text-slate-500 uppercase tracking-widest mb-3 block group-focus-within:text-blue-500 transition-colors">Username</label>
                    <div class="relative">
                        <i
                            class="fas fa-at absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors"></i>
                        <input type="text" name="username" id="username"
                            value="<?= old('username', $user['username']) ?>" required
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl py-3.5 pl-12 pr-4 text-sm font-bold text-slate-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all <?= isset($errors['username']) ? 'border-red-500 bg-red-50' : '' ?>"
                            placeholder="username123">
                    </div>
                    <?php if (isset($errors['username'])): ?>
                        <p class="mt-2 text-[10px] font-bold text-red-500 uppercase tracking-tighter">
                            <?= $errors['username'] ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Email -->
                <div class="group">
                    <label for="email"
                        class="text-xs font-black text-slate-500 uppercase tracking-widest mb-3 block group-focus-within:text-blue-500 transition-colors">Email
                        Address</label>
                    <div class="relative">
                        <i
                            class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors"></i>
                        <input type="email" name="email" id="email" value="<?= old('email', $user['email']) ?>" required
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl py-3.5 pl-12 pr-4 text-sm font-bold text-slate-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all <?= isset($errors['email']) ? 'border-red-500 bg-red-50' : '' ?>"
                            placeholder="email@contoh.com">
                    </div>
                    <?php if (isset($errors['email'])): ?>
                        <p class="mt-2 text-[10px] font-bold text-red-500 uppercase tracking-tighter">
                            <?= $errors['email'] ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Password -->
                <div class="group">
                    <label for="password"
                        class="text-xs font-black text-slate-500 uppercase tracking-widest mb-3 block group-focus-within:text-blue-500 transition-colors">Password
                        Baru (Opsional)</label>
                    <div class="relative">
                        <i
                            class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors"></i>
                        <input type="password" name="password" id="password"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl py-3.5 pl-12 pr-4 text-sm font-bold text-slate-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all <?= isset($errors['password']) ? 'border-red-500 bg-red-50' : '' ?>"
                            placeholder="Kosongkan jika tidak ingin diubah">
                    </div>
                    <?php if (isset($errors['password'])): ?>
                        <p class="mt-2 text-[10px] font-bold text-red-500 uppercase tracking-tighter">
                            <?= $errors['password'] ?>
                        </p>
                    <?php endif; ?>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Role -->
                    <div class="group">
                        <label for="role"
                            class="text-xs font-black text-slate-500 uppercase tracking-widest mb-3 block group-focus-within:text-blue-500 transition-colors">Role</label>
                        <div class="relative">
                            <i
                                class="fas fa-user-tag absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors"></i>
                            <select name="role" id="role" required
                                class="w-full bg-slate-50 border border-slate-300 rounded-xl py-3.5 pl-12 pr-10 text-sm font-bold text-slate-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all appearance-none cursor-pointer">
                                <option value="admin" <?= (old('role') ?? $user['role']) === 'admin' ? 'selected' : '' ?>>
                                    Admin</option>
                                <option value="user" <?= (old('role') ?? $user['role']) === 'user' ? 'selected' : '' ?>>
                                    User Biasa</option>
                                <option value="superadmin" <?= (old('role') ?? $user['role']) === 'superadmin' ? 'selected' : '' ?>>Superadmin</option>
                            </select>
                            <i
                                class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="group">
                        <label for="status"
                            class="text-xs font-black text-slate-500 uppercase tracking-widest mb-3 block group-focus-within:text-blue-500 transition-colors">Status
                            Akun</label>
                        <div class="relative">
                            <i
                                class="fas fa-toggle-on absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-blue-500 transition-colors"></i>
                            <select name="status" id="status" required
                                class="w-full bg-slate-50 border border-slate-300 rounded-xl py-3.5 pl-12 pr-10 text-sm font-bold text-slate-700 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all appearance-none cursor-pointer">
                                <option value="active" <?= (old('status') ?? $user['status']) === 'active' ? 'selected' : '' ?>>Aktif</option>
                                <option value="inactive" <?= (old('status') ?? $user['status']) === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                            </select>
                            <i
                                class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 pt-4">
                    <a href="<?= base_url('user-management') ?>"
                        class="flex-1 px-6 py-4 rounded-2xl bg-slate-100 text-slate-600 text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-all text-center leading-none flex items-center justify-center">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex-[2] px-6 py-4 rounded-2xl bg-gradient-to-r from-blue-600 to-blue-700 text-white text-xs font-black uppercase tracking-widest hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg shadow-blue-200 active:scale-95">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>