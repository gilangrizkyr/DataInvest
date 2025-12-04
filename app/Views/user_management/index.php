<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><?= $title ?></h2>
                <a href="<?= base_url('user-management/create') ?>" class="btn btn-primary">
                    + Tambah User Baru
                </a>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Search Form -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="<?= base_url('user-management') ?>" method="GET" class="d-flex gap-2">
                        <input type="text" name="search" class="form-control" placeholder="Cari username atau email..." 
                               value="<?= $search ?>">
                        <button type="submit" class="btn btn-outline-primary">Cari</button>
                        <?php if ($search): ?>
                            <a href="<?= base_url('user-management') ?>" class="btn btn-outline-secondary">Reset</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <!-- Users Table -->
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Last Login</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><strong><?= esc($user['username']) ?></strong></td>
                                        <td><?= esc($user['email']) ?></td>
                                        <td>
                                            <span class="badge 
                                                <?= $user['role'] === 'superadmin' ? 'bg-danger' : 
                                                    ($user['role'] === 'admin' ? 'bg-warning text-dark' : 'bg-info') ?>">
                                                <?= ucfirst($user['role']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                <?= $user['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>">
                                                <?= ucfirst($user['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?= $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : '-' ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url("user-management/edit/{$user['id']}") ?>" 
                                               class="btn btn-sm btn-warning">Edit</a>
                                            <a href="<?= base_url("user-management/delete/{$user['id']}") ?>" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Yakin ingin menghapus user ini?')">Hapus</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        Tidak ada user ditemukan
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <?php if ($pager): ?>
                <div class="mt-4">
                    <?= $pager->links() ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>