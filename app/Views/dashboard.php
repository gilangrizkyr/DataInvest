<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Statistik PMA dan PMDN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .chart-container {
            transition: all 0.3s ease;
        }

        .chart-container:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .sidebar-transition {
            transition: all 0.3s ease;
        }

        @media (max-width: 768px) {
            .sidebar-mobile {
                transform: translateX(-100%);
            }

            .sidebar-mobile.open {
                transform: translateX(0);
            }
        }
    </style>
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
                                    <div class="flex items-center space-x-2">
                                        <label class="text-sm font-medium text-gray-700">Tahun:</label>
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
        let currency = (currentFilters && currentFilters.currency) ? currentFilters.currency : "IDR";

        const usdRate = data.usd_rate ?? 15000;

        function generateColors(count) {
            const colors = [];
            for (let i = 0; i < count; i++) {
                const hue = (i * 360 / Math.max(count, 1)) % 360;
                colors.push('hsl(' + hue + ', 70%, 50%)');
            }
            return colors;
        }

        function formatRp(num) {
            if (!num) return 'Rp 0';
            return 'Rp ' + Number(num).toLocaleString('id-ID');
        }

        function formatUSD(num) {
            if (!num) return '$ 0';
            return '$ ' + Number(num).toLocaleString('en-US');
        }

        // Convert IDR to selected currency
        function convertCurrency(val) {
            if (currency === "USD") return val / usdRate;
            return val;
        }

        const totalProjectsPMA = parseInt(data.total_projects?.PMA ?? 0) || 0;
        const totalProjectsPMDN = parseInt(data.total_projects?.PMDN ?? 0) || 0;

        const invPMA = parseFloat(data.total_investment?.PMA ?? 0) || 0;
        const invPMDN = parseFloat(data.total_investment?.PMDN ?? 0) || 0;

        const addInvPMA = parseFloat(data.total_additional_investment?.PMA ?? 0) || 0;
        const addInvPMDN = parseFloat(data.total_additional_investment?.PMDN ?? 0) || 0;

        function populateStatsCards() {
            const totalInv = invPMA + invPMDN;
            const totalAddInv = addInvPMA + addInvPMDN;
            const invPMAConverted = invPMA;
            const invPMDNConverted = invPMDN;
            const addInvPMAConverted = addInvPMA;
            const addInvPMDNConverted = addInvPMDN;

            const formatNumber = (num) => {
                const str = currency === "USD" ? formatUSD(num) : formatRp(num);
                return str.length > 15 ? '<span class="text-2xl">' + str + '</span>' : '<span class="text-3xl">' + str + '</span>';
            };

            const statsContainer = document.getElementById('stats-cards');
            statsContainer.innerHTML =
                '<div class="modern-card bg-gradient-to-br from-blue-50 to-blue-100 shadow-xl rounded-xl p-6 chart-container animate-fade-in border-l-4 border-blue-500">' +
                '<div class="flex items-center justify-between">' +
                '<div class="flex-1">' +
                '<h3 class="text-lg font-semibold text-gray-800 mb-2">' + '<?= lang('Dashboard.total_projects') ?>' + '</h3>' +
                '<p class="font-bold text-blue-600">' + (totalProjectsPMA + totalProjectsPMDN) + '</p>' +
                '<p class="text-sm text-gray-600 mt-1">' + '<?= lang('Dashboard.pma_pmdn_combined') ?>' + '</p>' +
                '</div>' +
                '<div class="bg-blue-100 p-4 rounded-full shadow-lg">' +
                '<i class="fas fa-project-diagram text-3xl text-blue-600"></i>' +
                '</div>' +
                '</div>' +
                '</div>' +

                '<div class="modern-card bg-gradient-to-br from-green-50 to-green-100 shadow-xl rounded-xl p-6 chart-container animate-fade-in border-l-4 border-green-500">' +
                '<div class="flex items-center justify-between">' +
                '<div class="flex-1">' +
                '<h3 class="text-lg font-semibold text-gray-800 mb-2">' + '<?= lang('Dashboard.total_investment') ?>' + '</h3>' +
                '<p class="font-bold text-green-600 overflow-hidden text-ellipsis">' + formatNumber(totalInv) + '</p>' +
                '<p class="text-sm text-gray-600 mt-1">' + '<?= lang('Dashboard.pma_pmdn_investment_value') ?>' + '</p>' +
                '</div>' +
                '<div class="bg-green-100 p-4 rounded-full shadow-lg">' +
                '<i class="fas fa-money-bill-wave text-3xl text-green-600"></i>' +
                '</div>' +
                '</div>' +
                '</div>' +

                '<div class="modern-card bg-gradient-to-br from-teal-50 to-teal-100 shadow-xl rounded-xl p-6 chart-container animate-fade-in border-l-4 border-teal-500">' +
                '<div class="flex items-center justify-between">' +
                '<div class="flex-1">' +
                '<h3 class="text-lg font-semibold text-gray-800 mb-2">' + '<?= lang('Dashboard.total_additional_investment') ?>' + '</h3>' +
                '<p class="font-bold text-teal-600 overflow-hidden text-ellipsis">' + formatNumber(totalAddInv) + '</p>' +
                '<p class="text-sm text-gray-600 mt-1">' + '<?= lang('Dashboard.additional_pma_pmdn_investment') ?>' + '</p>' +
                '</div>' +
                '<div class="bg-teal-100 p-4 rounded-full shadow-lg">' +
                '<i class="fas fa-plus-circle text-3xl text-teal-600"></i>' +
                '</div>' +
                '</div>' +
                '</div>' +

                '<div class="modern-card bg-gradient-to-br from-purple-50 to-purple-100 shadow-xl rounded-xl p-6 chart-container animate-fade-in border-l-4 border-purple-500">' +
                '<div class="flex items-center justify-between">' +
                '<div class="flex-1">' +
                '<h3 class="text-lg font-semibold text-gray-800 mb-2">' + '<?= lang('Dashboard.pma_projects') ?>' + '</h3>' +
                '<p class="font-bold text-purple-600">' + totalProjectsPMA + '</p>' +
                '<p class="text-sm text-gray-600 mt-1">' + '<?= lang('Dashboard.foreign_direct_investment') ?>' + '</p>' +
                '</div>' +
                '<div class="bg-purple-100 p-4 rounded-full shadow-lg">' +
                '<i class="fas fa-globe text-3xl text-purple-600"></i>' +
                '</div>' +
                '</div>' +
                '</div>' +

                '<div class="modern-card bg-gradient-to-br from-orange-50 to-orange-100 shadow-xl rounded-xl p-6 chart-container animate-fade-in border-l-4 border-orange-500">' +
                '<div class="flex items-center justify-between">' +
                '<div class="flex-1">' +
                '<h3 class="text-lg font-semibold text-gray-800 mb-2">' + '<?= lang('Dashboard.pmdn_projects') ?>' + '</h3>' +
                '<p class="font-bold text-orange-600">' + totalProjectsPMDN + '</p>' +
                '<p class="text-sm text-gray-600 mt-1">' + '<?= lang('Dashboard.domestic_direct_investment') ?>' + '</p>' +
                '</div>' +
                '<div class="bg-orange-100 p-4 rounded-full shadow-lg">' +
                '<i class="fas fa-home text-3xl text-orange-600"></i>' +
                '</div>' +
                '</div>' +
                '</div>' +

                '<div class="modern-card bg-gradient-to-br from-indigo-50 to-indigo-100 shadow-xl rounded-xl p-6 chart-container animate-fade-in border-l-4 border-indigo-500">' +
                '<div class="flex items-center justify-between">' +
                '<div class="flex-1">' +
                '<h3 class="text-lg font-semibold text-gray-800 mb-2">' + '<?= lang('Dashboard.total_pma_investment') ?>' + '</h3>' +
                '<p class="font-bold text-indigo-600 overflow-hidden text-ellipsis">' + formatNumber(invPMAConverted) + '</p>' +
                '<p class="text-sm text-gray-600 mt-1">' + '<?= lang('Dashboard.pma_investment') ?>' + '</p>' +
                '</div>' +
                '<div class="bg-indigo-100 p-4 rounded-full shadow-lg">' +
                '<i class="fas fa-money-bill-wave text-3xl text-indigo-600"></i>' +
                '</div>' +
                '</div>' +
                '</div>' +

                '<div class="modern-card bg-gradient-to-br from-cyan-50 to-cyan-100 shadow-xl rounded-xl p-6 chart-container animate-fade-in border-l-4 border-cyan-500">' +
                '<div class="flex items-center justify-between">' +
                '<div class="flex-1">' +
                '<h3 class="text-lg font-semibold text-gray-800 mb-2">' + '<?= lang('Dashboard.total_pmdn_investment') ?>' + '</h3>' +
                '<p class="font-bold text-cyan-600 overflow-hidden text-ellipsis">' + formatNumber(invPMDNConverted) + '</p>' +
                '<p class="text-sm text-gray-600 mt-1">' + '<?= lang('Dashboard.pmdn_investment') ?>' + '</p>' +
                '</div>' +
                '<div class="bg-cyan-100 p-4 rounded-full shadow-lg">' +
                '<i class="fas fa-money-bill-wave text-3xl text-cyan-600"></i>' +
                '</div>' +
                '</div>' +
                '</div>' +

                '<div class="modern-card bg-gradient-to-br from-pink-50 to-pink-100 shadow-xl rounded-xl p-6 chart-container animate-fade-in border-l-4 border-pink-500">' +
                '<div class="flex items-center justify-between">' +
                '<div class="flex-1">' +
                '<h3 class="text-lg font-semibold text-gray-800 mb-2">' + '<?= lang('Dashboard.additional_pma_investment') ?>' + '</h3>' +
                '<p class="font-bold text-pink-600 overflow-hidden text-ellipsis">' + formatNumber(addInvPMAConverted) + '</p>' +
                '<p class="text-sm text-gray-600 mt-1">' + '<?= lang('Dashboard.additional_pma_investment_desc') ?>' + '</p>' +
                '</div>' +
                '<div class="bg-pink-100 p-4 rounded-full shadow-lg">' +
                '<i class="fas fa-plus-circle text-3xl text-pink-600"></i>' +
                '</div>' +
                '</div>' +
                '</div>' +

                '<div class="modern-card bg-gradient-to-br from-lime-50 to-lime-100 shadow-xl rounded-xl p-6 chart-container animate-fade-in border-l-4 border-lime-500">' +
                '<div class="flex items-center justify-between">' +
                '<div class="flex-1">' +
                '<h3 class="text-lg font-semibold text-gray-800 mb-2">' + '<?= lang('Dashboard.additional_pmdn_investment') ?>' + '</h3>' +
                '<p class="font-bold text-lime-600 overflow-hidden text-ellipsis">' + formatNumber(addInvPMDNConverted) + '</p>' +
                '<p class="text-sm text-gray-600 mt-1">' + '<?= lang('Dashboard.additional_pmdn_investment_desc') ?>' + '</p>' +
                '</div>' +
                '<div class="bg-lime-100 p-4 rounded-full shadow-lg">' +
                '<i class="fas fa-plus-circle text-3xl text-lime-600"></i>' +
                '</div>' +
                '</div>' +
                '</div>';
        }

        // ================= CHARTS ======================= //
        let pmaPmdnChart = null,
            districtChart = null,
            locationChart = null,
            sectorChart = null,
            workforcePmaChart = null,
            workforcePmdnChart = null,
            rankingDistrictChart = null,
            projectsPmaChart = null,
            projectsPmdnChart = null,
            countryChart = null,
            quarterlyAdditionalInvestmentChart = null;

        function createCharts() {
            pmaPmdnChart?.destroy();
            districtChart?.destroy();
            locationChart?.destroy();
            sectorChart?.destroy();
            workforcePmaChart?.destroy();
            workforcePmdnChart?.destroy();
            rankingDistrictChart?.destroy();
            projectsPmaChart?.destroy();
            projectsPmdnChart?.destroy();
            countryChart?.destroy();
            quarterlyAdditionalInvestmentChart?.destroy();

            pmaPmdnChart = new Chart(document.getElementById('pma-pmdn-chart'), {
                type: 'pie',
                data: {
                    labels: ['PMA', 'PMDN'],
                    datasets: [{
                        data: [totalProjectsPMA, totalProjectsPMDN],
                        backgroundColor: ['#3B82F6', '#F59E0B']
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const label = ctx.label || '';
                                    const value = ctx.raw || 0;
                                    const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${label}: ${value} proyek (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            const dist = data.charts.district;
            districtChart = new Chart(document.getElementById('district-chart'), {
                type: 'bar',
                data: {
                    labels: dist.labels,
                    datasets: [{
                        label: 'PMA',
                        data: dist.pma,
                        backgroundColor: '#3B82F6'
                    }, {
                        label: 'PMDN',
                        data: dist.pmdn,
                        backgroundColor: '#F59E0B'
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const district = ctx.label || '';
                                    const value = ctx.raw || 0;
                                    const type = ctx.dataset.label;
                                    return `${type} - ${district}: ${value} proyek`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            // LOCATION //
            const loc = data.charts.locations;

            locationChart = new Chart(document.getElementById('investment-location-chart'), {
                type: 'bar',
                data: {
                    labels: loc.labels,
                    datasets: [{
                        label: "Investasi",
                        data: loc.values,
                        backgroundColor: '#10B981'
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    return currency === "USD" ?
                                        formatUSD(ctx.raw) :
                                        formatRp(ctx.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            sectorChart = new Chart(document.getElementById('sector-chart'), {
                type: 'bar',
                data: {
                    labels: data.charts.sectors.labels,
                    datasets: [{
                        label: 'Jumlah Proyek',
                        data: data.charts.sectors.counts,
                        backgroundColor: '#8B5CF6'
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const sector = ctx.label || '';
                                    const value = ctx.raw || 0;
                                    return `${sector}: ${value} proyek`;
                                }
                            }
                        }
                    },
                    indexAxis: 'y'
                }
            });

            // WORKFORCE CHARTS //
            const workforceData = data.workforce_by_district || {};
            const workforcePma = workforceData.PMA || {};
            const pmaLabels = Object.keys(workforcePma);
            const pmaTki = pmaLabels.map(l => workforcePma[l].TKI ?? 0);
            const pmaTka = pmaLabels.map(l => workforcePma[l].TKA ?? 0);

            workforcePmaChart = new Chart(document.getElementById('workforce-pma-chart'), {
                type: 'bar',
                data: {
                    labels: pmaLabels,
                    datasets: [{
                        label: 'TKI',
                        data: pmaTki,
                        backgroundColor: '#EF4444'
                    }, {
                        label: 'TKA',
                        data: pmaTka,
                        backgroundColor: '#F97316'
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const district = ctx.label || '';
                                    const value = ctx.raw || 0;
                                    const type = ctx.dataset.label;
                                    return `${type} - ${district}: ${value} orang`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            const workforcePmdn = workforceData.PMDN || {};
            const pmdnLabels = Object.keys(workforcePmdn);
            const pmdnTki = pmdnLabels.map(l => workforcePmdn[l].TKI ?? 0);
            const pmdnTka = pmdnLabels.map(l => workforcePmdn[l].TKA ?? 0);

            workforcePmdnChart = new Chart(document.getElementById('workforce-pmdn-chart'), {
                type: 'bar',
                data: {
                    labels: pmdnLabels,
                    datasets: [{
                        label: 'TKI',
                        data: pmdnTki,
                        backgroundColor: '#EF4444'
                    }, {
                        label: 'TKA',
                        data: pmdnTka,
                        backgroundColor: '#F97316'
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const district = ctx.label || '';
                                    const value = ctx.raw || 0;
                                    const type = ctx.dataset.label;
                                    return `${type} - ${district}: ${value} orang`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // RANKING DISTRICT CHART //
            const rankingData = data.ranking_by_district || [];
            const rankingLabels = rankingData.map(item => item.district);
            const rankingValues = rankingData.map(item => item.total_projects);

            rankingDistrictChart = new Chart(document.getElementById('ranking-district-chart'), {
                type: 'bar',
                data: {
                    labels: rankingLabels,
                    datasets: [{
                        label: 'Total Proyek',
                        data: rankingValues,
                        backgroundColor: '#F59E0B'
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const district = ctx.label || '';
                                    const value = ctx.raw || 0;
                                    return `${district}: ${value} proyek`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Populate ranking list (all districts)
            const rankingList = document.getElementById('ranking-list');
            rankingList.innerHTML = '';
            rankingData.forEach((item, index) => {
                const medalColors = ['text-yellow-500', 'text-gray-400', 'text-amber-600', 'text-gray-500', 'text-gray-400'];
                const medalIcons = ['fa-trophy', 'fa-medal', 'fa-medal', 'fa-medal', 'fa-medal'];
                const li = document.createElement('li');
                li.className = 'flex items-center justify-between p-2 bg-white rounded-lg shadow-sm';
                li.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas ${index < 5 ? medalIcons[index] : 'fa-medal'} ${index < 5 ? medalColors[index] : 'text-gray-400'} mr-3"></i>
                        <span class="font-medium text-gray-800">${item.district}</span>
                    </div>
                    <span class="font-bold text-blue-600">${item.total_projects} proyek</span>
                `;
                rankingList.appendChild(li);
            });

            // PROJECTS PMA CHART //
            projectsPmaChart = new Chart(document.getElementById('projects-pma-chart'), {
                type: 'bar',
                data: {
                    labels: dist.labels,
                    datasets: [{
                        label: 'PMA',
                        data: dist.pma,
                        backgroundColor: '#3B82F6'
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const district = ctx.label || '';
                                    const value = ctx.raw || 0;
                                    return `PMA - ${district}: ${value} proyek`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // PROJECTS PMDN CHART //
            projectsPmdnChart = new Chart(document.getElementById('projects-pmdn-chart'), {
                type: 'bar',
                data: {
                    labels: dist.labels,
                    datasets: [{
                        label: 'PMDN',
                        data: dist.pmdn,
                        backgroundColor: '#F59E0B'
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const district = ctx.label || '';
                                    const value = ctx.raw || 0;
                                    return `PMDN - ${district}: ${value} proyek`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // COUNTRY CHART //
            const countryData = data.charts.countries;
            countryChart = new Chart(document.getElementById('country-chart'), {
                type: 'bar',
                data: {
                    labels: countryData.labels,
                    datasets: [{
                        label: 'Jumlah Proyek',
                        data: countryData.counts,
                        backgroundColor: '#10B981'
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const country = ctx.label || '';
                                    const value = ctx.raw || 0;
                                    return `${country}: ${value} proyek`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // QUARTERLY ADDITIONAL INVESTMENT CHART //
            const quarterlyData = data.charts.quarterly_additional_investment;
            console.log('Quarterly Additional Investment Data:', quarterlyData);
            console.log('Quarterly Data Labels:', quarterlyData ? quarterlyData.labels : 'No labels');
            console.log('Quarterly Data Values:', quarterlyData ? quarterlyData.values : 'No values');

            if (quarterlyData && quarterlyData.labels && quarterlyData.values) {
                console.log('Creating chart with data:', {
                    labels: quarterlyData.labels,
                    values: quarterlyData.values
                });

                const canvasElement = document.getElementById('quarterly-additional-investment-chart');
                console.log('Canvas element:', canvasElement);
                console.log('Canvas element exists:', !!canvasElement);

                if (canvasElement) {
                    quarterlyAdditionalInvestmentChart = new Chart(canvasElement, {
                        type: 'bar',
                        data: {
                            labels: quarterlyData.labels,
                            datasets: [{
                                label: 'Additional Investment',
                                data: quarterlyData.values,
                                backgroundColor: '#6366F1'
                            }]
                        },
                        options: {
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function(ctx) {
                                            return currency === "USD" ?
                                                formatUSD(ctx.raw) :
                                                formatRp(ctx.raw);
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                    console.log('Quarterly Additional Investment Chart created successfully');
                    console.log('Chart instance:', quarterlyAdditionalInvestmentChart);
                    console.log('Chart canvas after creation:', canvasElement);
                } else {
                    console.error('Canvas element not found!');
                }
            } else {
                console.log('Quarterly Additional Investment Data is missing or invalid');
                console.log('quarterlyData exists:', !!quarterlyData);
                console.log('quarterlyData.labels exists:', quarterlyData ? !!quarterlyData.labels : false);
                console.log('quarterlyData.values exists:', quarterlyData ? !!quarterlyData.values : false);
            }
        }
        // Show flashdata messages as SweetAlert2 popups
        <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?= session()->getFlashdata('success') ?>',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            });
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?= session()->getFlashdata('error') ?>',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>

        populateStatsCards();
        createCharts();



        // Chart visibility toggles
        const chartCheckboxes = [{
                id: 'show-pma-pmdn',
                container: 'pma-pmdn-container'
            },
            {
                id: 'show-district',
                container: 'district-container'
            },
            {
                id: 'show-investment',
                container: 'investment-container'
            },
            {
                id: 'show-sector',
                container: 'sector-container'
            },
            {
                id: 'show-workforce-pma',
                container: 'workforce-pma-container'
            },
            {
                id: 'show-workforce-pmdn',
                container: 'workforce-pmdn-container'
            },
            {
                id: 'show-ranking-district',
                container: 'ranking-district-container'
            },
            {
                id: 'show-projects-pma',
                container: 'projects-pma-container'
            },
            {
                id: 'show-projects-pmdn',
                container: 'projects-pmdn-container'
            },
            {
                id: 'show-country',
                container: 'country-container'
            },
            {
                id: 'show-quarterly-additional-investment',
                container: 'quarterly-additional-investment-container'
            }
        ];

        chartCheckboxes.forEach(({
            id,
            container
        }) => {
            const checkbox = document.getElementById(id);
            const containerEl = document.getElementById(container);

            // Debug logging for quarterly chart
            if (id === 'show-quarterly-additional-investment') {
                console.log('Quarterly checkbox element:', checkbox);
                console.log('Quarterly container element:', containerEl);
                console.log('Quarterly checkbox checked:', checkbox ? checkbox.checked : 'Checkbox not found');
                console.log('Quarterly container display:', containerEl ? containerEl.style.display : 'Container not found');
                console.log('Quarterly container computed style:', containerEl ? window.getComputedStyle(containerEl).display : 'Container not found');
            }

            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    containerEl.style.display = 'block';
                } else {
                    containerEl.style.display = 'none';
                }

                // Debug logging for quarterly chart
                if (id === 'show-quarterly-additional-investment') {
                    console.log('Quarterly chart visibility changed to:', containerEl.style.display);
                }
            });
        });

        // Chart type switching
        document.getElementById('pma-pmdn-type').addEventListener('change', function() {
            if (pmaPmdnChart) {
                pmaPmdnChart.destroy();
            }
            const chartType = this.value === 'doughnut' ? 'doughnut' : this.value;
            pmaPmdnChart = new Chart(document.getElementById('pma-pmdn-chart'), {
                type: chartType,
                data: {
                    labels: ['PMA', 'PMDN'],
                    datasets: [{
                        data: [totalProjectsPMA, totalProjectsPMDN],
                        backgroundColor: ['#3B82F6', '#F59E0B']
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const label = ctx.label || '';
                                    const value = ctx.raw || 0;
                                    const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${label}: ${value} proyek (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        });

        document.getElementById('district-type').addEventListener('change', function() {
            if (districtChart) {
                districtChart.destroy();
            }
            const chartType = this.value;
            const isHorizontal = chartType === 'horizontalBar';
            const actualType = isHorizontal ? 'bar' : chartType;
            const dist = data.charts.district;
            districtChart = new Chart(document.getElementById('district-chart'), {
                type: actualType,
                data: {
                    labels: dist.labels,
                    datasets: [{
                        label: 'PMA',
                        data: dist.pma,
                        backgroundColor: '#3B82F6'
                    }, {
                        label: 'PMDN',
                        data: dist.pmdn,
                        backgroundColor: '#F59E0B'
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const district = ctx.label || '';
                                    const value = ctx.raw || 0;
                                    const type = ctx.dataset.label;
                                    return `${type} - ${district}: ${value} proyek`;
                                }
                            }
                        }
                    },
                    scales: actualType !== 'pie' ? {
                        y: {
                            beginAtZero: true
                        }
                    } : {},
                    indexAxis: isHorizontal ? 'y' : 'x'
                }
            });
        });

        // Investment chart type switching
        document.getElementById('investment-type').addEventListener('change', function() {
            locationChart?.destroy();
            const chartType = this.value;
            const loc = data.charts.locations;

            let config = {
                type: chartType === 'area' ? 'line' : chartType,
                data: {
                    labels: loc.labels,
                    datasets: [{
                        label: "Investasi",
                        data: loc.values,
                        backgroundColor: chartType === 'area' ? '#10B981' : '#10B981',
                        fill: chartType === 'area'
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    return currency === "USD" ?
                                        formatUSD(ctx.raw) :
                                        formatRp(ctx.raw);
                                }
                            }
                        }
                    },
                    scales: chartType !== 'pie' ? {
                        y: {
                            beginAtZero: true
                        }
                    } : {}
                }
            };

            locationChart = new Chart(document.getElementById('investment-location-chart'), config);
        });

        // Sector chart type switching
        document.getElementById('sector-type').addEventListener('change', function() {
            if (sectorChart) {
                sectorChart.destroy();
            }
            const chartType = this.value;
            const isHorizontal = chartType === 'horizontalBar';

            sectorChart = new Chart(document.getElementById('sector-chart'), {
                type: isHorizontal ? 'bar' : chartType,
                data: {
                    labels: data.charts.sectors.labels,
                    datasets: [{
                        label: 'Jumlah Proyek',
                        data: data.charts.sectors.counts,
                        backgroundColor: chartType === 'pie' ? generateColors(data.charts.sectors.labels.length) : '#8B5CF6'
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const sector = ctx.label || '';
                                    const value = ctx.raw || 0;
                                    return `${sector}: ${value} proyek`;
                                }
                            }
                        }
                    },
                    indexAxis: isHorizontal ? 'y' : 'x',
                    scales: chartType !== 'pie' ? {
                        y: {
                            beginAtZero: true
                        }
                    } : {}
                }
            });
        });

        // Workforce PMA chart type switching
        document.getElementById('workforce-pma-type').addEventListener('change', function() {
            if (workforcePmaChart) {
                workforcePmaChart.destroy();
            }
            const chartType = this.value;
            const workforceData = data.workforce_by_district || {};
            const workforcePma = workforceData.PMA || {};
            const pmaLabels = Object.keys(workforcePma);
            const pmaTki = pmaLabels.map(l => workforcePma[l].TKI ?? 0);
            const pmaTka = pmaLabels.map(l => workforcePma[l].TKA ?? 0);

            let config = {
                type: chartType === 'stacked' ? 'bar' : chartType,
                data: {
                    labels: pmaLabels,
                    datasets: [{
                        label: 'TKI',
                        data: pmaTki,
                        backgroundColor: '#EF4444'
                    }, {
                        label: 'TKA',
                        data: pmaTka,
                        backgroundColor: '#F97316'
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const district = ctx.label || '';
                                    const value = ctx.raw || 0;
                                    const type = ctx.dataset.label;
                                    return `${type} - ${district}: ${value} orang`;
                                }
                            }
                        }
                    },
                    scales: chartType !== 'pie' ? {
                        y: {
                            beginAtZero: true
                        }
                    } : {},
                    indexAxis: chartType === 'horizontalBar' ? 'y' : 'x'
                }
            };

            if (chartType === 'stacked') {
                config.options.scales.x = {
                    stacked: true
                };
                config.options.scales.y = {
                    stacked: true
                };
            }

            workforcePmaChart = new Chart(document.getElementById('workforce-pma-chart'), config);
        });

        // Workforce PMDN chart type switching
        document.getElementById('workforce-pmdn-type').addEventListener('change', function() {
            if (workforcePmdnChart) {
                workforcePmdnChart.destroy();
            }
            const chartType = this.value;
            const workforceData = data.workforce_by_district || {};
            const workforcePmdn = workforceData.PMDN || {};
            const pmdnLabels = Object.keys(workforcePmdn);
            const pmdnTki = pmdnLabels.map(l => workforcePmdn[l].TKI ?? 0);
            const pmdnTka = pmdnLabels.map(l => workforcePmdn[l].TKA ?? 0);

            let config = {
                type: chartType === 'stacked' ? 'bar' : chartType,
                data: {
                    labels: pmdnLabels,
                    datasets: [{
                        label: 'TKI',
                        data: pmdnTki,
                        backgroundColor: '#EF4444'
                    }, {
                        label: 'TKA',
                        data: pmdnTka,
                        backgroundColor: '#F97316'
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const district = ctx.label || '';
                                    const value = ctx.raw || 0;
                                    const type = ctx.dataset.label;
                                    return `${type} - ${district}: ${value} orang`;
                                }
                            }
                        }
                    },
                    scales: chartType !== 'pie' ? {
                        y: {
                            beginAtZero: true
                        }
                    } : {},
                    indexAxis: chartType === 'horizontalBar' ? 'y' : 'x'
                }
            };

            if (chartType === 'stacked') {
                config.options.scales.x = {
                    stacked: true
                };
                config.options.scales.y = {
                    stacked: true
                };
            }

            workforcePmdnChart = new Chart(document.getElementById('workforce-pmdn-chart'), config);
        });

        // Ranking district chart type switching
        document.getElementById('ranking-district-type').addEventListener('change', function() {
            if (rankingDistrictChart) {
                rankingDistrictChart.destroy();
            }
            const chartType = this.value;
            const rankingData = data.ranking_by_district || [];
            const rankingLabels = rankingData.map(item => item.district);
            const rankingValues = rankingData.map(item => item.total_projects);

            rankingDistrictChart = new Chart(document.getElementById('ranking-district-chart'), {
                type: chartType === 'horizontalBar' ? 'bar' : chartType,
                data: {
                    labels: rankingLabels,
                    datasets: [{
                        label: 'Total Proyek',
                        data: rankingValues,
                        backgroundColor: '#F59E0B'
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const district = ctx.label || '';
                                    const value = ctx.raw || 0;
                                    return `${district}: ${value} proyek`;
                                }
                            }
                        }
                    },
                    scales: chartType !== 'pie' ? {
                        y: {
                            beginAtZero: true
                        }
                    } : {},
                    indexAxis: chartType === 'horizontalBar' ? 'y' : 'x'
                }
            });
        });

        // Projects PMA chart type switching
        document.getElementById('projects-pma-type').addEventListener('change', function() {
            if (projectsPmaChart) {
                projectsPmaChart.destroy();
            }
            const chartType = this.value;
            const dist = data.charts.district;

            projectsPmaChart = new Chart(document.getElementById('projects-pma-chart'), {
                type: chartType === 'horizontalBar' || chartType === 'horizontalLine' ? 'bar' : chartType,
                data: {
                    labels: dist.labels,
                    datasets: [{
                        label: 'PMA',
                        data: dist.pma,
                        backgroundColor: '#3B82F6'
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const district = ctx.label || '';
                                    const value = ctx.raw || 0;
                                    return `PMA - ${district}: ${value} proyek`;
                                }
                            }
                        }
                    },
                    scales: chartType !== 'pie' ? {
                        y: {
                            beginAtZero: true
                        }
                    } : {},
                    indexAxis: chartType === 'horizontalBar' ? 'y' : 'x',
                    elements: chartType === 'horizontalLine' ? {
                        point: {
                            radius: 0
                        },
                        line: {
                            borderWidth: 2
                        }
                    } : {}
                }
            });
        });

        // Projects PMDN chart type switching
        document.getElementById('projects-pmdn-type').addEventListener('change', function() {
            if (projectsPmdnChart) {
                projectsPmdnChart.destroy();
            }
            const chartType = this.value;
            const dist = data.charts.district;

            projectsPmdnChart = new Chart(document.getElementById('projects-pmdn-chart'), {
                type: chartType === 'horizontalBar' || chartType === 'horizontalLine' ? 'bar' : chartType,
                data: {
                    labels: dist.labels,
                    datasets: [{
                        label: 'PMDN',
                        data: dist.pmdn,
                        backgroundColor: '#F59E0B'
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const district = ctx.label || '';
                                    const value = ctx.raw || 0;
                                    return `PMDN - ${district}: ${value} proyek`;
                                }
                            }
                        }
                    },
                    scales: chartType !== 'pie' ? {
                        y: {
                            beginAtZero: true
                        }
                    } : {},
                    indexAxis: chartType === 'horizontalBar' ? 'y' : 'x',
                    elements: chartType === 'horizontalLine' ? {
                        point: {
                            radius: 0
                        },
                        line: {
                            borderWidth: 2
                        }
                    } : {}
                }
            });
        });

        // Country chart type switching
        document.getElementById('country-type').addEventListener('change', function() {
            if (countryChart) {
                countryChart.destroy();
            }
            const chartType = this.value;
            const countryData = data.charts.countries;

            countryChart = new Chart(document.getElementById('country-chart'), {
                type: chartType === 'horizontalBar' ? 'bar' : chartType,
                data: {
                    labels: countryData.labels,
                    datasets: [{
                        label: 'Jumlah Proyek',
                        data: countryData.counts,
                        backgroundColor: chartType === 'pie' || chartType === 'doughnut' ? generateColors(countryData.labels.length) : '#10B981'
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const country = ctx.label || '';
                                    const value = ctx.raw || 0;
                                    return `${country}: ${value} proyek`;
                                }
                            }
                        }
                    },
                    scales: chartType !== 'pie' && chartType !== 'doughnut' ? {
                        y: {
                            beginAtZero: true
                        }
                    } : {},
                    indexAxis: chartType === 'horizontalBar' ? 'y' : 'x'
                }
            });
        });

        // Quarterly Additional Investment chart type and year switching
        function updateQuarterlyChart() {
            console.log('updateQuarterlyChart called');
            if (quarterlyAdditionalInvestmentChart) {
                quarterlyAdditionalInvestmentChart.destroy();
            }

            const chartType = document.getElementById('quarterly-additional-investment-type').value;
            const selectedYear = document.getElementById('quarterly-additional-investment-year').value;

            console.log('Selected year:', selectedYear);
            console.log('Chart type:', chartType);
            console.log('Available quarterly data:', data.charts.quarterly_additional_investment_all_years);

            // Get data for selected year
            let quarterlyData;
            if (selectedYear === 'all') {
                quarterlyData = data.charts.quarterly_additional_investment;
                console.log('Using all years data:', quarterlyData);
            } else {
                // Filter data for specific year
                const allQuarterlyData = data.charts.quarterly_additional_investment_all_years || data.charts.quarterly_additional_investment;
                quarterlyData = allQuarterlyData[selectedYear] || {
                    labels: ['Q1', 'Q2', 'Q3', 'Q4'],
                    values: [0, 0, 0, 0]
                };
                console.log('Using specific year data for', selectedYear, ':', quarterlyData);
            }

            let config = {
                type: chartType === 'area' ? 'line' : chartType,
                data: {
                    labels: quarterlyData.labels,
                    datasets: [{
                        label: 'Additional Investment',
                        data: quarterlyData.values,
                        backgroundColor: chartType === 'area' ? '#6366F1' : '#6366F1',
                        fill: chartType === 'area'
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    return currency === "USD" ?
                                        formatUSD(ctx.raw) :
                                        formatRp(ctx.raw);
                                }
                            }
                        }
                    },
                    scales: chartType !== 'pie' ? {
                        y: {
                            beginAtZero: true
                        }
                    } : {}
                }
            };

            quarterlyAdditionalInvestmentChart = new Chart(document.getElementById('quarterly-additional-investment-chart'), config);
        }

        document.getElementById('quarterly-additional-investment-type').addEventListener('change', updateQuarterlyChart);
        document.getElementById('quarterly-additional-investment-year').addEventListener('change', updateQuarterlyChart);

        // Filter data based on currency selection
        function applyCurrencyFilter() {
            const selectedCurrency = document.getElementById('filter-currency').value;

            // Get current URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const currentUpload = urlParams.get('upload') || 'all';

            // Build URL with current upload and new currency
            const params = new URLSearchParams();
            if (currentUpload !== 'all') params.append('upload', currentUpload);
            if (selectedCurrency !== 'IDR') params.append('currency', selectedCurrency);

            // Redirect to dashboard with filters
            const url = '/dashboard' + (params.toString() ? '?' + params.toString() : '');
            window.location.href = url;
        }

        // Set current currency value from server
        if (currentFilters && typeof currentFilters === 'object') {
            if (currentFilters.currency) document.getElementById('filter-currency').value = currentFilters.currency;
        }

        // Currency filter
        document.getElementById("filter-currency").addEventListener("change", applyCurrencyFilter);



        // Add some CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in {
                animation: fadeIn 0.6s ease-out;
            }
        `;
        document.head.appendChild(style);

        // Drag and drop functionality
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('excel-file-input');
        const uploadText = document.getElementById('upload-text');
        const fileName = document.getElementById('file-name');

        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        // Highlight drop zone when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        // Handle drop
        dropZone.addEventListener('drop', handleDrop, false);

        // Handle file input change
        fileInput.addEventListener('change', handleFileSelect, false);

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight(e) {
            dropZone.classList.add('border-blue-600', 'bg-blue-50');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-blue-600', 'bg-blue-50');
        }

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0) {
                // Use DataTransfer to properly set files on input
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(files[0]);
                fileInput.files = dataTransfer.files;
                // Trigger change event to ensure form recognizes the file
                fileInput.dispatchEvent(new Event('change', {
                    bubbles: true
                }));
                displayFileName(files[0]);
            }
        }

        function handleFileSelect(e) {
            const files = e.target.files;
            if (files.length > 0) {
                displayFileName(files[0]);
            }
        }

        function displayFileName(file) {
            const fileNameText = file.name.length > 30 ? file.name.substring(0, 27) + '...' : file.name;
            fileName.textContent = `File dipilih: ${fileNameText}`;
            fileName.classList.remove('hidden');
            uploadText.textContent = 'File berhasil dipilih!';
        }

        function confirmDelete(uploadId) {
            Swal.fire({
                title: '<?= lang('Dashboard.confirm_delete') ?>',
                text: "Apakah Anda yakin ingin menghapus unggahan ini beserta seluruh datanya?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + uploadId).submit();
                }
            });
        }

        document.getElementById('language-switcher').addEventListener('change', function() {
            const selectedLanguage = this.value;

            fetch('/dashboard/setLanguage', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'language=' + encodeURIComponent(selectedLanguage)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Mengubah Bahasa',
                            text: 'Failed to set language: ' + (data.message || 'Unknown error'),
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'An error occurred while changing language',
                        confirmButtonText: 'OK'
                    });
                });
        });
    </script>

    <footer class="bg-gray-800/80 backdrop-blur-lg border-t border-gray-700 shadow-inner mt-12">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-center items-center text-sm text-white/80">
            &copy; <?= date('Y') ?> DPMPTSP-Tanah Bumbu
        </div>
    </footer>




</body>

</html>