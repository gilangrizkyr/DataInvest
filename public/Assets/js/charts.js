// Charts JavaScript - Chart management and rendering

class ChartsManager {
    constructor(data, currency, usdRate) {
        this.data = data;
        this.currency = currency;
        this.usdRate = usdRate;
        
        this.charts = {
            pmaPmdn: null,
            district: null,
            location: null,
            sector: null,
            workforcePma: null,
            workforcePmdn: null,
            rankingDistrict: null,
            projectsPma: null,
            projectsPmdn: null,
            country: null,
            quarterlyAdditionalInvestment: null
        };
        
        this.init();
    }
    
    init() {
        this.createAllCharts();
        this.initializeChartToggles();
        this.initializeChartTypeChanges();
    }
    
    formatRp(num) {
        if (!num) return 'Rp 0';
        return 'Rp ' + Number(num).toLocaleString('id-ID');
    }
    
    formatUSD(num) {
        if (!num) return '$ 0';
        return '$ ' + Number(num).toLocaleString('en-US');
    }
    
    generateColors(count) {
        const colors = [];
        for (let i = 0; i < count; i++) {
            const hue = (i * 360 / Math.max(count, 1)) % 360;
            colors.push('hsl(' + hue + ', 70%, 50%)');
        }
        return colors;
    }
    
    createAllCharts() {
        this.createPmaPmdnChart();
        this.createDistrictChart();
        this.createLocationChart();
        this.createSectorChart();
        this.createWorkforceCharts();
        this.createRankingChart();
        this.createProjectsCharts();
        this.createCountryChart();
        this.createQuarterlyChart();
    }
    
