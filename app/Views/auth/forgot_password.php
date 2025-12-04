<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - SST Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .forgot-container {
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
            max-width: 60px;
            height: auto;
            margin-bottom: 15px;
        }
        .form-control {
            border: 1px solid #ddd;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            width: 100%;
        }
        .btn-back {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            margin-top: 15px;
            display: block;
            text-align: center;
        }
        .alert {
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="logo-container">
            <img src="<?= base_url('logo-dpmptsp.png') ?>" alt="Logo">
            <h4>Lupa Password</h4>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success" role="alert">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" role="alert">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <p class="text-muted mb-4">Masukkan email Anda untuk menerima link reset password.</p>

        <form action="<?= base_url('auth/process-forgot-password') ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?= old('email') ?>" required autofocus>
                <?php if (isset($errors['email'])): ?>
                    <small class="text-danger"><?= $errors['email'] ?></small>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-submit">Kirim Link Reset</button>
            <a href="<?= base_url('auth/login') ?>" class="btn-back">← Kembali ke Login</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>