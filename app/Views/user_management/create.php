<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php $errors = session()->getFlashdata('errors'); ?>

<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-10 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-primary-600 text-white rounded-[2.5rem] shadow-2xl shadow-primary-100 mb-6 transform hover:rotate-12 transition-transform duration-500">
            <i class="fas fa-user-plus text-3xl"></i>
        </div>
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">Tambah User Baru</h1>
        <p class="text-slate-500 font-medium mt-2">Daftarkan akses baru untuk administrator atau unit kerja.</p>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-card border border-slate-100 overflow-hidden">
        <div class="p-10">
            <form action="<?= base_url('user-management/store') ?>" method="POST" class="space-y-8">
                <?= csrf_field() ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Nama Lengkap -->
                    <div class="group space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] group-focus-within:text-primary-600 transition-colors">Nama Lengkap</label>
                        <div class="relative">
                            <i class="fas fa-id-card absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-primary-500 transition-colors"></i>
                            <input type="text" name="name" value="<?= old('name') ?>" required class="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-700 focus:ring-4 focus:ring-primary-100 focus:border-primary-500 outline-none transition-all placeholder:text-slate-300" placeholder="Contoh: Ahmad Fauzi">
                        </div>
                        <?php if (isset($errors['name'])): ?>
                            <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest px-2 italic"><i class="fas fa-circle-exclamation mr-1"></i><?= $errors['name'] ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Username -->
                    <div class="group space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] group-focus-within:text-primary-600 transition-colors">ID Username</label>
                        <div class="relative">
                            <i class="fas fa-at absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-primary-500 transition-colors"></i>
                            <input type="text" name="username" value="<?= old('username') ?>" required class="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-700 focus:ring-4 focus:ring-primary-100 focus:border-primary-500 outline-none transition-all placeholder:text-slate-300" placeholder="fauzi_123">
                        </div>
                        <p class="text-[9px] font-bold text-slate-400 px-2 uppercase tracking-tighter">Hanya alfanumerik (min. 3 karakter)</p>
                        <?php if (isset($errors['username'])): ?>
                            <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest px-2 italic"><i class="fas fa-circle-exclamation mr-1"></i><?= $errors['username'] ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Email -->
                    <div class="group space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] group-focus-within:text-primary-600 transition-colors">Email Instansi</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-primary-500 transition-colors"></i>
                            <input type="email" name="email" value="<?= old('email') ?>" required class="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-700 focus:ring-4 focus:ring-primary-100 focus:border-primary-500 outline-none transition-all placeholder:text-slate-300" placeholder="nama@dpmptsp.go.id">
                        </div>
                        <?php if (isset($errors['email'])): ?>
                            <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest px-2 italic"><i class="fas fa-circle-exclamation mr-1"></i><?= $errors['email'] ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Password -->
                    <div class="group space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] group-focus-within:text-primary-600 transition-colors">Kata Sandi Awal</label>
                        <div class="relative">
                            <i class="fas fa-lock-open absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-primary-500 transition-colors"></i>
                            <input type="password" name="password" required class="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-700 focus:ring-4 focus:ring-primary-100 focus:border-primary-500 outline-none transition-all placeholder:text-slate-300" placeholder="Minimal 8 karakter">
                        </div>
                        <?php if (isset($errors['password'])): ?>
                            <p class="text-[10px] font-bold text-rose-500 uppercase tracking-widest px-2 italic"><i class="fas fa-circle-exclamation mr-1"></i><?= $errors['password'] ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Role -->
                    <div class="group space-y-3 md:col-span-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] group-focus-within:text-primary-600 transition-colors">Tentukan Role Hak Akses</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="relative flex items-center p-5 rounded-2xl border-2 border-slate-100 bg-slate-50 hover:bg-white hover:border-primary-500 cursor-pointer transition-all group/opt">
                                <input type="radio" name="role" value="admin" <?= old('role') === 'admin' ? 'checked' : '' ?> class="peer hidden">
                                <div class="w-5 h-5 rounded-full border-2 border-slate-300 mr-4 flex items-center justify-center peer-checked:border-primary-500 peer-checked:bg-primary-500 transition-all">
                                    <div class="w-1.5 h-1.5 rounded-full bg-white scale-0 peer-checked:scale-100 transition-transform"></div>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-slate-700">Administrator Unit</span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase mt-0.5 tracking-tight">Akses manajemen data & laporan</span>
                                </div>
                            </label>
                            
                            <label class="relative flex items-center p-5 rounded-2xl border-2 border-slate-100 bg-slate-50 hover:bg-white hover:border-primary-500 cursor-pointer transition-all group/opt">
                                <input type="radio" name="role" value="user" <?= old('role') === 'user' ? 'checked' : '' ?> class="peer hidden">
                                <div class="w-5 h-5 rounded-full border-2 border-slate-300 mr-4 flex items-center justify-center peer-checked:border-primary-500 peer-checked:bg-primary-500 transition-all">
                                    <div class="w-1.5 h-1.5 rounded-full bg-white scale-0 peer-checked:scale-100 transition-transform"></div>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-slate-700">User Standar</span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase mt-0.5 tracking-tight">Akses pantauan dashboard saja</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="p-6 bg-amber-50 rounded-3xl border border-amber-100 flex items-start gap-4">
                    <div class="w-10 h-10 bg-amber-200 text-amber-700 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-shield-halved"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs font-black text-amber-800 uppercase tracking-widest">Keamanan Superadmin</span>
                        <p class="text-[11px] font-medium text-amber-700 mt-1">Role Superadmin hanya dapat dikonfigurasi langsung melalui akses basis data inti untuk menjamin integritas sistem tingkat tinggi.</p>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="flex items-center gap-4 pt-6">
                    <a href="<?= base_url('user-management') ?>" class="flex-1 py-4 px-6 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-black uppercase tracking-widest rounded-2xl transition-all text-center">Batalkan</a>
                    <button type="submit" class="flex-[2] py-4 px-6 bg-slate-900 hover:bg-black text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-xl hover:-translate-y-1 active:translate-y-0 transition-all">
                        <i class="fas fa-save mr-2"></i> Daftarkan Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>