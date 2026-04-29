<?php $this->extend('layouts/app'); ?>

<?php $this->section('content'); ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8 space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight flex items-center gap-4">
                <span class="p-3 bg-primary-600 text-white rounded-2xl shadow-xl shadow-primary-100"><i class="fas fa-users-gear"></i></span>
                Manajemen Pengguna
            </h1>
            <p class="text-slate-500 font-medium mt-2">Kelola akses, role, dan status pengguna sistem dalam satu panel terpusat.</p>
        </div>
        <a href="<?= base_url('user-management/create') ?>" class="inline-flex items-center justify-center gap-3 px-6 py-4 bg-slate-900 hover:bg-black text-white text-xs font-black uppercase tracking-widest rounded-2xl transition-all shadow-xl hover:-translate-y-1 active:translate-y-0">
            <i class="fas fa-user-plus"></i> Tambah User Baru
        </a>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-card group hover:shadow-2xl transition-all duration-500">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-lg"><i class="fas fa-users"></i></div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-tight">Total Pengguna</p>
            </div>
            <h3 class="text-3xl font-black text-slate-800 tracking-tighter" x-text="'<?= count($users ?? []) ?>'">0</h3>
            <div class="mt-4 w-full bg-slate-50 h-1.5 rounded-full overflow-hidden">
                <div class="bg-blue-500 h-full rounded-full" style="width: 100%"></div>
            </div>
        </div>

        <!-- Superadmins -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-card group hover:shadow-2xl transition-all duration-500">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center text-lg"><i class="fas fa-user-shield"></i></div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-tight">Super Admins</p>
            </div>
            <h3 class="text-3xl font-black text-slate-800 tracking-tighter"><?= count(array_filter($users ?? [], fn($u) => $u['role'] === 'superadmin')) ?></h3>
            <div class="mt-4 w-full bg-slate-50 h-1.5 rounded-full overflow-hidden">
                <div class="bg-rose-500 h-full rounded-full" style="width: 25%"></div>
            </div>
        </div>

        <!-- Admins -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-card group hover:shadow-2xl transition-all duration-500">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-lg"><i class="fas fa-user-gear"></i></div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-tight">Admin Unit</p>
            </div>
            <h3 class="text-3xl font-black text-slate-800 tracking-tighter"><?= count(array_filter($users ?? [], fn($u) => $u['role'] === 'admin')) ?></h3>
            <div class="mt-4 w-full bg-slate-50 h-1.5 rounded-full overflow-hidden">
                <div class="bg-amber-500 h-full rounded-full" style="width: 45%"></div>
            </div>
        </div>

        <!-- Regular Users -->
        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-card group hover:shadow-2xl transition-all duration-500">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-lg"><i class="fas fa-user"></i></div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-tight">User Biasa</p>
            </div>
            <h3 class="text-3xl font-black text-slate-800 tracking-tighter"><?= count(array_filter($users ?? [], fn($u) => $u['role'] === 'user')) ?></h3>
            <div class="mt-4 w-full bg-slate-50 h-1.5 rounded-full overflow-hidden">
                <div class="bg-emerald-500 h-full rounded-full" style="width: 70%"></div>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-card overflow-hidden">
        <!-- Search & Filter Bar -->
        <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <form method="GET" action="<?= base_url('user-management') ?>" class="relative flex-1 max-w-lg group">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary-500 transition-colors"></i>
                <input type="text" name="search" value="<?= $search ?? '' ?>" placeholder="Cari berdasarkan nama, email, atau username..." class="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-bold text-slate-700 focus:ring-4 focus:ring-primary-100 focus:border-primary-500 outline-none transition-all">
                <?php if ($search): ?>
                    <a href="<?= base_url('user-management') ?>" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 transition-colors">
                        <i class="fas fa-times-circle"></i>
                    </a>
                <?php endif; ?>
            </form>
            
            <div class="flex items-center gap-4">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Urutkan:</span>
                <select class="bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-xs font-black text-slate-600 outline-none cursor-pointer hover:bg-white transition-all">
                    <option>Nama (A-Z)</option>
                    <option>Terbaru</option>
                    <option>Role</option>
                </select>
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full table-auto border-separate border-spacing-0">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="py-5 px-8 text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">Profil Pengguna</th>
                        <th class="py-5 px-8 text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">Informasi Kontak</th>
                        <th class="py-5 px-8 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Hak Akses</th>
                        <th class="py-5 px-8 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="py-5 px-8 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr class="hover:bg-slate-50/50 transition-all group">
                                <td class="py-5 px-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-slate-200 to-slate-100 border border-white shadow-sm flex items-center justify-center text-slate-600 font-black text-lg group-hover:scale-110 transition-transform duration-300">
                                            <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-black text-slate-800"><?= esc($user['name']) ?></span>
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">@<?= esc($user['username']) ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-5 px-8">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-slate-600"><?= esc($user['email']) ?></span>
                                        <span class="text-[10px] font-medium text-slate-400 mt-1 italic">Joined <?= isset($user['created_at']) ? date('M Y', strtotime($user['created_at'])) : 'N/A' ?></span>
                                    </div>
                                </td>
                                <td class="py-5 px-8 text-center">
                                    <?php
                                    $roleStyle = match($user['role']) {
                                        'superadmin' => 'bg-rose-50 text-rose-600 border-rose-100',
                                        'admin' => 'bg-amber-50 text-amber-600 border-amber-100',
                                        default => 'bg-blue-50 text-blue-600 border-blue-100',
                                    };
                                    ?>
                                    <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border <?= $roleStyle ?>">
                                        <?= esc($user['role']) ?>
                                    </span>
                                </td>
                                <td class="py-5 px-8 text-center">
                                    <?php $statusActive = ($user['status'] ?? 'active') === 'active'; ?>
                                    <div class="flex items-center justify-center gap-2">
                                        <span class="w-2 h-2 rounded-full <?= $statusActive ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300' ?>"></span>
                                        <span class="text-[10px] font-black uppercase tracking-widest <?= $statusActive ? 'text-emerald-600' : 'text-slate-400' ?>">
                                            <?= esc($user['status'] ?? 'active') ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="py-5 px-8 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="<?= base_url('user-management/edit/' . $user['id']) ?>" class="p-2.5 bg-slate-50 text-slate-400 rounded-xl hover:bg-primary-600 hover:text-white transition-all shadow-sm border border-slate-100" title="Edit User">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <button onclick="confirmDelete(<?= $user['id'] ?>)" class="p-2.5 bg-slate-50 text-slate-400 rounded-xl hover:bg-rose-600 hover:text-white transition-all shadow-sm border border-slate-100" title="Delete User">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="py-32 text-center bg-white">
                                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-user-slash text-4xl text-slate-200"></i>
                                </div>
                                <h4 class="text-xl font-black text-slate-800 tracking-tight">Tidak Ada Pengguna Ditemukan</h4>
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mt-2">Gunakan kata kunci pencarian lain atau tambahkan user baru</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        <?php if (!empty($pager)): ?>
            <div class="p-8 bg-slate-50/50 border-t border-slate-100 flex flex-col md:flex-row items-center justify-between gap-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    Menampilkan hasil pencarian data pengguna
                </p>
                <div class="modern-pagination">
                    <?= $pager->links() ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Premium Delete Modal (Alpine.js) -->
