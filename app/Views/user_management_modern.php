<?php $this->extend('layouts/app'); ?>

<?php $this->section('content'); ?>

<!-- Header with Action Button -->
<?= view('components/section_header', [
    'title' => 'User Management',
    'description' => 'Manage system users and their permissions',
    'icon' => 'fas fa-users',
    'action_text' => 'Create New User',
    'action_url' => 'user-management/create'
]) ?>

<!-- Search and Filter -->
<div class="card p-6 mb-6">
    <form method="GET" action="<?= base_url('user-management') ?>" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <?= view('components/form_input', [
                'name' => 'search',
                'placeholder' => 'Search users by name, email, or username...',
                'value' => $search ?? '',
                'icon' => 'fas fa-search'
            ]) ?>
        </div>
        <button type="submit" class="btn btn-primary md:mt-2">
            <i class="fas fa-search mr-2"></i>Search
        </button>
        <?php if ($search): ?>
            <a href="<?= base_url('user-management') ?>" class="btn btn-outline">
                <i class="fas fa-times mr-2"></i>Clear
            </a>
        <?php endif; ?>
    </form>
</div>

<!-- Users Table -->
<div class="card p-0 overflow-hidden">
    <div class="overflow-x-auto custom-scrollbar overflow-y-auto max-h-[600px]">
        <table class="w-full table-auto border-separate border-spacing-0">
            <thead class="bg-slate-50/90 backdrop-blur-md sticky top-0 z-10">
                <tr>
                    <th
                        class="py-5 px-6 text-xs font-black text-slate-500 uppercase tracking-widest text-left border-b border-slate-100">
                        Nama Pengguna</th>
                    <th
                        class="py-5 px-6 text-xs font-black text-slate-500 uppercase tracking-widest text-left border-b border-slate-100">
                        Email</th>
                    <th
                        class="py-5 px-6 text-xs font-black text-slate-500 uppercase tracking-widest text-left border-b border-slate-100">
                        Username</th>
                    <th
                        class="py-5 px-6 text-xs font-black text-slate-500 uppercase tracking-widest text-center border-b border-slate-100">
                        Role</th>
                    <th
                        class="py-5 px-6 text-xs font-black text-slate-500 uppercase tracking-widest text-center border-b border-slate-100">
                        Status</th>
                    <th
                        class="py-5 px-6 text-xs font-black text-slate-500 uppercase tracking-widest text-center border-b border-slate-100">
                        Bergabung</th>
                    <th
                        class="py-5 px-6 text-xs font-black text-slate-500 uppercase tracking-widest text-right border-b border-slate-100">
                        Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 bg-white">
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="py-4 px-6">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 text-white rounded-xl flex items-center justify-center font-black mr-4 shadow-lg shadow-blue-100 group-hover:scale-110 transition-transform duration-300">
                                        <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
                                    </div>
                                    <span
                                        class="font-bold text-slate-700 group-hover:text-blue-600 transition-colors"><?= esc($user['name']) ?></span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-sm font-bold text-slate-600"><?= esc($user['email']) ?></span>
                            </td>
                            <td class="py-4 px-6">
                                <code
                                    class="bg-slate-100 px-3 py-1.5 rounded-lg text-xs font-black text-slate-700"><?= esc($user['username']) ?></code>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <?php
                                $roleColor = $user['role'] === 'superadmin' ? 'bg-rose-50 text-rose-600 border-rose-100' : ($user['role'] === 'admin' ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-blue-50 text-blue-600 border-blue-100');
                                ?>
                                <span
                                    class="px-3 py-1.5 rounded-full text-xs font-black uppercase tracking-widest border <?= $roleColor ?>">
                                    <?= ucfirst($user['role']) ?>
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <?php
                                $statusActive = ($user['status'] ?? 'active') === 'active';
                                $statusColor = $statusActive ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200';
                                ?>
                                <span
                                    class="px-3 py-1.5 rounded-full text-xs font-black uppercase tracking-widest border <?= $statusColor ?>">
                                    <?= ucfirst($user['status'] ?? 'active') ?>
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="text-xs font-bold text-slate-500">
                                    <?= isset($user['created_at']) ? date('d M Y', strtotime($user['created_at'])) : 'N/A' ?>
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex justify-end gap-2 transition-opacity">
                                    <a href="<?= base_url('user-management/edit/' . $user['id']) ?>"
                                        class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <button type="button"
                                        class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all shadow-sm"
                                        onclick="confirmDelete(<?= $user['id'] ?>)">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-8">
                            <div class="text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-3 opacity-50"></i>
                                <p class="font-semibold">No users found</p>
                                <p class="text-sm">
                                    <?= $search ? 'Try a different search term.' : 'Create a new user to get started.' ?>
                                </p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if (!empty($pager)): ?>
        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-xs font-black text-slate-500 uppercase tracking-widest leading-none">
                    Menampilkan hasil pencarian
                </p>
                <div class="pager-container">
                    <?= $pager->links() ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Quick Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
    <?= view('components/kpi_card', [
        'label' => 'Total Users',
        'value' => count($users ?? []),
        'icon' => 'fas fa-users',
        'color' => 'primary',
        'subtitle' => 'Active and inactive'
    ]) ?>

    <?= view('components/kpi_card', [
        'label' => 'Administrators',
        'value' => count(array_filter($users ?? [], fn($u) => $u['role'] === 'admin')),
        'icon' => 'fas fa-user-shield',
        'color' => 'warning',
        'subtitle' => 'Admin users'
    ]) ?>

    <?= view('components/kpi_card', [
        'label' => 'Regular Users',
        'value' => count(array_filter($users ?? [], fn($u) => $u['role'] === 'user')),
        'icon' => 'fas fa-user',
        'color' => 'info',
        'subtitle' => 'Normal users'
    ]) ?>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center"
    x-data="{ show: false }">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm mx-4">
        <h3 class="text-lg font-bold text-gray-900 mb-2">
            <i class="fas fa-exclamation-triangle text-danger-600 mr-2"></i>Delete User
        </h3>
        <p class="text-gray-600 mb-6">Are you sure you want to delete this user? This action cannot be undone.</p>
        <div class="flex gap-3">
            <button type="button" class="btn btn-outline flex-1" onclick="closeDeleteModal()">
                Cancel
            </button>
            <form id="deleteForm" method="POST" class="flex-1">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-danger w-full">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmDelete(userId) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        form.action = `<?= base_url('user-management/delete/') ?>${userId}`;
        modal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('deleteModal')?.addEventListener('click', (e) => {
        if (e.target.id === 'deleteModal') {
            closeDeleteModal();
        }
    });
</script>

<?php $this->endSection(); ?>