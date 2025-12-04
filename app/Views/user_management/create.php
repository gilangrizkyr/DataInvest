<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h2 class="mb-4"><?= $title ?></h2>

            <div class="card">
                <div class="card-body">
                    <form action="<?= base_url('user-management/store') ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" 
                                   id="username" name="username" value="<?= old('username') ?>" required>
                            <?php if (isset($errors['username'])): ?>
                                <div class="invalid-feedback"><?= $errors['username'] ?></div>
                            <?php endif; ?>
                            <small class="text-muted">Hanya alfanumerik, minimal 3 karakter</small>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                   id="email" name="email" value="<?= old('email') ?>" required>
                            <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback"><?= $errors['email'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                                   id="password" name="password" required>
                            <?php if (isset($errors['password'])): ?>
                                <div class="invalid-feedback"><?= $errors['password'] ?></div>
                            <?php endif; ?>
                            <small class="text-muted">Minimal 8 karakter</small>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select <?= isset($errors['role']) ? 'is-invalid' : '' ?>" 
                                    id="role" name="role" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="user" <?= old('role') === 'user' ? 'selected' : '' ?>>User Biasa</option>
                            </select>
                            <?php if (isset($errors['role'])): ?>
                                <div class="invalid-feedback"><?= $errors['role'] ?></div>
                            <?php endif; ?>
                            <small class="text-muted">Superadmin hanya bisa dibuat manual</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">Buat User</button>
                            <a href="<?= base_url('user-management') ?>" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
