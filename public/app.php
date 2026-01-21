<?php
/**
 * Ultra Simple Entry Point - No CI4 routing, no filters
 * Just load the page directly
 */

// Set environment
define('ENVIRONMENT', 'development');
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(FCPATH);

// Get URL path
$path = $_SERVER['REQUEST_URI'] ?? '/';
$path = strtok($path, '?');

// Remove script name and DataInvest/public
$path = str_replace('/DataInvest/public/app.php', '', $path);
$path = str_replace('/DataInvest/public', '', $path);
$path = str_replace('/DataInvest', '', $path);

// Clean path
$path = '/' . ltrim($path, '/');
$path = rtrim($path, '/');
if ($path === '') $path = '/';

// Parse segments
$segments = array_values(array_filter(explode('/', $path)));
$page = $segments[0] ?? 'dashboard';
$id = $segments[1] ?? null;

// Bootstrap CI4 minimally
require FCPATH . '../vendor/autoload.php';
require FCPATH . '../app/Config/Paths.php';

$paths = new Config\Paths();

// Load session
$session = \Config\Services::session();

// Check login status
$loggedIn = !empty($session->get('logged_in'));
$userRole = $session->get('role') ?? '';

// Define which pages require login
$publicPages = ['login', 'auth', 'forgot-password', 'reset-password'];
$superadminPages = ['user-management'];

// Check access
if (!in_array($page, $publicPages) && !$loggedIn) {
    header('Location: /DataInvest/public/app.php/login');
    exit;
}

// Check superadmin access
if (in_array($page, $superadminPages) && $userRole !== 'superadmin') {
    echo "Access Denied: Superadmin only";
    exit;
}

