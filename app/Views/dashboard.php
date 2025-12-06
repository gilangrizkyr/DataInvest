<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Statistik PMA dan PMDN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-pl5ugin-datalabels"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js"
        integrity="sha512-JPcRR8yFa8mmCsfrw4TNte1ZvF1e3+1SdGMslZvmrzDYxS69J7J49vkFL8u6u8PlPJK+H3voElBtUCzaXj+6ig==" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
</head>

<body class="min-h-screen gradient-bg">
    <div class="min-h-screen">
        <!-- Content -->
        <div class="p-4 md:p-6">
            <div class="container mx-auto max-w-7xl">

                <!-- Navbar -->
                <!-- Navbar -->
                <nav class="bg-gradient-to-r from-blue-800 to-blue-900 shadow-lg mb-8">
                    <div class="container mx-auto px-6 py-4">
                        <div class="flex justify-between items-center">
                            <!-- Logo & Title Section -->
                            <div class="flex items-center space-x-4">
                                <i class="fas fa-chart-pie text-3xl text-blue-300"></i>
                                <div>
                                    <h1 class="text-2xl font-bold text-white">
                                        <?= lang('Dashboard.dashboard_title') ?>
                                    </h1>
                                    <p class="text-blue-100 text-sm"><?= lang('Dashboard.dashboard_subtitle') ?></p>
                                </div>
                            </div>

                            <!-- Navigation Actions -->
                            <div class="flex items-center space-x-3">
                                <!-- Language Switcher -->
                                <?php if (session()->get('role') === 'superadmin'): ?>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-blue-100 text-sm font-medium">
                                            <?= lang('Dashboard.language') ?>:
                                        </span>
                                        <select id="language-switcher"
                                            class="px-5 py-2 bg-gradient-to-r from-blue-400 to-blue-600 text-white font-semibold 
                                     rounded-lg shadow-md hover:from-blue-500 hover:to-blue-700 hover:scale-105 
                                         transform transition duration-300 focus:ring-2 focus:ring-blue-300 cursor-pointer">
                                            <option class="bg-white text-black"
                                                value="id" <?= service('request')->getLocale() === 'id' ? 'selected' : '' ?>>
                                                <?= lang('Dashboard.indonesian') ?>
                                            </option>
                                            <option class="bg-white text-black"
                                                value="en" <?= service('request')->getLocale() === 'en' ? 'selected' : '' ?>>
                                                <?= lang('Dashboard.english') ?>
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Security Check Button -->

                                    <button type="button"
                                        onclick="window.location.href='<?= base_url('security-monitoring') ?>'"
                                        class="px-5 py-2 bg-gradient-to-r from-blue-400 to-blue-600 text-white font-semibold 
                                          rounded-lg shadow-md hover:from-blue-500 hover:to-blue-700 hover:scale-105 
                                              transform transition duration-300">
                                        <i class="fas fa-shield-alt mr-2"></i>
                                        Cek Keamanan
                                    </button>

                                    <!-- User Management Button -->
                                    <button type="button"
                                        onclick="window.location.href='<?= base_url('/user-management') ?>'"
                                        class="px-5 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold 
                           rounded-lg shadow hover:from-green-600 hover:to-green-700 
                           transition duration-200">
                                        <i class="fas fa-users mr-2"></i>
                                        <?= lang('Dashboard.user_management') ?>
                                    </button>
                                <?php endif; ?>
                                <!-- Logout Button -->
                                <button type="button"
                                    onclick="window.location.href='<?= base_url('auth/logout') ?>'"
                                    class="px-5 py-2 bg-red-600 text-white font-semibold 
                           rounded-lg shadow hover:bg-red-700 
                           transition duration-200">
                                    <i class="fas fa-sign-out-alt mr-2"></i>
                                    Logout
                                </button>
                            </div>
                        </div>
                    </div>
                </nav>


                <!-- Control Panels -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Upload Box -->
                    <?php if (session()->get('role') !== 'user'): ?>
                        <div class="glass-card shadow-2xl rounded-xl p-6 chart-container lg:col-span-1">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-upload text-blue-600 mr-3 text-xl"></i>
                                <h2 class="text-xl font-semibold text-gray-800"><?= lang('Dashboard.upload_file_excel') ?></h2>
                            </div>
                            <form action="/dashboard/upload" method="post" enctype="multipart/form-data" class="space-y-4">
                                <div class="relative">
                                    <input type="file" name="excel_file" accept=".xlsx,.xls" id="excel-file-input"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                    <div id="drop-zone" class="border-2 border-dashed border-blue-300 rounded-lg p-6 text-center hover:border-blue-500 transition-colors cursor-pointer">
                                        <i class="fas fa-cloud-upload-alt text-4xl text-blue-400 mb-2"></i>
                                        <p id="upload-text" class="text-gray-600 mb-1"><?= lang('Dashboard.drag_drop_file') ?></p>
                                        <p class="text-sm text-gray-500"><?= lang('Dashboard.supported_formats') ?></p>
                                        <p id="file-name" class="text-sm text-blue-600 font-medium mt-2 hidden"></p>
                                    </div>
                                </div>
                                <button type="submit"
                                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3 px-6 rounded-lg transition-all transform hover:scale-105 shadow-lg">
                                    <i class="fas fa-cloud-upload-alt mr-2"></i><?= lang('Dashboard.upload_and_process') ?>
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>

                    <!-- Manajemen Upload -->
                    <div class="glass-card shadow-xl rounded-xl p-6 chart-container lg:col-span-2">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-folder-open text-blue-600 mr-3 text-xl"></i>
                                <h2 class="text-xl font-semibold text-gray-800"><?= lang('Dashboard.upload_management') ?></h2>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-dollar-sign mr-1 text-yellow-600"></i><?= lang('Dashboard.currency') ?></label>
                                <select id="filter-currency" class="rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <option value="IDR">IDR (Rp)</option>
                                    <option value="USD">$ USD</option>
                                </select>
                            </div>
                        </div>

                        <?php if (!empty($data['uploads'])): ?>
                            <div class="overflow-x-auto">
                                <table class="min-w-full table-auto">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= lang('Dashboard.upload_name') ?></th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= lang('Dashboard.quarter') ?></th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= lang('Dashboard.year') ?></th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= lang('Dashboard.status') ?></th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= lang('Dashboard.total_records') ?></th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai USD</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= lang('Dashboard.upload_date') ?></th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= lang('Dashboard.actions') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php foreach ($data['uploads'] as $upload): ?>
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($upload['upload_name'] ?? 'N/A'); ?>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($upload['quarter'] ?? '-'); ?>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($upload['year'] ?? '-'); ?>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <?php
                                                    $status = $upload['status'] ?? 'unknown';
                                                    $statusClasses = [
                                                        'completed' => 'bg-green-100 text-green-800',
                                                        'failed' => 'bg-red-100 text-red-800',
                                                        'processing' => 'bg-yellow-100 text-yellow-800',
                                                        'uploaded' => 'bg-blue-100 text-blue-800'
                                                    ];
                                                    $statusLabels = [
                                                        'completed' => lang('Dashboard.status_completed'),
                                                        'failed' => lang('Dashboard.status_failed'),
                                                        'processing' => lang('Dashboard.status_processing'),
                                                        'uploaded' => lang('Dashboard.status_uploaded')
                                                    ];
                                                    ?>
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php echo $statusClasses[$status] ?? 'bg-gray-100 text-gray-800'; ?>">
                                                        <?php echo $statusLabels[$status] ?? ucfirst($status); ?>
                                                    </span>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo number_format($upload['total_records'] ?? 0); ?>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo number_format($upload['usd_value'] ?? 0, 2, ',', '.'); ?>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo date('d/m/Y H:i', strtotime($upload['created_at'] ?? 'upload_date')); ?>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex space-x-2">
                                                        <a href="/dashboard?upload=<?php echo $upload['id']; ?>"
                                                            class="text-green-600 hover:text-green-900 transition-colors"
                                                            title="View Chart">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <?php if (session()->get('role') !== 'user'): ?>
                                                            <?php if ($upload['status'] === 'completed'): ?>
                                                                <a href="/dashboard/edit-metadata/<?php echo $upload['id']; ?>"
                                                                    class="text-blue-600 hover:text-blue-900 transition-colors"
                                                                    title="Edit Metadata">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                            <?php endif; ?>
                                                            <form action="/dashboard/deleteUpload" method="post" class="inline-block" id="delete-form-<?php echo $upload['id']; ?>">
                                                                <input type="hidden" name="upload_id" value="<?php echo $upload['id']; ?>">
                                                                <button type="button" onclick="confirmDelete(<?php echo $upload['id']; ?>)" class="text-red-600 hover:text-red-900 transition-colors" title="<?= lang('Dashboard.delete') ?>">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>

                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <i class="fas fa-folder-open text-gray-400 text-4xl mb-4"></i>
                                <p class="text-gray-500"><?= lang('Dashboard.no_uploads') ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Chart Options -->
                    <div class="glass-card shadow-xl rounded-xl p-6 chart-container lg:col-span-1">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-chart-bar mr-2 text-green-600"></i><?= lang('Dashboard.chart_options') ?>
                        </h3>
                        <div class="grid grid-cols-1 gap-3">
                            <label class="flex items-center">
                                <input type="checkbox" id="show-pma-pmdn" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700"><?= lang('Dashboard.pma_vs_pmdn') ?></span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" id="show-district" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700"><?= lang('Dashboard.projects_by_district') ?></span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" id="show-investment" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700"><?= lang('Dashboard.investment_by_location') ?></span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" id="show-sector" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700"><?= lang('Dashboard.projects_by_sector') ?></span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" id="show-workforce-pma" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700"><?= lang('Dashboard.workforce_pma') ?></span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" id="show-workforce-pmdn" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700"><?= lang('Dashboard.workforce_pmdn') ?></span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" id="show-ranking-district" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700"><?= lang('Dashboard.ranking_projects_district') ?></span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" id="show-projects-pma" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700"><?= lang('Dashboard.projects_pma_district') ?></span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" id="show-projects-pmdn" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700"><?= lang('Dashboard.projects_pmdn_district') ?></span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" id="show-country" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700"><?= lang('Dashboard.projects_by_country') ?></span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" id="show-quarterly-additional-investment" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Quarterly Additional Investment</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- STAT CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8" id="stats-cards"></div>

                <!-- CHARTS -->
                <div id="charts-container">
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-4" id="chart-row-1">
                        <div class="glass-card shadow-xl rounded-xl p-4 chart-container" id="pma-pmdn-container">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-chart-pie mr-3 text-blue-600"></i><?= lang('Dashboard.pma_pmdn_ratio') ?>
                                </h3>
                                <select id="pma-pmdn-type" class="text-sm border rounded px-2 py-1">
                                    <option value="pie">Pie</option>
                                    <option value="doughnut">Doughnut</option>
                                    <option value="bar">Bar</option>
                                </select>
                            </div>
                            <canvas id="pma-pmdn-chart" height="200"></canvas>
                        </div>

                        <div class="glass-card shadow-xl rounded-xl p-4 chart-container" id="district-container">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-map-marker-alt mr-3 text-green-600"></i><?= lang('Dashboard.projects_per_district') ?>
                                </h3>
                                <select id="district-type" class="text-sm border rounded px-2 py-1">
                                    <option value="bar">Bar</option>
                                    <option value="line">Line</option>
                                    <option value="horizontalBar">Horizontal Bar</option>
                                    <option value="pie">Pie</option>
                                </select>
                            </div>
                            <canvas id="district-chart" height="200"></canvas>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-4" id="chart-row-2">
                        <div class="glass-card shadow-xl rounded-xl p-4 chart-container" id="investment-container">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-money-bill-wave mr-3 text-emerald-600"></i><?= lang('Dashboard.investment_per_district_top10') ?>
                                </h3>
                                <select id="investment-type" class="text-sm border rounded px-2 py-1">
                                    <option value="bar">Bar</option>
                                    <option value="line">Line</option>
                                    <option value="area">Area</option>
                                    <option value="pie">Pie</option>
                                </select>
                            </div>
                            <canvas id="investment-location-chart" height="250"></canvas>
                        </div>

                        <div class="glass-card shadow-xl rounded-xl p-4 chart-container" id="sector-container">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-industry mr-3 text-purple-600"></i><?= lang('Dashboard.projects_per_sector') ?>
                                </h3>
                                <select id="sector-type" class="text-sm border rounded px-2 py-1">
                                    <option value="horizontalBar">Horizontal Bar</option>
                                    <option value="bar">Vertical Bar</option>
                                    <option value="pie">Pie</option>
                                </select>
                            </div>
                            <canvas id="sector-chart" height="200"></canvas>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-4" id="chart-row-3">
                        <div class="glass-card shadow-xl rounded-xl p-4 chart-container" id="workforce-pma-container">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-users mr-3 text-red-600"></i><?= lang('Dashboard.workforce_pma_district') ?>
                                </h3>
                                <select id="workforce-pma-type" class="text-sm border rounded px-2 py-1">
                                    <option value="bar">Bar</option>
                                    <option value="line">Line</option>
                                    <option value="horizontalBar">Horizontal Bar</option>
                                    <option value="stacked">Stacked</option>
                                    <option value="pie">Pie</option>
                                </select>
                            </div>
                            <canvas id="workforce-pma-chart" height="200"></canvas>
                        </div>

                        <div class="glass-card shadow-xl rounded-xl p-4 chart-container" id="workforce-pmdn-container">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-user-friends mr-3 text-orange-600"></i><?= lang('Dashboard.workforce_pmdn_district') ?>
                                </h3>
                                <select id="workforce-pmdn-type" class="text-sm border rounded px-2 py-1">
                                    <option value="bar">Bar</option>
                                    <option value="line">Line</option>
                                    <option value="horizontalBar">Horizontal Bar</option>
                                    <option value="stacked">Stacked</option>
                                    <option value="pie">Pie</option>
                                </select>
                            </div>
                            <canvas id="workforce-pmdn-chart" height="200"></canvas>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 mb-4" id="chart-row-4">
                        <div class="glass-card shadow-xl rounded-xl p-4 chart-container" id="ranking-district-container">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-trophy mr-3 text-yellow-600"></i><?= lang('Dashboard.ranking_projects_district_full') ?>
                                </h3>
                                <select id="ranking-district-type" class="text-sm border rounded px-2 py-1">
                                    <option value="bar">Bar</option>
                                    <option value="horizontalBar">Horizontal Bar</option>
                                    <option value="pie">Pie</option>
                                </select>
                            </div>
                            <div class="flex flex-col lg:flex-row gap-6">
                                <div class="flex-1">
                                    <canvas id="ranking-district-chart" height="200"></canvas>
                                </div>
                                <div class="lg:w-80 bg-gray-50 rounded-lg p-4 border">
                                    <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                        <i class="fas fa-medal mr-2 text-yellow-500"></i><?= lang('Dashboard.ranking_per_district') ?>
                                    </h4>
                                    <div id="ranking-list" class="space-y-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-4" id="chart-row-5">
                        <div class="glass-card shadow-xl rounded-xl p-4 chart-container" id="projects-pma-container">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-building mr-3 text-blue-600"></i><?= lang('Dashboard.projects_pma_district') ?>
                                </h3>
                                <select id="projects-pma-type" class="text-sm border rounded px-2 py-1">
                                    <option value="bar">Bar</option>
                                    <option value="line">Line</option>
                                    <option value="horizontalBar">Horizontal Bar</option>
                                    <option value="pie">Pie</option>
                                </select>
                            </div>
                            <canvas id="projects-pma-chart" height="200"></canvas>
                        </div>

                        <div class="glass-card shadow-xl rounded-xl p-4 chart-container" id="projects-pmdn-container">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-home mr-3 text-orange-600"></i><?= lang('Dashboard.projects_pmdn_district') ?>
                                </h3>
                                <select id="projects-pmdn-type" class="text-sm border rounded px-2 py-1">
                                    <option value="bar">Bar</option>
                                    <option value="line">Line</option>
                                    <option value="horizontalBar">Horizontal Bar</option>
                                    <option value="horizontalLine">Horizontal Line</option>
                                    <option value="pie">Pie</option>
                                </select>
                            </div>
                            <canvas id="projects-pmdn-chart" height="300"></canvas>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-4" id="chart-row-5">
                        <div class="grid grid-cols-1 gap-4 mb-4" id="chart-row-6">
                            <div class="glass-card shadow-xl rounded-xl p-4 chart-container" id="country-container">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                        <i class="fas fa-globe mr-3 text-emerald-600"></i><?= lang('Dashboard.projects_per_country') ?>
                                    </h3>
                                    <select id="country-type" class="text-sm border rounded px-2 py-1">
                                        <option value="bar">Bar</option>
                                        <option value="horizontalBar">Horizontal Bar</option>
                                        <option value="pie">Pie</option>
                                        <option value="doughnut">Doughnut</option>
                                    </select>
                                </div>
                                <canvas id="country-chart" height="200"></canvas>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 mb-4" id="chart-row-7">
                            <div class="glass-card shadow-xl rounded-xl p-4 chart-container" id="quarterly-additional-investment-container" style="display: block;">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                        <i class="fas fa-calendar-alt mr-3 text-indigo-600"></i><?= lang('Dashboard.quarterly_additional_investment') ?>
                                    </h3>
                                    <div class="flex items-center space-x-4">
                                        <?php if (session()->get('role') === 'superadmin'): ?>
                                            <div class="flex items-center space-x-2">
                                                <label class="text-sm font-medium text-gray-700">Tahun:<?= lang('') ?></label>
                                                <select id="quarterly-additional-investment-year" class="text-sm border rounded px-2 py-1">
                                                    <option value="all">Semua Tahun</option>
                                                    <?php
                                                    $availableYears = array_keys($data['charts']['quarterly_additional_investment_all_years'] ?? []);
                                                    sort($availableYears);
                                                    foreach ($availableYears as $year) {
                                                        $selected = (isset($data['filters']['quarterly_year']) && $data['filters']['quarterly_year'] == $year) ? 'selected' : '';
                                                        echo "<option value=\"$year\" $selected>$year</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        <?php endif; ?>
                                        <select id="quarterly-additional-investment-type" class="text-sm border rounded px-2 py-1">
                                            <option value="bar">Bar</option>
                                            <option value="line">Line</option>
                                            <option value="area">Area</option>
                                            <option value="pie">Pie</option>
                                        </select>
                                    </div>
                                </div>
                                <canvas id="quarterly-additional-investment-chart" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ADDITIONAL INVESTMENT PERCENTAGES -->
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-4">
                    <div class="glass-card shadow-xl rounded-xl p-4 chart-container">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-percentage mr-3 text-purple-600"></i><?= lang('Dashboard.additional_pma_investment_district') ?>
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= lang('Dashboard.district') ?></th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= lang('Dashboard.percentage') ?></th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= lang('Dashboard.investment_amount') ?></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php if (!empty($data['additional_investment_percentages']['PMA'])): ?>
                                        <?php
                                        $additional_investment_percentages_pma = $data['additional_investment_percentages']['PMA'];
                                        uasort($additional_investment_percentages_pma, function ($a, $b) {
                                            return $b['percentage'] <=> $a['percentage'];
                                        });
                                        ?>
                                        <?php foreach ($additional_investment_percentages_pma as $district => $info): ?>
                                            <tr>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($district); ?></td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500"><?php echo number_format($info['percentage'], 1); ?>%</td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500"><?php echo number_format($info['amount'], 0, ',', '.'); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr class="bg-gray-100 font-bold">
                                            <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">Total</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">100.0%</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500"><?php echo number_format(array_sum(array_column($additional_investment_percentages_pma, 'amount')), 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="px-4 py-2 text-center text-sm text-gray-500"><?= lang('Dashboard.no_data') ?></td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="glass-card shadow-xl rounded-xl p-4 chart-container">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-percentage mr-3 text-orange-600"></i><?= lang('Dashboard.additional_pmdn_investment_district') ?>
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= lang('Dashboard.district') ?></th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= lang('Dashboard.percentage') ?></th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= lang('Dashboard.investment_amount') ?></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php if (!empty($data['additional_investment_percentages']['PMDN'])): ?>
                                        <?php
                                        $additional_investment_percentages_pmdn = $data['additional_investment_percentages']['PMDN'];
                                        uasort($additional_investment_percentages_pmdn, function ($a, $b) {
                                            return $b['percentage'] <=> $a['percentage'];
                                        });
                                        ?>
                                        <?php foreach ($additional_investment_percentages_pmdn as $district => $info): ?>
                                            <tr>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($district); ?></td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500"><?php echo number_format($info['percentage'], 1); ?>%</td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500"><?php echo number_format($info['amount'], 0, ',', '.'); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr class="bg-gray-100 font-bold">
                                            <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">Total</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">100.0%</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500"><?php echo number_format(array_sum(array_column($additional_investment_percentages_pmdn, 'amount')), 0, ',', '.'); ?></td>
                                        </tr>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="px-4 py-2 text-center text-sm text-gray-500"><?= lang('Dashboard.no_data') ?></td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- DOWNLOAD -->
                <div class="glass-card shadow-xl rounded-xl p-6 chart-container">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-download text-green-600 mr-3 text-xl"></i>
                        <h2 class="text-xl font-semibold text-gray-800"><?= lang('Dashboard.download_analysis_results') ?></h2>
                    </div>
                    <a href="/dashboard/download"
                        class="inline-flex items-center bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold py-3 px-6 rounded-lg transition-all transform hover:scale-105 shadow-lg">
                        <i class="fas fa-file-excel mr-2"></i><?= lang('Dashboard.download_excel') ?>
                    </a>
                </div>



            </div>
        </div>
    </div>
    <script>
        const data = <?= json_encode($data, JSON_HEX_TAG) ?>;
        const currentFilters = <?= json_encode($data['filters'] ?? []) ?>;
    </script>
    <script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
    <script src="<?= base_url('assets/js/charts.js') ?>"></script>

    <!-- Show flashdata messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?= session()->getFlashdata('success') ?>',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            });
        </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?= session()->getFlashdata('error') ?>',
                confirmButtonText: 'OK'
            });
        </script>
    <?php endif; ?>

    <footer class="bg-gray-800/80 backdrop-blur-lg border-t border-gray-700 shadow-inner mt-12">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-center items-center text-sm text-white/80">
            &copy; <?= date('Y') ?> DPMPTSP-Tanah Bumbu
        </div>
    </footer>




</body>

</html>