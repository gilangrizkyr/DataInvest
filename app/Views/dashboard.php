<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Statistik PMA dan PMDN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
                <nav class="mt-6">
                    <a href="#" class="block py-2 px-4 text-gray-700 hover:bg-gray-200 rounded">Overview</a>
                    <a href="#" class="block py-2 px-4 text-gray-700 hover:bg-gray-200 rounded">Reports</a>
                </nav>
            </div>
            <!-- Filters -->
            <div class="p-6 border-t">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Filters</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">PMA/PMDN</label>
                        <select id="filter-type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="all">All</option>
                            <option value="PMA">PMA</option>
                            <option value="PMDN">PMDN</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Provinsi</label>
                        <select id="filter-province" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="all">All</option>
                            <!-- Populate dynamically -->
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tahun</label>
                        <select id="filter-year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="all">All</option>
                            <!-- Populate dynamically -->
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <div class="container mx-auto">
                <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard Statistik PMA dan PMDN</h1>

                <!-- Upload Form -->
                <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Upload File Excel</h2>
                    <form action="/dashboard/upload" method="post" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Pilih File Excel (.xlsx)</label>
                            <input type="file" name="excel_file" accept=".xlsx" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Upload dan Proses
                        </button>
                    </form>
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6" id="stats-cards">
                    <!-- Cards will be populated by JavaScript -->
                </div>

                <!-- Charts -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white shadow-lg rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Proporsi PMA vs PMDN</h3>
                        <canvas id="pma-pmdn-chart"></canvas>
                    </div>
                    <div class="bg-white shadow-lg rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Proyek per Kecamatan</h3>
                        <canvas id="district-chart"></canvas>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white shadow-lg rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Investasi per Lokasi</h3>
                        <canvas id="investment-location-chart"></canvas>
                    </div>
                    <div class="bg-white shadow-lg rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Proyek per Sektor</h3>
                        <canvas id="sector-chart"></canvas>
                    </div>
                </div>

                <!-- Download Button -->
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Download Hasil Analisis</h2>
                    <a href="/dashboard/download" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Download Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Data from server
        const data = <?= json_encode($data) ?>;
        const sampleData = data || {
            total_projects: {
                PMA: 0,
                PMDN: 0
            },
            total_investment: {
                PMA: 0,
                PMDN: 0
            },
            projects_by_district: {
                PMA: {},
                PMDN: {}
            },
            investment_by_location: {},
            sector_analysis: []
        };

        // Populate stats cards
        function populateStatsCards() {
            const statsContainer = document.getElementById('stats-cards');
            const totalProjects = sampleData.total_projects.PMA + sampleData.total_projects.PMDN;
            const totalInvestment = sampleData.total_investment.PMA + sampleData.total_investment.PMDN;

            statsContainer.innerHTML = `
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800">Total Proyek</h3>
                    <p class="text-3xl font-bold text-blue-600">${totalProjects}</p>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800">Total Investasi</h3>
                    <p class="text-3xl font-bold text-green-600">Rp ${totalInvestment.toLocaleString()}</p>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800">Proyek PMA</h3>
                    <p class="text-3xl font-bold text-purple-600">${sampleData.total_projects.PMA}</p>
                </div>
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800">Proyek PMDN</h3>
                    <p class="text-3xl font-bold text-orange-600">${sampleData.total_projects.PMDN}</p>
                </div>
            `;
        }

        // Create PMA vs PMDN chart
        function createPmaPmdnChart() {
            const ctx = document.getElementById('pma-pmdn-chart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['PMA', 'PMDN'],
                    datasets: [{
                        data: [sampleData.total_projects.PMA, sampleData.total_projects.PMDN],
                        backgroundColor: ['#3B82F6', '#F59E0B']
                    }]
                }
            });
        }

        // Create district chart
        function createDistrictChart() {
            const ctx = document.getElementById('district-chart').getContext('2d');
            const labels = Object.keys(sampleData.projects_by_district.PMA).concat(Object.keys(sampleData.projects_by_district.PMDN));
            const pmaData = Object.values(sampleData.projects_by_district.PMA);
            const pmdnData = Object.values(sampleData.projects_by_district.PMDN);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'PMA',
                        data: pmaData.concat(new Array(pmdnData.length).fill(0)),
                        backgroundColor: '#3B82F6'
                    }, {
                        label: 'PMDN',
                        data: new Array(pmaData.length).fill(0).concat(pmdnData),
                        backgroundColor: '#F59E0B'
                    }]
                }
            });
        }

        // Create investment by location chart
        function createInvestmentLocationChart() {
            const ctx = document.getElementById('investment-location-chart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: Object.keys(sampleData.investment_by_location),
                    datasets: [{
                        data: Object.values(sampleData.investment_by_location),
                        backgroundColor: '#10B981'
                    }]
                }
            });
        }

        // Create sector chart
        function createSectorChart() {
            const ctx = document.getElementById('sector-chart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: sampleData.sector_analysis.map(item => item.sector),
                    datasets: [{
                        data: sampleData.sector_analysis.map(item => item.count),
                        backgroundColor: '#8B5CF6'
                    }]
                }
            });
        }

        populateStatsCards();
        createPmaPmdnChart();
        createDistrictChart();
        createInvestmentLocationChart();
        createSectorChart();
    </script>
</body>

</html>