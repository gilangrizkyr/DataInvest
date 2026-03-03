<?php

namespace App\Services;

use App\Models\ProjectModel;
use App\Models\UploadModel;
use App\Libraries\FilterBuilder;

class DashboardService
{
    protected $projectModel;
    protected $uploadModel;
    protected $chartService;
    protected $statisticsService;
    protected $currencyService;
    protected $userModel;
    protected $filterBuilder;

    public function __construct()
    {
        $this->projectModel = new ProjectModel();
        $this->uploadModel = new UploadModel();
        $this->userModel = new \App\Models\UserModel();
        $this->chartService = new ChartService();
        $this->statisticsService = new StatisticsService();
        $this->currencyService = new CurrencyService();
        $this->filterBuilder = new FilterBuilder();
    }

    public function getDashboardData(array $filters): array
    {
        // Ambil upload berdasarkan filter
        $upload = $this->getUpload($filters['upload'] ?? null);
        $allUploads = $this->uploadModel->getAllUploads();

        // Jika tidak ada upload, kembalikan dashboard kosong
        if (!$upload) {
            return $this->getEmptyDashboard($filters, $allUploads);
        }

        $uploadId = $upload['id'];
        $filterConditions = $this->filterBuilder->build($filters);

        // Ambil data proyek berdasarkan upload dan filter
        $rawData = $this->projectModel->getProjectsByUpload($uploadId, $filterConditions);

        // Jika tidak ada data proyek, kembalikan dashboard kosong
        if (empty($rawData)) {
            return $this->getEmptyDashboard($filters, $allUploads, $upload);
        }

        // Hitung statistik proyek
        $statistics = $this->statisticsService->calculate($uploadId, $filterConditions, $upload['usd_value']);

        // Ambil jumlah sektor per perusahaan (LKPM)
        $sectorCountByCompany = $this->projectModel->getSectorCountByCompany($uploadId, $filterConditions);

        // Pastikan selalu ada key PMA & PMDN meskipun kosong
        $sectorCountByCompany = [
            'PMA' => $sectorCountByCompany['PMA'] ?? ['data' => [], 'total' => 0],
            'PMDN' => $sectorCountByCompany['PMDN'] ?? ['data' => [], 'total' => 0],
        ];
        log_message('debug', 'Sector count in DashboardService: ' . json_encode($sectorCountByCompany));

        // Hitung persentase tambahan investasi per distrik
        $additionalInvestmentPercentages = $this->statisticsService->calculateAdditionalInvestmentPercentages(
            $statistics['additional_investment_by_district'] ?? []
        );

        // Generate semua chart
        $charts = $this->chartService->generateAllCharts(
            $uploadId,
            $filterConditions,
            $upload['usd_value'],
            $filters
        );

        // Convert ke USD jika filter currency = USD
        if (($filters['currency'] ?? 'IDR') === 'USD') {
            $this->currencyService->convertToUSD($statistics, $upload['usd_value']);

            // Juga konversi data ranking perusahaan
            foreach (['PMA', 'PMDN'] as $type) {
                if (isset($sectorCountByCompany[$type]['data'])) {
                    foreach ($sectorCountByCompany[$type]['data'] as &$company) {
                        $company['tambahan_realisasi'] = round($company['tambahan_realisasi'] / $upload['usd_value'], 2);
                    }
                }
            }
        }

        // Ambil data untuk KPI cards
        $totalUploads = count($allUploads);
        $totalUsers = $this->userModel->countAll();

        // Ambil recent uploads (5 terakhir)
        $recentUploads = array_slice($allUploads, 0, 5);
        $recentUploadsFormatted = array_map(function ($u) {
            return [
                'file_name' => $u['upload_name'] ?? $u['filename'],
                'uploaded_at' => $u['upload_date'] ?? 'now',
                'rows_count' => $u['total_records'] ?? 0,
                'status' => $u['status'] ?? 'completed'
            ];
        }, $recentUploads);

        // Merge semua data untuk dikirim ke view
        return array_merge($statistics, [
            'raw' => $rawData,
            'charts' => $charts,
            'uploads' => $allUploads,
            'filters' => $filters,
            'usd_rate' => $upload['usd_value'],
            'additional_investment_percentages' => $additionalInvestmentPercentages,
            'sector_count_by_company' => $sectorCountByCompany,
            'ranking_pma' => $statistics['projects_by_district']['PMA'] ?? [],
            'ranking_pmdn' => $statistics['projects_by_district']['PMDN'] ?? [],
            // Key tambahan untuk dashboard_modern.php
            'total' => $totalUploads,
            'current_year' => $upload['year'] ?? date('Y'),
            'users' => $totalUsers,
            'validation_rate' => 100, // Placeholder
            'recent_uploads' => $recentUploadsFormatted,
            'trend' => ['total' => '+10%'] // Placeholder
        ]);
    }


