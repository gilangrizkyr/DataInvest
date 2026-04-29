<?php $this->extend('layouts/app'); ?>

<?php $this->section('content'); ?>

<!-- Section Header -->
<?= view('components/section_header', [
    'title' => 'Dashboard Statistik Realisasi Investasi',
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

    /* ═══ MOBILE RESPONSIVE OVERRIDES ═══ */
    @media (max-width: 767px) {
        .card {
            border-radius: 12px !important;
        }

        .card:hover {
            transform: none;
        }

        .card p-7,
        .card.p-7 {
            padding: 1rem !important;
        }

        .card.p-8,
        .card p-8 {
            padding: 1.25rem !important;
        }

        .kpi-icon {
            width: 40px;
            height: 40px;
            font-size: 1.1rem;
            border-radius: 10px;
        }

        .table thead th {
            padding: 0.75rem 0.75rem;
            font-size: 0.55rem;
        }
    }
</style>

<?php
// ─── Stat Filter Variables ────────────────────────────────────────────────────
$statFilterData = $stat_filter_data ?? null;
$availableYears = $available_years ?? [];
$isStatFilterActive = $is_stat_filter_active ?? false;
$activeStatYear = $data['filters']['stat_year'] ?? 'all';
$activeStatQuarters = $data['filters']['stat_quarters'] ?? [];

// Resolved display values for KPI cards
// When filter is active, use aggregated values; otherwise fall back to upload-based values
$kpiTotalInvestment = $isStatFilterActive && $statFilterData
    ? $statFilterData['total_investment']
    : ($data['total_investment'] ?? ['PMA' => 0, 'PMDN' => 0]);
$kpiTotalAdditional = $isStatFilterActive && $statFilterData
    ? $statFilterData['total_additional_investment']
    : ($data['total_additional_investment'] ?? ['PMA' => 0, 'PMDN' => 0]);
$kpiTotalProjects = $isStatFilterActive && $statFilterData
    ? $statFilterData['total_projects']
    : ($data['total_projects'] ?? ['PMA' => 0, 'PMDN' => 0]);
$kpiWorkforce = $isStatFilterActive && $statFilterData
    ? $statFilterData['workforce']
    : ($data['workforce'] ?? ['PMA' => ['TKI' => 0, 'TKA' => 0], 'PMDN' => ['TKI' => 0, 'TKA' => 0]]);
// ─────────────────────────────────────────────────────────────────────────────
?>

<!-- ═══════════════════════════════════════════════════════════════════════════
     FILTER STATISTIK: Tahun & Triwulan
     Hanya mempengaruhi nilai angka KPI — struktur & tabel TIDAK berubah
     ═══════════════════════════════════════════════════════════════════════════ -->
<style>
    /* Filter Panel Styles */
    .stat-filter-panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.05), 0 2px 4px -1px rgba(15, 23, 42, 0.02);
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.75rem;
        transition: box-shadow 0.2s ease;
    }

    .stat-filter-panel.active-filter {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08), 0 4px 6px -1px rgba(15, 23, 42, 0.06);
    }

    .stat-filter-label {
        font-size: 0.6rem;
        font-weight: 900;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #94a3b8;
    }

    .tw-checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .tw-checkbox-label {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.35rem 0.85rem;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        background: #f8fafc;
        cursor: pointer;
        font-size: 0.75rem;
        font-weight: 800;
        color: #64748b;
        transition: all 0.15s ease;
        user-select: none;
    }

    .tw-checkbox-label:hover {
        border-color: #93c5fd;
        background: #eff6ff;
        color: #2563eb;
    }

    .tw-checkbox-label input[type="checkbox"] {
        display: none;
    }

    .tw-checkbox-label.checked {
        background: #2563eb;
        border-color: #2563eb;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
    }

    .stat-filter-btn-apply {
        padding: 0.45rem 1.25rem;
        background: #2563eb;
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 900;
        cursor: pointer;
        transition: background 0.15s ease, transform 0.1s ease;
        letter-spacing: 0.05em;
    }

    .stat-filter-btn-apply:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
    }

    .stat-filter-btn-reset {
        padding: 0.45rem 1rem;
        background: #f1f5f9;
        color: #64748b;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 900;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .stat-filter-btn-reset:hover {
        background: #fee2e2;
        border-color: #fca5a5;
        color: #dc2626;
    }

    .filter-active-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.2rem 0.7rem;
        border-radius: 999px;
        background: #dbeafe;
        color: #1d4ed8;
        font-size: 0.65rem;
        font-weight: 900;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .filter-no-result-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.2rem 0.75rem;
        border-radius: 999px;
        background: #fee2e2;
        color: #dc2626;
        font-size: 0.65rem;
        font-weight: 900;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }
</style>

