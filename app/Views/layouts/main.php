<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SST Application' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand img {
            max-height: 40px;
            margin-right: 10px;
        }
        .main-content {
            min-height: calc(100vh - 60px);
            padding: 20px 0;
        }
        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .user-badge {
            background: #667eea;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }
        .dropdown-menu {
            min-width: 200px;
        }
        .dropdown-item {
            padding: 10px 15px;
        }
        .dropdown-item:hover {
            background-color: #f0f0f0;
        }
        .logout-btn {
            color: #dc3545;
        }
        .logout-btn:hover {
            background-color: #f8d7da;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                <img src="<?= base_url('logo-dpmptsp.png') ?>" alt="Logo">
                <span>SST Application</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (session()->has('user_id')): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('/') ?>">
                                <i class="bi bi-house"></i> Dashboard
                            </a>
                        </li>

                        <?php if (session()->get('role') === 'superadmin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('user-management') ?>">
                                    <i class="bi bi-people"></i> Manajemen User
                                </a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                                <span class="user-badge">
                                    <?= ucfirst(session()->get('role')) ?>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li>
                                    <h6 class="dropdown-header">
                                        <?= session()->get('username') ?>
                                    </h6>
                                </li>
                                <li>
                                    <small class="dropdown-header text-muted">
                                        <?= session()->get('email') ?>
                                    </small>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-gear"></i> Pengaturan
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-key"></i> Ubah Password
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item logout-btn" href="<?= base_url('auth/logout') ?>">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('auth/login') ?>">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <?= $this->renderSection('content') ?>
    </div>

    <footer class="bg-light text-center py-3 mt-5 border-top">
        <div class="container">
            <p class="mb-0 text-muted">&copy; 2025 SST Application. Semua hak dilindungi.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>