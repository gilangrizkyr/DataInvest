// Dashboard JavaScript - Extracted from dashboard.php

class DashboardApp {
    constructor(data, currentFilters) {
        this.data = data;
        this.currentFilters = currentFilters;
        this.currency = (currentFilters && currentFilters.currency) ? currentFilters.currency : "IDR";
        this.usdRate = data.usd_rate ?? 15000;
        
        this.totalProjectsPMA = parseInt(data.total_projects?.PMA ?? 0) || 0;
        this.totalProjectsPMDN = parseInt(data.total_projects?.PMDN ?? 0) || 0;
        this.invPMA = parseFloat(data.total_investment?.PMA ?? 0) || 0;
        this.invPMDN = parseFloat(data.total_investment?.PMDN ?? 0) || 0;
        this.addInvPMA = parseFloat(data.total_additional_investment?.PMA ?? 0) || 0;
        this.addInvPMDN = parseFloat(data.total_additional_investment?.PMDN ?? 0) || 0;
        
        this.init();
    }
    
    init() {
        this.populateStatsCards();
        this.initializeEventListeners();
        this.addAnimationStyles();
        this.initializeDragAndDrop();
    }
    
    generateColors(count) {
        const colors = [];
        for (let i = 0; i < count; i++) {
            const hue = (i * 360 / Math.max(count, 1)) % 360;
            colors.push('hsl(' + hue + ', 70%, 50%)');
        }
        return colors;
    }
    
    formatRp(num) {
        if (!num) return 'Rp 0';
        return 'Rp ' + Number(num).toLocaleString('id-ID');
    }
    
    formatUSD(num) {
        if (!num) return '$ 0';
        return '$ ' + Number(num).toLocaleString('en-US');
    }
    
    convertCurrency(val) {
        if (this.currency === "USD") return val / this.usdRate;
        return val;
    }
    
    populateStatsCards() {
        const totalInv = this.invPMA + this.invPMDN;
        const totalAddInv = this.addInvPMA + this.addInvPMDN;
        
        const formatNumber = (num) => {
            const str = this.currency === "USD" ? this.formatUSD(num) : this.formatRp(num);
            return str.length > 15 ? '<span class="text-2xl">' + str + '</span>' : '<span class="text-3xl">' + str + '</span>';
        };
        
        const statsContainer = document.getElementById('stats-cards');
        
        const cards = [
            this.createStatCard('Total Proyek', this.totalProjectsPMA + this.totalProjectsPMDN, 'PMA & PMDN Gabungan', 'blue', 'fa-project-diagram'),
            this.createStatCard('Total Investasi', formatNumber(totalInv), 'Nilai Investasi PMA & PMDN', 'green', 'fa-money-bill-wave'),
            this.createStatCard('Total Tambahan Investasi', formatNumber(totalAddInv), 'Tambahan Investasi PMA & PMDN', 'teal', 'fa-plus-circle'),
            this.createStatCard('Proyek PMA', this.totalProjectsPMA, 'Penanaman Modal Asing', 'purple', 'fa-globe'),
            this.createStatCard('Proyek PMDN', this.totalProjectsPMDN, 'Penanaman Modal Dalam Negeri', 'orange', 'fa-home'),
            this.createStatCard('Total Investasi PMA', formatNumber(this.invPMA), 'Investasi PMA', 'indigo', 'fa-money-bill-wave'),
            this.createStatCard('Total Investasi PMDN', formatNumber(this.invPMDN), 'Investasi PMDN', 'cyan', 'fa-money-bill-wave'),
            this.createStatCard('Tambahan Investasi PMA', formatNumber(this.addInvPMA), 'Tambahan Investasi PMA', 'pink', 'fa-plus-circle'),
            this.createStatCard('Tambahan Investasi PMDN', formatNumber(this.addInvPMDN), 'Tambahan Investasi PMDN', 'lime', 'fa-plus-circle')
        ];
        
        statsContainer.innerHTML = cards.join('');
    }
    
    createStatCard(title, value, subtitle, color, icon) {
        return `
            <div class="modern-card bg-gradient-to-br from-${color}-50 to-${color}-100 shadow-xl rounded-xl p-6 chart-container animate-fade-in border-l-4 border-${color}-500">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">${title}</h3>
                        <p class="font-bold text-${color}-600 overflow-hidden text-ellipsis">${value}</p>
                        <p class="text-sm text-gray-600 mt-1">${subtitle}</p>
                    </div>
                    <div class="bg-${color}-100 p-4 rounded-full shadow-lg">
                        <i class="fas ${icon} text-3xl text-${color}-600"></i>
                    </div>
                </div>
            </div>
        `;
    }
    
    initializeEventListeners() {
        // Currency filter
        const currencyFilter = document.getElementById("filter-currency");
        if (currencyFilter) {
            currencyFilter.addEventListener("change", () => this.applyCurrencyFilter());
        }
        
        // Language switcher
        const languageSwitcher = document.getElementById('language-switcher');
        if (languageSwitcher) {
            languageSwitcher.addEventListener('change', (e) => this.changeLanguage(e.target.value));
        }
        
        // Set current currency value
        if (this.currentFilters && typeof this.currentFilters === 'object') {
            if (this.currentFilters.currency && currencyFilter) {
                currencyFilter.value = this.currentFilters.currency;
            }
        }
    }
    
    applyCurrencyFilter() {
        const selectedCurrency = document.getElementById('filter-currency').value;
        const urlParams = new URLSearchParams(window.location.search);
        const currentUpload = urlParams.get('upload') || 'all';
        
        const params = new URLSearchParams();
        if (currentUpload !== 'all') params.append('upload', currentUpload);
        if (selectedCurrency !== 'IDR') params.append('currency', selectedCurrency);
        
        const url = '/dashboard' + (params.toString() ? '?' + params.toString() : '');
        window.location.href = url;
    }
    
    changeLanguage(language) {
        fetch('/dashboard/setLanguage', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'language=' + encodeURIComponent(language)
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
    }
    
    addAnimationStyles() {
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
    }
    
    initializeDragAndDrop() {
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('excel-file-input');
        const uploadText = document.getElementById('upload-text');
        const fileName = document.getElementById('file-name');
        
        if (!dropZone || !fileInput) return;
        
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, this.preventDefaults, false);
            document.body.addEventListener(eventName, this.preventDefaults, false);
        });
        
        // Highlight drop zone
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.add('border-blue-600', 'bg-blue-50');
            }, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.remove('border-blue-600', 'bg-blue-50');
            }, false);
        });
        
        // Handle drop
        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(files[0]);
                fileInput.files = dataTransfer.files;
                fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                this.displayFileName(files[0], fileName, uploadText);
            }
        }, false);
        
        // Handle file input change
        fileInput.addEventListener('change', (e) => {
            const files = e.target.files;
            if (files.length > 0) {
                this.displayFileName(files[0], fileName, uploadText);
            }
        }, false);
    }
    
    preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    displayFileName(file, fileNameElement, uploadTextElement) {
        const fileNameText = file.name.length > 30 ? file.name.substring(0, 27) + '...' : file.name;
        fileNameElement.textContent = `File dipilih: ${fileNameText}`;
        fileNameElement.classList.remove('hidden');
        uploadTextElement.textContent = 'File berhasil dipilih!';
    }
}

// Global functions for onclick handlers
function confirmDelete(uploadId) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
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

// Initialize app when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    if (typeof data !== 'undefined' && typeof currentFilters !== 'undefined') {
        window.dashboardApp = new DashboardApp(data, currentFilters);
    }
});