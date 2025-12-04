<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
        }
        .content {
            background: #f9f9f9;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Reset Password SST Application</h2>
        </div>

        <div class="content">
            <p>Halo,</p>
            <p>Kami menerima permintaan untuk mereset password akun Anda. Klik tombol di bawah untuk melanjutkan:</p>
            
            <div style="text-align: center;">
                <a href="<?= $resetLink ?>" class="button">Reset Password</a>
            </div>

            <p>Atau copy-paste link berikut di browser Anda:</p>
            <p style="word-break: break-all; background: white; padding: 10px; border-left: 3px solid #667eea;">
                <?= $resetLink ?>
            </p>

            <p><strong>Penting:</strong> Link ini akan kadaluarsa dalam <?= $expiryTime ?>. Jika Anda tidak meminta reset password, abaikan email ini.</p>
        </div>

        <div class="footer">
            <p>Email ini dikirim otomatis oleh SST Application. Jangan reply email ini.</p>
            <p>&copy; 2025 SST Application. Semua hak dilindungi.</p>
        </div>
    </div>
</body>
</html>
