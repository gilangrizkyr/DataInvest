<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - SST Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .reset-container {
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
        .alert {
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="logo-container">
            <img src="<?= base_url('logo-dpmptsp.png') ?>" alt="Logo">
            <h4>Reset Password</h4>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" role="alert">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('auth/process-reset-password') ?>" method="POST">
            <?= csrf_field() ?>
            
            <input type="hidden" name="token" value="<?= $token ?>">

            <div class="mb-3">
                <label for="password" class="form-label">Password Baru</label>
                <input type="password" class="form-control" id="password" name="password" required>
                <small class="text-muted">Minimal 8 karakter</small>
                <?php if (isset($errors['password'])): ?>
                    <small class="text-danger d-block"><?= $errors['password'] ?></small>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="password_confirm" class="form-label">Konfirmasi Password</label>
                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                <?php if (isset($errors['password_confirm'])): ?>
                    <small class="text-danger"><?= $errors['password_confirm'] ?></small>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-submit">Reset Password</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
