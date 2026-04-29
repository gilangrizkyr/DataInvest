<?php $this->extend('layouts/app'); ?>

<?php $this->section('content'); ?>

<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div x-data="securityDashboard()" x-init="init()" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8 space-y-8" x-cloak>
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight flex items-center gap-4">
                <span class="p-3 bg-slate-900 text-white rounded-2xl shadow-xl shadow-slate-200"><i class="fas fa-shield-halved"></i></span>
                Security Monitoring
            </h1>
            <p class="text-slate-500 font-medium mt-2">Pemantauan aktivitas mencurigakan dan pencegahan intrusi secara real-time.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl border border-emerald-100 flex items-center gap-3">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
                <span class="text-xs font-black uppercase tracking-widest">Live Monitoring Active</span>
            </div>
            <button @click="fetchData()" class="p-3 bg-white hover:bg-slate-50 text-slate-600 rounded-xl border border-slate-100 shadow-sm transition-all group">
                <i class="fas fa-sync-alt text-sm" :class="isLoading ? 'fa-spin' : ''"></i>
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card: Total Attempts -->
        <div class="relative bg-white rounded-3xl p-8 border border-slate-100 shadow-card overflow-hidden group hover:shadow-2xl transition-all duration-500">
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-amber-50 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <div class="p-4 bg-amber-50 text-amber-600 rounded-2xl"><i class="fas fa-bolt-lightning text-xl"></i></div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">24h Attempts</span>
                </div>
                <h3 class="text-4xl font-black text-slate-800 tracking-tighter" x-text="stats.total_attempts || '0'">0</h3>
                <p class="text-xs font-bold text-slate-400 mt-2">Serangan Terdeteksi</p>
            </div>
        </div>

        <!-- Card: Blocked -->
        <div class="relative bg-white rounded-3xl p-8 border border-slate-100 shadow-card overflow-hidden group hover:shadow-2xl transition-all duration-500">
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-emerald-50 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <div class="p-4 bg-emerald-50 text-emerald-600 rounded-2xl"><i class="fas fa-shield-check text-xl"></i></div>
                    <div class="px-3 py-1 bg-emerald-500 text-white rounded-lg text-[10px] font-black" x-text="(stats.block_rate || '0') + '%'">100%</div>
                </div>
                <h3 class="text-4xl font-black text-slate-800 tracking-tighter" x-text="stats.total_blocked || '0'">0</h3>
                <p class="text-xs font-bold text-slate-400 mt-2">Akses Diblokir</p>
            </div>
        </div>

        <!-- Card: Critical Threats -->
        <div class="relative bg-slate-900 rounded-3xl p-8 border border-slate-800 shadow-2xl overflow-hidden group">
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-rose-500/20 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <div class="p-4 bg-rose-500/20 text-rose-400 rounded-2xl" :class="stats.critical_threats > 0 ? 'animate-pulse' : ''">
                        <i class="fas fa-skull-crossbones text-xl"></i>
                    </div>
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">High Severity</span>
                </div>
                <h3 class="text-4xl font-black text-white tracking-tighter" x-text="stats.critical_threats || '0'">0</h3>
                <p class="text-xs font-bold text-slate-500 mt-2 uppercase tracking-widest">Ancaman Kritis</p>
            </div>
        </div>

        <!-- Card: System Status -->
        <div class="relative bg-white rounded-3xl p-8 border border-slate-100 shadow-card overflow-hidden group hover:shadow-2xl transition-all duration-500">
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-blue-50 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
            <div class="relative z-10 text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full border-4 border-white shadow-xl mx-auto mb-4 flex items-center justify-center overflow-hidden">
                    <i class="fas fa-microchip text-2xl" :class="stats.system_status === 'SECURE' ? 'text-blue-500' : 'text-amber-500'"></i>
                </div>
                <h3 class="text-xl font-black tracking-widest uppercase" :class="stats.system_status === 'SECURE' ? 'text-blue-600' : 'text-amber-600'" x-text="stats.system_status">SECURE</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase mt-2 tracking-tighter">System Health Status</p>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Main Timeline Chart -->
        <div class="lg:col-span-8 bg-white rounded-[2rem] p-8 border border-slate-100 shadow-card">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-lg font-black text-slate-800 flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-primary-600 rounded-full"></span>
                    Timeline Aktivitas Intrusi
                </h3>
                <div class="flex gap-2">
                    <div class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span> Total
                    </div>
                    <div class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase ml-4">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span> Blocked
                    </div>
                </div>
            </div>
            <div class="h-[400px] relative">
                <canvas id="timelineChart"></canvas>
                <div x-show="isLoading && !chart" class="absolute inset-0 bg-white/50 backdrop-blur-sm flex items-center justify-center rounded-2xl">
                    <i class="fas fa-circle-notch fa-spin text-3xl text-primary-500"></i>
                </div>
            </div>
        </div>

        <!-- Threat Types Breakdown -->
        <div class="lg:col-span-4 bg-white rounded-[2rem] p-8 border border-slate-100 shadow-card flex flex-col">
            <h3 class="text-lg font-black text-slate-800 flex items-center gap-3 mb-8">
                <span class="w-1.5 h-6 bg-amber-500 rounded-full"></span>
                Vektor Serangan
            </h3>
            <div class="flex-1 space-y-6">
                <template x-for="type in threatTypes" :key="type.type">
                    <div class="group">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-[11px] font-black text-slate-700 uppercase tracking-tight" x-text="type.type"></span>
                            <span class="text-[11px] font-black text-slate-400" x-text="type.count + ' hits'"></span>
                        </div>
                        <div class="w-full bg-slate-50 rounded-full h-3 overflow-hidden border border-slate-100">
                            <div class="bg-gradient-to-r from-primary-500 to-indigo-600 h-full rounded-full transition-all duration-1000 group-hover:brightness-110" :style="'width: ' + ((type.count / (stats.total_attempts || 1)) * 100) + '%'"></div>
                        </div>
                    </div>
                </template>

                <div x-show="threatTypes.length === 0" class="h-full flex flex-col items-center justify-center text-slate-200 py-12">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-shield-check text-4xl opacity-20"></i>
                    </div>
                    <p class="text-xs font-black uppercase tracking-widest text-slate-400">Zero threats detected</p>
                </div>
            </div>
            <div class="mt-8 pt-6 border-t border-slate-50">
                <div class="p-4 bg-slate-900 rounded-2xl text-white">
                    <div class="flex items-center gap-3 mb-2">
                        <i class="fas fa-circle-info text-blue-400 text-xs"></i>
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Security Score</span>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-black">98.2</span>
                        <span class="text-xs font-bold text-emerald-400">Excellent</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Logs Table -->
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-card overflow-hidden">
        <div class="p-8 border-b border-slate-50 bg-white flex items-center justify-between">
            <div>
                <h3 class="text-xl font-black text-slate-800 tracking-tight">Security Event Logs</h3>
                <p class="text-xs text-slate-400 font-medium mt-1 uppercase tracking-widest">Daftar aktivitas mencurigakan yang tercatat sistem</p>
            </div>
            <a href="<?= base_url('security-monitoring/export') ?>" class="px-6 py-3 bg-slate-900 hover:bg-black text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-lg flex items-center gap-3">
                <i class="fas fa-download"></i> Export Logs
            </a>
        </div>

        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full table-auto border-separate border-spacing-0">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="py-5 px-8 text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">Timestamp</th>
                        <th class="py-5 px-8 text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">Incident Type</th>
                        <th class="py-5 px-8 text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">Source IP</th>
                        <th class="py-5 px-8 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Severity</th>
                        <th class="py-5 px-8 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="py-5 px-8 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <template x-for="threat in threats" :key="threat.id">
                        <tr class="hover:bg-slate-50/50 transition-all group">
                            <td class="py-5 px-8">
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-slate-700" x-text="formatDate(threat.created_at).split(' - ')[0]"></span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter" x-text="formatDate(threat.created_at).split(' - ')[1]"></span>
                                </div>
                            </td>
                            <td class="py-5 px-8">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-black uppercase tracking-widest" x-text="threat.type"></span>
                            </td>
                            <td class="py-5 px-8">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-slate-300 group-hover:bg-blue-500 transition-colors"></div>
                                    <code class="text-xs font-black text-slate-600 bg-slate-50 px-2 py-1 rounded border border-slate-100" x-text="threat.ip_address"></code>
                                </div>
                            </td>
                            <td class="py-5 px-8 text-center">
                                <span class="px-4 py-2 rounded-full text-[9px] font-black uppercase tracking-widest border"
                                    :class="{
                                        'bg-rose-600 text-white border-rose-600 shadow-lg shadow-rose-100': threat.severity === 'critical',
                                        'bg-rose-50 text-rose-600 border-rose-100': threat.severity === 'high',
                                        'bg-amber-50 text-amber-600 border-amber-100': threat.severity === 'medium',
                                        'bg-blue-50 text-blue-600 border-blue-100': threat.severity === 'low'
                                    }"
                                    x-text="threat.severity"></span>
                            </td>
                            <td class="py-5 px-8 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <i class="fas fa-shield-halved text-[10px]" :class="threat.status === 'blocked' ? 'text-emerald-500' : 'text-slate-300'"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest" :class="threat.status === 'blocked' ? 'text-emerald-600' : 'text-slate-400'" x-text="threat.status"></span>
                                </div>
                            </td>
                            <td class="py-5 px-8 text-right">
                                <button class="p-2.5 bg-slate-50 text-slate-400 rounded-xl hover:bg-slate-900 hover:text-white transition-all shadow-sm border border-slate-100">
                                    <i class="fas fa-search-plus text-xs"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
            
            <div x-show="threats.length === 0" class="py-32 flex flex-col items-center justify-center bg-white">
                <div class="relative mb-6">
                    <div class="w-24 h-24 bg-blue-50 rounded-full animate-ping opacity-20 absolute inset-0"></div>
                    <div class="w-24 h-24 bg-white border border-slate-100 rounded-full flex items-center justify-center relative shadow-xl">
                        <i class="fas fa-shield-heart text-4xl text-blue-500"></i>
                    </div>
                </div>
                <h4 class="text-xl font-black text-slate-800 tracking-tight">Your System is Secure</h4>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mt-2">No security incidents recorded in the current filter</p>
            </div>
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
                // Start auto-refresh every 60 seconds
                setInterval(() => this.fetchData(), 60000);
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

                // Create Gradients
                const blueGrad = ctx.createLinearGradient(0, 0, 0, 400);
                blueGrad.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
                blueGrad.addColorStop(1, 'rgba(59, 130, 246, 0)');

                const roseGrad = ctx.createLinearGradient(0, 0, 0, 400);
                roseGrad.addColorStop(0, 'rgba(244, 63, 94, 0.2)');
                roseGrad.addColorStop(1, 'rgba(244, 63, 94, 0)');

                this.chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: this.timeline.map(t => t.time),
                        datasets: [
                            {
                                label: 'Blocked',
                                data: this.timeline.map(t => t.blocked),
                                borderColor: '#f43f5e',
                                backgroundColor: roseGrad,
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 0,
                                pointHoverRadius: 6,
                                pointBackgroundColor: '#f43f5e',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2
                            },
                            {
                                label: 'Total Attempts',
                                data: this.timeline.map(t => t.attempts),
                                borderColor: '#3b82f6',
                                backgroundColor: blueGrad,
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
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#0f172a',
                                titleFont: { family: 'Inter', weight: 'bold', size: 12 },
                                bodyFont: { family: 'Inter', size: 12 },
                                padding: 16,
                                cornerRadius: 16,
                                displayColors: true,
                                bodySpacing: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: '#f8fafc', drawBorder: false },
                                ticks: { 
                                    font: { family: 'Inter', weight: '700', size: 10 }, 
                                    color: '#94a3b8',
                                    callback: (v) => v % 1 === 0 ? v : '' 
                                }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { family: 'Inter', weight: '700', size: 10 }, color: '#94a3b8' }
                            }
                        }
                    }
                });
            },

            formatDate(dateStr) {
                const date = new Date(dateStr);
                const time = date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false });
                const day = date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
                return `${time} - ${day}`;
            }
        }
    }
</script>

<style>
    [x-cloak] { display: none !important; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>

<?php $this->endSection(); ?>