<div id="deleteModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-[2.5rem] p-10 max-w-md w-full shadow-2xl border border-slate-100 transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
        <div class="w-20 h-20 bg-rose-50 text-rose-600 rounded-3xl flex items-center justify-center text-3xl mx-auto mb-8">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 class="text-2xl font-black text-slate-800 text-center tracking-tight mb-4">Hapus Pengguna?</h3>
        <p class="text-slate-500 text-center font-medium mb-10">Tindakan ini tidak dapat dibatalkan. Pengguna yang dihapus akan kehilangan semua akses ke sistem secara permanen.</p>
        
        <div class="flex flex-col gap-3">
            <form id="deleteForm" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="w-full py-4 bg-rose-600 hover:bg-rose-700 text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-rose-100 transition-all">
                    Ya, Hapus Permanen
                </button>
            </form>
            <button onclick="closeDeleteModal()" class="w-full py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-black uppercase tracking-widest rounded-2xl transition-all">
                Batalkan
            </button>
        </div>
    </div>
</div>

<script>
    function confirmDelete(userId) {
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('modalContent');
        const form = document.getElementById('deleteForm');
        
        form.action = `<?= base_url('user-management/delete/') ?>/${userId}`;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const content = document.getElementById('modalContent');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Close on overlay click
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    
    /* Modern Pagination Styling */
    .modern-pagination ul { display: flex; gap: 8px; list-style: none; padding: 0; margin: 0; }
    .modern-pagination li a, .modern-pagination li span { 
        display: flex; align-items: center; justify-content: center; 
        width: 36px; height: 36px; border-radius: 12px; 
        font-size: 11px; font-weight: 900; transition: all 0.3s;
        background: white; border: 1px solid #f1f5f9; color: #64748b;
    }
    .modern-pagination li.active span { background: #2563eb; color: white; border-color: #2563eb; box-shadow: 0 4px 12px rgba(37,99,235,0.2); }
    .modern-pagination li a:hover { background: #f8fafc; border-color: #e2e8f0; color: #2563eb; }
</style>

<?php $this->endSection(); ?>