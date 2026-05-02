<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?? 'DataInvest - DPMPTSP Tanah Bumbu' ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('favicon_tanbu.png') ?>">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon_tanbu.png') ?>">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        accent: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                        }
                    },
                    fontFamily: {
                        sans: ['DM Sans', 'system-ui', 'sans-serif'],
                        display: ['Playfair Display', 'Georgia', 'serif'],
                    },
                    boxShadow: {
                        'sm': '0 1px 3px rgba(0, 0, 0, 0.06)',
                        'md': '0 4px 6px -1px rgba(0, 0, 0, 0.07)',
                        'lg': '0 10px 15px -3px rgba(0, 0, 0, 0.08)',
                        'xl': '0 20px 25px -5px rgba(0, 0, 0, 0.08)',
                        'card': '0 2px 12px rgba(30, 41, 59, 0.08)',
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>">
    
    <!-- Additional Styles -->
    <?php if(isset($additional_css)): ?>
        <?php foreach($additional_css as $css): ?>
            <link rel="stylesheet" href="<?= base_url($css) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <style>
        body {
            color: #374151;
            background-color: #f8fafc;
        }
    </style>
</head>
<body class="bg-slate-50">
    <!-- Navigation -->
    <?= view('components/navbar') ?>
    
    <!-- Main Content -->
    <main class="min-h-screen">
        <!-- Flash Messages -->
        <?= view('components/alerts') ?>
        
        <!-- Page Content -->
        <div class="container mx-auto px-4 pb-8 pt-4 lg:pt-6">
            <?= $this->renderSection('content') ?>
        </div>
    </main>
    
    <!-- Footer -->
    <?= view('components/footer') ?>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= base_url('assets/js/global.js') ?>"></script>
    
    <!-- Additional Scripts -->
    <?php if(isset($additional_scripts)): ?>
        <?php foreach($additional_scripts as $script): ?>
            <script src="<?= base_url($script) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
