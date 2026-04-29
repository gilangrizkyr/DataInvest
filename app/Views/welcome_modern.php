<?php $this->extend('layouts/app'); ?>

<?php $this->section('content'); ?>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    #investmentMap {
        height: 500px;
        width: 100%;
        border-radius: 24px;
        z-index: 10;
    }

    .info-legend {
        padding: 10px;
        font: 12px/14px Arial, Helvetica, sans-serif;
        background: white;
        background: rgba(255, 255, 255, 0.9);
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        border-radius: 12px;
        line-height: 18px;
        color: #333;
    }

    .info-legend i {
        width: 18px;
        height: 18px;
        float: left;
        margin-right: 8px;
        opacity: 0.7;
        border-radius: 4px;
    }

    .map-tooltip {
        background: rgba(15, 23, 42, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
        border-radius: 12px;
        padding: 12px;
        font-family: 'Inter', sans-serif;
    }
</style>

<!-- Hero Section: Professional Branding -->
<div class="relative overflow-hidden rounded-3xl bg-slate-900 mb-12 shadow-2xl">
    <!-- Abstract Background elements -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[100px] -mr-64 -mt-64">
    </div>
    <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-indigo-600/10 rounded-full blur-[80px] -ml-48 -mb-48">
    </div>

    <div class="relative z-10 px-8 py-20 md:px-16 md:py-24 flex flex-col items-center text-center">
        <div
            class="inline-flex items-center space-x-2 bg-blue-500/10 border border-blue-500/20 px-4 py-2 rounded-full mb-8">
            <span class="relative flex h-3 w-3">
                <span
                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span>
            </span>
            <span class="text-blue-400 text-xs font-bold uppercase tracking-wider">Portal Informasi Publik</span>
        </div>

        <h1 class="text-4xl md:text-6xl font-black text-white mb-6 leading-tight max-w-4xl">
            Realisasi Investasi <br />
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Kabupaten Tanah
                Bumbu</span>
        </h1>

        <p class="text-xl text-slate-400 mb-10 max-w-2xl leading-relaxed">
            DataInvest adalah platform resmi manajemen dan analisis Realisasi Investasi terintegrasi milik DPMPTSP Tanah
            Bumbu. Memastikan pertumbuhan ekonomi yang terukur dan transparan.
        </p>

        <div class="flex flex-col sm:flex-row gap-4">
            <a href="#public-stats"
                class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold transition transform hover:scale-105 shadow-xl shadow-blue-900/40">
                <i class="fas fa-chart-pie mr-2"></i>Lihat Statistik
            </a>
        </div>
    </div>
</div>

<!-- Live Public Statistics Section -->
<div id="public-stats" class="mb-16 scroll-mt-24">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4 px-2">
        <div>
            <h2 class="text-3xl font-black text-slate-900 mb-2">Statistik Utama</h2>
            <p class="text-slate-500">Ringkasan realisasi investasi berdasarkan data terunggah terbaru.</p>
        </div>
        <div class="px-4 py-2 bg-slate-100 rounded-xl border border-slate-200">
            <span class="text-sm text-slate-500 font-semibold italic">Diperbarui: <?= date('d F Y') ?></span>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <!-- Total Realisasi (IDR) -->
        <div
            class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 group hover:border-blue-500 transition-all">
            <div
                class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 transition-colors">
                <i class="fas fa-coins text-blue-600 text-xl group-hover:text-white"></i>
            </div>
            <h3 class="text-slate-500 font-bold mb-1">Total Realisasi (IDR)</h3>
            <div class="text-2xl font-black text-slate-900 mb-2">
                <?php $totalRealisasi = array_sum($data['total_additional_investment'] ?? []); ?>
                Rp <?= number_format($totalRealisasi, 0, ',', '.') ?>
            </div>
            <p class="text-xs text-slate-400 font-medium">Realisasi kumulatif saat ini</p>
        </div>

        <!-- Total Proyek -->
        <div
            class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 group hover:border-emerald-500 transition-all">
            <div
                class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-emerald-600 transition-colors">
                <i class="fas fa-project-diagram text-emerald-600 text-xl group-hover:text-white"></i>
            </div>
            <h3 class="text-slate-500 font-bold mb-1">Total Proyek</h3>
            <div class="text-2xl font-black text-slate-900 mb-2">
                <?php $totalProyek = array_sum($data['total_projects'] ?? []); ?>
                <?= number_format($totalProyek, 0, ',', '.') ?> Proyek
            </div>
            <p class="text-xs text-slate-400 font-medium">Cakupan penyebaran proyek</p>
        </div>

        <!-- Tenaga Kerja -->
        <div
            class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 group hover:border-amber-500 transition-all">
            <div
                class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-amber-600 transition-colors">
                <i class="fas fa-user-tie text-amber-600 text-xl group-hover:text-white"></i>
            </div>
            <h3 class="text-slate-500 font-bold mb-1">Tenaga Kerja (TKI+TKA)</h3>
            <div class="text-2xl font-black text-slate-900 mb-2">
                <?php
                $totalTKI = ($data['workforce']['PMA']['TKI'] ?? 0) + ($data['workforce']['PMDN']['TKI'] ?? 0);
                $totalTKA = ($data['workforce']['PMA']['TKA'] ?? 0) + ($data['workforce']['PMDN']['TKA'] ?? 0);
                ?>
                <?= number_format($totalTKI + $totalTKA, 0, ',', '.') ?> Orang
            </div>
            <p class="text-xs text-slate-400 font-medium">Penyerapan tenaga kerja lokal & asing</p>
        </div>

        <!-- Capaian Wilayah -->
        <div
            class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 group hover:border-purple-500 transition-all">
            <div
                class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-purple-600 transition-colors">
                <i class="fas fa-map-marked-alt text-purple-600 text-xl group-hover:text-white-all"></i>
            </div>
            <h3 class="text-slate-500 font-bold mb-1">Cakupan Kecamatan</h3>
            <div class="text-2xl font-black text-slate-900 mb-2">
                <?= count($data['charts']['locations']['labels'] ?? []) ?> Wilayah
            </div>
            <p class="text-xs text-slate-400 font-medium">Distribusi investasi di Tanah Bumbu</p>
        </div>
    </div>

    <!-- Public Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Sector Distribution (Horizontal Bar) -->
        <div class="bg-slate-50 p-8 rounded-[32px] border border-slate-200 shadow-sm relative overflow-hidden group">
            <div class="flex items-center space-x-4 mb-8">
                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-md">
                    <i class="fas fa-industry text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900">Analisis Proyek per Sektor</h3>
                    <p class="text-sm text-slate-500">Jumlah proyek yang aktif per kategori industri</p>
                </div>
            </div>
            <div class="h-[400px]">
                <canvas id="sectorChartPublic"></canvas>
            </div>
        </div>

        <!-- Realization per Location (Vertical Bar) -->
        <div class="bg-slate-50 p-8 rounded-[32px] border border-slate-200 shadow-sm relative overflow-hidden group">
            <div class="flex items-center space-x-4 mb-8">
                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-md">
                    <i class="fas fa-map-pin text-rose-600"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900">Top 10 Sebaran Wilayah</h3>
                    <p class="text-sm text-slate-500">Peringkat realisasi investasi berdasarkan kecamatan</p>
                </div>
            </div>
            <div class="h-[400px]">
                <canvas id="locationChartPublic"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Interactive Spasial Map Section -->


<!-- Why DataInvest Section -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-20 px-4">
    <div class="flex flex-col items-center text-center">
        <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-3xl flex items-center justify-center mb-6 shadow-inner">
            <i class="fas fa-lightbulb text-3xl"></i>
        </div>
        <h4 class="text-xl font-black mb-3">Visi Strategis</h4>
        <p class="text-slate-600 leading-relaxed text-sm">Memberikan pandangan strategis bagi pemerintah untuk memetakan
            potensi investasi daerah yang belum tergarap maksimal.</p>
    </div>
    <div class="flex flex-col items-center text-center">
        <div
            class="w-20 h-20 bg-emerald-50 text-emerald-600 rounded-3xl flex items-center justify-center mb-6 shadow-inner">
            <i class="fas fa-check-circle text-3xl"></i>
        </div>
        <h4 class="text-xl font-black mb-3">Akurasi Valid</h4>
        <p class="text-slate-600 leading-relaxed text-sm">Setiap data diproses melalui validasi sistematis untuk
            memastikan angka realisasi yang disajikan adalah fakta lapangan yang kredibel.</p>
    </div>
    <div class="flex flex-col items-center text-center">
        <div
            class="w-20 h-20 bg-amber-50 text-amber-600 rounded-3xl flex items-center justify-center mb-6 shadow-inner">
            <i class="fas fa-users text-3xl"></i>
        </div>
        <h4 class="text-xl font-black mb-3">Akses Terbuka</h4>
        <p class="text-slate-600 leading-relaxed text-sm">Masyarakat dan investor dapat memantau tren ekonomi Tanah
            Bumbu kapan saja untuk mendukung pengambilan keputusan yang tepat.</p>
    </div>
</div>

<!-- Chart Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
    Chart.register(ChartDataLabels);

    // Common configurations
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        layout: {
            padding: {
                top: 40,
                right: 60,
                bottom: 10,
                left: 10
            }
        },
        plugins: {
            datalabels: {
                color: '#000000',
                font: { family: 'Inter', weight: '900', size: 10 },
                formatter: (value) => value.toLocaleString('id-ID'),
                clip: false // Prevent labels from being clipped
            }
        }
    };

    // 1. Sector Chart (Project Counts)
    const sectorCtx = document.getElementById('sectorChartPublic');
    const sectorChart = new Chart(sectorCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($data['charts']['sectors']['labels'] ?? []) ?>,
            datasets: [{
                data: <?= json_encode($data['charts']['sectors']['counts'] ?? []) ?>,
                backgroundColor: 'rgba(37, 99, 235, 0.7)',
                borderColor: '#2563eb',
                borderWidth: 1,
                borderRadius: 12
            }]
        },
        options: {
            ...commonOptions,
            indexAxis: 'y',
            plugins: {
                ...commonOptions.plugins,
                legend: { display: false },
                datalabels: {
                    ...commonOptions.plugins.datalabels,
                    anchor: 'end',
                    align: 'right',
                    offset: 5
                }
            },
            scales: {
                x: {
                    display: false,
                    grace: '15%' // Add space at the end of the scale
                },
                y: { grid: { display: false }, ticks: { font: { family: 'Inter', weight: '700', size: 11 }, color: '#64748b' } }
            }
        }
    });

    // 2. Location Chart (Investment Values)
    const locationCtx = document.getElementById('locationChartPublic');
    const locationChart = new Chart(locationCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($data['charts']['locations']['labels'] ?? []) ?>,
            datasets: [{
                data: <?= json_encode($data['charts']['locations']['values'] ?? []) ?>,
                backgroundColor: 'rgba(225, 29, 72, 0.7)',
                borderColor: '#e11d48',
                borderWidth: 1,
                borderRadius: 12
            }]
        },
        options: {
            ...commonOptions,
            indexAxis: 'y', // Switch to horizontal for better label space
            layout: {
                padding: {
                    right: 80, // Extra space for large labels
                    top: 10,
                    bottom: 10,
                    left: 10
                }
            },
            plugins: {
                ...commonOptions.plugins,
                legend: { display: false },
                datalabels: {
                    ...commonOptions.plugins.datalabels,
                    anchor: 'end',
                    align: 'right',
                    offset: 8,
                    formatter: (value) => {
                        return 'Rp ' + Math.round(value).toLocaleString('id-ID');
                    },
                    font: { family: 'Inter', weight: '900', size: 10 }
                }
            },
            scales: {
                x: { display: false, grace: '30%' },
                y: { 
                    grid: { display: false }, 
                    ticks: { 
                        font: { family: 'Inter', weight: '700', size: 11 }, 
                        color: '#64748b' 
                    } 
                }
            }
        }
    });

    // --- BUBBLE SORT ANIMATION LOGIC ---

    // Function to shuffle data for visual effect
    function shuffleChartData(chart) {
        const data = chart.data.datasets[0].data;
        const labels = chart.data.labels;
        for (let i = data.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [data[i], data[j]] = [data[j], data[i]];
            [labels[i], labels[j]] = [labels[j], labels[i]];
        }
        chart.update();
    }

    // Function to perform visual bubble sort
    async function animateBubbleSort(chart) {
        if (!chart || !chart.data.datasets[0]) return;
        
        const data = chart.data.datasets[0].data;
        const labels = chart.data.labels;
        let n = data.length;
        
        // Skip if no data
        if (n <= 1) return;

        for (let i = 0; i < n - 1; i++) {
            let swapped = false;
            for (let j = 0; j < n - i - 1; j++) {
                // Ensure numeric comparison
                if (Number(data[j]) < Number(data[j + 1])) { 
                    // Swap values
                    let tempVal = data[j];
                    data[j] = data[j + 1];
                    data[j + 1] = tempVal;
                    
                    // Swap labels
                    let tempLabel = labels[j];
                    labels[j] = labels[j + 1];
                    labels[j + 1] = tempLabel;
                    swapped = true;
                }
            }
            
            // If something changed, update the chart visually once per pass for speed
            if (swapped) {
                chart.update({
                    duration: 300,
                    easing: 'easeOutQuart'
                });
                // Small pause to let the user see the "bubbly" progress
                await new Promise(resolve => setTimeout(resolve, 350));
            } else {
                // Already sorted
                break;
            }
        }
        
        // Final update to ensure everything is perfect
        chart.update();
    }

    // Trigger animation when the section is visible
    const observerOptions = { threshold: 0.3 };
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Wait briefly for smooth entry
                setTimeout(async () => {
                    // Shuffle first for "wow" effect of seeing it reorganize
                    shuffleChartData(sectorChart);
                    shuffleChartData(locationChart);

                    await new Promise(r => setTimeout(r, 1000));

                    // Sort them!
                    animateBubbleSort(sectorChart);
                    animateBubbleSort(locationChart);
                }, 500);

                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    observer.observe(document.getElementById('public-stats'));
