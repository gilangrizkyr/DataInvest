<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Monitoring Center</title>
    <link rel="icon" type="image/png" href="<?= base_url('logo-dpmptsp.png') ?>">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link rel="stylesheet" href="<?= base_url('assets/css/security.css') ?>">
    <style>

    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div>
                <h1>🛡️ Security Monitoring Center</h1>
                <p>Real-time security system and cyber threat detection</p>
            </div>
        </div>

        <!-- Error Messages -->
        <div id="errorContainer"></div>

        <!-- KPI Cards -->
        <div class="kpi-grid" id="kpiContainer">
            <div class="loading">Loading...</div>
        </div>

        <!-- Charts -->
        <div class="charts-grid">
            <div class="chart-card">
                <div class="chart-title">📊 Attack Trend (24 Jam)</div>
                <div class="chart-container">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-title">🎯 Threat Type</div>
                <div class="chart-container">
                    <canvas id="threatChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Activities -->
        <div class="activities-section">
            <div class="section-header">
                <div class="chart-title">⚠️ Suspicious Activity</div>
                <div class="filter-group">
                    <select class="filter-select" id="severityFilter">
                        <option value="all">Semua Severity</option>
                        <option value="critical">Critical</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                    <select class="filter-select" id="statusFilter">
                        <option value="all">Semua Status</option>
                        <option value="blocked">Blocked</option>
                        <option value="detected">Detected</option>
                        <option value="quarantined">Quarantined</option>
                        <option value="mitigated">Mitigated</option>
                    </select>
                    <button class="btn btn-success" onclick="refreshData()">🔄 Refresh</button>
                    <button class="btn" onclick="exportData()">📥 Export CSV</button>
                </div>
            </div>
            <div class="activities-list" id="activitiesList">
                <div class="loading">Loading...</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Last updated: <span id="lastUpdate"></span></p>
            <p>Sistem monitoring berjalan 24/7 untuk menjaga keamanan aplikasi</p>
            <p style="font-size: 0.7rem; margin-top: 1rem;">Auto refresh setiap 30 detik</p>
        </div>
    </div>

    <script>
        const API_BASE = '<?= base_url('/api/security') ?>';
        let chartsInitialized = false;
        let currentThreats = [];
        let trendChart = null;
        let threatChart = null;

        // Initialize
        async function init() {
            await fetchAndRenderAllData();
            updateTime();

            // Auto refresh setiap 30 detik
            setInterval(fetchAndRenderAllData, 5000);
        }

        // Fetch semua data dari API
        async function fetchAndRenderAllData() {
            try {
                showLoading(true);
                clearError();

                const response = await fetch(`${API_BASE}/threats`);
                const result = await response.json();

                if (!result.success) {
                    throw new Error(result.message || 'Failed to fetch data');
                }

                const {
                    threats,
                    stats,
                    trend,
                    threat_types
                } = result.data;
                currentThreats = threats;

                renderKPIs(stats);
                renderCharts(trend, threat_types);
                renderActivities(threats);
                updateTime();
                showLoading(false);
            } catch (error) {
                showError('Error: ' + error.message);
                showLoading(false);
            }
        }

        function renderKPIs(stats) {
            const kpis = [{
                    label: 'Total Attempts',
                    value: stats.total_attempts,
                    subtitle: 'in the last 24 hours',
                    icon: '🚨',
                    color: '#ef4444'
                },
                {
                    label: 'Blocked',
                    value: stats.total_blocked,
                    subtitle: stats.block_rate + '% success rate',
                    icon: '✓',
                    color: '#22c55e'
                },
                {
                    label: 'Passed Through',
                    value: stats.passed,
                    subtitle: 'needs investigation',
                    icon: '⚠️',
                    color: '#eab308'
                },
                {
                    label: 'Critical Threats',
                    value: stats.critical_threats,
                    subtitle: 'serious threat',
                    icon: '🛑',
                    color: '#dc2626'
                },
            ];

            const container = document.getElementById('kpiContainer');
            container.innerHTML = kpis.map(kpi => `
                <div class="kpi-card">
                    <div class="kpi-header">
                        <span class="kpi-label">${kpi.label}</span>
                        <span style="font-size: 1.5rem;">${kpi.icon}</span>
                    </div>
                    <div class="kpi-value" style="color: ${kpi.color};">${kpi.value}</div>
                    <div class="kpi-subtitle">${kpi.subtitle}</div>
                </div>
            `).join('');
        }

        function renderCharts(trendData, threatTypes) {
            // Destroy existing charts
            if (trendChart) trendChart.destroy();
            if (threatChart) threatChart.destroy();

            // Trend Chart
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            trendChart = new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: trendData.map(d => d.time),
                    datasets: [{
                            label: 'Total Attempts',
                            data: trendData.map(d => d.attempts),
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            tension: 0.4,
                            fill: true,
                        },
                        {
                            label: 'Blocked',
                            data: trendData.map(d => d.blocked),
                            borderColor: '#22c55e',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            tension: 0.4,
                            fill: true,
                        },
                        {
                            label: 'Passed',
                            data: trendData.map(d => d.passed),
                            borderColor: '#eab308',
                            backgroundColor: 'rgba(234, 179, 8, 0.1)',
                            tension: 0.4,
                            fill: true,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#cbd5e1'
                            }
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                color: '#94a3b8'
                            },
                            grid: {
                                color: '#334155'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#94a3b8'
                            },
                            grid: {
                                color: '#334155'
                            }
                        }
                    }
                }
            });

            // Threat Type Chart
            const threatCtx = document.getElementById('threatChart').getContext('2d');
            const colors = ['#ef4444', '#f97316', '#eab308', '#ec4899', '#8b5cf6', '#6366f1'];

            threatChart = new Chart(threatCtx, {
                type: 'doughnut',
                data: {
                    labels: threatTypes.map(t => t.type),
                    datasets: [{
                        data: threatTypes.map(t => t.count),
                        backgroundColor: colors.slice(0, threatTypes.length),
                        borderColor: '#0f172a',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#cbd5e1'
                            }
                        }
                    }
                }
            });
        }

        function renderActivities(threats) {
            const severity = document.getElementById('severityFilter').value;
            const status = document.getElementById('statusFilter').value;

            let filtered = threats;
            if (severity !== 'all') {
                filtered = filtered.filter(t => t.severity === severity);
            }
            if (status !== 'all') {
                filtered = filtered.filter(t => t.status === status);
            }

            const container = document.getElementById('activitiesList');

            if (filtered.length === 0) {
                container.innerHTML = '<div class="loading">Tidak ada data</div>';
                return;
            }

            container.innerHTML = filtered.map(threat => `
                <div class="activity-item activity-${threat.severity}">
                    <div class="activity-header">
                        <div>
                            <div class="activity-type">
                                <span class="severity-dot dot-${threat.severity}"></span>
                                ${threat.type}
                            </div>
                        </div>
                        <span class="status-badge status-${threat.status}">${threat.status.toUpperCase()}</span>
                    </div>
                    <div class="activity-info">
                        <strong>IP:</strong> ${threat.ip_address} | <strong>Time:</strong> ${new Date(threat.created_at).toLocaleString('id-ID')}
                    </div>
                    ${threat.description ? `<div class="activity-info"><strong>Deskripsi:</strong> ${threat.description}</div>` : ''}
                    ${threat.request_uri ? `<div class="activity-details"><strong>URI:</strong> ${threat.request_uri}</div>` : ''}
                    ${threat.payload ? `<div class="activity-details"><strong>Payload:</strong> ${threat.payload.substring(0, 100)}${threat.payload.length > 100 ? '...' : ''}</div>` : ''}
                </div>
            `).join('');
        }

        function updateTime() {
            document.getElementById('lastUpdate').textContent = new Date().toLocaleTimeString('id-ID');
        }

        function showLoading(show) {
            document.body.classList.toggle('refreshing', show);
        }

        function showError(message) {
            const container = document.getElementById('errorContainer');
            container.innerHTML = `<div class="error">${message}</div>`;
            setTimeout(() => {
                container.innerHTML = '';
            }, 5000);
        }

        function clearError() {
            document.getElementById('errorContainer').innerHTML = '';
        }

        function refreshData() {
            fetchAndRenderAllData();
        }

        async function exportData() {
            try {
                const response = await fetch(`${API_BASE}/export`);
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `security_logs_${new Date().getTime()}.csv`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            } catch (error) {
                showError('Error exporting data: ' + error.message);
            }
        }

        // Event Listeners
        document.getElementById('severityFilter').addEventListener('change', () => {
            renderActivities(currentThreats);
        });

        document.getElementById('statusFilter').addEventListener('change', () => {
            renderActivities(currentThreats);
        });

        // Initialize on page load
        window.addEventListener('load', init);
    </script>
</body>

</html>