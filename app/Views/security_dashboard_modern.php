<?php $this->extend('layouts/app'); ?>

<?php $this->section('content'); ?>

<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div x-data="securityDashboard()" x-init="init()" class="space-y-8">
    <!-- Header -->
    <?= view('components/section_header', [
        'title' => 'Security Monitoring',
        'description' => 'Pantau dan kelola ancaman keamanan sistem secara real-time.',
        'icon' => 'fas fa-shield-alt'
    ]) ?>

    <!-- Statistics Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Total Attempts -->
        <div class="card border-l-4 border-amber-400 p-6 flex items-center justify-between">
            <div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Total Serangan</p>
                <h3 class="text-3xl font-black text-slate-800 tabular-nums" x-text="stats.total_attempts || '0'">0</h3>
                <p class="text-[10px] font-bold text-slate-400 mt-2 uppercase tracking-tighter">Dalam 24 Jam Terakhir
                </p>
            </div>
            <div
                class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600 shadow-sm border border-amber-200">
                <i class="fas fa-exclamation-triangle text-xl"></i>
            </div>
        </div>

        <!-- Blocked -->
        <div class="card border-l-4 border-rose-500 p-6 flex items-center justify-between">
            <div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Diblokir</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-black text-slate-800 tabular-nums" x-text="stats.total_blocked || '0'">0
                    </h3>
                    <span
                        class="text-[10px] font-black text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-100"
                        x-text="(stats.block_rate || '0') + '%'">0%</span>
                </div>
                <p class="text-[10px] font-bold text-slate-400 mt-2 uppercase tracking-tighter">Serangan Berhasil
                    Dicegah
                </p>
            </div>
            <div
                class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center text-rose-600 shadow-sm border border-rose-200">
                <i class="fas fa-ban text-xl"></i>
            </div>
        </div>

        <!-- Passed -->
        <div class="card border-l-4 border-emerald-500 p-6 flex items-center justify-between">
            <div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Lolos</p>
                <h3 class="text-3xl font-black text-slate-800 tabular-nums" x-text="stats.passed || '0'">0</h3>
                <p class="text-[10px] font-bold text-slate-400 mt-2 uppercase tracking-tighter">Aktivitas Mencurigakan
                </p>
            </div>
            <div
                class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 shadow-sm border border-emerald-200">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
        </div>

        <!-- Critical -->
        <div class="card border-l-4 border-rose-600 p-6 flex items-center justify-between bg-rose-50/30">
            <div>
                <p class="text-xs font-black text-rose-600 uppercase tracking-widest mb-1">Kritis</p>
                <h3 class="text-3xl font-black text-rose-600 tabular-nums" x-text="stats.critical_threats || '0'">0</h3>
                <p class="text-[10px] font-bold text-rose-400 mt-2 uppercase tracking-tighter">Butuh Penanganan</p>
            </div>
            <div
                class="w-12 h-12 bg-rose-600 text-white rounded-xl flex items-center justify-center shadow-lg shadow-rose-200">
                <i class="fas fa-skull-crossbones text-xl"></i>
            </div>
        </div>

        <!-- System Status -->
        <div class="card border-l-4 border-blue-600 p-6 flex items-center justify-between overflow-hidden relative">
            <div class="relative z-10">
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Status Sistem</p>
                <h3 class="text-2xl font-black text-blue-600 uppercase tracking-tight"
                    x-text="stats.system_status || 'SECURE'">SECURE</h3>
                <p class="text-[10px] font-bold text-slate-400 mt-2 uppercase tracking-tighter">Sistem Berjalan Normal
                </p>
            </div>
            <div
                class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center shadow-sm border border-blue-200 relative z-10">
                <i class="fas fa-shield-halved text-xl"></i>
            </div>
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-50 rounded-full opacity-50 z-0"></div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Threat Timeline Chart -->
        <div class="card flex flex-col min-h-[450px]">
            <div class="p-6 border-b border-slate-100 bg-white">
                <h3 class="text-lg font-black text-slate-800 flex items-center tracking-tight">
                    <span class="w-1.5 h-6 bg-blue-600 rounded-full mr-4"></span>
                    Timeline Ancaman (24 Jam)
                </h3>
            </div>
            <div class="p-8 flex-1 relative">
                <canvas id="timelineChart"></canvas>
                <div x-show="isLoading"
                    class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center z-20">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-circle-notch fa-spin text-3xl text-blue-500 mb-4"></i>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Memuat Data...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Threat Types Breakdown -->
        <div class="card flex flex-col">
            <div class="p-6 border-b border-slate-100 bg-white">
                <h3 class="text-lg font-black text-slate-800 flex items-center tracking-tight">
                    <span class="w-1.5 h-6 bg-emerald-500 rounded-full mr-4"></span>
                    Distribusi Tipe Ancaman
                </h3>
            </div>
            <div class="p-8 flex-1">
                <div class="space-y-6">
                    <template x-for="type in threatTypes" :key="type.type">
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-black text-slate-700 uppercase tracking-tight"
                                    x-text="type.type">SQL Injection</span>
                                <span
                                    class="px-2 py-1 rounded-lg bg-white border border-slate-100 text-xs font-black text-slate-600 shadow-sm"
                                    x-text="type.count + ' Attempts'">0 Attempts</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden shadow-inner">
                                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-full rounded-full transition-all duration-1000"
                                    :style="'width: ' + ((type.count / stats.total_attempts) * 100) + '%'"></div>
                            </div>
                        </div>
                    </template>

                    <div x-show="threatTypes.length === 0"
                        class="h-full flex flex-col items-center justify-center text-slate-300 py-12">
                        <i class="fas fa-shield-check text-5xl mb-4 opacity-10"></i>
                        <p class="text-xs font-black uppercase tracking-widest text-slate-400">Tidak ada ancaman
                            terdeteksi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Threats Table -->
    <div class="card overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-white flex items-center justify-between">
            <h3 class="text-lg font-black text-slate-800 flex items-center tracking-tight">
                <span class="w-1.5 h-6 bg-slate-400 rounded-full mr-4"></span>
                Event Keamanan Terbaru
            </h3>
            <button @click="fetchData()"
                class="w-10 h-10 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center hover:bg-white hover:text-blue-600 transition-all shadow-sm border border-slate-100">
                <i class="fas fa-sync-alt text-xs" :class="isLoading ? 'fa-spin' : ''"></i>
            </button>
        </div>

        <div class="overflow-x-auto custom-scrollbar overflow-y-auto max-h-[500px]">
            <table class="w-full table-auto border-separate border-spacing-0">
                <thead class="bg-slate-50/90 backdrop-blur-md sticky top-0 z-10">
                    <tr>
                        <th
                            class="py-5 px-6 text-xs font-black text-slate-500 uppercase tracking-widest text-left border-b border-slate-100">
                            Waktu</th>
                        <th
                            class="py-5 px-6 text-xs font-black text-slate-500 uppercase tracking-widest text-left border-b border-slate-100">
                            Tipe</th>
                        <th
                            class="py-5 px-6 text-xs font-black text-slate-500 uppercase tracking-widest text-left border-b border-slate-100">
                            IP Sumber</th>
                        <th
                            class="py-5 px-6 text-xs font-black text-slate-500 uppercase tracking-widest text-center border-b border-slate-100">
                            Tingkat Bahaya</th>
                        <th
                            class="py-5 px-6 text-xs font-black text-slate-500 uppercase tracking-widest text-center border-b border-slate-100">
                            Status</th>
                        <th
                            class="py-5 px-6 text-xs font-black text-slate-500 uppercase tracking-widest text-right border-b border-slate-100">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 bg-white">
                    <template x-for="threat in threats" :key="threat.id">
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="py-4 px-6">
                                <span class="text-xs font-bold text-slate-500"
                                    x-text="formatDate(threat.created_at)"></span>
                            </td>
                            <td class="py-4 px-6">
                                <span
                                    class="px-2 py-1 rounded-lg bg-slate-50 text-xs font-black text-slate-600 uppercase tracking-widest border border-slate-200"
                                    x-text="threat.type"></span>
                            </td>
                            <td class="py-4 px-6">
                                <code
                                    class="bg-slate-50 text-slate-600 border border-slate-100 px-3 py-1.5 rounded-lg text-xs font-black"
                                    x-text="threat.ip_address"></code>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <template x-if="threat.severity === 'critical'">
                                    <span
                                        class="px-3 py-1.5 rounded-full bg-rose-600 text-white text-[10px] font-black uppercase tracking-widest shadow-sm">Critical</span>
                                </template>
                                <template x-if="threat.severity === 'high'">
                                    <span
                                        class="px-3 py-1.5 rounded-full bg-rose-50 text-rose-600 text-[10px] font-black uppercase tracking-widest border border-rose-100">High</span>
                                </template>
                                <template x-if="threat.severity === 'medium'">
                                    <span
                                        class="px-3 py-1.5 rounded-full bg-amber-50 text-amber-600 text-[10px] font-black uppercase tracking-widest border border-amber-100">Medium</span>
                                </template>
                                <template x-if="threat.severity === 'low'">
                                    <span
                                        class="px-3 py-1.5 rounded-full bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest border border-blue-100">Low</span>
                                </template>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span
                                    class="px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border"
                                    :class="threat.status === 'blocked' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-50 text-slate-400 border-slate-100'"
                                    x-text="threat.status"></span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <button
                                    class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm border border-slate-100">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="threats.length === 0">
                        <td colspan="6" class="py-20 text-center">
                            <i class="fas fa-shield-check text-slate-200 text-5xl mb-4 block"></i>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Tidak ada event
                                keamanan terbaru</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function securityDashboard() {
        return {
            stats: {
                total_attempts: 0,
                total_blocked: 0,
                block_rate: 0,
                critical_threats: 0,
                passed: 0,
                system_status: 'SECURE'
            },
            threats: [],
            threatTypes: [],
            timeline: [],
            isLoading: true,
            chart: null,

            async init() {
                await this.fetchData();
                // Start auto-refresh every 30 seconds
                setInterval(() => this.fetchData(), 30000);
            },

            async fetchData() {
                this.isLoading = true;
                try {
                    const response = await fetch('<?= base_url('security-monitoring/getThreats') ?>');
                    const result = await response.json();

                    if (result.success) {
                        this.stats = result.data.stats;
                        this.threats = result.data.threats;
                        this.threatTypes = result.data.threat_types;
                        this.timeline = result.data.trend;

                        // Simple logic for system status
                        if (this.stats.critical_threats > 0) {
                            this.stats.system_status = 'WARNING';
                        } else {
                            this.stats.system_status = 'SECURE';
                        }

                        this.$nextTick(() => {
                            this.renderTimelineChart();
                        });
                    }
                } catch (error) {
                    console.error('Failed to fetch security data:', error);
                } finally {
                    this.isLoading = false;
                }
            },

            renderTimelineChart() {
                const ctx = document.getElementById('timelineChart').getContext('2d');

                if (this.chart) {
                    this.chart.destroy();
                }

                this.chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: this.timeline.map(t => t.time),
                        datasets: [
                            {
                                label: 'Blocked Attempts',
                                data: this.timeline.map(t => t.blocked),
                                borderColor: '#ef4444',
                                backgroundColor: 'rgba(239, 68, 68, 0.05)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 0,
                                pointHoverRadius: 6,
                                pointBackgroundColor: '#ef4444',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2
                            },
                            {
                                label: 'Total Attempts',
                                data: this.timeline.map(t => t.attempts),
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.05)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 0,
                                pointHoverRadius: 6,
                                pointBackgroundColor: '#3b82f6',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    font: { family: 'DM Sans', weight: 'bold', size: 10 }
                                }
                            },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleFont: { family: 'DM Sans', weight: 'bold', size: 12 },
                                bodyFont: { family: 'DM Sans', weight: 'normal', size: 12 },
                                padding: 12,
                                cornerRadius: 12,
                                displayColors: true
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: '#f1f5f9', drawBorder: false },
                                ticks: { font: { family: 'DM Sans', weight: 'bold', size: 10 }, color: '#94a3b8' }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { family: 'DM Sans', weight: 'bold', size: 10 }, color: '#94a3b8' }
                            }
                        }
                    }
                });
            },

            formatDate(dateStr) {
                const date = new Date(dateStr);
                return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' - ' +
                    date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
            }
        }
    }
</script>

<?= $this->endSection(); ?>