</script>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    // -------------------------------------------------------------------------
    // INVESTMENT MAP LOGIC
    // -------------------------------------------------------------------------

    let investmentMap;
    let geojsonLayer;
    let districtData = <?= json_encode($data['investment_by_location'] ?? []) ?>;

    function initMap() {
        // Center of Tanah Bumbu
        const center = [-3.4572, 115.7037];
        investmentMap = L.map('investmentMap', {
            scrollWheelZoom: false,
            zoomControl: false
        }).setView(center, 10);

        // Custom Zoom Control position
        L.control.zoom({ position: 'topright' }).addTo(investmentMap);

        // Professional Light Base Maps
        const lightTiles = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(investmentMap);

        loadGeoJSON();
    }

    function getColor(d) {
        // Dynamic scaling based on Tanah Bumbu investment values
        return d > 1000000000000 ? '#1e3a8a' : // > 1 Trillion
            d > 500000000000 ? '#1d4ed8' : // > 500 Billion
                d > 100000000000 ? '#3b82f6' : // > 100 Billion
                    d > 50000000000 ? '#60a5fa' : // > 50 Billion
                        d > 10000000000 ? '#93c5fd' : // > 10 Billion
                            '#dbeafe';    // Low
    }

    function style(feature) {
        const name = feature.properties.name;
        const val = districtData[name] || 0;
        return {
            fillColor: getColor(val),
            weight: 2,
            opacity: 1,
            color: 'white',
            dashArray: '3',
            fillOpacity: 0.7
        };
    }

    function highlightFeature(e) {
        const layer = e.target;
        layer.setStyle({
            weight: 3,
            color: '#1e40af',
            dashArray: '',
            fillOpacity: 0.9
        });
        layer.bringToFront();
    }

    function resetHighlight(e) {
        geojsonLayer.resetStyle(e.target);
    }

    function onEachFeature(feature, layer) {
        const name = feature.properties.name;
        const amount = districtData[name] || 0;
        const formattedAmount = 'Rp ' + amount.toLocaleString('id-ID');

        layer.bindTooltip(`
            <div class="map-tooltip">
                <div class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-1">${name}</div>
                <div class="text-lg font-black text-white">${formattedAmount}</div>
                <div class="text-[10px] text-slate-400 mt-1">Klik untuk detail wilayah</div>
            </div>
        `, { sticky: true, opacity: 1, className: 'leaflet-tooltip-custom' });

        layer.on({
            mouseover: highlightFeature,
            mouseout: resetHighlight,
            click: function (e) {
                investmentMap.fitBounds(e.target.getBounds());
            }
        });
    }

    async function loadGeoJSON() {
        try {
            const response = await fetch('<?= base_url('assets/geojson/tanah_bumbu.json') ?>');
            const data = await response.json();

            geojsonLayer = L.geoJson(data, {
                style: style,
                onEachFeature: onEachFeature
            }).addTo(investmentMap);

            investmentMap.fitBounds(geojsonLayer.getBounds(), { padding: [20, 20] });
            updateLegend();
        } catch (error) {
            console.error('Error loading GeoJSON:', error);
        }
    }

    function updateLegend() {
        const grades = [0, 10000000000, 50000000000, 100000000000, 500000000000, 1000000000000];
        const labels = ['0 - 10M', '10M - 50M', '50M - 100M', '100M - 500M', '500M - 1T', '> 1T'];
        const legendContainer = document.getElementById('mapLegend');
        legendContainer.innerHTML = '';

        for (let i = 0; i < grades.length; i++) {
            legendContainer.innerHTML += `
                <div class="flex items-center text-[10px] font-bold text-slate-600">
                    <i class="w-3 h-3 rounded-sm mr-2" style="background:${getColor(grades[i] + 1)}"></i>
                    <span>${labels[i]}</span>
                </div>
            `;
        }
    }

    async function refreshMapData() {
        const year = document.getElementById('mapFilterYear').value;
        const quarter = document.getElementById('mapFilterQuarter').value;
        const loader = document.getElementById('mapLoading');

        loader.classList.remove('opacity-0', 'pointer-events-none');

        try {
            const url = `<?= base_url('api/public/data') ?>?year=${year}&quarter=${quarter}`;
            const response = await fetch(url);
            const data = await response.json();

            districtData = data.investment_by_location || {};

            // Refresh GeoJSON styling with new data
            if (geojsonLayer) {
                geojsonLayer.eachLayer(function (layer) {
                    geojsonLayer.resetStyle(layer);
                });
            }

        } catch (error) {
            console.error('Error refreshing map data:', error);
        } finally {
            loader.classList.add('opacity-0', 'pointer-events-none');
        }
    }

    // Event Listeners for Filters
    document.getElementById('mapFilterYear').addEventListener('change', refreshMapData);
    document.getElementById('mapFilterQuarter').addEventListener('change', refreshMapData);

    // Bootstrap
    document.addEventListener('DOMContentLoaded', initMap);
</script>

<style>
    .text-white-all i {
        color: white !important;
    }

    #public-stats {
        scroll-behavior: smooth;
    }
</style>

<?php $this->endSection(); ?>