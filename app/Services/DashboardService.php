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
            // Update: data charts juga dikirim untuk dikonversi
            $conversionData = &$statistics;
            $conversionData['charts'] = &$charts;
            $conversionData['investment_by_location'] = &$statistics['investment_by_location'];
            $conversionData['additional_investment_percentages'] = &$additionalInvestmentPercentages;

            $this->currencyService->convertToUSD($conversionData, $upload['usd_value']);

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

        // Ambil data LKPM per triwulan (TW1-TW4) berdasarkan upload yang dipilih
        $currentYear = (int) ($upload['year'] ?? date('Y'));
        $lkpmPMA = $this->projectModel->getSectorCountByCompanyAllQuarters($uploadId, 'PMA');
        $lkpmPMDN = $this->projectModel->getSectorCountByCompanyAllQuarters($uploadId, 'PMDN');

        // Konversi nilai ke USD jika currency dipilih USD
        if (($filters['currency'] ?? 'IDR') === 'USD') {
            $usdRate = $upload['usd_value'];
            foreach ($lkpmPMA as &$row) {
                $row['tambahan_realisasi_tw1'] = round($row['tambahan_realisasi_tw1'] / $usdRate, 2);
                $row['tambahan_realisasi_tw2'] = round($row['tambahan_realisasi_tw2'] / $usdRate, 2);
                $row['tambahan_realisasi_tw3'] = round($row['tambahan_realisasi_tw3'] / $usdRate, 2);
                $row['tambahan_realisasi_tw4'] = round($row['tambahan_realisasi_tw4'] / $usdRate, 2);
                $row['tambahan_realisasi'] = round($row['tambahan_realisasi'] / $usdRate, 2);
            }
            foreach ($lkpmPMDN as &$row) {
                $row['tambahan_realisasi_tw1'] = round($row['tambahan_realisasi_tw1'] / $usdRate, 2);
                $row['tambahan_realisasi_tw2'] = round($row['tambahan_realisasi_tw2'] / $usdRate, 2);
                $row['tambahan_realisasi_tw3'] = round($row['tambahan_realisasi_tw3'] / $usdRate, 2);
                $row['tambahan_realisasi_tw4'] = round($row['tambahan_realisasi_tw4'] / $usdRate, 2);
                $row['tambahan_realisasi'] = round($row['tambahan_realisasi'] / $usdRate, 2);
            }
        }

        // Merge semua data untuk dikirim ke view
        return array_merge($statistics, [
            'raw' => $rawData,
            'charts' => $charts,
            'uploads' => $allUploads,
            'filters' => $filters,
            'usd_rate' => $upload['usd_value'],
            'upload_name' => $upload['upload_name'],
            'quarter' => $upload['quarter'],
            'additional_investment_percentages' => $additionalInvestmentPercentages,
            'sector_count_by_company' => $sectorCountByCompany,
            'ranking_pma' => $statistics['projects_by_district']['PMA'] ?? [],
            'ranking_pmdn' => $statistics['projects_by_district']['PMDN'] ?? [],
            // Data LKPM triwulan (pivot per TW1-TW4)
            'lkpm_by_quarter' => [
                'PMA' => ['data' => $lkpmPMA, 'total' => count($lkpmPMA)],
                'PMDN' => ['data' => $lkpmPMDN, 'total' => count($lkpmPMDN)],
            ],
            'total_quarterly_reports' => [
                'PMA' => count($lkpmPMA),
                'PMDN' => count($lkpmPMDN),
            ],
            // Key tambahan untuk dashboard_modern.php
            'total' => $totalUploads,
            'current_year' => $currentYear,
            'users' => $totalUsers,
            'validation_rate' => 100, // Placeholder
            'recent_uploads' => $recentUploadsFormatted,
            'trend' => ['total' => '+10%'] // Placeholder
        ]);
    }


    /**
     * Aggregates KPI statistics across multiple uploads filtered by year and/or quarters.
     * Called ONLY by the new Tahun + Triwulan filter feature.
     * Does NOT affect table data (Peringkat, LKPM).
     *
     * @param  string|null $statYear      e.g. '2025' or 'all'
     * @param  array       $statQuarters  e.g. ['Q1','Q2'] or [] = all
     * @param  string      $currency      'IDR' or 'USD'
     * @param  float       $usdRate       Exchange rate for USD conversion
     * @return array|null  null if no matching uploads found
     */
    public function getAggregatedStatsByFilter(
        ?string $statYear,
        array $statQuarters,
        string $currency = 'IDR',
        float $usdRate = 16653
    ): ?array {
        // Step 1: Resolve upload IDs
        $uploadIds = $this->uploadModel->getUploadIdsByYearQuarters($statYear, $statQuarters);

        if (empty($uploadIds)) {
            return null;
        }

        $db = \Config\Database::connect();
        $table = 'projects';

        // Helper closure for aggregation
        $aggregate = function (string $type) use ($db, $table, $uploadIds) {
            return $db->table($table)
                ->select("
                    COALESCE(SUM(total_investment), 0)     AS total_investment,
                    COALESCE(SUM(additional_investment), 0) AS total_additional_investment,
                    COUNT(*)                               AS total_projects,
                    COALESCE(SUM(tki), 0)                 AS total_tki,
                    COALESCE(SUM(tka), 0)                 AS total_tka
                ")
                ->where('investment_type', $type)
                ->whereIn('upload_id', $uploadIds)
                ->get()->getRowArray();
        };

        $pma = $aggregate('PMA');
        $pmdn = $aggregate('PMDN');

        $totalInvestment = [
            'PMA' => (float) ($pma['total_investment'] ?? 0),
            'PMDN' => (float) ($pmdn['total_investment'] ?? 0),
        ];
        $totalAdditional = [
            'PMA' => (float) ($pma['total_additional_investment'] ?? 0),
            'PMDN' => (float) ($pmdn['total_additional_investment'] ?? 0),
        ];
        $totalProjects = [
            'PMA' => (int) ($pma['total_projects'] ?? 0),
            'PMDN' => (int) ($pmdn['total_projects'] ?? 0),
        ];
        $workforce = [
            'PMA' => ['TKI' => (int) ($pma['total_tki'] ?? 0), 'TKA' => (int) ($pma['total_tka'] ?? 0)],
            'PMDN' => ['TKI' => (int) ($pmdn['total_tki'] ?? 0), 'TKA' => (int) ($pmdn['total_tka'] ?? 0)],
        ];

        // Apply USD conversion if needed
        if ($currency === 'USD' && $usdRate > 0) {
            foreach (['PMA', 'PMDN'] as $t) {
                $totalInvestment[$t] = round($totalInvestment[$t] / $usdRate, 2);
                $totalAdditional[$t] = round($totalAdditional[$t] / $usdRate, 2);
            }
        }

        return [
            'total_investment' => $totalInvestment,
            'total_additional_investment' => $totalAdditional,
            'total_projects' => $totalProjects,
            'workforce' => $workforce,
            'upload_ids_used' => $uploadIds,
        ];
    }

    /**
     * Builds FULL dashboard data (charts + tables + labels) for a set of upload IDs.
     * Return array has the SAME keys as getDashboardData() so the view doesn't need changes.
     */
    public function getFullAggregatedData(array $uploadIds, array $filters): array
    {
        if (empty($uploadIds))
            return [];

        $pm = $this->projectModel;
        $cs = $this->chartService;
        $currency = $filters['currency'] ?? 'IDR';
        $usdRate = $this->uploadModel->getLatestUpload()['usd_value'] ?? 16653;
        $statYear = $filters['stat_year'] ?? 'all';

        // --- Chart source data ---
        $projectsByDistrict = $pm->getProjectsByDistrictMulti($uploadIds);
        $investmentByLoc = $pm->getInvestmentByDistrictMulti($uploadIds);
        $addByDistrict = $pm->getAdditionalInvestmentByDistrictMulti($uploadIds);
        $sectors = $pm->getSectorAnalysisMulti($uploadIds);
        $countries = $pm->getProjectsByCountryMulti($uploadIds);
        $workforceByDistrict = $pm->getWorkforceByDistrictMulti($uploadIds);
        $sectorCountByCompany = $pm->getSectorCountByCompany($uploadIds, []);

        // Sort PMA/PMDN district lists
        $pmaDistrict = $projectsByDistrict['PMA'] ?? [];
        arsort($pmaDistrict);
        $pmdnDistrict = $projectsByDistrict['PMDN'] ?? [];
        arsort($pmdnDistrict);

        // --- Additional investment percentages ---
        $additionalPercentages = $this->statisticsService->calculateAdditionalInvestmentPercentages($addByDistrict);

        // --- Quarterly trend: for selected year, show all 4 TW; for 'all', show all years ---
        $quarterlyData = ['Q1' => 0, 'Q2' => 0, 'Q3' => 0, 'Q4' => 0];
        // Always build for selected year (ignore TW sub-filter for this chart — show all TW of year)
        $yearUploads = $statYear !== 'all'
            ? $this->uploadModel->getUploadIdsByYearQuarters($statYear, []) // all quarters of year
            : $uploadIds;

        foreach ((array) $yearUploads as $uid) {
            $uploadInfo = $this->uploadModel->getUploadById($uid);
            if (!$uploadInfo || $uploadInfo['status'] !== 'completed')
                continue;
            $qKey = strtoupper(trim($uploadInfo['quarter'] ?? ''));
            if (!in_array($qKey, ['Q1', 'Q2', 'Q3', 'Q4']))
                continue;
            $addInv = $pm->getAdditionalInvestment($uid, []);
            $quarterlyData[$qKey] += ($addInv['PMA'] ?? 0) + ($addInv['PMDN'] ?? 0);
        }
        if ($currency === 'USD' && $usdRate > 0) {
            foreach ($quarterlyData as &$v)
                $v = round($v / $usdRate, 2);
        }
        $quarterlyChart = [
            'labels' => ['Q1', 'Q2', 'Q3', 'Q4'],
            'values' => array_values($quarterlyData),
            'year' => $statYear !== 'all' ? $statYear : 'Semua Tahun',
        ];

        // Also rebuild all-years quarterly for the year switcher buttons
        $allYearsData = (new QuarterlyChartService())->generateAllYears($usdRate, $currency);

        // Build USD-converted investment if needed
        if ($currency === 'USD' && $usdRate > 0) {
            foreach ($investmentByLoc as &$v)
                $v = round($v / $usdRate, 2);
            foreach ($addByDistrict as &$type) {
                foreach ($type as &$v)
                    $v = round($v / $usdRate, 2);
            }
            foreach ($additionalPercentages as &$type) {
                foreach ($type as &$row)
                    $row['amount'] = round(($row['amount'] ?? 0) / $usdRate, 2);
            }
        }

        // --- LKPM quarterly tables ---
        $lkpmPMA = $pm->getSectorCountByCompanyAllQuartersMulti($uploadIds, 'PMA');
        $lkpmPMDN = $pm->getSectorCountByCompanyAllQuartersMulti($uploadIds, 'PMDN');
        if ($currency === 'USD' && $usdRate > 0) {
            foreach ($lkpmPMA as &$row) {
                foreach (['tw1', 'tw2', 'tw3', 'tw4', ''] as $sfx) {
                    $k = 'tambahan_realisasi' . ($sfx ? "_$sfx" : '');
                    if (isset($row[$k]))
                        $row[$k] = round($row[$k] / $usdRate, 2);
                }
            }
            foreach ($lkpmPMDN as &$row) {
                foreach (['tw1', 'tw2', 'tw3', 'tw4', ''] as $sfx) {
                    $k = 'tambahan_realisasi' . ($sfx ? "_$sfx" : '');
                    if (isset($row[$k]))
                        $row[$k] = round($row[$k] / $usdRate, 2);
                }
            }
        }

        // --- Period label for insight text ---
        $quarterNames = ['Q1' => 'TW 1', 'Q2' => 'TW 2', 'Q3' => 'TW 3', 'Q4' => 'TW 4'];
        $statQuarters = $filters['stat_quarters'] ?? [];
        if (!empty($statQuarters)) {
            $twLabels = implode(' + ', array_map(fn($q) => $quarterNames[$q] ?? $q, $statQuarters));
        } else {
            $twLabels = 'Semua TW';
        }
        $periodLabel = ($statYear !== 'all' ? $statYear : 'Semua Tahun') . ' / ' . $twLabels;

        // Top district by project count (PMA+PMDN)
        $allDistricts = [];
        foreach (['PMA', 'PMDN'] as $t) {
            foreach ($projectsByDistrict[$t] ?? [] as $d => $cnt) {
                $allDistricts[$d] = ($allDistricts[$d] ?? 0) + $cnt;
            }
        }
        arsort($allDistricts);
        $topDistrict = !empty($allDistricts) ? array_key_first($allDistricts) : '-';
        $topDistrictCount = $allDistricts[$topDistrict] ?? 0;

        // --- Build ranking_by_district (array of ['kecamatan','jumlah_proyek']) ---
        // Combines PMA + PMDN counts per district, sorted DESC — same format as getRankingByDistrict()
        $rankingByDistrict = [];
        foreach ($allDistricts as $kec => $count) {
            $rankingByDistrict[] = ['kecamatan' => $kec, 'jumlah_proyek' => $count];
        }

        // --- Normalize sector_count_by_company ---
        $sectorCountByCompany = [
            'PMA'  => $sectorCountByCompany['PMA']  ?? ['data' => [], 'total' => 0],
            'PMDN' => $sectorCountByCompany['PMDN'] ?? ['data' => [], 'total' => 0],
        ];

        // --- Aggregate workforce totals (PMA+PMDN) ---
        $workforceTotals = ['PMA' => ['TKI' => 0, 'TKA' => 0], 'PMDN' => ['TKI' => 0, 'TKA' => 0]];
        foreach (['PMA', 'PMDN'] as $wt) {
            foreach ($workforceByDistrict[$wt] ?? [] as $wData) {
                $workforceTotals[$wt]['TKI'] += $wData['TKI'] ?? 0;
                $workforceTotals[$wt]['TKA'] += $wData['TKA'] ?? 0;
            }
        }

        return [
            // Charts (same keys as getDashboardData)
            'charts' => [
                'district'         => $cs->generateDistrictChart($projectsByDistrict),
                'locations'        => $cs->generateLocationChart($investmentByLoc),
                'sectors'          => $cs->generateSectorChart($sectors),
                'countries'        => $cs->generateCountryChart($countries),
                'workforce'        => $cs->generateWorkforceChart($workforceByDistrict),
                'quarterly_additional_investment'           => $quarterlyChart,
                'quarterly_additional_investment_all_years' => $allYearsData,
            ],
            // District ranking tables (both formats used by view)
            'ranking_by_district'            => $rankingByDistrict,      // [{kecamatan,jumlah_proyek}]
            'ranking_pma'                    => $pmaDistrict,             // [kec=>count] keyed
            'ranking_pmdn'                   => $pmdnDistrict,
            // Company tables
            'sector_count_by_company'        => $sectorCountByCompany,
            // LKPM quarterly pivot
            'lkpm_by_quarter'                => [
                'PMA'  => ['data' => $lkpmPMA,  'total' => count($lkpmPMA)],
                'PMDN' => ['data' => $lkpmPMDN, 'total' => count($lkpmPMDN)],
            ],
            'total_quarterly_reports'        => [
                'PMA'  => count($lkpmPMA),
                'PMDN' => count($lkpmPMDN),
            ],
            // District-level data
            'projects_by_district'           => $projectsByDistrict,
            'projects_by_district_pma'       => $pmaDistrict,
            'projects_by_district_pmdn'      => $pmdnDistrict,
            'additional_investment_by_district' => $addByDistrict,
            'additional_investment_percentages' => $additionalPercentages,
            'investment_by_location'         => $investmentByLoc,
            // Analysis
            'sector_analysis'                => $sectors,
            'workforce'                      => $workforceTotals,
            'workforce_by_district'          => $workforceByDistrict,
            'projects_by_country'            => $countries,
            // Insight / period info
            'period_label'                   => $periodLabel,
            'top_district'                   => $topDistrict,
            'top_district_count'             => $topDistrictCount,
        ];
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
            'lkpm_by_quarter' => [
                'PMA' => ['data' => [], 'total' => 0],
                'PMDN' => ['data' => [], 'total' => 0],
            ],
            'total_quarterly_reports' => ['PMA' => 0, 'PMDN' => 0],
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