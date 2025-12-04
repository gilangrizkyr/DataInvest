<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SST Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            padding: 40px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-container img {
            max-width: 80px;
            height: auto;
            margin-bottom: 15px;
        }

        .logo-container h2 {
            color: #333;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .logo-container p {
            color: #999;
            font-size: 14px;
        }

        .form-control {
            border: 1px solid #ddd;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            width: 100%;
            transition: transform 0.2s;
        }

        .btn-login:hover {
            color: white;
            transform: translateY(-2px);
        }

        .forgot-password {
            text-align: right;
            margin-bottom: 20px;
        }

        .forgot-password a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .divider {
            text-align: center;
            margin: 30px 0;
            color: #999;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="logo-container">
            <img src="<?= base_url('logo-dpmptsp.png') ?>" alt="Logo">
            <h2>Sistem Statistik Terpadu</h2>
            <p>Sistem Informasi Terpadu untuk Analisis Investasi</p>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" role="alert">
                <strong>Error!</strong> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success" role="alert">
                <strong>Sukses!</strong> <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('auth/process-login') ?>" method="POST">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="username" class="form-label">Username atau Email</label>
                <input type="text" class="form-control" id="username" name="username"
                    value="<?= old('username') ?>" required autofocus>
                <?php if (isset($errors['username'])): ?>
                    <small class="text-danger"><?= $errors['username'] ?></small>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <?php if (isset($errors['password'])): ?>
                    <small class="text-danger"><?= $errors['password'] ?></small>
                <?php endif; ?>
            </div>

            <!-- <div class="forgot-password">
                <a href="<?= base_url('auth/forgot-password') ?>">Lupa Password?</a>
            </div> -->

            <button type="submit" class="btn btn-login">Login</button>
        </form>

        <div class="divider">
            Account<br>
            Username: <strong>superadmin</strong><br>
            Password: <strong>SuperAdmin@123</strong>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>