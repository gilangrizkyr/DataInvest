<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Statistik Terpadu DPMPTSP</title>
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        /* Animated background pattern */
        .bg-pattern {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(255,255,255,0.05) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(255,255,255,0.05) 0%, transparent 50%);
            background-size: 100px 100px;
            animation: bg-shift 20s ease-in-out infinite alternate;
        }

        @keyframes bg-shift {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }

        /* Floating particles */
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: float-up 15s linear infinite;
        }

        @keyframes float-up {
            0% {
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) scale(1);
                opacity: 0;
            }
        }

        .login-wrapper {
            position: relative;
            width: 100%;
            max-width: 420px;
            z-index: 10;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 24px;
            box-shadow: 
                0 25px 80px rgba(0, 0, 0, 0.4),
                0 0 0 1px rgba(255, 255, 255, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            padding: 2.5rem;
            position: relative;
            animation: card-bounce 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes card-bounce {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .logo-img {
            width: 70px;
            height: 70px;
            object-fit: contain;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
            animation: logo-sway 3s ease-in-out infinite;
        }

        @keyframes logo-sway {
            0%, 100% { transform: rotate(-2deg); }
            50% { transform: rotate(2deg); }
        }

        .logo-text {
            text-align: left;
        }

        .logo-text h1 {
            font-size: 1.25rem;
            font-weight: 800;
            color: #1e40af;
            line-height: 1.2;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .logo-text p {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .system-title {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .system-title h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.25rem;
        }

        .system-title p {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 500;
        }

        .badge {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 0.5rem;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.4);
        }

        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: alert-slide 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes alert-slide {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .alert-danger {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
        }

        .alert i {
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .input-group input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            font-size: 0.9375rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            background: #f9fafb;
            transition: all 0.3s ease;
            font-family: inherit;
            font-weight: 500;
        }

        .input-group input::placeholder {
            color: #9ca3af;
        }

        .input-group input:focus {
            outline: none;
            border-color: #3b82f6;
            background: white;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .input-group input:focus + i {
            color: #3b82f6;
        }

        .btn-login {
            width: 100%;
            padding: 1rem 1.5rem;
            font-size: 1rem;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
            background-size: 200% 200%;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.4);
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(59, 130, 246, 0.5);
            background-position: 100% 0;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .btn-login i {
            font-size: 1.125rem;
            transition: transform 0.3s;
        }

        .btn-login:hover i {
            transform: translateX(4px);
        }

        .footer-info {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .footer-info p {
            color: #6b7280;
            font-size: 0.8125rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .footer-info i {
            color: #10b981;
        }

        .version {
            font-size: 0.75rem;
            color: #9ca3af;
            margin-top: 0.5rem;
        }

        /* Loading state */
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-login.loading i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-card {
                padding: 2rem 1.5rem;
            }
            
            .logo-container {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .logo-text {
                text-align: center;
            }
            
            .logo-img {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="bg-pattern"></div>

    <!-- Floating particles -->
    <script>
        for (let i = 0; i < 20; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 15 + 's';
            particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
            document.body.appendChild(particle);
        }
    </script>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="logo-section">
                <div class="logo-container">
<img src="<?= base_url('logo-dpmptsp.png') ?>" alt="Logo DPMPTSP" class="logo-img">
                    <div class="logo-text">
                        <h1>DPMPTSP</h1>
                        <p>Kabupaten Tanah Bumbu</p>
                    </div>
                </div>
            </div>

            <div class="system-title">
                <h2><i class="fas fa-shield-alt" style="color: #3b82f6;"></i> Sistem Statistik</h2>
                <p>Dashboard Realisasi Investasi</p>
                <!-- <span class="badge">Terintegrasi</span> -->
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <div><strong>Gagal!</strong> <?= session()->getFlashdata('error') ?></div>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div><strong>Berhasil!</strong> <?= session()->getFlashdata('success') ?></div>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('auth/process-login') ?>" method="POST" id="loginForm">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="username" name="username"
                            required autofocus placeholder="Masukkan username"
                            value="<?= old('username') ?>">
                        <i class="fas fa-user"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" 
                            required placeholder="Masukkan password">
                        <i class="fas fa-lock"></i>
                    </div>
                </div>

                <button type="submit" class="btn-login" id="submitBtn">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Masuk Sistem</span>
                </button>
            </form>

            <div class="footer-info">
                <p>
                    <i class="fas fa-check-circle"></i>
                    Sistem Terintegrasi DPMPTSP
                </p>
                <div class="version">2026</div>
            </div>
        </div>
    </div>

    <script>
        // Form submit animation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            btn.classList.add('loading');
            btn.innerHTML = '<i class="fas fa-spinner"></i><span>Memproses...</span>';
        });
    </script>
</body>
</html>

