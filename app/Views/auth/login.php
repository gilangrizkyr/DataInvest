<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Statistik Terpadu DPMPTSP</title>
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
            background: #0a0a0f;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        /* Animated gradient background */
        .gradient-bg {
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #667eea 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            opacity: 0.9;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Floating orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.6;
            animation: float-orb 20s ease-in-out infinite;
        }

        .orb1 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.8), transparent);
            top: -10%;
            left: -10%;
            animation-delay: 0s;
        }

        .orb2 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(240, 147, 251, 0.8), transparent);
            bottom: -10%;
            right: -10%;
            animation-delay: 5s;
        }

        .orb3 {
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(79, 172, 254, 0.8), transparent);
            top: 50%;
            left: 50%;
            animation-delay: 10s;
        }

        @keyframes float-orb {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(50px, -80px) scale(1.1); }
            66% { transform: translate(-50px, 50px) scale(0.9); }
        }

        /* Particle system */
        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: white;
            border-radius: 50%;
            opacity: 0;
            animation: particle-float 10s linear infinite;
        }

        @keyframes particle-float {
            0% {
                transform: translateY(100vh) translateX(0) scale(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) translateX(100px) scale(1);
                opacity: 0;
            }
        }

        .login-wrapper {
            position: relative;
            width: 100%;
            max-width: 480px;
            z-index: 10;
            perspective: 1500px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(30px) saturate(180%);
            -webkit-backdrop-filter: blur(30px) saturate(180%);
            border-radius: 32px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 
                0 40px 80px rgba(0, 0, 0, 0.4),
                0 0 0 1px rgba(255, 255, 255, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.2),
                inset 0 -1px 0 rgba(0, 0, 0, 0.2);
            padding: 3rem;
            position: relative;
            transform-style: preserve-3d;
            transition: transform 0.3s ease;
            animation: cardEntrance 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes cardEntrance {
            from {
                opacity: 0;
                transform: translateY(50px) rotateX(-15deg) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) rotateX(0) scale(1);
            }
        }

        /* Glossy shine effect */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 50%;
            background: linear-gradient(180deg, rgba(255,255,255,0.2) 0%, transparent 100%);
            border-radius: 32px 32px 0 0;
            pointer-events: none;
        }

        /* Dynamic light effect */
        .light-effect {
            position: absolute;
            width: 200%;
            height: 200%;
            top: -50%;
            left: -50%;
            background: radial-gradient(circle at center, rgba(255,255,255,0.15) 0%, transparent 60%);
            pointer-events: none;
            transition: transform 0.2s ease;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
            z-index: 1;
        }

        .logo-container {
            position: relative;
            display: inline-block;
            margin-bottom: 1.5rem;
        }

        /* 3D rotating rings around logo */
        .ring {
            position: absolute;
            top: 50%;
            left: 50%;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform-style: preserve-3d;
            animation: rotate3d 10s linear infinite;
        }

        .ring1 {
            width: 140px;
            height: 140px;
            margin: -70px 0 0 -70px;
            animation-duration: 8s;
        }

        .ring2 {
            width: 160px;
            height: 160px;
            margin: -80px 0 0 -80px;
            animation-duration: 12s;
            animation-direction: reverse;
        }

        @keyframes rotate3d {
            from { transform: rotateY(0deg) rotateX(60deg); }
            to { transform: rotateY(360deg) rotateX(60deg); }
        }

        .logo-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            border-radius: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: 
                0 20px 60px rgba(102, 126, 234, 0.5),
                0 0 40px rgba(240, 147, 251, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
            position: relative;
            animation: logo-pulse 3s ease-in-out infinite;
            transform-style: preserve-3d;
        }

        @keyframes logo-pulse {
            0%, 100% {
                transform: scale(1) translateZ(0);
                box-shadow: 0 20px 60px rgba(102, 126, 234, 0.5), 0 0 40px rgba(240, 147, 251, 0.3);
            }
            50% {
                transform: scale(1.1) translateZ(20px);
                box-shadow: 0 30px 80px rgba(102, 126, 234, 0.7), 0 0 60px rgba(240, 147, 251, 0.5);
            }
        }

        .logo-icon::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 32px;
            background: linear-gradient(45deg, #667eea, #764ba2, #f093fb, #4facfe);
            background-size: 300% 300%;
            animation: gradient-rotate 3s ease infinite;
            filter: blur(10px);
            opacity: 0.7;
            z-index: -1;
        }

        @keyframes gradient-rotate {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .logo-icon i {
            font-size: 3rem;
            color: white;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));
            animation: icon-float 3s ease-in-out infinite;
        }

        @keyframes icon-float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .logo-section h1 {
            font-size: 1.75rem;
            font-weight: 800;
            background: linear-gradient(135deg, #ffffff 0%, #e0e7ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 20px rgba(255,255,255,0.3);
        }

        .logo-section p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9375rem;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: 16px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            animation: alert-slide 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            backdrop-filter: blur(10px);
        }

        @keyframes alert-slide {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .alert-danger {
            background: rgba(254, 226, 226, 0.15);
            border: 1px solid rgba(248, 113, 113, 0.3);
            color: #fecaca;
        }

        .alert-success {
            background: rgba(220, 252, 231, 0.15);
            border: 1px solid rgba(134, 239, 172, 0.3);
            color: #bbf7d0;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 0.5rem;
            letter-spacing: 0.3px;
        }

        .input-wrapper {
            position: relative;
            transform-style: preserve-3d;
        }

        .input-wrapper::before {
            content: '';
            position: absolute;
            inset: -2px;
            background: linear-gradient(135deg, #667eea, #764ba2, #f093fb);
            border-radius: 16px;
            opacity: 0;
            transition: opacity 0.3s;
            z-index: -1;
            filter: blur(8px);
        }

        .input-wrapper:focus-within::before {
            opacity: 0.6;
        }

        .input-wrapper i {
            position: absolute;
            left: 1.125rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.5);
            font-size: 1.125rem;
            transition: all 0.3s;
            z-index: 1;
        }

        .form-control {
            width: 100%;
            padding: 1rem 1.25rem 1rem 3.25rem;
            font-size: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.08);
            color: white;
            transition: all 0.3s ease;
            font-family: inherit;
            font-weight: 500;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .form-control:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.4);
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 
                0 0 0 4px rgba(102, 126, 234, 0.2),
                0 8px 32px rgba(102, 126, 234, 0.3);
            transform: translateY(-2px);
        }

        .form-control:focus + i {
            color: white;
            transform: translateY(-50%) scale(1.1);
        }

        .btn-login {
            width: 100%;
            padding: 1.125rem 1.5rem;
            font-size: 1.0625rem;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            background-size: 200% 200%;
            border: none;
            border-radius: 16px;
            cursor: pointer;
            font-family: inherit;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.625rem;
            box-shadow: 
                0 10px 40px rgba(102, 126, 234, 0.6),
                0 0 20px rgba(240, 147, 251, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
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
            transition: left 0.6s;
        }

        .btn-login::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 18px;
            background: linear-gradient(135deg, #667eea, #764ba2, #f093fb, #4facfe);
            background-size: 300% 300%;
            animation: gradient-border 3s ease infinite;
            filter: blur(12px);
            opacity: 0;
            z-index: -1;
            transition: opacity 0.3s;
        }

        .btn-login:hover::after {
            opacity: 0.8;
        }

        @keyframes gradient-border {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .btn-login:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 
                0 20px 60px rgba(102, 126, 234, 0.8),
                0 0 40px rgba(240, 147, 251, 0.6),
                inset 0 1px 0 rgba(255, 255, 255, 0.4);
            background-position: 100% 0;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:active {
            transform: translateY(-2px) scale(1);
        }

        .btn-login i {
            font-size: 1.25rem;
            transition: transform 0.3s;
        }

        .btn-login:hover i {
            transform: translateX(4px);
        }

        .footer-text {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-text p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .footer-text i {
            color: #10b981;
            animation: gear-spin 3s linear infinite;
        }

        @keyframes gear-spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Loading state */
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-login.loading i {
            animation: spin-fast 0.8s linear infinite;
        }

        @keyframes spin-fast {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-card {
                padding: 2rem 1.5rem;
            }
            
            .logo-icon {
                width: 80px;
                height: 80px;
            }
            
            .logo-icon i {
                font-size: 2.5rem;
            }
            
            .ring1 {
                width: 120px;
                height: 120px;
                margin: -60px 0 0 -60px;
            }

            .ring2 {
                width: 140px;
                height: 140px;
                margin: -70px 0 0 -70px;
            }
        }

        /* Extra glow on hover */
        .login-card:hover {
            box-shadow: 
                0 50px 100px rgba(0, 0, 0, 0.5),
                0 0 80px rgba(102, 126, 234, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body>
    <div class="gradient-bg"></div>
    
    <!-- Floating orbs -->
    <div class="orb orb1"></div>
    <div class="orb orb2"></div>
    <div class="orb orb3"></div>

    <!-- Particle system -->
    <script>
        for (let i = 0; i < 30; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 10 + 's';
            particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
            document.body.appendChild(particle);
        }
    </script>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="light-effect"></div>
            
            <div class="logo-section">
                <div class="logo-container">
                    <div class="ring ring1"></div>
                    <div class="ring ring2"></div>
                    <div class="logo-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <h1>Realisasi Investasi</h1>
                <p>DPMPTSP Kabupaten Tanah Bumbu</p>
            </div>

            <form action="#" method="POST" id="loginForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <input type="text" class="form-control" id="username" name="username"
                            required autofocus placeholder="Masukkan username Anda">
                        <i class="fas fa-user"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" class="form-control" id="password" name="password" 
                            required placeholder="Masukkan password Anda">
                        <i class="fas fa-lock"></i>
                    </div>
                </div>

                <button type="submit" class="btn-login" id="submitBtn">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Masuk Dashboard</span>
                </button>
            </form>

            <div class="footer-text">
                <p>
                    <i class="fas fa-cogs"></i>
                    Sistem terintegrasi untuk kemudahan pengelolaan
                </p>
            </div>
        </div>
    </div>

    <script>
        // Form submit animation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('submitBtn');
            btn.classList.add('loading');
            btn.innerHTML = '<i class="fas fa-spinner"></i><span>Memproses...</span>';
            
            // Simulate processing
            setTimeout(() => {
                alert('Login berhasil! (Demo)');
                btn.classList.remove('loading');
                btn.innerHTML = '<i class="fas fa-sign-in-alt"></i><span>Masuk Dashboard</span>';
            }, 2000);
        });

        // Advanced 3D tilt effect
        const card = document.querySelector('.login-card');
        const wrapper = document.querySelector('.login-wrapper');
        const lightEffect = document.querySelector('.light-effect');

        wrapper.addEventListener('mousemove', function(e) {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const rotateX = (y - centerY) / 20;
            const rotateY = (centerX - x) / 20;
            
            card.style.transform = `
                perspective(1500px) 
                rotateX(${rotateX}deg) 
                rotateY(${rotateY}deg) 
                translateZ(10px)
                scale3d(1.02, 1.02, 1.02)
            `;
            
            // Move light effect
            lightEffect.style.transform = `translate(${(x - centerX) / 5}px, ${(y - centerY) / 5}px)`;
        });

        wrapper.addEventListener('mouseleave', function() {
            card.style.transform = 'perspective(1500px) rotateX(0) rotateY(0) translateZ(0) scale3d(1, 1, 1)';
            lightEffect.style.transform = 'translate(0, 0)';
        });

        // Input focus animations
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateZ(10px)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateZ(0)';
            });
        });
    </script>
</body>
</html>