    createPmaPmdnChart() {
        const totalProjectsPMA = parseInt(this.data.total_projects?.PMA ?? 0) || 0;
        const totalProjectsPMDN = parseInt(this.data.total_projects?.PMDN ?? 0) || 0;
        const addInvPMA = parseFloat(this.data.total_additional_investment?.PMA ?? 0) || 0;
        const addInvPMDN = parseFloat(this.data.total_additional_investment?.PMDN ?? 0) || 0;
        
        this.charts.pmaPmdn = new Chart(document.getElementById('pma-pmdn-chart'), {
            type: 'pie',
            data: {
                labels: ['PMA', 'PMDN'],
                datasets: [{
                    data: [totalProjectsPMA, totalProjectsPMDN],
                    backgroundColor: ['#3B82F6', '#F59E0B']
                }]
            },
            plugins: [ChartDataLabels],
            options: {
                plugins: {
                    datalabels: {
                        color: '#ffffff',
                        formatter: (value, ctx) => {
                            const total = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            const label = ctx.chart.data.labels[ctx.dataIndex];
                            const addInv = label === 'PMA' ? addInvPMA : addInvPMDN;
                            const addInvFormatted = this.currency === "USD" ? this.formatUSD(addInv) : this.formatRp(addInv);
                            return `${value} (${percentage}%)\nTambahan: ${addInvFormatted}`;
                        },
                        font: { weight: 'bold', size: 14 }
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => {
                                const label = ctx.label || '';
                                const value = ctx.raw || 0;
                                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                const addInv = label === 'PMA' ? addInvPMA : addInvPMDN;
                                const addInvFormatted = this.currency === "USD" ? this.formatUSD(addInv) : this.formatRp(addInv);
                                return `${label}: ${value} proyek (${percentage}%)\nTambahan Investasi: ${addInvFormatted}`;
                            }
                        }
                    }
                }
            }
        });
    }
    
    createDistrictChart() {
        const dist = this.data.charts.district;
        this.charts.district = new Chart(document.getElementById('district-chart'), {
            type: 'bar',
            data: {
                labels: dist.labels,
                datasets: [
                    { label: 'PMA', data: dist.pma, backgroundColor: '#3B82F6' },
                    { label: 'PMDN', data: dist.pmdn, backgroundColor: '#F59E0B' }
                ]
            },
            options: {
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (ctx) => {
                                return `${ctx.dataset.label} - ${ctx.label}: ${ctx.raw} proyek`;
                            }
                        }
                    }
                },
                scales: { y: { beginAtZero: true } }
            }
        });
    }
    
    createLocationChart() {
        const loc = this.data.charts.locations;
        this.charts.location = new Chart(document.getElementById('investment-location-chart'), {
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
                            label: (ctx) => {
                                return this.currency === "USD" ? this.formatUSD(ctx.raw) : this.formatRp(ctx.raw);
                            }
                        }
                    }
                },
                scales: { y: { beginAtZero: true } }
            }
        });
    }
    
    createSectorChart() {
        this.charts.sector = new Chart(document.getElementById('sector-chart'), {
            type: 'bar',
            data: {
                labels: this.data.charts.sectors.labels,
                datasets: [{
                    label: 'Jumlah Proyek',
                    data: this.data.charts.sectors.counts,
                    backgroundColor: '#8B5CF6'
                }]
            },
            options: {
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `${ctx.label}: ${ctx.raw} proyek`
                        }
                    }
                },
                indexAxis: 'y'
            }
        });
    }
    
    createWorkforceCharts() {
        const workforceData = this.data.workforce_by_district || {};
        
        // PMA Workforce
        const workforcePma = workforceData.PMA || {};
        const pmaLabels = Object.keys(workforcePma);
        const pmaTki = pmaLabels.map(l => workforcePma[l].TKI ?? 0);
        const pmaTka = pmaLabels.map(l => workforcePma[l].TKA ?? 0);
        
        this.charts.workforcePma = new Chart(document.getElementById('workforce-pma-chart'), {
            type: 'bar',
            data: {
                labels: pmaLabels,
                datasets: [
                    { label: 'TKI', data: pmaTki, backgroundColor: '#EF4444' },
                    { label: 'TKA', data: pmaTka, backgroundColor: '#F97316' }
                ]
            },
            options: {
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `${ctx.dataset.label} - ${ctx.label}: ${ctx.raw} orang`
                        }
                    }
                },
                scales: { y: { beginAtZero: true } }
            }
        });
        
        // PMDN Workforce
        const workforcePmdn = workforceData.PMDN || {};
        const pmdnLabels = Object.keys(workforcePmdn);
        const pmdnTki = pmdnLabels.map(l => workforcePmdn[l].TKI ?? 0);
        const pmdnTka = pmdnLabels.map(l => workforcePmdn[l].TKA ?? 0);
        
        this.charts.workforcePmdn = new Chart(document.getElementById('workforce-pmdn-chart'), {
            type: 'bar',
            data: {
                labels: pmdnLabels,
                datasets: [
                    { label: 'TKI', data: pmdnTki, backgroundColor: '#EF4444' },
                    { label: 'TKA', data: pmdnTka, backgroundColor: '#F97316' }
                ]
            },
            options: {
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `${ctx.dataset.label} - ${ctx.label}: ${ctx.raw} orang`
                        }
                    }
                },
                scales: { y: { beginAtZero: true } }
            }
        });
    }
    
    createRankingChart() {
        const rankingData = this.data.ranking_by_district || [];
        const rankingLabels = rankingData.map(item => item.district);
        const rankingValues = rankingData.map(item => item.total_projects);
        
        this.charts.rankingDistrict = new Chart(document.getElementById('ranking-district-chart'), {
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
                            label: (ctx) => `${ctx.label}: ${ctx.raw} proyek`
                        }
                    }
                },
                scales: { y: { beginAtZero: true } }
            }
        });
        
        // Populate ranking list
        this.populateRankingList(rankingData);
    }
    
    populateRankingList(rankingData) {
        const rankingList = document.getElementById('ranking-list');
        rankingList.innerHTML = '';
        
        const medalColors = ['text-yellow-500', 'text-gray-400', 'text-amber-600', 'text-gray-500', 'text-gray-400'];
        const medalIcons = ['fa-trophy', 'fa-medal', 'fa-medal', 'fa-medal', 'fa-medal'];
        
        rankingData.forEach((item, index) => {
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
    }
    
    createProjectsCharts() {
        const dist = this.data.charts.district;
        
        // PMA Projects
        this.charts.projectsPma = new Chart(document.getElementById('projects-pma-chart'), {
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
                            label: (ctx) => `PMA - ${ctx.label}: ${ctx.raw} proyek`
                        }
                    }
                },
                scales: { y: { beginAtZero: true } }
            }
        });
        
        // PMDN Projects
        this.charts.projectsPmdn = new Chart(document.getElementById('projects-pmdn-chart'), {
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
                            label: (ctx) => `PMDN - ${ctx.label}: ${ctx.raw} proyek`
                        }
                    }
                },
                scales: { y: { beginAtZero: true } }
            }
        });
    }
    
    createCountryChart() {
        const countryData = this.data.charts.countries;
        this.charts.country = new Chart(document.getElementById('country-chart'), {
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
                            label: (ctx) => `${ctx.label}: ${ctx.raw} proyek`
                        }
                    }
                },
                scales: { y: { beginAtZero: true } }
            }
        });
    }
    
    createQuarterlyChart() {
        const quarterlyData = this.data.charts.quarterly_additional_investment;
        
        if (quarterlyData && quarterlyData.labels && quarterlyData.values) {
            const canvasElement = document.getElementById('quarterly-additional-investment-chart');
            
            if (canvasElement) {
                this.charts.quarterlyAdditionalInvestment = new Chart(canvasElement, {
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
                                    label: (ctx) => {
                                        return this.currency === "USD" ? this.formatUSD(ctx.raw) : this.formatRp(ctx.raw);
                                    }
                                }
                            }
                        },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }
        }
    }
    
    initializeChartToggles() {
        const chartCheckboxes = [
            { id: 'show-pma-pmdn', container: 'pma-pmdn-container' },
            { id: 'show-district', container: 'district-container' },
            { id: 'show-investment', container: 'investment-container' },
            { id: 'show-sector', container: 'sector-container' },
            { id: 'show-workforce-pma', container: 'workforce-pma-container' },
            { id: 'show-workforce-pmdn', container: 'workforce-pmdn-container' },
            { id: 'show-ranking-district', container: 'ranking-district-container' },
            { id: 'show-projects-pma', container: 'projects-pma-container' },
            { id: 'show-projects-pmdn', container: 'projects-pmdn-container' },
            { id: 'show-country', container: 'country-container' },
            { id: 'show-quarterly-additional-investment', container: 'quarterly-additional-investment-container' }
        ];
        
        chartCheckboxes.forEach(({ id, container }) => {
            const checkbox = document.getElementById(id);
            const containerEl = document.getElementById(container);
            
            if (checkbox && containerEl) {
                checkbox.addEventListener('change', function() {
                    containerEl.style.display = this.checked ? 'block' : 'none';
                });
            }
        });
    }
    
    initializeChartTypeChanges() {
        // Initialize all chart type change event listeners
        this.initPmaPmdnTypeChange();
        this.initDistrictTypeChange();
        this.initInvestmentTypeChange();
        this.initSectorTypeChange();
        // Add more as needed...
    }
    
    initPmaPmdnTypeChange() {
        const typeSelect = document.getElementById('pma-pmdn-type');
        if (!typeSelect) return;
        
        typeSelect.addEventListener('change', (e) => {
            if (this.charts.pmaPmdn) {
                this.charts.pmaPmdn.destroy();
            }
            
            const totalProjectsPMA = parseInt(this.data.total_projects?.PMA ?? 0) || 0;
            const totalProjectsPMDN = parseInt(this.data.total_projects?.PMDN ?? 0) || 0;
            
            const chartType = e.target.value === 'doughnut' ? 'doughnut' : e.target.value;
            this.charts.pmaPmdn = new Chart(document.getElementById('pma-pmdn-chart'), {
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
                                label: (ctx) => {
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
    }
    
    initDistrictTypeChange() {
        const typeSelect = document.getElementById('district-type');
        if (!typeSelect) return;
        
        typeSelect.addEventListener('change', (e) => {
            if (this.charts.district) {
                this.charts.district.destroy();
            }
            
            const chartType = e.target.value;
            const isHorizontal = chartType === 'horizontalBar';
            const actualType = isHorizontal ? 'bar' : chartType;
            const dist = this.data.charts.district;
            
            this.charts.district = new Chart(document.getElementById('district-chart'), {
                type: actualType,
                data: {
                    labels: dist.labels,
                    datasets: [
                        { label: 'PMA', data: dist.pma, backgroundColor: '#3B82F6' },
                        { label: 'PMDN', data: dist.pmdn, backgroundColor: '#F59E0B' }
                    ]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: (ctx) => `${ctx.dataset.label} - ${ctx.label}: ${ctx.raw} proyek`
                            }
                        }
                    },
                    scales: actualType !== 'pie' ? { y: { beginAtZero: true } } : {},
                    indexAxis: isHorizontal ? 'y' : 'x'
                }
            });
        });
    }
    
    initInvestmentTypeChange() {
        const typeSelect = document.getElementById('investment-type');
        if (!typeSelect) return;
        
        typeSelect.addEventListener('change', (e) => {
            if (this.charts.location) {
                this.charts.location.destroy();
            }
            
            const chartType = e.target.value;
            const loc = this.data.charts.locations;
            
            this.charts.location = new Chart(document.getElementById('investment-location-chart'), {
                type: chartType === 'area' ? 'line' : chartType,
                data: {
                    labels: loc.labels,
                    datasets: [{
                        label: "Investasi",
                        data: loc.values,
                        backgroundColor: '#10B981',
                        fill: chartType === 'area'
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: (ctx) => {
                                    return this.currency === "USD" ? this.formatUSD(ctx.raw) : this.formatRp(ctx.raw);
                                }
                            }
                        }
                    },
                    scales: chartType !== 'pie' ? { y: { beginAtZero: true } } : {}
                }
            });
        });
    }
    
    initSectorTypeChange() {
        const typeSelect = document.getElementById('sector-type');
        if (!typeSelect) return;
        
        typeSelect.addEventListener('change', (e) => {
            if (this.charts.sector) {
                this.charts.sector.destroy();
            }
            
            const chartType = e.target.value;
            const isHorizontal = chartType === 'horizontalBar';
            
            this.charts.sector = new Chart(document.getElementById('sector-chart'), {
                type: isHorizontal ? 'bar' : chartType,
                data: {
                    labels: this.data.charts.sectors.labels,
                    datasets: [{
                        label: 'Jumlah Proyek',
                        data: this.data.charts.sectors.counts,
                        backgroundColor: chartType === 'pie' ? this.generateColors(this.data.charts.sectors.labels.length) : '#8B5CF6'
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: (ctx) => `${ctx.label}: ${ctx.raw} proyek`
                            }
                        }
                    },
                    indexAxis: isHorizontal ? 'y' : 'x',
                    scales: chartType !== 'pie' ? { y: { beginAtZero: true } } : {}
                }
            });
        });
    }
    
}

// Initialize charts when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    if (typeof data !== 'undefined' && typeof currentFilters !== 'undefined') {
        const currency = (currentFilters && currentFilters.currency) ? currentFilters.currency : "IDR";
        const usdRate = data.usd_rate ?? 15000;
        window.chartsManager = new ChartsManager(data, currency, usdRate);
    }
});