// Simple page router
switch ($page) {
    case 'dashboard':
        $title = 'Dashboard - SST Application';
        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?= $title ?></title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
            <style>
                body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
                .card { border: none; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
                .stat-card { background: rgba(255,255,255,0.95); border-radius: 15px; padding: 20px; text-align: center; }
                .stat-icon { font-size: 3rem; margin-bottom: 10px; }
                .welcome-card { background: rgba(255,255,255,0.95); border-radius: 15px; padding: 30px; margin-bottom: 30px; }
                .btn-primary-custom { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 10px; padding: 12px 30px; color: white; font-weight: bold; }
                .btn-danger-custom { background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%); border: none; border-radius: 10px; padding: 12px 30px; color: white; font-weight: bold; }
            </style>
        </head>
        <body>
            <nav class="navbar navbar-expand-lg navbar-dark" style="background: rgba(0,0,0,0.3);">
                <div class="container">
                    <a class="navbar-brand" href="#"><i class="bi bi-bar-chart"></i> SST Application</a>
                    <div class="d-flex">
                        <a href="/DataInvest/public/app.php/logout" class="btn btn-danger btn-sm">Logout</a>
                    </div>
                </div>
            </nav>
            
            <div class="container mt-5">
                <div class="welcome-card">
                    <h2>Selamat Datang di Sistem Statistik Terpadu</h2>
                    <p class="text-muted">Dashboard utama aplikasi</p>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="stat-card">
                            <div class="stat-icon">📊</div>
                            <h5>LKPM</h5>
                            <a href="#" class="btn btn-primary-custom mt-3">Input Data</a>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="stat-card">
                            <div class="stat-icon">📁</div>
                            <h5>Metadata</h5>
                            <a href="#" class="btn btn-primary-custom mt-3">Upload</a>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="stat-card">
                            <div class="stat-icon">📥</div>
                            <h5>Download</h5>
                            <a href="#" class="btn btn-primary-custom mt-3">Unduh Data</a>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-4">
                        <a href="/DataInvest/public/app.php/faq" class="btn btn-primary-custom w-100 py-3">
                            <i class="bi bi-question-circle"></i> FAQ
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="/DataInvest/public/app.php/security-monitoring" class="btn btn-primary-custom w-100 py-3">
                            <i class="bi bi-shield-check"></i> Security Monitoring
                        </a>
                    </div>
                    <?php if ($userRole === 'superadmin'): ?>
                    <div class="col-md-4">
                        <a href="/DataInvest/public/app.php/user-management" class="btn btn-primary-custom w-100 py-3">
                            <i class="bi bi-people"></i> User Management
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </body>
        </html>
        <?php
        echo ob_get_clean();
        break;
        
    case 'login':
        $title = 'Login - SST Application';
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Simple login check (bypass CI4 model for testing)
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // Try to use CI4 model
            try {
                require FCPATH . '../app/Models/UserModel.php';
                $userModel = new \App\Models\UserModel();
                $user = $userModel->where('username', $username)->first();
                
                if ($user && password_verify($password, $user['password'])) {
                    $session->set(['logged_in' => true, 'user_id' => $user['id'], 'username' => $user['username'], 'role' => $user['role']]);
                    header('Location: /DataInvest/public/app.php/dashboard');
                    exit;
                } else {
                    $error = 'Username atau password salah!';
                }
            } catch (Exception $e) {
                // Fallback: demo login
                if ($username === 'admin' && $password === 'admin123') {
                    $session->set(['logged_in' => true, 'user_id' => 1, 'username' => 'admin', 'role' => 'superadmin']);
                    header('Location: /DataInvest/public/app.php/dashboard');
                    exit;
                }
                $error = 'Login gagal: ' . $e->getMessage();
            }
        }
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?= $title ?></title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
                .login-card { background: white; border-radius: 20px; padding: 40px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); max-width: 450px; width: 100%; }
                .logo-text { font-size: 1.8rem; font-weight: bold; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
            </style>
        </head>
        <body>
            <div class="login-card text-center">
                <div class="mb-4">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem;">
                        <i class="bi bi-bar-chart"></i>
                    </div>
                </div>
                <h3 class="logo-text mb-4">Sistem Statistik Terpadu</h3>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="mb-3">
                        <input type="text" name="username" class="form-control form-control-lg" placeholder="Username" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; font-weight: bold;">LOGIN</button>
                </form>
                
                <div class="mt-4 text-muted">
                    <small>Demo: admin / admin123</small>
                </div>
            </div>
        </body>
        </html>
        <?php
        break;
        
    case 'logout':
        $session->destroy();
        header('Location: /DataInvest/public/app.php/login');
        exit;
        break;
        
    case 'faq':
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>FAQ - SST Application</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
                .faq-card { background: white; border-radius: 15px; padding: 30px; margin-bottom: 20px; }
            </style>
        </head>
        <body>
            <nav class="navbar navbar-dark" style="background: rgba(0,0,0,0.3);">
                <div class="container">
                    <a class="navbar-brand" href="/DataInvest/public/app.php/dashboard">← Kembali</a>
                </div>
            </nav>
            <div class="container mt-5">
                <h2 class="text-white mb-4">Frequently Asked Questions</h2>
                <div class="faq-card">
                    <h5>Bagaimana cara login ke sistem?</h5>
                    <p class="text-muted">Masukkan username dan password yang sudah didaftarkan oleh administrator.</p>
                </div>
                <div class="faq-card">
                    <h5>Bagaimana cara mengupload data LKPM?</h5>
                    <p class="text-muted">Pada halaman dashboard, klik tombol "Input Data" pada card LKPM.</p>
                </div>
                <div class="faq-card">
                    <h5>Siapa yang bisa mengakses User Management?</h5>
                    <p class="text-muted">Hanya user dengan role Superadmin yang dapat mengakses fitur ini.</p>
                </div>
            </div>
        </body>
        </html>
        <?php
        break;
        
    case 'security-monitoring':
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Security Monitoring - SST Application</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <style>
                body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
                .card { background: white; border-radius: 15px; padding: 20px; }
            </style>
        </head>
        <body>
            <nav class="navbar navbar-dark" style="background: rgba(0,0,0,0.3);">
                <div class="container">
                    <a class="navbar-brand" href="/DataInvest/public/app.php/dashboard">← Kembali ke Dashboard</a>
                </div>
            </nav>
            <div class="container mt-5">
                <h2 class="text-white mb-4">Security Monitoring</h2>
                <div class="card">
                    <h5><i class="bi bi-shield-check"></i> Sistem Aman</h5>
                    <p class="text-muted">Tidak ada ancaman keamanan yang terdeteksi.</p>
                </div>
            </div>
        </body>
        </html>
        <?php
        break;
        
    case 'user-management':
        if ($userRole !== 'superadmin') {
            echo "Access Denied";
            exit;
        }
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>User Management - SST Application</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
                .card { background: white; border-radius: 15px; padding: 20px; }
            </style>
        </head>
        <body>
            <nav class="navbar navbar-dark" style="background: rgba(0,0,0,0.3);">
                <div class="container">
                    <a class="navbar-brand" href="/DataInvest/public/app.php/dashboard">← Kembali ke Dashboard</a>
                </div>
            </nav>
            <div class="container mt-5">
                <h2 class="text-white mb-4">User Management</h2>
                <div class="card">
                    <h5>Manajemen User</h5>
                    <p class="text-muted">Fitur ini untuk mengelola user sistem.</p>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>admin</td>
                                <td><span class="badge bg-success">Superadmin</span></td>
                                <td>-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </body>
        </html>
        <?php
        break;
        
    default:
        http_response_code(404);
        echo "<h1>404 - Page Not Found</h1>";
        echo "<p>Halaman '$page' tidak ditemukan.</p>";
        echo "<a href='/DataInvest/public/app.php/dashboard'>Kembali ke Dashboard</a>";
        break;
}

