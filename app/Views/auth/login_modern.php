<?php $this->extend('layouts/auth'); ?>

<?php $this->section('content'); ?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary-600 to-primary-900">

    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl p-8">

            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">DataInvest</h1>
                <p class="text-gray-600">Login menggunakan SSO</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="bg-red-100 p-3 rounded mb-4 text-red-600 text-sm text-center">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($ssoUrl)): ?>
                <button id="ssoLogin"
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg">
                    Login dengan SSO
                </button>
            <?php else: ?>
                <p class="text-red-500 text-center">SSO tidak tersedia</p>
            <?php endif; ?>

        </div>
    </div>
</div>

<script>
    document.getElementById('ssoLogin')?.addEventListener('click', function () {

        const url = '<?= $ssoUrl ?? '' ?>';

        const width = 500;
        const height = 600;
        const left = (screen.width / 2) - (width / 2);
        const top = (screen.height / 2) - (height / 2);

        // POPUP MODE
        window.open(
            url,
            'SSOLogin',
            `width=${width},height=${height},top=${top},left=${left}`
        );
    });

    // LISTENER (kalau popup sukses)
    window.addEventListener('message', function (event) {

        // optional security
        // if (event.origin !== "https://sso.devuna.web.id") return;

        if (event.data.success) {
            window.location.href = '/';
        } else {
            alert('Login gagal: ' + event.data.message);
        }
    });
</script>

<?php $this->endSection(); ?>