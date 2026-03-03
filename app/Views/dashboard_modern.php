<?php $this->extend('layouts/app'); ?>

<?php $this->section('content'); ?>

<!-- Section Header -->
<?= view('components/section_header', [
    'title' => 'Dashboard Statistik',
    'description' => 'Management data investasi dan statistik terpadu',
    'icon' => 'fas fa-chart-line'
]) ?>

<!-- Professional Analytics Libraries -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<!-- Custom Styles for Clean UI -->
<style>
    :root {
        --slate-50: #f8fafc;
        --slate-100: #f1f5f9;
        --slate-200: #e2e8f0;
        --slate-300: #cbd5e1;
        --slate-400: #94a3b8;
        --slate-500: #64748b;
        --slate-600: #475569;
        --slate-700: #334155;
        --slate-800: #1e293b;
        --slate-900: #0f172a;

        --accent-blue: #2563eb;
        --accent-green: #10b981;
        --accent-amber: #f59e0b;
        --accent-red: #ef4444;
        --accent-sky: #0ea5e9;
    }

    .card {
        background: #ffffff !important;
        border: 1px solid var(--slate-200) !important;
        box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.05), 0 2px 4px -1px rgba(15, 23, 42, 0.02) !important;
        border-radius: 16px !important;
        /* Standardized radius */
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }

    .card:hover {
        box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.08), 0 10px 10px -5px rgba(15, 23, 42, 0.03) !important;
        transform: translateY(-4px);
    }

    /* Accent Shadows */
    .shadow-blue-glow {
        box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.1), 0 4px 6px -2px rgba(37, 99, 235, 0.05) !important;
    }

    .shadow-green-glow {
        box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.1), 0 4px 6px -2px rgba(16, 185, 129, 0.05) !important;
    }

    .shadow-sky-glow {
        box-shadow: 0 10px 15px -3px rgba(14, 165, 233, 0.1), 0 4px 6px -2px rgba(14, 165, 233, 0.05) !important;
    }

    .shadow-amber-glow {
        box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.1), 0 4px 6px -2px rgba(245, 158, 11, 0.05) !important;
    }

    .kpi-icon {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        font-size: 1.4rem;
    }

    .table-container {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid var(--slate-200);
    }

    .table thead th {
        background-color: var(--slate-50);
        border-bottom: 2px solid var(--slate-100);
        text-transform: uppercase;
        font-size: 0.65rem;
        letter-spacing: 0.1em;
        font-weight: 900;
        color: var(--slate-500);
        padding: 1rem 1.5rem;
    }

    /* Professional Transitions */
    .glass-effect {
        backdrop-filter: blur(12px);
        background: rgba(255, 255, 255, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 5px;
        height: 5px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: var(--slate-50);
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: var(--slate-200);
        border-radius: 20px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: var(--slate-300);
    }
</style>

<!-- Detailed KPI Section -->
<div class="mb-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-black text-slate-900 flex items-center">
            <span class="w-2 h-8 bg-blue-600 rounded-full mr-4 shadow-sm"></span>
            Ringkasan Realisasi Investasi
        </h2>
        <div class="flex items-center gap-3 bg-white p-2.5 rounded-xl shadow-sm border border-slate-200">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-2">Mata Uang</span>
            <select id="currency-toggle"
                class="form-select text-sm font-bold border-0 focus:ring-0 cursor-pointer bg-slate-50 rounded-lg text-slate-700">
                <option value="IDR" <?= ($data['filters']['currency'] ?? 'IDR') === 'IDR' ? 'selected' : '' ?>>IDR (Rp)
                </option>
                <option value="USD" <?= ($data['filters']['currency'] ?? 'IDR') === 'USD' ? 'selected' : '' ?>>USD ($)
                </option>
            </select>
        </div>
    </div>
    <?php
    $currency = $data['filters']['currency'] ?? 'IDR';
    $currencySymbol = $currency === 'USD' ? '$' : 'Rp';
    $currencyLabel = $currency === 'USD' ? 'USD ($)' : 'IDR (Rp)';
    ?>

    <!-- Lampu Sorot Data (Data Spotlight) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <?php
        $top5 = $data['ranking_by_district'] ?? [];
        $winner = !empty($top5) ? $top5[0] : null;
        ?>
        <div
            class="md:col-span-3 bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-950 rounded-2xl p-0.5 shadow-xl shadow-blue-900/10 overflow-hidden">
            <div
                class="bg-slate-900/40 backdrop-blur-xl rounded-[15px] p-7 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-6">
                    <div
                        class="w-16 h-16 bg-blue-500/20 rounded-2xl flex items-center justify-center text-blue-400 text-3xl shadow-inner border border-blue-500/30">
                        <i class="fas fa-magic"></i>
                    </div>
                    <div>
                        <h4 class="text-white font-black text-xl tracking-tight">Lampu Sorot Data</h4>
                        <p class="text-slate-300 text-sm font-medium opacity-80">Insight cerdas berdasarkan data
                            investasi terbaru.</p>
                    </div>
                </div>
                <div class="flex-1 md:max-w-2xl">
                    <div class="bg-white/5 backdrop-blur-sm rounded-xl p-5 border border-white/10 shadow-2xl">
                        <p class="text-slate-100 text-base font-bold leading-relaxed">
                            <?php if ($winner): ?>
                                <i class="fas fa-star text-amber-400 mr-3 animate-pulse"></i>
                                Kecamatan <span
                                    class="text-blue-400 underline decoration-blue-500/50 underline-offset-4 font-black"><?= esc($winner['kecamatan']) ?></span>
                                unggul sebagai kontributor utama dengan total <span
                                    class="text-white font-black px-2 py-0.5 bg-blue-600 rounded-lg"><?= number_format($winner['jumlah_proyek']) ?>
                                    proyek</span> aktif.
                            <?php else: ?>
                                <i class="fas fa-info-circle text-slate-400 mr-2"></i>
                                Belum ada data peringkat yang cukup untuk menampilkan insight.
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
        <!-- Total Investasi -->
        <div class="card p-7 border-t-4 border-t-blue-600 shadow-blue-glow">
            <div class="flex justify-between items-start mb-5">
                <div>
                    <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest leading-none mb-2">Total
                        Realisasi</p>
                    <h3 class="text-2xl font-black text-slate-900 tabular-nums">
                        <span class="text-blue-600 font-bold"><?= $currencySymbol ?></span>
                        <?= number_format(($data['total_investment']['PMA'] ?? 0) + ($data['total_investment']['PMDN'] ?? 0), 0, ',', '.') ?>
                    </h3>
                </div>
                <div class="kpi-icon bg-blue-50 text-blue-600 shadow-sm border border-blue-100">
                    <i class="fas fa-money-bill-trend-up"></i>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                <div class="flex flex-col">
                    <span class="text-[9px] text-slate-400 font-black uppercase tracking-tighter mb-0.5">PMA</span>
                    <span class="text-sm font-black text-blue-600 tabular-nums"><?= $currencySymbol ?>
                        <?= number_format($data['total_investment']['PMA'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div class="flex flex-col text-right">
                    <span class="text-[9px] text-slate-400 font-black uppercase tracking-tighter mb-0.5">PMDN</span>
                    <span class="text-sm font-black text-emerald-600 tabular-nums"><?= $currencySymbol ?>
                        <?= number_format($data['total_investment']['PMDN'] ?? 0, 0, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <!-- Tambahan Investasi -->
        <div class="card p-7 border-t-4 border-t-sky-500 shadow-sky-glow">
            <div class="flex justify-between items-start mb-5">
                <div>
                    <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest leading-none mb-2">
                        Tambahan Investasi</p>
                    <h3 class="text-2xl font-black text-slate-900 tabular-nums">
                        <span class="text-sky-600 font-bold"><?= $currencySymbol ?></span>
                        <?= number_format(($data['total_additional_investment']['PMA'] ?? 0) + ($data['total_additional_investment']['PMDN'] ?? 0), 0, ',', '.') ?>
                    </h3>
                </div>
                <div class="kpi-icon bg-sky-50 text-sky-600 shadow-sm border border-sky-100">
                    <i class="fas fa-plus-circle"></i>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                <div class="flex flex-col">
                    <span class="text-[9px] text-slate-400 font-black uppercase tracking-tighter mb-0.5">PMA</span>
                    <span class="text-sm font-black text-blue-600 tabular-nums"><?= $currencySymbol ?>
                        <?= number_format($data['total_additional_investment']['PMA'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div class="flex flex-col text-right">
                    <span class="text-[9px] text-slate-400 font-black uppercase tracking-tighter mb-0.5">PMDN</span>
                    <span class="text-sm font-black text-emerald-600 tabular-nums"><?= $currencySymbol ?>
                        <?= number_format($data['total_additional_investment']['PMDN'] ?? 0, 0, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <!-- Total Proyek -->
        <div class="card p-7 border-t-4 border-t-green-500 shadow-green-glow">
            <div class="flex justify-between items-start mb-5">
                <div>
                    <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest leading-none mb-2">Unit
                        Proyek</p>
                    <h3 class="text-2xl font-black text-slate-900 tabular-nums">
                        <?= number_format(($data['total_projects']['PMA'] ?? 0) + ($data['total_projects']['PMDN'] ?? 0)) ?>
                        <span class="text-sm font-black text-slate-300 ml-1">UNIT</span>
                    </h3>
                </div>
                <div class="kpi-icon bg-green-50 text-green-600 shadow-sm border border-green-100">
                    <i class="fas fa-diagram-project"></i>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                <div class="flex flex-col">
                    <span class="text-[9px] text-slate-400 font-black uppercase tracking-tighter mb-0.5">PMA</span>
                    <span
                        class="text-sm font-black text-blue-600 tabular-nums"><?= number_format($data['total_projects']['PMA'] ?? 0) ?></span>
                </div>
                <div class="flex flex-col text-right">
                    <span class="text-[9px] text-slate-400 font-black uppercase tracking-tighter mb-0.5">PMDN</span>
                    <span
                        class="text-sm font-black text-emerald-600 tabular-nums"><?= number_format($data['total_projects']['PMDN'] ?? 0) ?></span>
                </div>
            </div>
        </div>

        <!-- Tenaga Kerja (TKI) -->
        <div class="card p-7 border-t-4 border-t-sky-500 shadow-sky-glow">
            <div class="flex justify-between items-start mb-5">
                <div>
                    <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest leading-none mb-2">
                        Penyerapan TKI</p>
                    <h3 class="text-2xl font-black text-slate-900 tabular-nums">
                        <?= number_format(($data['workforce']['PMA']['TKI'] ?? 0) + ($data['workforce']['PMDN']['TKI'] ?? 0)) ?>
                        <span class="text-sm font-black text-slate-300 ml-1">JIWA</span>
                    </h3>
                </div>
                <div class="kpi-icon bg-sky-50 text-sky-600 shadow-sm border border-sky-100">
                    <i class="fas fa-users-viewfinder"></i>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                <div class="flex flex-col">
                    <span class="text-[9px] text-slate-400 font-black uppercase tracking-tighter mb-0.5">PMA</span>
                    <span
                        class="text-sm font-black text-blue-600 tabular-nums"><?= number_format($data['workforce']['PMA']['TKI'] ?? 0) ?></span>
                </div>
                <div class="flex flex-col text-right">
                    <span class="text-[9px] text-slate-400 font-black uppercase tracking-tighter mb-0.5">PMDN</span>
                    <span
                        class="text-sm font-black text-emerald-600 tabular-nums"><?= number_format($data['workforce']['PMDN']['TKI'] ?? 0) ?></span>
                </div>
            </div>
        </div>

        <!-- Tenaga Kerja (TKA) -->
        <div class="card p-7 border-t-4 border-t-amber-500 shadow-amber-glow">
            <div class="flex justify-between items-start mb-5">
                <div>
                    <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest leading-none mb-2">Tenaga
                        Kerja Asing</p>
                    <h3 class="text-2xl font-black text-slate-900 tabular-nums">
                        <?= number_format(($data['workforce']['PMA']['TKA'] ?? 0) + ($data['workforce']['PMDN']['TKA'] ?? 0)) ?>
                        <span class="text-sm font-black text-slate-300 ml-1">JIWA</span>
                    </h3>
                </div>
                <div class="kpi-icon bg-amber-50 text-amber-600 shadow-sm border border-amber-100">
                    <i class="fas fa-passport"></i>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                <div class="flex flex-col">
                    <span class="text-[9px] text-slate-400 font-black uppercase tracking-tighter mb-0.5">PMA</span>
                    <span
                        class="text-sm font-black text-blue-600 tabular-nums"><?= number_format($data['workforce']['PMA']['TKA'] ?? 0) ?></span>
                </div>
                <div class="flex flex-col text-right">
                    <span class="text-[9px] text-slate-400 font-black uppercase tracking-tighter mb-0.5">PMDN</span>
                    <span
                        class="text-sm font-black text-emerald-600 tabular-nums"><?= number_format($data['workforce']['PMDN']['TKA'] ?? 0) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sebaran Proyek per Kecamatan Row -->
<div class="card p-8 mb-8">
    <div class="flex items-center justify-between mb-8">
        <h3 class="text-xl font-black text-slate-800 flex items-center tracking-tight">
            <span class="w-1.5 h-6 bg-green-500 rounded-full mr-4"></span>
            Sebaran Proyek per Kecamatan
        </h3>
        <div class="flex items-center gap-3">
            <span
                class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-3 py-1 bg-slate-50 rounded-full border border-slate-100">Statistik
                Geografis</span>
        </div>
    </div>
    <div class="flex flex-col lg:flex-row gap-10">
        <!-- Chart -->
        <div class="flex-1 min-h-[400px] relative bg-slate-50/30 rounded-2xl p-4 border border-slate-100/50">
            <canvas id="districtChart"></canvas>
        </div>
        <!-- Ranking List Summary -->
        <div class="lg:w-96 bg-slate-50 rounded-2xl p-6 border border-slate-100 shadow-inner">
            <h4 class="text-xs font-black text-slate-500 mb-6 flex items-center uppercase tracking-widest">
                <i class="fas fa-trophy mr-3 text-amber-500"></i>
                Top 5 Kontributor Proyek
            </h4>
            <div class="space-y-5">
                <?php
                $topDistricts = $data['ranking_by_district'] ?? [];
                $top5 = array_slice($topDistricts, 0, 5);
                foreach ($top5 as $idx => $district):
                    ?>
                    <div class="flex items-center justify-between group">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 rounded-xl bg-white shadow-sm border border-slate-200 flex items-center justify-center text-sm font-black mr-4 text-slate-700 group-hover:border-blue-500 group-hover:text-blue-600 group-hover:shadow-blue-100 transition-all duration-300">
                                <?= $idx + 1 ?>
                            </div>
                            <span
                                class="text-sm font-bold text-slate-700 group-hover:text-slate-900 transition-colors"><?= esc($district['kecamatan']) ?></span>
                        </div>
                        <div class="flex flex-col items-end">
                            <span
                                class="text-sm font-black text-slate-800"><?= number_format($district['jumlah_proyek']) ?></span>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Proyek</span>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($top5)): ?>
                    <div class="py-16 text-center">
                        <i class="fas fa-ghost text-slate-200 text-3xl mb-3"></i>
                        <p class="text-xs font-bold text-slate-400 uppercase">Data Kosong</p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="mt-8 pt-6 border-t border-slate-200/60">
                <div class="flex items-center justify-between bg-white/50 p-3 rounded-xl border border-slate-200/50">
                    <span class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Total Cakupan</span>
                    <span
                        class="text-xs font-black text-slate-600 px-2 py-0.5 bg-slate-100 rounded-lg"><?= count($topDistricts) ?>
                        Kecamatan</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tenaga Kerja per Kecamatan Row -->
<div class="card p-8 mb-8">
    <div class="flex items-center justify-between mb-8">
        <h3 class="text-xl font-black text-slate-800 flex items-center tracking-tight">
            <span class="w-1.5 h-6 bg-amber-500 rounded-full mr-4"></span>
            Tenaga Kerja (TKI & TKA) per Kecamatan
        </h3>
        <div class="flex items-center gap-3">
            <select onchange="switchChartType('workforceChart', this.value)"
                class="text-[10px] font-black border-slate-300 rounded-lg py-1 px-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-100 text-slate-800 shadow-sm cursor-pointer outline-none">
                <option value="bar">Grouped Bar</option>
                <option value="horizontalBar">Horizontal Bar</option>
            </select>
            <span
                class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-3 py-1 bg-slate-50 rounded-full border border-slate-100">SDM
                Investasi</span>
        </div>
    </div>
    <div class="h-[450px] relative bg-slate-50/30 rounded-2xl p-6 border border-slate-100/50">
        <canvas id="workforceChart"></canvas>
    </div>
</div>

<!-- Secondary Charts Row -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">
    <!-- Chart PMA vs PMDN -->
    <div class="card p-7">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-lg font-black text-slate-800 flex items-center tracking-tight">
                <span class="w-1.5 h-5 bg-blue-600 rounded-full mr-3"></span>
                Rasio Investasi PMA vs PMDN
            </h3>
            <select onchange="switchChartType('ratioChart', this.value)"
                class="text-[10px] font-black border-slate-300 rounded-lg py-1 px-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-100 text-slate-800 shadow-sm cursor-pointer outline-none">
                <option value="doughnut">Doughnut</option>
                <option value="pie">Pie</option>
            </select>
        </div>
        <div class="h-64 relative">
            <canvas id="ratioChart"></canvas>
        </div>
        <div class="mt-8 flex justify-center gap-12 border-t border-slate-50 pt-6">
            <?php
            $totalInv = ($data['total_additional_investment']['PMA'] ?? 0) + ($data['total_additional_investment']['PMDN'] ?? 0);
            $pmaRatio = $totalInv > 0 ? (($data['total_additional_investment']['PMA'] ?? 0) / $totalInv) * 100 : 0;
            $pmdnRatio = $totalInv > 0 ? (($data['total_additional_investment']['PMDN'] ?? 0) / $totalInv) * 100 : 0;
            ?>
            <div class="flex flex-col items-center">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">PMA</span>
                <span class="text-xl font-black text-blue-600 tabular-nums"><?= number_format($pmaRatio, 1) ?>%</span>
            </div>
            <div class="flex flex-col items-center">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">PMDN</span>
                <span class="text-xl font-black text-green-600 tabular-nums"><?= number_format($pmdnRatio, 1) ?>%</span>
            </div>
        </div>
    </div>

    <!-- Chart Investasi per Lokasi -->
    <div class="card p-7">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-lg font-black text-slate-800 flex items-center tracking-tight">
                <span class="w-1.5 h-5 bg-amber-500 rounded-full mr-3"></span>
                Top 10 Realisasi per Kecamatan (<?= $currency ?>)
            </h3>
            <select onchange="switchChartType('locationChart', this.value)"
                class="text-[10px] font-black border-slate-300 rounded-lg py-1 px-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-100 text-slate-800 shadow-sm cursor-pointer outline-none">
                <option value="horizontalBar">Horizontal Bar</option>
                <option value="bar">Vertical Bar</option>
                <option value="line">Line Chart</option>
            </select>
        </div>
        <div class="h-80 relative bg-slate-50/30 rounded-2xl p-4 border border-slate-100/50">
            <canvas id="locationChart"></canvas>
        </div>
    </div>
</div>

<!-- Tertiary Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Chart Analisis Sektor -->
    <div class="card p-7">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-lg font-black text-slate-800 flex items-center tracking-tight">
                <span class="w-1.5 h-5 bg-sky-500 rounded-full mr-3"></span>
                Analisis Sektor Proyek
            </h3>
            <select onchange="switchChartType('sectorChart', this.value)"
                class="text-[10px] font-black border-slate-300 rounded-lg py-1 px-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-100 text-slate-800 shadow-sm cursor-pointer outline-none">
                <option value="polarArea">Polar Area</option>
                <option value="pie">Pie</option>
                <option value="doughnut">Doughnut</option>
                <option value="horizontalBar">Horizontal Bar</option>
            </select>
        </div>
        <div class="h-80 relative bg-slate-50/30 rounded-2xl p-4 border border-slate-100/50">
            <canvas id="sectorChart"></canvas>
        </div>
    </div>

    <!-- Chart Proyek per Negara -->
    <div class="card p-7">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-lg font-black text-slate-800 flex items-center tracking-tight">
                <span class="w-1.5 h-5 bg-emerald-500 rounded-full mr-3"></span>
                Proyek per Negara (PMA)
            </h3>
            <select onchange="switchChartType('countryChart', this.value)"
                class="text-[10px] font-black border-slate-300 rounded-lg py-1 px-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-100 text-slate-800 shadow-sm cursor-pointer outline-none">
                <option value="pie">Pie</option>
                <option value="doughnut">Doughnut</option>
                <option value="polarArea">Polar Area</option>
            </select>
        </div>
        <div class="h-80 relative bg-slate-50/30 rounded-2xl p-4 border border-slate-100/50">
            <canvas id="countryChart"></canvas>
        </div>
    </div>
</div>

<!-- Quarterly Trend Section (Full Width) -->
<div class="card p-8 mb-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div class="flex items-center gap-6">
            <h3 class="text-xl font-black text-slate-800 flex items-center tracking-tight">
                <span class="w-1.5 h-6 bg-indigo-500 rounded-full mr-4"></span>
                Tren Investasi Kuartalan
            </h3>
            <select onchange="switchChartType('quarterlyChart', this.value)"
                class="text-[10px] font-black border-slate-300 rounded-lg py-1 px-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-100 text-slate-800 shadow-sm cursor-pointer outline-none">
                <option value="line">Line Chart</option>
                <option value="bar">Bar Chart</option>
            </select>
        </div>
        <div class="flex items-center gap-3 bg-slate-50 p-2 rounded-xl border border-slate-200 shadow-sm">
            <span
                class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-3 border-r border-slate-200">Filter
                Tahun</span>
            <select id="quarterly-year-filter"
                class="form-select text-xs font-black border-0 bg-transparent focus:ring-0 cursor-pointer text-slate-700 min-w-[120px]">
                <option value="all">Semua Tahun</option>
                <?php foreach (array_keys($data['charts']['quarterly_additional_investment_all_years'] ?? []) as $year): ?>
                    <option value="<?= $year ?>" <?= ($data['filters']['quarterly_year'] ?? 'all') == $year ? 'selected' : '' ?>><?= $year ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="h-96 relative bg-slate-50/20 rounded-2xl p-6 border border-slate-100/50">
        <canvas id="quarterlyChart"></canvas>
    </div>
</div>

<!-- Percentage Contribution Tables -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Kontribusi PMA -->
    <div class="card border-0 shadow-lg">
        <div class="p-6 border-b border-slate-100 bg-white">
            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center">
                <span class="w-1.5 h-4 bg-blue-600 rounded-full mr-3"></span>
                Kontribusi PMA per Kecamatan
            </h3>
        </div>
        <div class="overflow-x-auto max-h-96 custom-scrollbar">
            <table class="w-full table-auto border-separate border-spacing-0">
                <thead class="bg-slate-50/80 backdrop-blur-md sticky top-0 z-10">
                    <tr>
                        <th
                            class="text-left py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                            Kecamatan</th>
                        <th
                            class="text-center py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                            Persentase</th>
                        <th
                            class="text-right py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                            Nilai (<?= $currency ?>)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (!empty($data['additional_investment_percentages']['PMA'])): ?>
                        <?php foreach ($data['additional_investment_percentages']['PMA'] as $district => $info): ?>
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="py-4 px-6 text-xs font-bold text-slate-700 group-hover:text-blue-600">
                                    <?= esc($district) ?>
                                </td>
                                <td class="py-4 px-6 text-xs text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        <div class="w-20 bg-slate-100 rounded-full h-1.5 overflow-hidden shadow-inner">
                                            <div class="bg-blue-600 h-full rounded-full shadow-[0_0_8px_rgba(37,99,235,0.4)]"
                                                style="width: <?= $info['percentage'] ?>%"></div>
                                        </div>
                                        <span
                                            class="font-black text-blue-600 tabular-nums"><?= number_format($info['percentage'], 1) ?>%</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-xs text-right font-black text-slate-600 tabular-nums">
                                    <?= $currencySymbol ?>         <?= number_format($info['amount'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="py-20 text-center"><i
                                    class="fas fa-inbox text-slate-200 text-3xl mb-3 block"></i>
                                <p class="text-xs font-black text-slate-400 uppercase">Data Kosong</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Total Bar PMA Contribution -->
        <div
            class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 px-6 py-4 flex justify-between items-center text-white shadow-2xl rounded-b-2xl border-l-4 border-l-blue-600 border-t border-slate-700/30">
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Kontribusi PMA</span>
            <div class="flex gap-6 items-center">
                <div class="flex flex-col items-center">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Porsi</span>
                    <span class="text-sm font-black text-blue-400 tabular-nums">100.0%</span>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-[9px] font-bold text-blue-400 uppercase tracking-tighter">Nilai
                        (<?= $currency ?>)</span>
                    <span class="text-lg font-black tabular-nums">
                        <?= $currencySymbol ?>
                        <?= number_format(array_sum(array_column($data['additional_investment_percentages']['PMA'] ?? [], 'amount')), 0, ',', '.') ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Kontribusi PMDN -->
    <div class="card border-0 shadow-lg">
        <div class="p-6 border-b border-slate-100 bg-white">
            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center">
                <span class="w-1.5 h-4 bg-green-600 rounded-full mr-3"></span>
                Kontribusi PMDN per Kecamatan
            </h3>
        </div>
        <div class="overflow-x-auto max-h-96 custom-scrollbar">
            <table class="w-full table-auto border-separate border-spacing-0">
                <thead class="bg-slate-50/80 backdrop-blur-md sticky top-0 z-10">
                    <tr>
                        <th
                            class="text-left py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                            Kecamatan</th>
                        <th
                            class="text-center py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                            Persentase</th>
                        <th
                            class="text-right py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                            Nilai (<?= $currency ?>)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (!empty($data['additional_investment_percentages']['PMDN'])): ?>
                        <?php foreach ($data['additional_investment_percentages']['PMDN'] as $district => $info): ?>
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="py-4 px-6 text-xs font-bold text-slate-700 group-hover:text-green-600">
                                    <?= esc($district) ?>
                                </td>
                                <td class="py-4 px-6 text-xs text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        <div class="w-20 bg-slate-100 rounded-full h-1.5 overflow-hidden shadow-inner">
                                            <div class="bg-green-600 h-full rounded-full shadow-[0_0_8px_rgba(16,185,129,0.4)]"
                                                style="width: <?= $info['percentage'] ?>%"></div>
                                        </div>
                                        <span
                                            class="font-black text-green-600 tabular-nums"><?= number_format($info['percentage'], 1) ?>%</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-xs text-right font-black text-slate-600 tabular-nums">
                                    <?= $currencySymbol ?>         <?= number_format($info['amount'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="py-20 text-center"><i
                                    class="fas fa-inbox text-slate-200 text-3xl mb-3 block"></i>
                                <p class="text-xs font-black text-slate-400 uppercase">Data Kosong</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Total Bar PMDN Contribution -->
        <div
            class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 px-6 py-4 flex justify-between items-center text-white shadow-2xl rounded-b-2xl border-l-4 border-l-green-600 border-t border-slate-700/30">
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Kontribusi PMDN</span>
            <div class="flex gap-6 items-center">
                <div class="flex flex-col items-center">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Porsi</span>
                    <span class="text-sm font-black text-green-400 tabular-nums">100.0%</span>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-[9px] font-bold text-green-400 uppercase tracking-tighter">Nilai
                        (<?= $currency ?>)</span>
                    <span class="text-lg font-black tabular-nums">
                        <?= $currencySymbol ?>
                        <?= number_format(array_sum(array_column($data['additional_investment_percentages']['PMDN'] ?? [], 'amount')), 0, ',', '.') ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Company Ranking Tables -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8" x-data="{ searchPMA: '', searchPMDN: '' }">
    <!-- Tabel PMA -->
    <div class="card flex flex-col">
        <div class="p-6 border-b border-slate-100 bg-white">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                <h3 class="text-lg font-black text-slate-800 flex items-center tracking-tight">
                    <span class="w-1.5 h-6 bg-blue-600 rounded-full mr-4"></span>
                    Peringkat Perusahaan (PMA)
                </h3>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" x-model="searchPMA" placeholder="Cari..."
                            class="form-input text-xs py-2 pl-9 pr-4 rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 w-40 transition-all shadow-sm">
                    </div>
                    <div class="flex gap-1.5 bg-slate-50 p-1 rounded-xl border border-slate-200">
                        <button @click="exportRanking('PMA')"
                            class="w-8 h-8 rounded-lg bg-white text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all shadow-sm border border-slate-100"
                            title="Ekspor PDF">
                            <i class="fas fa-file-pdf"></i>
                        </button>
                        <button @click="exportExcel('PMA')"
                            class="w-8 h-8 rounded-lg bg-white text-emerald-500 flex items-center justify-center hover:bg-emerald-500 hover:text-white transition-all shadow-sm border border-slate-100"
                            title="Ekspor Excel">
                            <i class="fas fa-file-excel"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto max-h-[500px] overflow-y-auto custom-scrollbar flex-1">
            <table class="w-full table-auto border-separate border-spacing-0" id="table-pma">
                <thead class="bg-slate-50/90 backdrop-blur-md sticky top-0 z-20">
                    <tr>
                        <th
                            class="text-left py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                            Nama Perusahaan</th>
                        <th
                            class="text-right py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                            Realisasi (<?= $currency ?>)</th>
                        <th
                            class="text-center py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                            TKA</th>
                        <th
                            class="text-center py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                            TKI</th>
                        <th
                            class="text-center py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                            Proyek</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (!empty($data['sector_count_by_company']['PMA']['data'])): ?>
                        <?php foreach ($data['sector_count_by_company']['PMA']['data'] as $row): ?>
                            <tr class="hover:bg-blue-50/50 transition-colors group"
                                x-show="'<?= addslashes(strtolower($row['nama_perusahaan'])) ?>'.includes(searchPMA.toLowerCase())">
                                <td
                                    class="py-4 px-6 text-sm font-bold text-slate-700 group-hover:text-blue-700 transition-colors">
                                    <?= esc($row['nama_perusahaan']) ?>
                                </td>
                                <td class="py-4 px-6 text-sm text-right font-black text-blue-600 tabular-nums">
                                    <?= $currencySymbol ?>         <?= number_format($row['tambahan_realisasi'] ?? 0, 0, ',', '.') ?>
                                </td>
                                <td class="py-4 px-6 text-sm text-center font-bold text-slate-500 tabular-nums">
                                    <?= number_format($row['jumlah_tka'] ?? 0) ?>
                                </td>
                                <td class="py-4 px-6 text-sm text-center font-bold text-slate-500 tabular-nums">
                                    <?= number_format($row['jumlah_tki'] ?? 0) ?>
                                </td>
                                <td class="py-4 px-6 text-sm text-center">
                                    <span
                                        class="px-3 py-1 rounded-lg bg-slate-100 text-slate-600 font-black text-[10px] group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                                        <?= number_format($row['jumlah_proyek'] ?? 0) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="py-32 text-center"><i
                                    class="fas fa-inbox text-slate-200 text-5xl mb-4 block"></i>
                                <p class="text-slate-400 font-black uppercase text-xs">Belum ada data tersedia</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Total Bar PMA -->
        <div
            class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 px-6 py-5 flex justify-between items-center text-white shadow-2xl rounded-b-2xl border-l-4 border-l-blue-600 border-t border-slate-700/30">
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Akumulasi PMA</span>
            <div class="flex gap-8 items-center">
                <div class="flex flex-col items-end">
                    <span class="text-[9px] font-bold text-blue-400 uppercase tracking-tighter">Proyek</span>
                    <span
                        class="text-xl font-black tabular-nums"><?= number_format($data['total_projects']['PMA'] ?? 0) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel PMDN -->
    <div class="card flex flex-col">
        <div class="p-6 border-b border-slate-100 bg-white">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                <h3 class="text-lg font-black text-slate-800 flex items-center tracking-tight">
                    <span class="w-1.5 h-6 bg-green-600 rounded-full mr-4"></span>
                    Peringkat Perusahaan (PMDN)
                </h3>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" x-model="searchPMDN" placeholder="Cari..."
                            class="form-input text-xs py-2 pl-9 pr-4 rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500 w-40 transition-all shadow-sm">
                    </div>
                    <div class="flex gap-1.5 bg-slate-50 p-1 rounded-xl border border-slate-200">
                        <button @click="exportRanking('PMDN')"
                            class="w-8 h-8 rounded-lg bg-white text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all shadow-sm border border-slate-100"
                            title="Ekspor PDF">
                            <i class="fas fa-file-pdf"></i>
                        </button>
                        <button @click="exportExcel('PMDN')"
                            class="w-8 h-8 rounded-lg bg-white text-emerald-500 flex items-center justify-center hover:bg-emerald-500 hover:text-white transition-all shadow-sm border border-slate-100"
                            title="Ekspor Excel">
                            <i class="fas fa-file-excel"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto max-h-[500px] overflow-y-auto custom-scrollbar flex-1">
            <table class="w-full table-auto border-separate border-spacing-0" id="table-pmdn">
                <thead class="bg-slate-50/90 backdrop-blur-md sticky top-0 z-20">
                    <tr>
                        <th
                            class="text-left py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                            Nama Perusahaan</th>
                        <th
                            class="text-right py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                            Realisasi (<?= $currency ?>)</th>
                        <th
                            class="text-center py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                            TKA</th>
                        <th
                            class="text-center py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                            TKI</th>
                        <th
                            class="text-center py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                            Proyek</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (!empty($data['sector_count_by_company']['PMDN']['data'])): ?>
                        <?php foreach ($data['sector_count_by_company']['PMDN']['data'] as $row): ?>
                            <tr class="hover:bg-green-50/50 transition-colors group"
                                x-show="'<?= addslashes(strtolower($row['nama_perusahaan'])) ?>'.includes(searchPMDN.toLowerCase())">
                                <td
                                    class="py-4 px-6 text-sm font-bold text-slate-700 group-hover:text-green-700 transition-colors">
                                    <?= esc($row['nama_perusahaan']) ?>
                                </td>
                                <td class="py-4 px-6 text-sm text-right font-black text-green-600 tabular-nums">
                                    <?= $currencySymbol ?>         <?= number_format($row['tambahan_realisasi'] ?? 0, 0, ',', '.') ?>
                                </td>
                                <td class="py-4 px-6 text-sm text-center font-bold text-slate-500 tabular-nums">
                                    <?= number_format($row['jumlah_tka'] ?? 0) ?>
                                </td>
                                <td class="py-4 px-6 text-sm text-center font-bold text-slate-500 tabular-nums">
                                    <?= number_format($row['jumlah_tki'] ?? 0) ?>
                                </td>
                                <td class="py-4 px-6 text-sm text-center">
                                    <span
                                        class="px-3 py-1 rounded-lg bg-slate-100 text-slate-600 font-black text-[10px] group-hover:bg-green-600 group-hover:text-white transition-all shadow-sm">
                                        <?= number_format($row['jumlah_proyek'] ?? 0) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="py-32 text-center"><i
                                    class="fas fa-inbox text-slate-200 text-5xl mb-4 block"></i>
                                <p class="text-slate-400 font-black uppercase text-xs">Belum ada data tersedia</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Total Bar PMDN -->
        <div
            class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 px-6 py-5 flex justify-between items-center text-white shadow-2xl rounded-b-2xl border-l-4 border-l-green-600 border-t border-slate-700/30">
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Akumulasi PMDN</span>
            <div class="flex gap-8 items-center">
                <div class="flex flex-col items-end">
                    <span class="text-[9px] font-bold text-green-400 uppercase tracking-tighter">Proyek</span>
                    <span
                        class="text-xl font-black tabular-nums"><?= number_format($data['total_projects']['PMDN'] ?? 0) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload & Recent History Section -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-8" x-data="{ dragOver: false }">
    <!-- Upload Card -->
    <div class="card xl:col-span-1 p-8 flex flex-col justify-center min-h-[400px]">
        <h3 class="text-xl font-black text-slate-800 mb-8 flex items-center tracking-tight">
            <span class="w-1.5 h-6 bg-blue-600 rounded-full mr-4"></span>
            Unggah Data Baru
        </h3>
        <form action="<?= base_url('dashboard/upload') ?>" method="post" enctype="multipart/form-data" class="relative">
            <input type="file" name="excel_file" id="excel_file" class="hidden" accept=".xlsx,.xls"
                @change="$el.form.submit()">
            <label for="excel_file"
                class="group relative flex flex-col items-center justify-center w-full h-64 border-2 border-dashed rounded-2xl cursor-pointer transition-all duration-300"
                :class="dragOver ? 'border-blue-500 bg-blue-50/50 shadow-xl' : 'border-slate-200 bg-slate-50/30 hover:bg-white hover:border-blue-400 hover:shadow-lg'"
                @dragover.prevent="dragOver = true" @dragleave.prevent="dragOver = false"
                @drop.prevent="dragOver = false; $refs.fileInput.files = $event.dataTransfer.files; $el.form.submit()">

                <div
                    class="w-20 h-20 bg-white rounded-2xl shadow-sm border border-slate-100 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                    <i class="fas fa-cloud-upload-alt text-3xl text-blue-500"></i>
                </div>
                <div class="text-center px-4 relative">
                    <p class="text-slate-800 font-black text-lg mb-1">Klik atau seret file</p>
                    <p class="text-slate-400 font-bold text-xs uppercase tracking-widest">Format: .XLSX / .XLS (Max
                        10MB)</p>
                </div>
            </label>
        </form>
    </div>

    <!-- Recent Uploads Table -->
    <div class="card xl:col-span-2 flex flex-col">
        <div class="p-6 border-b border-slate-100 bg-white">
            <h3 class="text-lg font-black text-slate-800 flex items-center tracking-tight">
                <span class="w-1.5 h-6 bg-slate-400 rounded-full mr-4"></span>
                Riwayat Unggahan Terbaru
            </h3>
        </div>
        <div class="overflow-x-auto flex-1 custom-scrollbar">
            <table class="w-full table-auto border-separate border-spacing-0">
                <thead class="bg-slate-50/80 backdrop-blur-md sticky top-0 z-10">
                    <tr>
                        <th
                            class="py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest text-left border-b border-slate-100">
                            Nama File</th>
                        <th
                            class="py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center border-b border-slate-100">
                            Tanggal</th>
                        <th
                            class="py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center border-b border-slate-100">
                            Baris</th>
                        <th
                            class="py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center border-b border-slate-100">
                            Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (!empty($data['recent_uploads'])): ?>
                        <?php foreach (array_slice($data['recent_uploads'], 0, 5) as $upload): ?>
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="py-4 px-6 text-sm font-bold text-slate-700 group-hover:text-blue-600">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-file-excel text-emerald-500 text-base"></i>
                                        <?= esc($upload['file_name']) ?>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-xs text-center font-bold text-slate-500 tabular-nums">
                                    <span
                                        class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg"><?= date('d M Y, H:i', strtotime($upload['uploaded_at'])) ?></span>
                                </td>
                                <td class="py-4 px-6 text-sm text-center font-black text-slate-900 tabular-nums">
                                    <?= number_format($upload['rows_count']) ?>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span
                                        class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 font-black text-[10px] uppercase tracking-widest shadow-sm">Berhasil</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="py-24 text-center"><i
                                    class="fas fa-history text-slate-200 text-4xl mb-4 block"></i>
                                <p class="text-slate-400 font-black uppercase text-xs">Belum ada riwayat</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- History & Logs Section -->
<div class="card overflow-hidden mb-8">
    <div class="p-6 border-b border-slate-100 bg-white">
        <h3 class="text-lg font-black text-slate-800 flex items-center tracking-tight">
            <i class="fas fa-database mr-3 text-slate-400"></i>
            Log Metadata & Riwayat Data (Full)
        </h3>
    </div>
    <div class="overflow-x-auto custom-scrollbar">
        <table class="w-full table-auto border-separate border-spacing-0" id="dataTable">
            <thead class="bg-slate-50/80">
                <tr>
                    <th class="py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest text-left">
                        Label Upload</th>
                    <th class="py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">
                        Periode</th>
                    <th class="py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">
                        Tahun</th>
                    <th class="py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">
                        Kurs USD (IDR)</th>
                    <th class="py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">
                        Status</th>
                    <th class="py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">
                        Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php if (isset($data['uploads']) && count($data['uploads']) > 0): ?>
                    <?php foreach ($data['uploads'] as $up): ?>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 text-sm font-bold text-slate-700"><?= esc($up['upload_name'] ?? 'N/A') ?></td>
                            <td class="py-4 px-6 text-sm text-center font-bold text-slate-500"><?= esc($up['quarter'] ?? '-') ?>
                            </td>
                            <td class="py-4 px-6 text-sm text-center font-black text-slate-900"><?= esc($up['year'] ?? '-') ?>
                            </td>
                            <td class="py-4 px-6 text-xs text-center font-bold text-blue-600 tabular-nums">
                                <span class="px-2 py-0.5 bg-blue-50 rounded text-[10px]">
                                    <?= number_format($up['usd_value'] ?? 0, 0, ',', '.') ?>
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <?php
                                $status = $up['status'] ?? 'pending';
                                $badgeClass = $status === 'completed' ? 'bg-emerald-100 text-emerald-700' : ($status === 'error' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700');
                                ?>
                                <span
                                    class="px-3 py-1 rounded-full font-black text-[10px] uppercase tracking-widest <?= $badgeClass ?>">
                                    <?= $status ?>
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="<?= base_url('dashboard?upload=' . $up['id']) ?>"
                                        class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm"
                                        title="Muat Statistik">
                                        <i class="fas fa-sync-alt text-xs"></i>
                                    </a>
                                    <a href="<?= base_url('dashboard/editMetadata/' . $up['id']) ?>"
                                        class="w-8 h-8 rounded-lg bg-slate-100 text-amber-600 flex items-center justify-center hover:bg-amber-500 hover:text-white transition-all shadow-sm"
                                        title="Edit Metadata">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <button type="button" onclick="confirmDelete(<?= $up['id'] ?>)"
                                        class="w-8 h-8 rounded-lg bg-slate-100 text-rose-600 flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all shadow-sm"
                                        title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="py-16 text-center text-slate-400 font-bold uppercase text-xs">Belum ada
                            riwayat data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Hidden Metadata Table (for Exporting) -->
<div class="hidden">
    <div class="card p-10 bg-white" id="metadata-container">
        <h2 class="text-3xl font-black text-slate-900 mb-8 tracking-tighter" id="export-title">LAPORAN DATA INVESTASI
        </h2>
        <div class="grid grid-cols-2 gap-10 mb-10">
            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Periode Laporan</p>
                <p class="text-lg font-bold text-slate-800"><?= $data['filters']['month'] ?? 'All' ?> /
                    <?= $data['filters']['year'] ?? 'All' ?>
                </p>
            </div>
            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Unit Mata Uang</p>
                <p class="text-lg font-bold text-slate-800"><?= $currency ?></p>
            </div>
        </div>
        <table id="metadata-table" class="w-full border-collapse">
            <thead>
                <tr class="bg-slate-900 text-white">
                    <th class="p-5 text-left font-black uppercase tracking-widest text-xs">Indikator Kinerja Utama</th>
                    <th class="p-5 text-right font-black uppercase tracking-widest text-xs">PMA (<?= $currency ?>)</th>
                    <th class="p-5 text-right font-black uppercase tracking-widest text-xs">PMDN (<?= $currency ?>)</th>
                    <th class="p-5 text-right font-black uppercase tracking-widest text-xs">TOTAL (<?= $currency ?>)
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b-2 border-slate-100">
                    <td class="p-5 font-bold text-slate-700">Realisasi Investasi</td>
                    <td class="p-5 text-right tabular-nums">
                        <?= number_format($data['total_investment']['PMA'] ?? 0, 0, ',', '.') ?>
                    </td>
                    <td class="p-5 text-right tabular-nums">
                        <?= number_format($data['total_investment']['PMDN'] ?? 0, 0, ',', '.') ?>
                    </td>
                    <td class="p-5 text-right font-black text-slate-900 tabular-nums">
                        <?= number_format(($data['total_investment']['PMA'] ?? 0) + ($data['total_investment']['PMDN'] ?? 0), 0, ',', '.') ?>
                    </td>
                </tr>
                <tr class="border-b-2 border-slate-100">
                    <td class="p-5 font-bold text-slate-700">Jumlah Unit Proyek</td>
                    <td class="p-5 text-right tabular-nums"><?= number_format($data['total_projects']['PMA'] ?? 0) ?>
                    </td>
                    <td class="p-5 text-right tabular-nums"><?= number_format($data['total_projects']['PMDN'] ?? 0) ?>
                    </td>
                    <td class="p-5 text-right font-black text-slate-900 tabular-nums">
                        <?= number_format(($data['total_projects']['PMA'] ?? 0) + ($data['total_projects']['PMDN'] ?? 0)) ?>
                    </td>
                </tr>
                <tr>
                    <td class="p-5 font-bold text-slate-700">Penyerapan Tenaga Kerja</td>
                    <td class="p-5 text-right tabular-nums">
                        <?= number_format(($data['total_workers']['PMA']['TKI'] ?? 0) + ($data['total_workers']['PMA']['TKA'] ?? 0)) ?>
                    </td>
                    <td class="p-5 text-right tabular-nums">
                        <?= number_format(($data['total_workers']['PMDN']['TKI'] ?? 0) + ($data['total_workers']['PMDN']['TKA'] ?? 0)) ?>
                    </td>
                    <td class="p-5 text-right font-black text-slate-900 tabular-nums">
                        <?= number_format(($data['total_workers']['PMA']['TKI'] ?? 0) + ($data['total_workers']['PMA']['TKA'] ?? 0) + ($data['total_workers']['PMDN']['TKI'] ?? 0) + ($data['total_workers']['PMDN']['TKA'] ?? 0)) ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


<script>
    const dashboardData = <?= json_encode($data) ?>;
    const currentFilters = <?= json_encode($data['filters'] ?? []) ?>;

    document.addEventListener('DOMContentLoaded', function () {
        // Global chart instances
        window.charts = {};

        // Shared chart options
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    right: 40,
                    top: 25,
                    bottom: 10,
                    left: 10
                }
            },
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, usePointStyle: true } }
            }
        };

        // 1. Ratio Chart (Doughnut)
        const ratioCtx = document.getElementById('ratioChart').getContext('2d');
        window.charts['ratioChart'] = new Chart(ratioCtx, {
            type: 'doughnut',
            plugins: [ChartDataLabels],
            data: {
                labels: ['PMA', 'PMDN'],
                datasets: [{
                    data: [<?= $data['total_additional_investment']['PMA'] ?? 0 ?>, <?= $data['total_additional_investment']['PMDN'] ?? 0 ?>],
                    backgroundColor: ['#2563eb', '#10b981'],
                    hoverOffset: 12,
                    borderWidth: 4,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                ...commonOptions,
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { family: 'Inter', weight: '900', size: 10 },
                            padding: 20,
                            usePointStyle: true
                        }
                    },
                    datalabels: {
                        color: '#000',
                        font: { family: 'Inter', weight: '900', size: 10 },
                        formatter: (value, ctx) => {
                            if (value === 0) return '';
                            const currency = '<?= $currencySymbol ?>';
                            return currency + ' ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        });

        // 2. District Chart (Bar)
        const districtCtx = document.getElementById('districtChart').getContext('2d');
        window.charts['districtChart'] = new Chart(districtCtx, {
            type: 'bar',
            plugins: [ChartDataLabels],
            data: {
                labels: <?= json_encode($data['charts']['district']['labels'] ?? []) ?>,
                datasets: [
                    {
                        label: 'PMA',
                        data: <?= json_encode($data['charts']['district']['pma'] ?? []) ?>,
                        backgroundColor: '#2563eb',
                        borderRadius: 6,
                        barPercentage: 0.7
                    },
                    {
                        label: 'PMDN',
                        data: <?= json_encode($data['charts']['district']['pmdn'] ?? []) ?>,
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                        barPercentage: 0.7
                    }
                ]
            },
            options: {
                ...commonOptions,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9', drawBorder: false },
                        ticks: { font: { family: 'Inter', weight: '700', size: 10 }, color: '#64748b' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Inter', weight: '700', size: 10 }, color: '#64748b' }
                    }
                },
                plugins: {
                    legend: {
                        labels: { font: { family: 'Inter', weight: '900', size: 10 } }
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        color: '#1e293b',
                        font: { family: 'Inter', weight: '900', size: 9 },
                        offset: 4
                    }
                }
            }
        });

        // 2b. Workforce Chart (Grouped Bar)
        const workforceCtx = document.getElementById('workforceChart').getContext('2d');
        window.charts['workforceChart'] = new Chart(workforceCtx, {
            type: 'bar',
            plugins: [ChartDataLabels],
            data: {
                labels: <?= json_encode($data['charts']['workforce']['labels'] ?? []) ?>,
                datasets: [
                    {
                        label: 'TKI (Lokal)',
                        data: <?= json_encode($data['charts']['workforce']['tki'] ?? []) ?>,
                        backgroundColor: '#f59e0b',
                        borderRadius: 6,
                        barPercentage: 0.8
                    },
                    {
                        label: 'TKA (Asing)',
                        data: <?= json_encode($data['charts']['workforce']['tka'] ?? []) ?>,
                        backgroundColor: '#ef4444',
                        borderRadius: 6,
                        barPercentage: 0.8
                    }
                ]
            },
            options: {
                ...commonOptions,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9', drawBorder: false },
                        ticks: { font: { family: 'Inter', weight: '700', size: 10 }, color: '#475569' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Inter', weight: '700', size: 10 }, color: '#475569' }
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { family: 'Inter', weight: '900', size: 10 }, usePointStyle: true }
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        color: '#1e293b',
                        font: { family: 'Inter', weight: '900', size: 9 },
                        formatter: (value) => value.toLocaleString('id-ID'),
                        offset: 4
                    }
                }
            }
        });

        // 3. Location Investment Chart (Horizontal Bar)
        const locationCtx = document.getElementById('locationChart').getContext('2d');
        window.charts['locationChart'] = new Chart(locationCtx, {
            type: 'bar',
            plugins: [ChartDataLabels],
            data: {
                labels: <?= json_encode($data['charts']['locations']['labels'] ?? []) ?>,
                datasets: [{
                    label: 'Investasi',
                    data: <?= json_encode($data['charts']['locations']['values'] ?? []) ?>,
                    backgroundColor: '#f59e0b',
                    borderRadius: 6,
                    barPercentage: 0.6
                }]
            },
            options: {
                ...commonOptions,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false },
                    datalabels: {
                        anchor: 'end',
                        align: 'right',
                        color: '#000000',
                        font: { family: 'Inter', weight: '900', size: 10 },
                        formatter: (value) => {
                            const currency = currentFilters.currency || 'IDR';
                            const prefix = currency === 'USD' ? '$' : 'Rp ';
                            return prefix + value.toLocaleString('id-ID');
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { display: false } },
                    y: { grid: { display: false }, ticks: { font: { family: 'Inter', weight: '700', size: 10 }, color: '#475569' } }
                }
            }
        });

        // 4. Sector Analysis Chart (Default: Horizontal Bar)
        const sectorCtx = document.getElementById('sectorChart').getContext('2d');
        window.charts['sectorChart'] = new Chart(sectorCtx, {
            type: 'bar',
            plugins: [ChartDataLabels],
            data: {
                labels: <?= json_encode($data['charts']['sectors']['labels'] ?? []) ?>,
                datasets: [{
                    data: <?= json_encode($data['charts']['sectors']['counts'] ?? []) ?>,
                    backgroundColor: [
                        'rgba(37, 99, 235, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(245, 158, 11, 0.7)',
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(139, 92, 246, 0.7)',
                        'rgba(14, 165, 233, 0.7)',
                        'rgba(20, 184, 166, 0.7)'
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 3
                }]
            },
            options: {
                ...commonOptions,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { font: { family: 'Inter', weight: '700', size: 10 }, padding: 10 }
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'right',
                        offset: 4,
                        color: '#000000',
                        font: { family: 'Inter', weight: '900', size: 12 },
                        formatter: (value) => {
                            return value.toLocaleString('id-ID');
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { display: false } },
                    y: { grid: { display: false }, ticks: { font: { family: 'Inter', weight: '700', size: 10 }, padding: 10 } }
                }
            }
        });

        // 5. Country Chart (Pie)
        const countryCtx = document.getElementById('countryChart').getContext('2d');
        window.charts['countryChart'] = new Chart(countryCtx, {
            type: 'pie',
            plugins: [ChartDataLabels],
            data: {
                labels: <?= json_encode($data['charts']['countries']['labels'] ?? []) ?>,
                datasets: [{
                    data: <?= json_encode($data['charts']['countries']['counts'] ?? []) ?>,
                    backgroundColor: [
                        '#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#0ea5e9', '#14b8a6', '#f97316'
                    ],
                    borderWidth: 3,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: { font: { family: 'Inter', weight: '700', size: 10 }, padding: 15 }
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'end',
                        offset: 10,
                        color: '#1e293b',
                        font: { family: 'Inter', weight: '900', size: 11 },
                        formatter: (value, ctx) => {
                            return value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        });

        // 6. Quarterly Investment Trend (Line/Area)
        const quarterlyCtx = document.getElementById('quarterlyChart').getContext('2d');
        window.charts['quarterlyChart'] = new Chart(quarterlyCtx, {
            type: 'line',
            plugins: [ChartDataLabels],
            data: {
                labels: <?= json_encode($data['charts']['quarterly_additional_investment']['labels'] ?? ['Q1', 'Q2', 'Q3', 'Q4']) ?>,
                datasets: [{
                    label: 'Investasi',
                    data: <?= json_encode($data['charts']['quarterly_additional_investment']['values'] ?? [0, 0, 0, 0]) ?>,
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.08)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointHoverRadius: 10,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 4
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    legend: { display: false },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        color: '#6366f1',
                        font: { family: 'Inter', weight: '900', size: 10 },
                        formatter: (value) => {
                            const currency = currentFilters.currency || 'IDR';
                            const prefix = currency === 'USD' ? '$' : 'Rp ';
                            return prefix + value.toLocaleString('id-ID');
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9', drawBorder: false },
                        ticks: { font: { family: 'Inter', weight: '700', size: 10 }, color: '#64748b' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Inter', weight: '700', size: 10 }, color: '#64748b' }
                    }
                }
            }
        });

        // Quarterly Year Filter Logic
        document.getElementById('quarterly-year-filter').addEventListener('change', function () {
            const year = this.value;
            const allYearsData = <?= json_encode($data['charts']['quarterly_additional_investment_all_years'] ?? []) ?>;

            if (year === 'all') {
                window.charts['quarterlyChart'].data.datasets[0].data = <?= json_encode($data['charts']['quarterly_additional_investment']['values'] ?? [0, 0, 0, 0]) ?>;
            } else if (allYearsData[year]) {
                window.charts['quarterlyChart'].data.datasets[0].data = allYearsData[year].values;
            }
            window.charts['quarterlyChart'].update();
        });

        // Currency Switcher
        const currencyToggle = document.getElementById('currency-toggle');
        if (currencyToggle) {
            currencyToggle.addEventListener('change', function () {
                const url = new URL(window.location.href);
                url.searchParams.set('currency', this.value);
                window.location.href = url.toString();
            });
        }

        // Export Functions (Global scope via window)
        window.exportRanking = function (type) {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('p', 'mm', 'a4');
            const listData = dashboardData.sector_count_by_company[type].data;

            if (!listData || listData.length === 0) {
                Swal.fire('Info', 'Tidak ada data untuk diekspor', 'info');
                return;
            }

            doc.setFontSize(16);
            doc.text(`Peringkat Perusahaan ${type}`, 105, 15, { align: 'center' });
            doc.setFontSize(10);
            doc.text(`Dicetak pada: ${new Date().toLocaleString('id-ID')}`, 14, 25);

            const tableBody = listData.map((row, idx) => [
                idx + 1,
                row.nama_perusahaan,
                (row.tambahan_realisasi || 0).toLocaleString('id-ID'),
                row.jumlah_tka || 0,
                row.jumlah_tki || 0,
                row.jumlah_proyek || 0
            ]);

            doc.autoTable({
                startY: 30,
                head: [['No', 'Perusahaan', 'Realisasi', 'TKA', 'TKI', 'Proyek']],
                body: tableBody,
                theme: 'grid',
                headStyles: { fillColor: type === 'PMA' ? [37, 99, 235] : [16, 185, 129] },
                columnStyles: {
                    2: { halign: 'right' },
                    3: { halign: 'center' },
                    4: { halign: 'center' },
                    5: { halign: 'center' }
                }
            });

            doc.save(`Ranking_${type}_${new Date().getTime()}.pdf`);
        };

        window.exportExcel = function (type) {
            const listData = dashboardData.sector_count_by_company[type].data;
            if (!listData || listData.length === 0) return;

            const worksheet = XLSX.utils.json_to_sheet(listData.map(row => ({
                'Nama Perusahaan': row.nama_perusahaan,
                'Tambahan Realisasi': row.tambahan_realisasi,
                'TKA': row.jumlah_tka,
                'TKI': row.jumlah_tki,
                'Jumlah Proyek': row.jumlah_proyek
            })));

            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, `Ranking ${type}`);
            XLSX.writeFile(workbook, `Ranking_${type}_${new Date().getTime()}.xlsx`);
        };
    });

    /**
     * Switch chart type dynamically
     * @param {string} chartId 
     * @param {string} newType 
     */
    function switchChartType(chartId, newType) {
        const chart = window.charts[chartId];
        if (!chart) return;

        const ctx = chart.ctx;
        const config = chart.config;
        const finalType = (newType === 'horizontalBar') ? 'bar' : newType;

        // 1. Core Config Update
        config.type = finalType;

        // Ensure options object exists
        config.options = config.options || {};

        // 2. Scale Configuration Reset
        // Each type transition needs a clean scales state to avoid residue from previous types
        if (finalType === 'bar' || finalType === 'line') {
            config.options.scales = {
                x: {
                    display: true,
                    grid: { display: false },
                    ticks: { display: true, autoSkip: false, font: { family: 'Inter', weight: '700', size: 10 }, color: '#64748b' }
                },
                y: {
                    display: true,
                    beginAtZero: true,
                    grid: { color: '#f1f5f9', drawBorder: false },
                    ticks: { display: true, font: { family: 'Inter', weight: '700', size: 10 }, color: '#64748b' }
                }
            };

            // If it's the locationChart, we want to be extra sure labels are shown
            if (chartId === 'locationChart') {
                config.options.scales.x.ticks.autoSkip = false;
                config.options.scales.y.ticks.autoSkip = false;
            }

            // Handle Horizontal/Vertical orientation
            if (newType === 'horizontalBar') {
                config.options.indexAxis = 'y';
                config.options.scales.x.ticks.display = false; // Hide values axis for cleaner look if datalabels exist
            } else {
                config.options.indexAxis = 'x';
            }
        } else if (finalType === 'polarArea') {
            config.options.scales = {
                r: {
                    grid: { color: '#f1f5f9' },
                    angleLines: { color: '#f1f5f9' },
                    ticks: { display: false }
                }
            };
            if (config.options.indexAxis) delete config.options.indexAxis;
        } else {
            // Pie/Doughnut - no scales
            delete config.options.scales;
            if (config.options.indexAxis) delete config.options.indexAxis;
        }

        // 3. Chart Specific Refinements
        // Adjust datalabels alignment based on orientation
        if (config.options.plugins && config.options.plugins.datalabels) {
            if (newType === 'horizontalBar') {
                config.options.plugins.datalabels.align = 'right';
                config.options.plugins.datalabels.anchor = 'end';
            } else if (finalType === 'bar' || finalType === 'line') {
                config.options.plugins.datalabels.align = 'top';
                config.options.plugins.datalabels.anchor = 'end';
            }

            // Ensure formatter exists and uses correct currency prefix ONLY for specific charts
            config.options.plugins.datalabels.formatter = (value) => {
                const currency = currentFilters.currency || 'IDR';
                const prefix = currency === 'USD' ? '$' : 'Rp ';

                // Charts that represent money values
                const valueBasedCharts = ['locationChart', 'quarterlyChart'];
                if (valueBasedCharts.includes(chartId)) {
                    return prefix + value.toLocaleString('id-ID');
                }

                // Frequency/Count based charts (Sectors, Countries, Districts, Workforce)
                return value.toLocaleString('id-ID');
            };
        }

        if (chartId === 'quarterlyChart') {
            if (newType === 'bar') {
                config.data.datasets[0].backgroundColor = 'rgba(99, 102, 241, 0.8)';
                config.data.datasets[0].borderColor = '#6366f1';
            } else {
                config.data.datasets[0].backgroundColor = 'rgba(99, 102, 241, 0.08)';
                config.data.datasets[0].borderColor = '#6366f1';
                config.data.datasets[0].fill = true;
            }
        }

        if (finalType === 'doughnut') {
            config.options.cutout = '75%';
        } else if (finalType === 'pie') {
            config.options.cutout = 0;
        }

        // Special fix for locationChart (categories)
        if (chartId === 'locationChart') {
            config.options.scales.y.grid = { display: false };
            config.options.scales.x.grid = { display: false };
        }

        // Chart.js 3+ requires destroy and recreate for type changes
        chart.destroy();
        window.charts[chartId] = new Chart(ctx, config);
    }
    function uploadHandler() {
        return {
            uploading: false,
            progress: 0,
            tempProgress: 0,
            fileName: '',
            fileSelected: false,
            isDragging: false,
            statusMessage: 'Mengunggah file...',

            handleFileSelect(event) {
                const file = event.target.files[0];
                if (file) {
                    this.fileName = file.name;
                    this.fileSelected = true;
                }
            },

            handleDrop(event) {
                this.isDragging = false;
                const file = event.dataTransfer.files[0];
                if (file) {
                    this.$refs.fileInput.files = event.dataTransfer.files;
                    this.fileName = file.name;
                    this.fileSelected = true;
                }
            },

            submitForm() {
                if (!this.fileSelected) return;

                const file = this.$refs.fileInput.files[0];
                const formData = new FormData();
                formData.append('excel_file', file);
                formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

                this.uploading = true;
                this.progress = 0;
                this.statusMessage = 'Mengunggah file...';

                const xhr = new XMLHttpRequest();
                xhr.open('POST', '<?= base_url('dashboard/upload') ?>', true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                // Track upload progress
                xhr.upload.onprogress = (e) => {
                    if (e.lengthComputable) {
                        this.progress = Math.round((e.loaded / e.total) * 90); // 0-90% for upload
                        if (this.progress === 90) {
                            this.statusMessage = 'Memvalidasi struktur file...';
                        }
                    }
                };

                xhr.onload = () => {
                    if (xhr.status === 200 || xhr.status === 302) {
                        this.progress = 100;
                        this.statusMessage = 'Selesai!';

                        // Check if we got a redirect or JSON response
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                window.location.href = '<?= base_url('dashboard/metadata/') ?>' + response.uploadId;
                            } else {
                                this.handleError(response.message || 'Terjadi kesalahan');
                            }
                        } catch (e) {
                            // If not JSON, it might be a redirect or error page
                            // For CI4 redirects, we might just need to reload or check location
                            const redirectUrl = xhr.getResponseHeader('Location');
                            if (redirectUrl) {
                                window.location.href = redirectUrl;
                            } else {
                                // Fallback: reload dashboard and let flashdata show results
                                window.location.href = '<?= base_url('dashboard') ?>';
                            }
                        }
                    } else {
                        this.handleError('Upload gagal (HTTP ' + xhr.status + ')');
                    }
                };

                xhr.onerror = () => {
                    this.handleError('Koneksi terputus atau upload dibatalkan.');
                };

                xhr.send(formData);
            },

            handleError(message) {
                this.uploading = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Gagal',
                    html: message,
                    confirmButtonColor: '#2563eb'
                });
            }
        };
    }

    // Auto-dismiss flash messages after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('[role="alert"]');
        alerts.forEach(alert => {
            if (alert.style.animation !== undefined) {
                alert.style.animation = 'fadeOut 0.3s ease-out forwards';
            }
        });
    }, 5000);

    // Delete confirmation
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Data?',
            text: "Seluruh data proyek terkait upload ini akan ikut terhapus.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= base_url('dashboard/deleteUpload') ?>';
                form.innerHTML = `
                <?= csrf_field() ?>
                <input type="hidden" name="upload_id" value="${id}">
            `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>

<?php $this->endSection(); ?>