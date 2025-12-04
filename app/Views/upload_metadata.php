<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Metadata Upload - Sistem Statistik Terpadu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body class="min-h-screen gradient-bg">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="container mx-auto max-w-2xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full mb-4">
                    <i class="fas fa-file-upload text-2xl text-white"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2"><?php echo isset($isEdit) && $isEdit ? 'Edit Metadata Upload' : 'Input Metadata Upload'; ?></h1>
                <p class="text-blue-100"><?php echo isset($isEdit) && $isEdit ? 'Perbarui informasi metadata upload' : 'Lengkapi informasi metadata sebelum memproses data'; ?></p>
            </div>

            <!-- File Info Card -->
            <div class="glass-card shadow-2xl rounded-xl p-6 mb-6">
                <div class="flex items-center mb-4">
                    <i class="fas fa-file-excel text-green-600 mr-3 text-xl"></i>
                    <h2 class="text-xl font-semibold text-gray-800">Informasi File</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm text-gray-600 mb-1">Nama File</div>
                        <div class="font-medium text-gray-800"><?php echo htmlspecialchars($upload['original_filename']); ?></div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm text-gray-600 mb-1">Ukuran File</div>
                        <div class="font-medium text-gray-800"><?php echo number_format($upload['file_size'] / 1024, 1); ?> KB</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm text-gray-600 mb-1">Tanggal Upload</div>
                        <div class="font-medium text-gray-800"><?php echo date('d/m/Y H:i', strtotime($upload['upload_date'])); ?></div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm text-gray-600 mb-1">Status</div>
                        <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php echo isset($isEdit) && $isEdit ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'; ?>">
                            <i class="fas <?php echo isset($isEdit) && $isEdit ? 'fa-edit' : 'fa-clock'; ?> mr-2"></i><?php echo isset($isEdit) && $isEdit ? 'Sedang Diedit' : 'Menunggu Metadata'; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metadata Form -->
            <form action="<?php echo isset($isEdit) && $isEdit ? '/dashboard/updateMetadata' : '/dashboard/processMetadata'; ?>" method="post" class="glass-card shadow-2xl rounded-xl p-6">
                <input type="hidden" name="upload_id" value="<?php echo $upload['id']; ?>">

                <div class="flex items-center mb-6">
                    <i class="fas fa-tags text-blue-600 mr-3 text-xl"></i>
                    <h2 class="text-xl font-semibold text-gray-800">Informasi Metadata</h2>
                </div>

                <div class="space-y-6">
                    <!-- Upload Name -->
                    <div>
                        <label for="upload_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-heading mr-2 text-blue-600"></i>Nama Upload
                        </label>
                        <input type="text" id="upload_name" name="upload_name" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            placeholder="Contoh: Data Investasi Q1 2024"
                            value="<?php echo isset($upload['upload_name']) ? htmlspecialchars($upload['upload_name']) : ''; ?>">
                        <p class="text-sm text-gray-500 mt-1">Berikan nama yang deskriptif untuk upload ini</p>
                    </div>

                    <!-- Quarter -->
                    <div>
                        <label for="quarter" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-2 text-green-600"></i>Quarter
                        </label>
                        <select id="quarter" name="quarter" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">Pilih Quarter</option>
                            <option value="1" <?php echo (isset($upload['quarter']) && $upload['quarter'] == '1') ? 'selected' : ''; ?>>Q1 (Januari - Maret)</option>
                            <option value="2" <?php echo (isset($upload['quarter']) && $upload['quarter'] == '2') ? 'selected' : ''; ?>>Q2 (April - Juni)</option>
                            <option value="3" <?php echo (isset($upload['quarter']) && $upload['quarter'] == '3') ? 'selected' : ''; ?>>Q3 (Juli - September)</option>
                            <option value="4" <?php echo (isset($upload['quarter']) && $upload['quarter'] == '4') ? 'selected' : ''; ?>>Q4 (Oktober - Desember)</option>
                        </select>
                    </div>

                    <!-- Year -->
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2 text-purple-600"></i>Tahun
                        </label>
                        <select id="year" name="year" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">Pilih Tahun</option>
                            <?php
                            $currentYear = date('Y');
                            for ($year = $currentYear + 1; $year >= $currentYear - 5; $year--) {
                                $selected = (isset($upload['year']) && $upload['year'] == $year) ? 'selected' : '';
                                echo "<option value=\"$year\" $selected>$year</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- USD Value -->
                    <div>
                        <label for="usd_value" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-dollar-sign mr-2 text-yellow-600"></i>Nilai USD (Rate Konversi)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" id="usd_value" name="usd_value" required step="0.01" min="1"
                                class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                placeholder="16500.00"
                                value="<?php echo isset($upload['usd_value']) ? htmlspecialchars($upload['usd_value']) : '16653'; ?>">
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Kurs USD ke IDR yang digunakan untuk konversi</p>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mt-8">
                    <button type="submit"
                        class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3 px-6 rounded-lg transition-all transform hover:scale-105 shadow-lg">
                        <i class="fas <?php echo isset($isEdit) && $isEdit ? 'fa-save' : 'fa-play'; ?> mr-2"></i><?php echo isset($isEdit) && $isEdit ? 'Update Metadata' : 'Proses Data'; ?>
                    </button>
                    <a href="/dashboard"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg transition-all text-center shadow-lg">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                </div>
            </form>

           <!-- Flash Messages - Enhanced untuk menampilkan HTML -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="mt-6 glass-card shadow-xl rounded-xl p-4 border-l-4 border-green-500 animate-fade-in">
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                        <div class="text-green-800 flex-1"><?php echo session()->getFlashdata('success'); ?></div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="mt-6 glass-card shadow-xl rounded-xl p-4 border-l-4 border-red-500 animate-fade-in">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-3 mt-1 text-xl"></i>
                        <div class="flex-1">
                            <h3 class="text-red-800 font-semibold mb-2">⚠️ Data Duplikat Terdeteksi!</h3>
                            <div class="text-red-700 text-sm space-y-1">
                                <?php echo session()->getFlashdata('error'); ?>
                            </div>
                            <div class="mt-3 p-3 bg-red-50 rounded-lg border border-red-200">
                                <p class="text-xs text-red-600">
                                    <strong>💡 Saran:</strong> Gunakan quarter/tahun yang berbeda, atau hapus upload sebelumnya jika ingin menggantinya.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

    <script>
        // Auto-format USD value input
        document.getElementById('usd_value').addEventListener('input', function(e) {
            let value = e.target.value;
            // Remove any non-numeric characters except decimal point
            value = value.replace(/[^0-9.]/g, '');
            // Ensure only one decimal point
            const parts = value.split('.');
            if (parts.length > 2) {
                value = parts[0] + '.' + parts.slice(1).join('');
            }
            e.target.value = value;
        });

        // Form validation with duplicate warning
        document.querySelector('form').addEventListener('submit', function(e) {
            const uploadName = document.getElementById('upload_name').value.trim();
            const quarter = document.getElementById('quarter').value;
            const year = document.getElementById('year').value;
            const usdValue = document.getElementById('usd_value').value;

            if (!uploadName || !quarter || !year || !usdValue) {
                e.preventDefault();
                alert('❌ Semua field harus diisi!');
                return false;
            }

            if (parseFloat(usdValue) <= 0) {
                e.preventDefault();
                alert('❌ Nilai USD harus lebih besar dari 0!');
                return false;
            }

            // Show loading state
            const submitBtn = document.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            submitBtn.disabled = true;
        });

        // Add animation for flash messages
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in {
                animation: fadeIn 0.5s ease-out;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>

</html>