    public function generateExcelDownload(): array
    {
        $latestUpload = $this->uploadModel->getLatestUpload();

        if (!$latestUpload) {
            return ['success' => false, 'message' => 'Tidak ada data untuk diunduh.'];
        }

        $uploadId = $latestUpload['id'];
        $rawData = $this->projectModel->getProjectsByUpload($uploadId);

        if (empty($rawData)) {
            return ['success' => false, 'message' => 'Tidak ada data proyek untuk diunduh.'];
        }

        $spreadsheet = $this->createExcelSpreadsheet($rawData, $uploadId);

        return [
            'success' => true,
            'spreadsheet' => $spreadsheet
        ];
    }

    private function createExcelSpreadsheet(array $rawData, int $uploadId): \PhpOffice\PhpSpreadsheet\Spreadsheet
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        // Sheet 1: Raw Data
        $this->createRawDataSheet($spreadsheet->getActiveSheet(), $rawData);

        // Sheet 2: Ranking
        $spreadsheet->createSheet();
        $this->createRankingSheet($spreadsheet->getSheet(1), $uploadId);

        // Sheet 3: Statistics
        $spreadsheet->createSheet();
        $this->createStatisticsSheet($spreadsheet->getSheet(2), $uploadId);

        return $spreadsheet;
    }

    private function createRawDataSheet($sheet, array $rawData): void
    {
        $sheet->setTitle('Raw Data');

        $headers = [
            'ID Laporan',
            'ID Proyek',
            'Nama Perusahaan',
            'PMA/PMDN',
            'Periode Tahap',
            'Sektor Utama',
            '23 Sektor',
            'Jenis Badan Usaha',
            'Email',
            'Alamat',
            'Cetak Lokasi',
            'Sektor',
            'Deskripsi KBLI',
            'Provinsi',
            'Kabkot',
            'Kecamatan',
            'No Izin',
            'Tambahan Investasi',
            'Total Investasi',
            'Rencana Total Investasi',
            'Rencana Modal Tetap',
            'TKI',
            'TKA',
            'Nama Petugas',
            'Keterangan Masalah',
            'Penjelasan Modal Tetap',
            'No Telp',
            'Negara'
        ];

        $sheet->fromArray($headers, null, 'A1');

        $rows = [];
        foreach ($rawData as $project) {
            $rows[] = [
                $project['report_id'],
                $project['project_id'],
                $project['company_name'],
                $project['investment_type'],
                $project['period_stage'],
                $project['main_sector'],
                $project['sector_23'],
                $project['business_type'],
                $project['email'],
                $project['address'],
                $project['location_print'],
                $project['sector_detail'],
                $project['kbli_description'],
                $project['province'] ?: '-',
                $project['district'] ?: '-',
                $project['subdistrict'] ?: '-',
                $project['license_number'],
                $project['additional_investment'],
                $project['total_investment'],
                $project['planned_total_investment'],
                $project['fixed_capital_planned'],
                $project['tki'],
                $project['tka'],
                $project['officer_name'],
                $project['problem_description'],
                $project['fixed_capital_explanation'],
                $project['phone_number'],
                $project['country']
            ];
        }
        $sheet->fromArray($rows, null, 'A2');
    }

    private function createRankingSheet($sheet, int $uploadId): void
    {
        $sheet->setTitle('Ranking Proyek');
        $sheet->fromArray(['Kecamatan', 'PMA', 'PMDN'], null, 'A1');

        $districtData = $this->projectModel->getProjectsByDistrict($uploadId);
        $allDistricts = array_unique(array_merge(
            array_keys($districtData['PMA'] ?? []),
            array_keys($districtData['PMDN'] ?? [])
        ));

        $rows = [];
        foreach ($allDistricts as $district) {
            $rows[] = [
                $district,
                $districtData['PMA'][$district] ?? 0,
                $districtData['PMDN'][$district] ?? 0
            ];
        }
        $sheet->fromArray($rows, null, 'A2');
    }

    private function createStatisticsSheet($sheet, int $uploadId): void
    {
        $sheet->setTitle('Statistik Summary');

        $stats = $this->projectModel->getStatisticsByUpload($uploadId);
        $totalProjects = $this->projectModel->getTotalProjects($uploadId);
        $totalInvestment = $this->projectModel->getTotalInvestment($uploadId);

        $summaryData = [
            ['Statistik', 'PMA', 'PMDN', 'Total'],
            [
                'Total Proyek',
                $totalProjects['PMA'] ?? 0,
                $totalProjects['PMDN'] ?? 0,
                ($totalProjects['PMA'] ?? 0) + ($totalProjects['PMDN'] ?? 0)
            ],
            [
                'Total Investasi',
                $totalInvestment['PMA'] ?? 0,
                $totalInvestment['PMDN'] ?? 0,
                ($totalInvestment['PMA'] ?? 0) + ($totalInvestment['PMDN'] ?? 0)
            ],
            [
                'Total Proyek dari DB',
                $stats['total_projects_pma'] ?? 0,
                $stats['total_projects_pmdn'] ?? 0,
                ($stats['total_projects_pma'] ?? 0) + ($stats['total_projects_pmdn'] ?? 0)
            ],
            [
                'Total Investasi dari DB',
                $stats['total_investment_pma'] ?? 0,
                $stats['total_investment_pmdn'] ?? 0,
                ($stats['total_investment_pma'] ?? 0) + ($stats['total_investment_pmdn'] ?? 0)
            ]
        ];

        $sheet->fromArray($summaryData, null, 'A1');
    }

    private function getUpload(?string $uploadId)
    {
        if ($uploadId === 'all' || !$uploadId) {
            return $this->uploadModel->getLatestUpload();
        }
        return $this->uploadModel->getUploadById($uploadId);
    }

    private function getEmptyDashboard(array $filters, array $allUploads, ?array $upload = null): array
    {
        return [
            'uploads' => $allUploads,
            'filters' => $filters,
            'raw' => [],
            'total_projects' => ['PMA' => 0, 'PMDN' => 0],
            'total_investment' => ['PMA' => 0, 'PMDN' => 0],
            'total_additional_investment' => ['PMA' => 0, 'PMDN' => 0],
            'total_investment_usd' => ['PMA' => 0, 'PMDN' => 0],
            'additional_investment_by_district' => ['PMA' => [], 'PMDN' => []],
            'projects_by_district' => ['PMA' => [], 'PMDN' => []],
            'projects_by_district_pma' => [],
            'projects_by_district_pmdn' => [],
            'investment_by_location' => [],
            'sector_analysis' => [],
            'workforce' => ['PMA' => ['TKI' => 0, 'TKA' => 0], 'PMDN' => ['TKI' => 0, 'TKA' => 0]],
            'workforce_by_district' => ['PMA' => [], 'PMDN' => []],
            'projects_by_country' => [],
            'ranking_by_district' => [],
            'ranking_pma' => [],
            'ranking_pmdn' => [],
            'realization_investment' => ['PMA' => 0, 'PMDN' => 0],
            'quarterly_results' => [],
            'additional_investment_percentages' => ['PMA' => [], 'PMDN' => []],
            'sector_count_by_company' => [
                'PMA' => ['data' => [], 'total' => 0],
                'PMDN' => ['data' => [], 'total' => 0]
            ],
            'charts' => $this->chartService->getEmptyCharts(),
            'usd_rate' => $upload['usd_value'] ?? 16653,
            // Tambahan untuk dashboard_modern.php agar tidak error saat data kosong
            'total' => count($allUploads),
            'current_year' => $upload['year'] ?? date('Y'),
            'users' => $this->userModel->countAll(),
            'validation_rate' => 0,
            'recent_uploads' => [],
            'trend' => ['total' => '0%']
        ];
    }
}