<div class="stat-filter-panel <?= $isStatFilterActive ? 'active-filter' : '' ?>">
    <form method="GET" action="<?= current_url() ?>" id="stat-filter-form">
        <?php /* Hanya simpan currency; upload selalu diabaikan */ ?>
        <?php if (!empty($data['filters']['currency']) && $data['filters']['currency'] !== 'IDR'): ?>
            <input type="hidden" name="currency" value="<?= esc($data['filters']['currency']) ?>">
        <?php endif; ?>

        <div class="flex flex-col md:flex-row md:items-end gap-5 flex-wrap">

            <!-- Header label -->
            <div class="flex items-center gap-3 md:w-auto">
                <div
                    class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 border border-blue-100 shadow-sm flex-shrink-0">
                    <i class="fas fa-filter text-sm"></i>
                </div>
                <div>
                    <p class="stat-filter-label">Filter Statistik</p>
                    <p class="text-xs font-black text-slate-700 leading-tight">Tahun & Triwulan</p>
                </div>
                <?php if ($isStatFilterActive): ?>
                    <?php if ($statFilterData !== null): ?>
                        <span class="filter-active-badge">
                            <i class="fas fa-check-circle"></i>
                            Aktif
                        </span>
                    <?php else: ?>
                        <span class="filter-no-result-badge">
                            <i class="fas fa-exclamation-triangle"></i>
                            Tidak ada data
                        </span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <!-- Divider -->
            <div class="hidden md:block w-px bg-slate-200 self-stretch"></div>

            <!-- Tahun -->
            <div class="flex flex-col gap-1.5">
                <span class="stat-filter-label">Tahun</span>
                <select name="stat_year" id="stat-year-select"
                    class="form-select text-xs font-black border-slate-200 focus:border-blue-500 focus:ring-blue-500 rounded-xl bg-slate-50 text-slate-700 py-2 px-3 min-w-[130px] cursor-pointer shadow-sm">
                    <option value="all" <?= ($activeStatYear === 'all') ? 'selected' : '' ?>>Semua Tahun</option>
                    <?php foreach ($availableYears as $yr): ?>
                        <option value="<?= esc($yr) ?>" <?= ($activeStatYear == $yr) ? 'selected' : '' ?>><?= esc($yr) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Divider -->
            <div class="hidden md:block w-px bg-slate-200 self-stretch"></div>

            <!-- Triwulan Multi-Select -->
            <div class="flex flex-col gap-1.5">
                <span class="stat-filter-label">Triwulan (dapat pilih lebih dari satu)</span>
                <div class="tw-checkbox-group" id="tw-checkbox-group">
                    <?php
                    $twOptions = ['Q1' => 'TW 1', 'Q2' => 'TW 2', 'Q3' => 'TW 3', 'Q4' => 'TW 4'];
                    foreach ($twOptions as $qVal => $qLabel):
                        $isChecked = in_array($qVal, (array) $activeStatQuarters, true);
                        ?>
                        <label class="tw-checkbox-label <?= $isChecked ? 'checked' : '' ?>">
                            <input type="checkbox" name="stat_quarters[]" value="<?= $qVal ?>" <?= $isChecked ? 'checked' : '' ?> onchange="updateCheckboxStyle(this)">
                            <i class="fas <?= $isChecked ? 'fa-check-square' : 'fa-square' ?> text-[10px]"
                                id="icon-<?= $qVal ?>"></i>
                            <?= $qLabel ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2 ml-auto">
                <?php if ($isStatFilterActive): ?>
                    <a href="<?= base_url('dashboard' . ($data['filters']['currency'] !== 'IDR' ? '?currency=' . esc($data['filters']['currency']) : '')) ?>"
                        class="stat-filter-btn-reset">
                        <i class="fas fa-times mr-1"></i> Reset
                    </a>
                <?php endif; ?>
                <button type="submit" class="stat-filter-btn-apply">
                    <i class="fas fa-search mr-1.5"></i> Terapkan
                </button>
            </div>

        </div>

        <?php if ($isStatFilterActive && $statFilterData !== null): ?>
            <!-- Active filter info bar -->
            <div class="mt-4 pt-4 border-t border-slate-100 flex flex-wrap gap-2 items-center">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Menampilkan agregasi:</span>
                <?php if ($activeStatYear !== 'all'): ?>
                    <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded-lg text-[10px] font-black border border-blue-100">
                        <i class="fas fa-calendar mr-1"></i>Tahun <?= esc($activeStatYear) ?>
                    </span>
                <?php endif; ?>
                <?php foreach ((array) $activeStatQuarters as $aq): ?>
                    <?php $aqLabel = $twOptions[$aq] ?? $aq; ?>
                    <span
                        class="px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded-lg text-[10px] font-black border border-indigo-100">
                        <i class="fas fa-layer-group mr-1"></i><?= esc($aqLabel) ?>
                    </span>
                <?php endforeach; ?>
                <span class="ml-auto text-[10px] font-black text-slate-400">
                    Data dari <?= count($statFilterData['upload_ids_used']) ?> upload
                </span>
            </div>
        <?php endif; ?>
    </form>
</div>

<script>
    // Checkbox style toggle for Triwulan
    function updateCheckboxStyle(checkbox) {
        const label = checkbox.closest('.tw-checkbox-label');
        const icon = label.querySelector('i');
        if (checkbox.checked) {
            label.classList.add('checked');
            icon.classList.remove('fa-square');
            icon.classList.add('fa-check-square');
        } else {
            label.classList.remove('checked');
            icon.classList.remove('fa-check-square');
            icon.classList.add('fa-square');
        }
    }
</script>

<!-- Detailed KPI Section -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
        <h2 class="text-xl sm:text-2xl font-black text-slate-900 flex items-center">
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
    $quarterLabels = ['Q1' => 'TW 1', 'Q2' => 'TW 2', 'Q3' => 'TW 3', 'Q4' => 'TW 4'];
    $quarterKeyMap = ['Q1' => 'tambahan_realisasi_tw1', 'Q2' => 'tambahan_realisasi_tw2', 'Q3' => 'tambahan_realisasi_tw3', 'Q4' => 'tambahan_realisasi_tw4'];
    // Ketika filter statistik aktif, LKPM selalu tampilkan semua 4 TW
    if ($isStatFilterActive) {
        $selectedQuarter = 'all';
        $selectedQuarterLabel = null;  // null = tampilkan 4 kolom TW
        $selectedQuarterKey = null;
    } else {
        $selectedQuarter = $data['quarter'] ?? ($data['filters']['quarter'] ?? 'all');
        $selectedQuarterLabel = $quarterLabels[$selectedQuarter] ?? null;
        $selectedQuarterKey = $quarterKeyMap[$selectedQuarter] ?? null;
    }
    $selectedUpload = $data['filters']['upload'] ?? 'all';
    ?>


    <!-- Lampu Sorot Data (Data Spotlight) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <?php
        if ($isStatFilterActive && !empty($data['top_district'])) {
            // Aggregated data — top district from multi-upload
            $winner = [
                'kecamatan' => $data['top_district'],
                'jumlah_proyek' => $data['top_district_count'] ?? 0,
            ];
        } else {
            $top5 = $data['ranking_by_district'] ?? [];
            $winner = !empty($top5) ? $top5[0] : null;
        }
        // Period label
        if ($isStatFilterActive && !empty($data['period_label'])) {
            $periodLabelDisplay = $data['period_label'];
        } else {
            $q = $data['quarter'] ?? '-';
            $y = $data['current_year'] ?? '-';
            $periodLabelDisplay = "$q Tahun $y";
        }
        ?>
        <div
            class="md:col-span-3 bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-950 rounded-2xl p-0.5 shadow-xl shadow-blue-900/10 overflow-hidden">
            <div
                class="bg-slate-900/40 backdrop-blur-xl rounded-[15px] p-5 sm:p-7 flex flex-col md:flex-row items-start md:items-center justify-between gap-5 sm:gap-6">
                <div class="flex items-center gap-6">
                    <div
                        class="w-16 h-16 bg-blue-500/20 rounded-2xl flex items-center justify-center text-blue-400 text-3xl shadow-inner border border-blue-500/30">
                        <i class="fas fa-magic"></i>
                    </div>
                    <div>
                        <h4 class="text-white font-black text-xl tracking-tight">Lampu Sorot Data</h4>
                        <p class="text-slate-300 text-sm font-medium opacity-80">
                            Insight cerdas berdasarkan data <span class="text-blue-400 font-bold"> Periode
                                <span><?= esc($periodLabelDisplay) ?></span>

                        </p>
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

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-5 gap-3 sm:gap-6">
        <!-- Total Investasi -->
        <div class="card p-4 sm:p-7 border-t-4 border-t-blue-600 shadow-blue-glow">
            <div class="flex justify-between items-start mb-3 sm:mb-5">
                <div class="min-w-0 flex-1">
                    <p
                        class="text-slate-500 text-[8px] sm:text-[10px] font-black uppercase tracking-widest leading-none mb-2">
                        Total
                        Realisasi</p>
                    <h3 class="text-[13px] sm:text-2xl font-black text-slate-900 tabular-nums leading-tight">
                        <span class="text-blue-600 font-bold"><?= $currencySymbol ?></span>
                        <?= number_format(($kpiTotalInvestment['PMA'] ?? 0) + ($kpiTotalInvestment['PMDN'] ?? 0), 0, ',', '.') ?>
                    </h3>
                </div>
                <div class="kpi-icon bg-blue-50 text-blue-600 shadow-sm border border-blue-100">
                    <i class="fas fa-money-bill-trend-up"></i>
                </div>
            </div>
            <div class="space-y-2 sm:space-y-3 pt-3 sm:pt-4 border-t border-slate-100 hidden sm:block">
                <div class="flex items-center justify-between p-2 rounded-lg bg-blue-50/30 border border-blue-100/20">
                    <span class="text-[10px] text-slate-500 font-black uppercase tracking-widest">PMA</span>
                    <span class="text-sm font-black text-blue-600 tabular-nums"><?= $currencySymbol ?>
                        <?= number_format($kpiTotalInvestment['PMA'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div
                    class="flex items-center justify-between p-2 rounded-lg bg-emerald-50/30 border border-emerald-100/20">
                    <span class="text-[10px] text-slate-500 font-black uppercase tracking-widest">PMDN</span>
                    <span class="text-sm font-black text-emerald-600 tabular-nums"><?= $currencySymbol ?>
                        <?= number_format($kpiTotalInvestment['PMDN'] ?? 0, 0, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <!-- Tambahan Investasi -->
        <div class="card p-4 sm:p-7 border-t-4 border-t-sky-500 shadow-sky-glow">
            <div class="flex justify-between items-start mb-3 sm:mb-5">
                <div class="min-w-0 flex-1">
                    <p
                        class="text-slate-500 text-[8px] sm:text-[10px] font-black uppercase tracking-widest leading-none mb-2">
                        Tambahan Investasi</p>
                    <h3 class="text-[13px] sm:text-2xl font-black text-slate-900 tabular-nums leading-tight">
                        <span class="text-sky-600 font-bold"><?= $currencySymbol ?></span>
                        <?= number_format(($kpiTotalAdditional['PMA'] ?? 0) + ($kpiTotalAdditional['PMDN'] ?? 0), 0, ',', '.') ?>
                    </h3>
                </div>
                <div class="kpi-icon bg-sky-50 text-sky-600 shadow-sm border border-sky-100">
                    <i class="fas fa-plus-circle"></i>
                </div>
            </div>
            <div class="space-y-2 sm:space-y-3 pt-3 sm:pt-4 border-t border-slate-100 hidden sm:block">
                <div class="flex items-center justify-between p-2 rounded-lg bg-blue-50/30 border border-blue-100/20">
                    <span class="text-[10px] text-slate-500 font-black uppercase tracking-widest">PMA</span>
                    <span class="text-sm font-black text-blue-600 tabular-nums"><?= $currencySymbol ?>
                        <?= number_format($kpiTotalAdditional['PMA'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div
                    class="flex items-center justify-between p-2 rounded-lg bg-emerald-50/30 border border-emerald-100/20">
                    <span class="text-[10px] text-slate-500 font-black uppercase tracking-widest">PMDN</span>
                    <span class="text-sm font-black text-emerald-600 tabular-nums"><?= $currencySymbol ?>
                        <?= number_format($kpiTotalAdditional['PMDN'] ?? 0, 0, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <!-- Total Proyek -->
        <div class="card p-4 sm:p-7 border-t-4 border-t-green-500 shadow-green-glow">
            <div class="flex justify-between items-start mb-3 sm:mb-5">
                <div class="min-w-0 flex-1">
                    <p
                        class="text-slate-500 text-[8px] sm:text-[10px] font-black uppercase tracking-widest leading-none mb-2">
                        Unit
                        Proyek</p>
                    <h3 class="text-[13px] sm:text-2xl font-black text-slate-900 tabular-nums leading-tight">
                        <?= number_format(($kpiTotalProjects['PMA'] ?? 0) + ($kpiTotalProjects['PMDN'] ?? 0)) ?>
                        <span class="text-xs sm:text-sm font-black text-slate-300 ml-1">UNIT</span>
                    </h3>
                </div>
                <div class="kpi-icon bg-green-50 text-green-600 shadow-sm border border-green-100">
                    <i class="fas fa-diagram-project"></i>
                </div>
            </div>
            <div class="space-y-2 sm:space-y-3 pt-3 sm:pt-4 border-t border-slate-100 hidden sm:block">
                <div class="flex items-center justify-between p-2 rounded-lg bg-blue-50/30 border border-blue-100/20">
                    <span class="text-[10px] text-slate-500 font-black uppercase tracking-widest">PMA</span>
                    <span
                        class="text-sm font-black text-blue-600 tabular-nums"><?= number_format($kpiTotalProjects['PMA'] ?? 0) ?>
                        Unit</span>
                </div>
                <div
                    class="flex items-center justify-between p-2 rounded-lg bg-emerald-50/30 border border-emerald-100/20">
                    <span class="text-[10px] text-slate-500 font-black uppercase tracking-widest">PMDN</span>
                    <span
                        class="text-sm font-black text-emerald-600 tabular-nums"><?= number_format($kpiTotalProjects['PMDN'] ?? 0) ?>
                        Unit</span>
                </div>
            </div>
        </div>

        <!-- Tenaga Kerja (TKI) -->
        <div class="card p-4 sm:p-7 border-t-4 border-t-sky-500 shadow-sky-glow">
            <div class="flex justify-between items-start mb-3 sm:mb-5">
                <div class="min-w-0 flex-1">
                    <p
                        class="text-slate-500 text-[8px] sm:text-[10px] font-black uppercase tracking-widest leading-none mb-2">
                        Penyerapan TKI</p>
                    <h3 class="text-[13px] sm:text-2xl font-black text-slate-900 tabular-nums leading-tight">
                        <?= number_format(($kpiWorkforce['PMA']['TKI'] ?? 0) + ($kpiWorkforce['PMDN']['TKI'] ?? 0)) ?>
                        <span class="text-xs sm:text-sm font-black text-slate-300 ml-1">JIWA</span>
                    </h3>
                </div>
                <div class="kpi-icon bg-sky-50 text-sky-600 shadow-sm border border-sky-100">
                    <i class="fas fa-users-viewfinder"></i>
                </div>
            </div>
            <div class="space-y-2 sm:space-y-3 pt-3 sm:pt-4 border-t border-slate-100 hidden sm:block">
                <div class="flex items-center justify-between p-2 rounded-lg bg-blue-50/30 border border-blue-100/20">
                    <span class="text-[10px] text-slate-500 font-black uppercase tracking-widest">PMA</span>
                    <span
                        class="text-sm font-black text-blue-600 tabular-nums"><?= number_format($kpiWorkforce['PMA']['TKI'] ?? 0) ?>
                        Jiwa</span>
                </div>
                <div
                    class="flex items-center justify-between p-2 rounded-lg bg-emerald-50/30 border border-emerald-100/20">
                    <span class="text-[10px] text-slate-500 font-black uppercase tracking-widest">PMDN</span>
                    <span
                        class="text-sm font-black text-emerald-600 tabular-nums"><?= number_format($kpiWorkforce['PMDN']['TKI'] ?? 0) ?>
                        Jiwa</span>
                </div>
            </div>
        </div>

        <!-- Tenaga Kerja (TKA) -->
        <div class="card p-4 sm:p-7 border-t-4 border-t-amber-500 shadow-amber-glow">
            <div class="flex justify-between items-start mb-3 sm:mb-5">
                <div class="min-w-0 flex-1">
                    <p
                        class="text-slate-500 text-[8px] sm:text-[10px] font-black uppercase tracking-widest leading-none mb-2">
                        Tenaga
                        Kerja Asing</p>
                    <h3 class="text-[13px] sm:text-2xl font-black text-slate-900 tabular-nums leading-tight">
                        <?= number_format(($kpiWorkforce['PMA']['TKA'] ?? 0) + ($kpiWorkforce['PMDN']['TKA'] ?? 0)) ?>
                        <span class="text-xs sm:text-sm font-black text-slate-300 ml-1">JIWA</span>
                    </h3>
                </div>
                <div class="kpi-icon bg-amber-50 text-amber-600 shadow-sm border border-amber-100">
                    <i class="fas fa-passport"></i>
                </div>
            </div>
            <div class="space-y-2 sm:space-y-3 pt-3 sm:pt-4 border-t border-slate-100 hidden sm:block">
                <div class="flex items-center justify-between p-2 rounded-lg bg-blue-50/30 border border-blue-100/20">
                    <span class="text-[10px] text-slate-500 font-black uppercase tracking-widest">PMA</span>
                    <span
                        class="text-sm font-black text-blue-600 tabular-nums"><?= number_format($kpiWorkforce['PMA']['TKA'] ?? 0) ?>
                        Jiwa</span>
                </div>
                <div
                    class="flex items-center justify-between p-2 rounded-lg bg-emerald-50/30 border border-emerald-100/20">
                    <span class="text-[10px] text-slate-500 font-black uppercase tracking-widest">PMDN</span>
                    <span
                        class="text-sm font-black text-emerald-600 tabular-nums"><?= number_format($kpiWorkforce['PMDN']['TKA'] ?? 0) ?>
                        Jiwa</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sebaran Proyek per Kecamatan Row -->
<div class="card p-4 sm:p-8 mb-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 sm:mb-8 gap-3">
        <h3 class="text-base sm:text-xl font-black text-slate-800 flex items-center tracking-tight">
            <span class="w-1.5 h-6 bg-green-500 rounded-full mr-3 sm:mr-4"></span>
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
<div class="card p-4 sm:p-8 mb-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 sm:mb-8 gap-3">
        <h3 class="text-base sm:text-xl font-black text-slate-800 flex items-center tracking-tight">
            <span class="w-1.5 h-6 bg-amber-500 rounded-full mr-3 sm:mr-4"></span>
            Tenaga Kerja (TKI & TKA) per Kecamatan
        </h3>
        <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
            <select onchange="switchChartType('workforceChart', this.value)"
                class="text-[10px] font-black border-slate-300 rounded-lg py-1 px-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-100 text-slate-800 shadow-sm cursor-pointer outline-none">
                <option value="bar">Grouped Bar</option>
                <option value="horizontalBar">Horizontal Bar</option>
            </select>
            <span
                class="hidden sm:inline text-[10px] font-black text-slate-400 uppercase tracking-widest px-3 py-1 bg-slate-50 rounded-full border border-slate-100">SDM
                Investasi</span>
        </div>
    </div>
    <div class="h-72 sm:h-[450px] relative bg-slate-50/30 rounded-2xl p-3 sm:p-6 border border-slate-100/50">
        <canvas id="workforceChart"></canvas>
    </div>
</div>

<!-- Secondary Charts Row -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-4 sm:gap-8 mb-8">
    <!-- Chart PMA vs PMDN -->
    <div class="card p-4 sm:p-7">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-5 sm:mb-8 gap-3">
            <h3 class="text-sm sm:text-lg font-black text-slate-800 flex items-center tracking-tight">
                <span class="w-1.5 h-5 bg-blue-600 rounded-full mr-3"></span>
                Rasio Investasi PMA vs PMDN
            </h3>
            <select onchange="switchChartType('ratioChart', this.value)"
                class="text-[10px] font-black border-slate-300 rounded-lg py-1 px-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-100 text-slate-800 shadow-sm cursor-pointer outline-none">
                <option value="doughnut">Doughnut</option>
                <option value="pie">Pie</option>
            </select>
        </div>
        <div class="h-72 sm:h-80 relative">
            <canvas id="ratioChart"></canvas>
        </div>
        <div class="mt-8 flex justify-center gap-12 border-t border-slate-50 pt-6">
            <?php
            $totalInv = ($kpiTotalAdditional['PMA'] ?? 0) + ($kpiTotalAdditional['PMDN'] ?? 0);
            $pmaRatio = $totalInv > 0 ? (($kpiTotalAdditional['PMA'] ?? 0) / $totalInv) * 100 : 0;
            $pmdnRatio = $totalInv > 0 ? (($kpiTotalAdditional['PMDN'] ?? 0) / $totalInv) * 100 : 0;
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
    <div class="card p-4 sm:p-7">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-5 sm:mb-8 gap-3">
            <h3 class="text-sm sm:text-lg font-black text-slate-800 flex items-center tracking-tight">
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
        <div
            class="h-72 sm:h-[28rem] relative bg-slate-50/30 rounded-2xl p-2 sm:p-4 border border-slate-100/50 overflow-visible">
            <canvas id="locationChart"></canvas>
        </div>
    </div>
</div>

<!-- Tertiary Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-8 mb-8">
    <!-- Chart Analisis Sektor -->
    <div class="card p-4 sm:p-7">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-5 sm:mb-8 gap-3">
            <h3 class="text-sm sm:text-lg font-black text-slate-800 flex items-center tracking-tight">
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
        <div
            class="h-72 sm:h-[28rem] relative bg-slate-50/30 rounded-2xl p-2 sm:p-4 border border-slate-100/50 overflow-visible">
            <canvas id="sectorChart"></canvas>
        </div>
    </div>

    <!-- Chart Proyek per Negara -->
    <div class="card p-4 sm:p-7">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-5 sm:mb-8 gap-3">
            <h3 class="text-sm sm:text-lg font-black text-slate-800 flex items-center tracking-tight">
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
        <div class="h-64 sm:h-80 relative bg-slate-50/30 rounded-2xl p-2 sm:p-4 border border-slate-100/50">
            <canvas id="countryChart"></canvas>
        </div>
    </div>
</div>

<!-- Quarterly Trend Section (Full Width) -->
<div class="card p-4 sm:p-8 mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 sm:gap-6 mb-6 sm:mb-10">
        <div class="flex items-center gap-4 sm:gap-6 flex-wrap">
            <h3 class="text-base sm:text-xl font-black text-slate-800 flex items-center tracking-tight">
                <span class="w-1.5 h-6 bg-indigo-500 rounded-full mr-4"></span>
                Tren Investasi Kuartalan
            </h3>
            <select onchange="switchChartType('quarterlyChart', this.value)"
                class="text-[10px] font-black border-slate-300 rounded-lg py-1 px-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-100 text-slate-800 shadow-sm cursor-pointer outline-none">
                <option value="line">Line Chart</option>
                <option value="bar">Bar Chart</option>
            </select>
        </div>
    </div>
    <div class="h-64 sm:h-96 relative bg-slate-50/20 rounded-2xl p-3 sm:p-6 border border-slate-100/50">
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
                            class="text-left py-4 px-4 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                            Sektor</th>
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
                                <td class="py-4 px-4 text-xs font-semibold text-slate-500 max-w-[150px]">
                                    <span
                                        class="inline-block px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-lg border border-indigo-100 text-[10px] font-black leading-tight">
                                        <?= esc($row['sektor'] ?? '-') ?>
                                    </span>
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
                            <td colspan="6" class="py-32 text-center"><i
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
                        class="text-xl font-black tabular-nums"><?= number_format($kpiTotalProjects['PMA'] ?? 0) ?></span>
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
                            class="text-left py-4 px-4 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                            Sektor</th>
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
                                <td class="py-4 px-4 text-xs font-semibold text-slate-500 max-w-[150px]">
                                    <span
                                        class="inline-block px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded-lg border border-emerald-100 text-[10px] font-black leading-tight">
                                        <?= esc($row['sektor'] ?? '-') ?>
                                    </span>
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
                            <td colspan="6" class="py-32 text-center"><i
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
                        class="text-xl font-black tabular-nums"><?= number_format($kpiTotalProjects['PMDN'] ?? 0) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Laporan Perusahaan Triwulan / Quarterly Company Report-->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8" x-data="{ searchPMA: '', searchPMDN: '' }">
    <!-- Tabel PMA -->
    <div class="card flex flex-col">
        <div class="p-6 border-b border-slate-100 bg-white">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                <h3 class="text-lg font-black text-slate-800 flex items-center tracking-tight">
                    <span class="w-1.5 h-6 bg-blue-600 rounded-full mr-4"></span>
                    Laporan Perusahaan LKPM (PMA)
                </h3>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" x-model="searchPMA" placeholder="Cari..."
                            class="form-input text-xs py-2 pl-9 pr-4 rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 w-40 transition-all shadow-sm">
                    </div>
                    <div class="flex gap-1.5 bg-slate-50 p-1 rounded-xl border border-slate-200">
                        <button @click="exportLkpmPdf('PMA')"
                            class="w-8 h-8 rounded-lg bg-white text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all shadow-sm border border-slate-100"
                            title="Ekspor PDF">
                            <i class="fas fa-file-pdf"></i>
                        </button>
                        <button @click="exportLkpmExcel('PMA')"
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
                            class="text-left py-4 px-4 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                            Sektor</th>
                        <?php if ($selectedQuarterLabel): ?>
                            <th
                                class="text-right py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                                <?= $selectedQuarterLabel ?> (<?= $currency ?>)
                            </th>
                        <?php else: ?>
                            <th
                                class="text-right py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                                TW 1 (<?= $currency ?>)</th>
                            <th
                                class="text-right py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                                TW 2 (<?= $currency ?>)</th>
                            <th
                                class="text-right py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                                TW 3 (<?= $currency ?>)</th>
                            <th
                                class="text-right py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                                TW 4 (<?= $currency ?>)</th>
                        <?php endif; ?>
                        <?php if (!$selectedQuarterLabel): ?>
                            <th
                                class="text-right py-4 px-6 text-[10px] font-black text-green-600 uppercase tracking-widest border-b border-slate-100">
                                Total (<?= $currency ?>)</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (!empty($data['lkpm_by_quarter']['PMA']['data'])): ?>
                        <?php foreach ($data['lkpm_by_quarter']['PMA']['data'] as $row): ?>
                            <tr class="hover:bg-blue-50/50 transition-colors group"
                                x-show="'<?= addslashes(strtolower($row['nama_perusahaan'])) ?>'.includes(searchPMA.toLowerCase())">
                                <td
                                    class="py-4 px-6 text-sm font-bold text-slate-700 group-hover:text-blue-700 transition-colors">
                                    <?= esc($row['nama_perusahaan']) ?>
                                </td>
                                <td class="py-4 px-4 text-xs font-semibold text-slate-500 max-w-[150px]">
                                    <span
                                        class="inline-block px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-lg border border-indigo-100 text-[10px] font-black leading-tight">
                                        <?= esc($row['sektor'] ?? '-') ?>
                                    </span>
                                </td>
                                <?php if ($selectedQuarterLabel): ?>
                                    <td class="py-4 px-6 text-sm text-right font-black text-blue-600 tabular-nums">
                                        <?= $currencySymbol ?>
                                        <?= number_format($row[$selectedQuarterKey] ?? 0, 0, ',', '.') ?>
                                    </td>
                                <?php else: ?>
                                    <td class="py-4 px-6 text-sm text-right font-black text-blue-600 tabular-nums">
                                        <?= $currencySymbol ?>
                                        <?= number_format($row['tambahan_realisasi_tw1'] ?? 0, 0, ',', '.') ?>
                                    </td>
                                    <td class="py-4 px-6 text-sm text-right font-black text-blue-600 tabular-nums">
                                        <?= $currencySymbol ?>
                                        <?= number_format($row['tambahan_realisasi_tw2'] ?? 0, 0, ',', '.') ?>
                                    </td>
                                    <td class="py-4 px-6 text-sm text-right font-black text-blue-600 tabular-nums">
                                        <?= $currencySymbol ?>
                                        <?= number_format($row['tambahan_realisasi_tw3'] ?? 0, 0, ',', '.') ?>
                                    </td>
                                    <td class="py-4 px-6 text-sm text-right font-black text-blue-600 tabular-nums">
                                        <?= $currencySymbol ?>
                                        <?= number_format($row['tambahan_realisasi_tw4'] ?? 0, 0, ',', '.') ?>
                                    </td>
                                <?php endif; ?>
                                <?php if (!$selectedQuarterLabel): ?>
                                    <td class="py-4 px-6 text-sm text-right font-black text-green-600 tabular-nums">
                                        <?= $currencySymbol ?>
                                        <?= number_format((float) ($row['tambahan_realisasi'] ?? 0), 0, ',', '.') ?>
                                    </td>
                                <?php endif; ?>
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
                    <span class="text-[9px] font-bold text-blue-400 uppercase tracking-tighter">Total Laporan
                        Triwulanan</span>
                    <span
                        class="text-xl font-black tabular-nums"><?= number_format($data['total_quarterly_reports']['PMA'] ?? 0) ?></span>
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
                    Laporan Perusahaan LKPM (PMDN)
                </h3>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" x-model="searchPMDN" placeholder="Cari..."
                            class="form-input text-xs py-2 pl-9 pr-4 rounded-xl border-slate-200 focus:border-green-500 focus:ring-green-500 w-40 transition-all shadow-sm">
                    </div>
                    <div class="flex gap-1.5 bg-slate-50 p-1 rounded-xl border border-slate-200">
                        <button @click="exportLkpmPdf('PMDN')"
                            class="w-8 h-8 rounded-lg bg-white text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all shadow-sm border border-slate-100"
                            title="Ekspor PDF">
                            <i class="fas fa-file-pdf"></i>
                        </button>
                        <button @click="exportLkpmExcel('PMDN')"
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
                            class="text-left py-4 px-4 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                            Sektor</th>
                        <?php if ($selectedQuarterLabel): ?>
                            <th
                                class="text-right py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                                <?= $selectedQuarterLabel ?> (<?= $currency ?>)
                            </th>
                        <?php else: ?>
                            <th
                                class="text-right py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                                TW 1 (<?= $currency ?>)</th>
                            <th
                                class="text-right py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                                TW 2 (<?= $currency ?>)</th>
                            <th
                                class="text-right py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                                TW 3 (<?= $currency ?>)</th>
                            <th
                                class="text-right py-4 px-6 text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-slate-100">
                                TW 4 (<?= $currency ?>)</th>
                        <?php endif; ?>
                        <?php if (!$selectedQuarterLabel): ?>
                            <th
                                class="text-right py-4 px-6 text-[10px] font-black text-green-600 uppercase tracking-widest border-b border-slate-100">
                                Total (<?= $currency ?>)</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (!empty($data['lkpm_by_quarter']['PMDN']['data'])): ?>
                        <?php foreach ($data['lkpm_by_quarter']['PMDN']['data'] as $row): ?>
                            <tr class="hover:bg-green-50/50 transition-colors group"
                                x-show="'<?= addslashes(strtolower($row['nama_perusahaan'])) ?>'.includes(searchPMDN.toLowerCase())">
                                <td
                                    class="py-4 px-6 text-sm font-bold text-slate-700 group-hover:text-blue-700 transition-colors">
                                    <?= esc($row['nama_perusahaan']) ?>
                                </td>
                                <td class="py-4 px-4 text-xs font-semibold text-slate-500 max-w-[150px]">
                                    <span
                                        class="inline-block px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded-lg border border-emerald-100 text-[10px] font-black leading-tight">
                                        <?= esc($row['sektor'] ?? '-') ?>
                                    </span>
                                </td>
                                <?php if ($selectedQuarterLabel): ?>
                                    <td class="py-4 px-6 text-sm text-right font-black text-blue-600 tabular-nums">
                                        <?= $currencySymbol ?>
                                        <?= number_format($row[$selectedQuarterKey] ?? 0, 0, ',', '.') ?>
                                    </td>
                                <?php else: ?>
                                    <td class="py-4 px-6 text-sm text-right font-black text-blue-600 tabular-nums">
                                        <?= $currencySymbol ?>
                                        <?= number_format($row['tambahan_realisasi_tw1'] ?? 0, 0, ',', '.') ?>
                                    </td>
                                    <td class="py-4 px-6 text-sm text-right font-black text-blue-600 tabular-nums">
                                        <?= $currencySymbol ?>
                                        <?= number_format($row['tambahan_realisasi_tw2'] ?? 0, 0, ',', '.') ?>
                                    </td>
                                    <td class="py-4 px-6 text-sm text-right font-black text-blue-600 tabular-nums">
                                        <?= $currencySymbol ?>
                                        <?= number_format($row['tambahan_realisasi_tw3'] ?? 0, 0, ',', '.') ?>
                                    </td>
                                    <td class="py-4 px-6 text-sm text-right font-black text-blue-600 tabular-nums">
                                        <?= $currencySymbol ?>
                                        <?= number_format($row['tambahan_realisasi_tw4'] ?? 0, 0, ',', '.') ?>
                                    </td>
                                <?php endif; ?>
                                <?php if (!$selectedQuarterLabel): ?>
                                    <td class="py-4 px-6 text-sm text-right font-black text-green-600 tabular-nums">
                                        <?= $currencySymbol ?>
                                        <?= number_format((float) ($row['tambahan_realisasi'] ?? 0), 0, ',', '.') ?>
                                    </td>
                                <?php endif; ?>
                                <!-- <td class="py-4 px-6 text-sm text-center">
                                    <span
                                        class="px-3 py-1 rounded-lg bg-slate-100 text-slate-600 font-black text-[10px] group-hover:bg-green-600 group-hover:text-white transition-all shadow-sm">
                                        <?= number_format($row['jumlah_proyek'] ?? 0) ?>
                                    </span>
                                </td> -->
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
                    <span class="text-[9px] font-bold text-green-400 uppercase tracking-tighter">Total Laporan
                        Triwulanan</span>
                    <span
                        class="text-xl font-black tabular-nums"><?= number_format($data['total_quarterly_reports']['PMDN'] ?? 0) ?></span>
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
                        <?php $isSelectedUpload = $selectedUpload !== 'all' && (string) $selectedUpload === (string) $up['id']; ?>
                        <tr
                            class="hover:bg-slate-50/50 transition-colors <?= $isSelectedUpload ? 'bg-slate-100 border-l-4 border-l-blue-500' : '' ?>">
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
                        <?= number_format($kpiTotalInvestment['PMA'] ?? 0, 0, ',', '.') ?>
                    </td>
                    <td class="p-5 text-right tabular-nums">
                        <?= number_format($kpiTotalInvestment['PMDN'] ?? 0, 0, ',', '.') ?>
                    </td>
                    <td class="p-5 text-right font-black text-slate-900 tabular-nums">
                        <?= number_format(($kpiTotalInvestment['PMA'] ?? 0) + ($kpiTotalInvestment['PMDN'] ?? 0), 0, ',', '.') ?>
                    </td>
                </tr>
                <tr class="border-b-2 border-slate-100">
                    <td class="p-5 font-bold text-slate-700">Jumlah Unit Proyek</td>
                    <td class="p-5 text-right tabular-nums"><?= number_format($kpiTotalProjects['PMA'] ?? 0) ?>
                    </td>
                    <td class="p-5 text-right tabular-nums"><?= number_format($kpiTotalProjects['PMDN'] ?? 0) ?>
                    </td>
                    <td class="p-5 text-right font-black text-slate-900 tabular-nums">
                        <?= number_format(($kpiTotalProjects['PMA'] ?? 0) + ($kpiTotalProjects['PMDN'] ?? 0)) ?>
                    </td>
                </tr>
                <tr>
                    <td class="p-5 font-bold text-slate-700">Penyerapan Tenaga Kerja</td>
                    <td class="p-5 text-right tabular-nums">
                        <?= number_format(($kpiWorkforce['PMA']['TKI'] ?? 0) + ($kpiWorkforce['PMA']['TKA'] ?? 0)) ?>
                    </td>
                    <td class="p-5 text-right tabular-nums">
                        <?= number_format(($kpiWorkforce['PMDN']['TKI'] ?? 0) + ($kpiWorkforce['PMDN']['TKA'] ?? 0)) ?>
                    </td>
                    <td class="p-5 text-right font-black text-slate-900 tabular-nums">
                        <?= number_format(($kpiWorkforce['PMA']['TKI'] ?? 0) + ($kpiWorkforce['PMA']['TKA'] ?? 0) + ($kpiWorkforce['PMDN']['TKI'] ?? 0) + ($kpiWorkforce['PMDN']['TKA'] ?? 0)) ?>
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
                    data: [<?= $kpiTotalAdditional['PMA'] ?? 0 ?>, <?= $kpiTotalAdditional['PMDN'] ?? 0 ?>],
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
                layout: {
                    padding: { right: 120, top: 10, bottom: 10, left: 10 }
                },
                plugins: {
                    legend: { display: false },
                    datalabels: {
                        anchor: 'end',
                        align: 'right',
                        clip: false,
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
                layout: {
                    padding: { right: 60, top: 10, bottom: 10, left: 10 }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { font: { family: 'Inter', weight: '700', size: 10 }, padding: 10 }
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'right',
                        clip: false,
                        offset: 4,
                        color: '#000000',
                        font: { family: 'Inter', weight: '900', size: 11 },
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
                        ticks: { 
                            font: { family: 'Inter', weight: '700', size: 10 }, 
                            color: '#64748b',
                            padding: 25
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Inter', weight: '700', size: 10 }, color: '#64748b' }
                    }
                }
            }
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
            const doc = new jsPDF('l', 'mm', 'a4');
            const listData = dashboardData.sector_count_by_company[type].data;

            if (!listData || listData.length === 0) {
                Swal.fire('Info', 'Tidak ada data untuk diekspor', 'info');
                return;
            }

            doc.setFontSize(16);
            doc.text(`Peringkat Perusahaan ${type}`, 148, 15, { align: 'center' });
            doc.setFontSize(10);
            doc.text(`Dicetak pada: ${new Date().toLocaleString('id-ID')}`, 14, 25);

            const tableBody = listData.map((row, idx) => [
                idx + 1,
                row.nama_perusahaan,
                row.sektor || '-',
                (row.tambahan_realisasi || 0).toLocaleString('id-ID'),
                row.jumlah_tka || 0,
                row.jumlah_tki || 0,
                row.jumlah_proyek || 0
            ]);

            doc.autoTable({
                startY: 30,
                head: [['No', 'Perusahaan', 'Sektor', 'Realisasi', 'TKA', 'TKI', 'Proyek']],
                body: tableBody,
                theme: 'grid',
                headStyles: { fillColor: type === 'PMA' ? [37, 99, 235] : [16, 185, 129] },
                columnStyles: {
                    2: { cellWidth: 50 },
                    3: { halign: 'right' },
                    4: { halign: 'center' },
                    5: { halign: 'center' },
                    6: { halign: 'center' }
                }
            });

            doc.save(`Ranking_${type}_${new Date().getTime()}.pdf`);
        };

        window.exportExcel = function (type) {
            const listData = dashboardData.sector_count_by_company[type].data;
            if (!listData || listData.length === 0) return;

            const worksheet = XLSX.utils.json_to_sheet(listData.map(row => ({
                'Nama Perusahaan': row.nama_perusahaan,
                'Sektor': row.sektor || '-',
                'Tambahan Realisasi': row.tambahan_realisasi,
                'TKA': row.jumlah_tka,
                'TKI': row.jumlah_tki,
                'Jumlah Proyek': row.jumlah_proyek
            })));

            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, `Ranking ${type}`);
            XLSX.writeFile(workbook, `Ranking_${type}_${new Date().getTime()}.xlsx`);
        };

        // ─── LKPM EXPORT FUNCTIONS ───────────────────────────────────────
        window.exportLkpmPdf = function (type) {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('l', 'mm', 'a4'); // landscape for wider table
            const lkpmData = dashboardData.lkpm_by_quarter?.[type]?.data;

            if (!lkpmData || lkpmData.length === 0) {
                Swal.fire('Info', 'Tidak ada data LKPM untuk diekspor', 'info');
                return;
            }

            const currency = currentFilters.currency || 'IDR';
            const prefix = currency === 'USD' ? '$' : 'Rp';

            doc.setFontSize(16);
            doc.text(`Laporan Perusahaan LKPM (${type})`, 148, 15, { align: 'center' });
            doc.setFontSize(10);
            doc.text(`Dicetak pada: ${new Date().toLocaleString('id-ID')}`, 14, 25);
            doc.text(`Mata Uang: ${currency}`, 14, 30);

            const tableBody = lkpmData.map((row, idx) => [
                idx + 1,
                row.nama_perusahaan,
                row.sektor || '-',
                prefix + ' ' + (row.tambahan_realisasi_tw1 || 0).toLocaleString('id-ID'),
                prefix + ' ' + (row.tambahan_realisasi_tw2 || 0).toLocaleString('id-ID'),
                prefix + ' ' + (row.tambahan_realisasi_tw3 || 0).toLocaleString('id-ID'),
                prefix + ' ' + (row.tambahan_realisasi_tw4 || 0).toLocaleString('id-ID'),
                prefix + ' ' + (row.tambahan_realisasi || 0).toLocaleString('id-ID')
            ]);

            doc.autoTable({
                startY: 35,
                head: [['No', 'Perusahaan', 'Sektor', `TW1 (${currency})`, `TW2 (${currency})`, `TW3 (${currency})`, `TW4 (${currency})`, `Total (${currency})`]],
                body: tableBody,
                theme: 'grid',
                headStyles: { fillColor: type === 'PMA' ? [37, 99, 235] : [16, 185, 129], fontSize: 8 },
                styles: { fontSize: 7 },
                columnStyles: {
                    0: { halign: 'center', cellWidth: 10 },
                    2: { cellWidth: 35 },
                    3: { halign: 'right' },
                    4: { halign: 'right' },
                    5: { halign: 'right' },
                    6: { halign: 'right' },
                    7: { halign: 'right', fontStyle: 'bold' }
                }
            });

            doc.save(`LKPM_${type}_${new Date().getTime()}.pdf`);
        };

        window.exportLkpmExcel = function (type) {
            const lkpmData = dashboardData.lkpm_by_quarter?.[type]?.data;
            if (!lkpmData || lkpmData.length === 0) {
                Swal.fire('Info', 'Tidak ada data LKPM untuk diekspor', 'info');
                return;
            }

            const currency = currentFilters.currency || 'IDR';

            const worksheet = XLSX.utils.json_to_sheet(lkpmData.map(row => ({
                'Nama Perusahaan': row.nama_perusahaan,
                'Sektor': row.sektor || '-',
                [`TW1 (${currency})`]: row.tambahan_realisasi_tw1 || 0,
                [`TW2 (${currency})`]: row.tambahan_realisasi_tw2 || 0,
                [`TW3 (${currency})`]: row.tambahan_realisasi_tw3 || 0,
                [`TW4 (${currency})`]: row.tambahan_realisasi_tw4 || 0,
                [`Total (${currency})`]: row.tambahan_realisasi || 0
            })));

            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, `LKPM ${type}`);
            XLSX.writeFile(workbook, `LKPM_${type}_${new Date().getTime()}.xlsx`);
        };

        // --- BUBBLE SORT ANIMATION LOGIC FOR DASHBOARD ---

        function shuffleChartData(chart) {
            if (!chart || !chart.data.datasets[0]) return;
            const datasets = chart.data.datasets;
            const labels = chart.data.labels;
            const n = labels.length;

            for (let i = n - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                // Swap labels
                [labels[i], labels[j]] = [labels[j], labels[i]];
                // Swap data in all datasets
                datasets.forEach(ds => {
                    [ds.data[i], ds.data[j]] = [ds.data[j], ds.data[i]];
                });
            }
            chart.update();
        }

        async function animateBubbleSort(chart) {
            if (!chart || !chart.data.datasets[0]) return;

            const datasets = chart.data.datasets;
            const labels = chart.data.labels;
            const n = labels.length;

            if (n <= 1) return;

            for (let i = 0; i < n - 1; i++) {
                let swapped = false;
                for (let j = 0; j < n - i - 1; j++) {
                    // Logic for comparison: 
                    // If multi-dataset, use sum of values at that index
                    let val1 = 0;
                    let val2 = 0;
                    datasets.forEach(ds => {
                        val1 += Number(ds.data[j] || 0);
                        val2 += Number(ds.data[j + 1] || 0);
                    });

                    if (val1 < val2) { // Descending
                        // Swap labels
                        let tempLabel = labels[j];
                        labels[j] = labels[j + 1];
                        labels[j + 1] = tempLabel;

                        // Swap data in all datasets
                        datasets.forEach(ds => {
                            let tempVal = ds.data[j];
                            ds.data[j] = ds.data[j + 1];
                            ds.data[j + 1] = tempVal;
                        });
                        swapped = true;
                    }
                }

                if (swapped) {
                    chart.update({
                        duration: 400,
                        easing: 'easeOutQuart'
                    });
                    await new Promise(resolve => setTimeout(resolve, 450));
                } else {
                    break;
                }
            }
            chart.update();
        }

        // Trigger animations using IntersectionObserver
        const observerOptions = { threshold: 0.2 };
        const chartObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const canvasId = entry.target.id;
                    const chart = window.charts[canvasId];
                    if (chart) {
                        setTimeout(async () => {
                            shuffleChartData(chart);
                            await new Promise(r => setTimeout(r, 1000));
                            animateBubbleSort(chart);
                        }, 500);
                        chartObserver.unobserve(entry.target);
                    }
                }
            });
        }, observerOptions);

        // Observe the target charts
        ['districtChart', 'workforceChart', 'locationChart'].forEach(id => {
            const el = document.getElementById(id);
            if (el) chartObserver.observe(el);
        });
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