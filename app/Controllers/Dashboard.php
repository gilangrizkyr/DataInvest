<?php

namespace App\Controllers;

use App\Models\ExcelModel;
use App\Models\ProjectModel;
use App\Models\UploadModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Dashboard extends BaseController
{
    protected $excelModel;
    protected $projectModel;
    protected $uploadModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->excelModel = new ExcelModel();
        $this->projectModel = new ProjectModel();
        $this->uploadModel = new UploadModel();
    }

    public function index()
    {
        // Get filter parameters
        $selectedUpload = $this->request->getGet('upload') ?? 'all';
        $selectedQuarter = $this->request->getGet('quarter') ?? 'all';
        $selectedYear = $this->request->getGet('year') ?? 'all';
        $selectedQuarterlyYear = $this->request->getGet('quarterly_year') ?? 'all';
        $selectedCurrency = $this->request->getGet('currency') ?? 'IDR';

        // Get all uploads for management and filtering
        $allUploads = $this->uploadModel->getAllUploads();

        // Determine which upload to use
        if ($selectedUpload === 'all') {
            $upload = $this->uploadModel->getLatestUpload();
        } else {
            $upload = $this->uploadModel->getUploadById($selectedUpload);
        }

        // If no upload found, show empty dashboard
        if (!$upload) {
            $data = [
                'uploads' => $allUploads,
                'filters' => [
                    'upload' => $selectedUpload,
                    'quarter' => $selectedQuarter,
                    'year' => $selectedYear,
                    'currency' => $selectedCurrency
                ],
                'total_projects' => ['PMA' => 0, 'PMDN' => 0],
                'total_investment' => ['PMA' => 0, 'PMDN' => 0],
                'total_additional_investment' => ['PMA' => 0, 'PMDN' => 0],
                'total_investment_usd' => ['PMA' => 0, 'PMDN' => 0],
                'projects_by_district' => ['PMA' => [], 'PMDN' => []],
                'investment_by_location' => [],
                'sector_analysis' => [],
                'workforce' => [
                    'PMA' => ['TKI' => 0, 'TKA' => 0],
                    'PMDN' => ['TKI' => 0, 'TKA' => 0]
                ],
                'workforce_by_district' => ['PMA' => [], 'PMDN' => []],
                'projects_by_country' => [],
                'charts' => [
                    'district' => ['labels' => [], 'pma' => [], 'pmdn' => []],
                    'locations' => ['labels' => [], 'values' => []],
                    'sectors' => ['labels' => [], 'counts' => []],
                    'countries' => ['labels' => [], 'counts' => []],
                    'quarterly_additional_investment' => [
                        'labels' => ['Q1', 'Q2', 'Q3', 'Q4'],
                        'values' => [0, 0, 0, 0],
                        'year' => 'Semua Tahun'
                    ],
                    'quarterly_additional_investment_all_years' => [] // Tambahkan ini
                ],
                'ranking_pma' => [],
                'ranking_pmdn' => [],
                'ranking_by_district' => [],
                'usd_rate' => 16653
            ];
            return view('dashboard', ['data' => $data]);
        }

        $uploadId = $upload['id'];

        // Apply filters to get filtered data
        $filterConditions = [];
        if ($selectedQuarter !== 'all') {
            $filterConditions['quarter'] = $selectedQuarter;
        }
        if ($selectedYear !== 'all') {
            $filterConditions['year'] = $selectedYear;
        }

        // Check if filtered data exists
        $rawData = $this->projectModel->getProjectsByUpload($uploadId, $filterConditions);
        $hasFilteredData = !empty($rawData);

        // If no data matches filters, show empty statistics
        if (!$hasFilteredData) {
            $data = [
                'raw' => [],
                'total_projects' => ['PMA' => 0, 'PMDN' => 0],
                'total_investment' => ['PMA' => 0, 'PMDN' => 0],
                'total_additional_investment' => ['PMA' => 0, 'PMDN' => 0],
                'additional_investment_by_district' => ['PMA' => [], 'PMDN' => []],
                'projects_by_district' => ['PMA' => [], 'PMDN' => []],
                'investment_by_location' => [],
                'sector_analysis' => [],
                'workforce' => ['PMA' => ['TKI' => 0, 'TKA' => 0], 'PMDN' => ['TKI' => 0, 'TKA' => 0]],
                'workforce_by_district' => ['PMA' => [], 'PMDN' => []],
                'projects_by_country' => [],
                'ranking_by_district' => [],
                'realization_investment' => ['PMA' => 0, 'PMDN' => 0],
                'quarterly_results' => [],
                'usd_rate' => $upload['usd_value'] ?? 16653
            ];
        } else {
            // Fetch all statistics from database with filters
            $data = [
                'raw' => $rawData,
                'total_projects' => $this->projectModel->getTotalProjects($uploadId, $filterConditions),
                'total_investment' => $this->projectModel->getTotalInvestment($uploadId, $filterConditions),
                'total_additional_investment' => $this->projectModel->getAdditionalInvestment($uploadId, $filterConditions),
                'additional_investment_by_district' => $this->projectModel->getAdditionalInvestmentByDistrict($uploadId, $filterConditions),
                'projects_by_district' => $this->projectModel->getProjectsByDistrict($uploadId, $filterConditions),
                'investment_by_location' => $this->projectModel->getInvestmentByDistrict($uploadId, $filterConditions),
                'sector_analysis' => $this->projectModel->getSectorAnalysis($uploadId, $filterConditions),
                'workforce' => $this->projectModel->getWorkforce($uploadId, $filterConditions),
                'workforce_by_district' => $this->projectModel->getWorkforceByDistrict($uploadId, $filterConditions),
                'projects_by_country' => $this->projectModel->getProjectsByCountry($uploadId, $filterConditions),
                'ranking_by_district' => $this->projectModel->getRankingByDistrict($uploadId, $filterConditions),
                'realization_investment' => $this->projectModel->getRealizationInvestment($uploadId, $filterConditions),
                'quarterly_results' => $this->projectModel->getQuarterlyResults($uploadId, $filterConditions),
                'usd_rate' => $upload['usd_value'] ?? 16653
            ];
        }

        // Calculate USD investment using upload's USD rate
        $usdRate = $data['usd_rate'];
        $data['total_investment_usd'] = [
            'PMA' => !empty($data['total_investment']) ? round(($data['total_investment']['PMA'] ?? 0) / $usdRate, 2) : 0,
            'PMDN' => !empty($data['total_investment']) ? round(($data['total_investment']['PMDN'] ?? 0) / $usdRate, 2) : 0
        ];

        // Convert data to selected currency if USD
        if ($selectedCurrency === 'USD') {
            $this->convertDataToUSD($data, $usdRate);
        }

        // Prepare ranking PMA/PMDN from district data
        $data['ranking_pma'] = [];
        $data['ranking_pmdn'] = [];
        foreach ($data['projects_by_district']['PMA'] ?? [] as $district => $count) {
            $data['ranking_pma'][$district] = $count;
        }
        foreach ($data['projects_by_district']['PMDN'] ?? [] as $district => $count) {
            $data['ranking_pmdn'][$district] = $count;
        }

        // Calculate additional investment percentages by district for PMA and PMDN separately
        $additionalInvestmentByDistrict = $data['additional_investment_by_district'];
        $totalAdditionalPMA = array_sum($additionalInvestmentByDistrict['PMA'] ?? []);
        $totalAdditionalPMDN = array_sum($additionalInvestmentByDistrict['PMDN'] ?? []);

        $data['additional_investment_percentages'] = [
            'PMA' => [],
            'PMDN' => []
        ];

        foreach ($additionalInvestmentByDistrict['PMA'] ?? [] as $district => $amount) {
            $percentage = $totalAdditionalPMA > 0 ? round(($amount / $totalAdditionalPMA) * 100, 1) : 0;
            $data['additional_investment_percentages']['PMA'][$district] = [
                'percentage' => $percentage,
                'amount' => $amount
            ];
        }

        foreach ($additionalInvestmentByDistrict['PMDN'] ?? [] as $district => $amount) {
            $percentage = $totalAdditionalPMDN > 0 ? round(($amount / $totalAdditionalPMDN) * 100, 1) : 0;
            $data['additional_investment_percentages']['PMDN'][$district] = [
                'percentage' => $percentage,
                'amount' => $amount
            ];
        }

        // Prepare charts data
        // Prepare charts data
        $data['charts'] = [
            'district' => $this->prepareDistrictChartData($data),
            'locations' => $this->prepareLocationChartData($data),
            'sectors' => $this->prepareSectorChartData($data),
            'countries' => $this->prepareCountryChartData($data),
            'quarterly_additional_investment' => $this->prepareQuarterlyAdditionalInvestmentChartData(
                'all', // Selalu gunakan 'all' untuk data default
                $usdRate,
                $selectedCurrency
            ),
            'quarterly_additional_investment_all_years' => $this->prepareQuarterlyAdditionalInvestmentChartDataAllYears(
                $usdRate,
                $selectedCurrency
            )
        ];

        // Add uploads data and current filters
        $data['uploads'] = $allUploads;
        $data['filters'] = [
            'upload' => $selectedUpload,
            'quarter' => $selectedQuarter,
            'year' => $selectedYear,
            'quarterly_year' => $selectedQuarterlyYear,
            'currency' => $selectedCurrency
        ];

        return view('dashboard', ['data' => $data]);
    }

    public function upload()
    {
        $file = $this->request->getFile('excel_file');

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid atau tidak ditemukan.');
        }

        $ext = strtolower($file->getClientExtension() ?: $file->getExtension());
        if (!in_array($ext, ['xlsx', 'xls'])) {
            return redirect()->back()->with('error', 'Hanya file .xlsx/.xls yang diperbolehkan.');
        }

        $filePath = WRITEPATH . 'uploads/' . $file->getRandomName();
        $file->move(WRITEPATH . 'uploads/', basename($filePath));

        // Create upload record
        $uploadId = $this->uploadModel->createUpload([
            'filename' => $file->getName(),
            'original_filename' => $file->getClientName(),
            'file_path' => $filePath,
            'status' => 'uploaded'
        ]);

        // validasi
        $validation = $this->excelModel->validateColumns($filePath);

        if (!$validation['valid']) {
            // Update upload status to failed
            $this->uploadModel->updateStatus($uploadId, 'failed', [
                'error_message' => 'Kolom tidak lengkap: ' . implode(', ', $validation['missing'])
            ]);
            unlink($filePath);
            return redirect()->to('/dashboard')->with('error', 'Data gagal diproses, kolom tidak lengkap: ' . implode(', ', $validation['missing']));
        }

        // Redirect to metadata input with success message
        return redirect()->to('/dashboard/metadata/' . $uploadId)->with('success', 'File berhasil diupload. Silakan lengkapi metadata sebelum memproses data.');
    }

    public function download()
    {
        // Get latest upload
        $latestUpload = $this->uploadModel->getLatestUpload();
        if (!$latestUpload) {
            return redirect()->to('/dashboard')->with('error', 'Tidak ada data untuk diunduh.');
        }

        $uploadId = $latestUpload['id'];

        // Get raw project data
        $rawData = $this->projectModel->getProjectsByUpload($uploadId);
        if (empty($rawData)) {
            return redirect()->to('/dashboard')->with('error', 'Tidak ada data proyek untuk diunduh.');
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        // SHEET 1 RAW DATA
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Raw Data');

        // Define headers for export
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

        // SHEET 2 RANKING PROYEK PMA / PMDN
        $spreadsheet->createSheet();
        $sheet = $spreadsheet->getSheet(1);
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

        // SHEET 3 STATISTICS SUMMARY
        $spreadsheet->createSheet();
        $sheet = $spreadsheet->getSheet(2);
        $sheet->setTitle('Statistik Summary');

        $stats = $this->projectModel->getStatisticsByUpload($uploadId);
        $totalProjects = $this->projectModel->getTotalProjects($uploadId);
        $totalInvestment = $this->projectModel->getTotalInvestment($uploadId);

        $summaryData = [
            ['Statistik', 'PMA', 'PMDN', 'Total'],
            ['Total Proyek', $totalProjects['PMA'] ?? 0, $totalProjects['PMDN'] ?? 0, ($totalProjects['PMA'] ?? 0) + ($totalProjects['PMDN'] ?? 0)],
            ['Total Investasi', $totalInvestment['PMA'] ?? 0, $totalInvestment['PMDN'] ?? 0, ($totalInvestment['PMA'] ?? 0) + ($totalInvestment['PMDN'] ?? 0)],
            ['Total Proyek dari DB', $stats['total_projects_pma'] ?? 0, $stats['total_projects_pmdn'] ?? 0, ($stats['total_projects_pma'] ?? 0) + ($stats['total_projects_pmdn'] ?? 0)],
            ['Total Investasi dari DB', $stats['total_investment_pma'] ?? 0, $stats['total_investment_pmdn'] ?? 0, ($stats['total_investment_pma'] ?? 0) + ($stats['total_investment_pmdn'] ?? 0)]
        ];

        $sheet->fromArray($summaryData, null, 'A1');

        // OUTPUT FILE
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'hasil_analisis_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    private function prepareDistrictChartData($data)
    {
        $districts = $data['projects_by_district'] ?? ['PMA' => [], 'PMDN' => []];
        $allDistricts = array_unique(array_merge(array_keys($districts['PMA']), array_keys($districts['PMDN'])));

        $labels = [];
        $pma = [];
        $pmdn = [];

        foreach ($allDistricts as $district) {
            $labels[] = $district;
            $pma[] = $districts['PMA'][$district] ?? 0;
            $pmdn[] = $districts['PMDN'][$district] ?? 0;
        }

        return ['labels' => $labels, 'pma' => $pma, 'pmdn' => $pmdn];
    }

    private function prepareLocationChartData($data)
    {
        $locations = $data['investment_by_location'] ?? [];
        arsort($locations); // Sort by investment amount descending
        $top10 = array_slice($locations, 0, 10, true); // Get top 10

        return [
            'labels' => array_keys($top10),
            'values' => array_values($top10)
        ];
    }

    private function prepareSectorChartData($data)
    {
        $sectors = $data['sector_analysis'] ?? [];

        $labels = [];
        $counts = [];

        foreach ($sectors as $sector) {
            $labels[] = $sector['sector'];
            $counts[] = $sector['count'];
        }

        return ['labels' => $labels, 'counts' => $counts];
    }

    private function prepareCountryChartData($data)
    {
        $countries = $data['projects_by_country'] ?? [];

        $labels = [];
        $counts = [];

        foreach ($countries as $country => $count) {
            $labels[] = $country ?: 'Tidak Diketahui';
            $counts[] = $count;
        }

        return ['labels' => $labels, 'counts' => $counts];
    }

    private function prepareQuarterlyAdditionalInvestmentChartData($selectedYear, $usdRate, $selectedCurrency)
    {
        // Get all uploads to aggregate quarterly data for the selected year
        $allUploads = $this->uploadModel->getAllUploads();
        $allUploads = $allUploads ?? [];

        // Debug: Log all uploads
        log_message('debug', 'prepareQuarterlyAdditionalInvestmentChartData - Total uploads: ' . count($allUploads));
        log_message('debug', 'prepareQuarterlyAdditionalInvestmentChartData - Selected year: ' . $selectedYear);

        // Initialize quarterly data
        $quarterlyData = [
            'Q1' => 0,
            'Q2' => 0,
            'Q3' => 0,
            'Q4' => 0
        ];

        // Aggregate data from all uploads for the selected year
        foreach ($allUploads as $upload) {
            // Debug: Log each upload
            log_message('debug', 'Processing upload ID: ' . ($upload['id'] ?? 'N/A') .
                ', Status: ' . ($upload['status'] ?? 'N/A') .
                ', Year: ' . ($upload['year'] ?? 'N/A') .
                ', Quarter: ' . ($upload['quarter'] ?? 'N/A'));

            // Filter berdasarkan tahun dan status completed
            if ($upload['status'] === 'completed') {
                // Jika selectedYear adalah 'all', ambil semua tahun
                // Jika selectedYear spesifik, hanya ambil upload dengan tahun tersebut
                if ($selectedYear === 'all' || $upload['year'] == $selectedYear) {
                    $uploadId = $upload['id'];

                    // Get total additional investment data for this upload (tanpa filter)
                    $additionalInvestment = $this->projectModel->getAdditionalInvestment($uploadId, []);

                    // Debug: Log investment data
                    log_message('debug', 'Upload ID ' . $uploadId . ' - PMA: ' . ($additionalInvestment['PMA'] ?? 0) .
                        ', PMDN: ' . ($additionalInvestment['PMDN'] ?? 0));

                    // FIX: Ambil quarter langsung sebagai string Q1, Q2, Q3, Q4
                    $quarterKey = isset($upload['quarter']) ? strtoupper(trim($upload['quarter'])) : '';

                    // Validasi quarter harus Q1, Q2, Q3, atau Q4
                    if (in_array($quarterKey, ['Q1', 'Q2', 'Q3', 'Q4'])) {
                        // Tambahkan PMA + PMDN ke quarter yang sesuai
                        $pmaInvestment = isset($additionalInvestment['PMA']) ? floatval($additionalInvestment['PMA']) : 0;
                        $pmdnInvestment = isset($additionalInvestment['PMDN']) ? floatval($additionalInvestment['PMDN']) : 0;
                        $totalInvestment = $pmaInvestment + $pmdnInvestment;

                        $quarterlyData[$quarterKey] += $totalInvestment;

                        // Debug: Log addition
                        log_message('debug', 'Added to ' . $quarterKey . ': ' . $totalInvestment .
                            ' (Total now: ' . $quarterlyData[$quarterKey] . ')');
                    } else {
                        log_message('warning', 'Invalid quarter for upload ID ' . $uploadId . ': ' . $quarterKey);
                    }
                }
            }
        }

        // Debug: Log final quarterly data before currency conversion
        log_message('debug', 'Final quarterly data before conversion: ' . json_encode($quarterlyData));

        // Convert to USD if selected currency is USD
        if ($selectedCurrency === 'USD') {
            foreach ($quarterlyData as $quarter => &$amount) {
                $amount = round($amount / $usdRate, 2);
            }
            log_message('debug', 'Converted to USD with rate ' . $usdRate . ': ' . json_encode($quarterlyData));
        }

        $result = [
            'labels' => ['Q1', 'Q2', 'Q3', 'Q4'],
            'values' => array_values($quarterlyData),
            'year' => $selectedYear === 'all' ? 'Semua Tahun' : $selectedYear
        ];

        // Debug: Log final result
        log_message('debug', 'prepareQuarterlyAdditionalInvestmentChartData - Final result: ' . json_encode($result));

        return $result;
    }

    private function prepareQuarterlyAdditionalInvestmentChartDataAllYears($usdRate, $selectedCurrency)
    {
        // Get all uploads to aggregate quarterly data for all years
        $allUploads = $this->uploadModel->getAllUploads();
        $allUploads = $allUploads ?? [];

        // Debug: Log all uploads
        log_message('debug', 'prepareQuarterlyAdditionalInvestmentChartDataAllYears - Total uploads: ' . count($allUploads));

        // Initialize data structure for all years
        $yearlyQuarterlyData = [];

        // Aggregate data from all uploads grouped by year
        foreach ($allUploads as $upload) {
            // Debug: Log each upload
            log_message('debug', 'Processing upload ID: ' . ($upload['id'] ?? 'N/A') .
                ', Status: ' . ($upload['status'] ?? 'N/A') .
                ', Year: ' . ($upload['year'] ?? 'N/A') .
                ', Quarter: ' . ($upload['quarter'] ?? 'N/A'));

            if ($upload['status'] === 'completed') {
                $uploadId = $upload['id'];
                $year = isset($upload['year']) ? strval($upload['year']) : 'Unknown';

                // FIX: Ambil quarter langsung sebagai string Q1, Q2, Q3, Q4
                $quarterKey = isset($upload['quarter']) ? strtoupper(trim($upload['quarter'])) : '';

                // Initialize year data if not exists
                if (!isset($yearlyQuarterlyData[$year])) {
                    $yearlyQuarterlyData[$year] = [
                        'Q1' => 0,
                        'Q2' => 0,
                        'Q3' => 0,
                        'Q4' => 0
                    ];
                    log_message('debug', 'Initialized year: ' . $year);
                }

                // Get total additional investment data for this upload
                $additionalInvestment = $this->projectModel->getAdditionalInvestment($uploadId, []);

                // Debug: Log investment data
                log_message('debug', 'Upload ID ' . $uploadId . ' (Year: ' . $year . ', ' . $quarterKey . ') - PMA: ' .
                    ($additionalInvestment['PMA'] ?? 0) . ', PMDN: ' . ($additionalInvestment['PMDN'] ?? 0));

                // Add to quarterly totals based on upload's quarter (PMA + PMDN combined)
                if (in_array($quarterKey, ['Q1', 'Q2', 'Q3', 'Q4'])) {
                    $pmaInvestment = isset($additionalInvestment['PMA']) ? floatval($additionalInvestment['PMA']) : 0;
                    $pmdnInvestment = isset($additionalInvestment['PMDN']) ? floatval($additionalInvestment['PMDN']) : 0;
                    $totalInvestment = $pmaInvestment + $pmdnInvestment;

                    $yearlyQuarterlyData[$year][$quarterKey] += $totalInvestment;

                    // Debug: Log addition
                    log_message('debug', 'Added to Year ' . $year . ' ' . $quarterKey . ': ' . $totalInvestment .
                        ' (Total now: ' . $yearlyQuarterlyData[$year][$quarterKey] . ')');
                } else {
                    log_message('warning', 'Invalid quarter for upload ID ' . $uploadId . ': ' . $quarterKey);
                }
            }
        }

        // Debug: Log data before currency conversion
        log_message('debug', 'Yearly quarterly data before conversion: ' . json_encode($yearlyQuarterlyData));

        // Convert to USD if selected currency is USD
        if ($selectedCurrency === 'USD') {
            foreach ($yearlyQuarterlyData as $year => &$quarterlyData) {
                foreach ($quarterlyData as $quarter => &$amount) {
                    $amount = round($amount / $usdRate, 2);
                }
            }
            log_message('debug', 'Converted to USD with rate ' . $usdRate);
        }

        // Convert to the format expected by JavaScript
        $result = [];
        foreach ($yearlyQuarterlyData as $year => $quarterlyData) {
            $result[$year] = [
                'labels' => ['Q1', 'Q2', 'Q3', 'Q4'],
                'values' => array_values($quarterlyData)
            ];
        }

        // Debug: Log final result
        log_message('debug', 'prepareQuarterlyAdditionalInvestmentChartDataAllYears - Final result: ' . json_encode($result));

        return $result;
    }

    public function metadata($uploadId)
    {
        $upload = $this->uploadModel->getUploadById($uploadId);

        if (!$upload || $upload['status'] !== 'uploaded') {
            return redirect()->to('/dashboard')->with('error', 'Upload tidak ditemukan atau tidak valid.');
        }

        return view('upload_metadata', ['upload' => $upload]);
    }

    public function processMetadata()
    {
        $uploadId = $this->request->getPost('upload_id');
        $uploadName = $this->request->getPost('upload_name');
        $quarter = $this->request->getPost('quarter');
        $year = $this->request->getPost('year');
        $usdValue = $this->request->getPost('usd_value');

        if (!$uploadId || !$uploadName || !$quarter || !$year || !$usdValue) {
            return redirect()->back()->with('error', 'Semua field metadata harus diisi.');
        }

        $upload = $this->uploadModel->getUploadById($uploadId);
        if (!$upload || $upload['status'] !== 'uploaded') {
            return redirect()->to('/dashboard')->with('error', 'Upload tidak valid.');
        }

        // VALIDASI DUPLIKAT - CEK QUARTER DAN YEAR
        $validation = $this->uploadModel->validateMetadata($uploadId, $quarter, $year);
        if (!$validation['valid']) {
            // Jika ada duplikat, tampilkan peringatan dengan detail
            $duplicate = $validation['duplicate'];
            $errorMessage = $validation['message'];

            // Tambahkan informasi detail tentang upload yang sudah ada
            if ($duplicate) {
                $errorMessage .= "<br><br><strong>Detail Upload yang Sudah Ada:</strong>";
                $errorMessage .= "<br>• Nama Upload: " . htmlspecialchars($duplicate['upload_name']);
                $errorMessage .= "<br>• Quarter: " . htmlspecialchars($duplicate['quarter']);
                $errorMessage .= "<br>• Tahun: " . htmlspecialchars($duplicate['year']);
                $errorMessage .= "<br>• Total Records: " . number_format($duplicate['total_records']);
                $errorMessage .= "<br>• Tanggal Upload: " . date('d/m/Y H:i', strtotime($duplicate['upload_date']));
            }

            return redirect()->back()->with('error', $errorMessage);
        }

        // Update metadata
        $this->uploadModel->update($uploadId, [
            'upload_name' => $uploadName,
            'quarter' => $quarter,
            'year' => $year,
            'usd_value' => $usdValue,
            'status' => 'processing'
        ]);

        try {
            // Process data
            $totalRecords = $this->excelModel->processData($upload['file_path'], $uploadId);

            // Update status to completed
            $this->uploadModel->updateStatus($uploadId, 'completed', [
                'total_records' => $totalRecords,
                'processed_records' => $totalRecords
            ]);

            unlink($upload['file_path']);

            return redirect()->to('/dashboard')->with('success', "Data berhasil diproses. Total {$totalRecords} record diproses.");
        } catch (\Exception $e) {
            $this->uploadModel->updateStatus($uploadId, 'failed', [
                'error_message' => $e->getMessage()
            ]);
            if (file_exists($upload['file_path'])) {
                unlink($upload['file_path']);
            }
            return redirect()->to('/dashboard')->with('error', 'Terjadi kesalahan saat memproses data: ' . $e->getMessage());
        }
    }

    public function editMetadata($uploadId)
    {
        $upload = $this->uploadModel->getUploadById($uploadId);

        if (!$upload) {
            return redirect()->to('/dashboard')->with('error', 'Upload tidak ditemukan.');
        }

        return view('upload_metadata', ['upload' => $upload, 'isEdit' => true]);
    }

    public function updateMetadata()
    {
        $uploadId = $this->request->getPost('upload_id');
        $uploadName = $this->request->getPost('upload_name');
        $quarter = $this->request->getPost('quarter');
        $year = $this->request->getPost('year');
        $usdValue = $this->request->getPost('usd_value');

        if (!$uploadId || !$uploadName || !$quarter || !$year || !$usdValue) {
            return redirect()->back()->with('error', 'Semua field metadata harus diisi.');
        }

        $upload = $this->uploadModel->getUploadById($uploadId);
        if (!$upload) {
            return redirect()->to('/dashboard')->with('error', 'Upload tidak ditemukan.');
        }

        // VALIDASI DUPLIKAT - CEK QUARTER DAN YEAR (exclude current upload)
        $validation = $this->uploadModel->validateMetadata($uploadId, $quarter, $year);
        if (!$validation['valid']) {
            // Jika ada duplikat, tampilkan peringatan dengan detail
            $duplicate = $validation['duplicate'];
            $errorMessage = $validation['message'];

            // Tambahkan informasi detail tentang upload yang sudah ada
            if ($duplicate) {
                $errorMessage .= "<br><br><strong>Detail Upload yang Sudah Ada:</strong>";
                $errorMessage .= "<br>• Nama Upload: " . htmlspecialchars($duplicate['upload_name']);
                $errorMessage .= "<br>• Quarter: " . htmlspecialchars($duplicate['quarter']);
                $errorMessage .= "<br>• Tahun: " . htmlspecialchars($duplicate['year']);
                $errorMessage .= "<br>• Total Records: " . number_format($duplicate['total_records']);
                $errorMessage .= "<br>• Tanggal Upload: " . date('d/m/Y H:i', strtotime($duplicate['upload_date']));
            }

            return redirect()->back()->with('error', $errorMessage);
        }

        try {
            $updateData = [
                'upload_name' => $uploadName,
                'quarter' => $quarter,
                'year' => $year,
                'usd_value' => $usdValue
            ];

            $result = $this->uploadModel->update($uploadId, $updateData);

            if ($result === false) {
                // Get database error
                $dbError = $this->uploadModel->db->error();
                log_message('error', 'Failed to update metadata for upload ID ' . $uploadId . ': ' . json_encode($dbError));
                return redirect()->back()->with('error', 'Gagal memperbarui metadata. Silakan coba lagi.');
            }

            // Verify the update was successful by checking the data
            $updatedUpload = $this->uploadModel->getUploadById($uploadId);
            if (
                !$updatedUpload ||
                $updatedUpload['upload_name'] !== $uploadName ||
                $updatedUpload['quarter'] !== $quarter ||
                $updatedUpload['year'] != $year ||
                $updatedUpload['usd_value'] != $usdValue
            ) {
                log_message('error', 'Metadata update verification failed for upload ID ' . $uploadId);
                return redirect()->back()->with('error', 'Metadata berhasil disimpan tetapi verifikasi gagal. Silakan refresh halaman.');
            }

            return redirect()->to('/dashboard')->with('success', 'Metadata berhasil diperbarui.');
        } catch (\Exception $e) {
            log_message('error', 'Exception during metadata update for upload ID ' . $uploadId . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui metadata: ' . $e->getMessage());
        }
    }

    public function deleteUpload()
    {
        $uploadId = $this->request->getPost('upload_id');

        if (!$uploadId) {
            return redirect()->to('/dashboard')->with('error', 'ID upload tidak valid.');
        }

        $upload = $this->uploadModel->getUploadById($uploadId);
        if (!$upload) {
            return redirect()->to('/dashboard')->with('error', 'Upload tidak ditemukan.');
        }

        try {
            // Delete associated project data
            $this->projectModel->deleteProjectsByUpload($uploadId);

            // Delete upload record
            $this->uploadModel->delete($uploadId);

            // Delete file if exists
            if (file_exists($upload['file_path'])) {
                unlink($upload['file_path']);
            }

            return redirect()->to('/dashboard')->with('success', 'Upload dan data terkait berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to('/dashboard')->with('error', 'Terjadi kesalahan saat menghapus upload: ' . $e->getMessage());
        }
    }

    public function setLanguage()
    {
        $language = $this->request->getPost('language');

        if (!in_array($language, ['id', 'en'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid language']);
        }

        // Set the locale in the session
        session()->set('locale', $language);

        // Set the locale for the current request
        service('request')->setLocale($language);

        // Force reload the language service with new locale
        $languageService = \Config\Services::language();
        $languageService->setLocale($language);

        // Force reload by accessing a line from the Dashboard language file
        $languageService->getLine('Dashboard.dashboard_title');

        return $this->response->setJSON(['success' => true, 'message' => 'Language set successfully']);
    }

    private function convertDataToUSD(&$data, $usdRate)
    {
        // Convert total investments
        if (isset($data['total_investment']['PMA'])) {
            $data['total_investment']['PMA'] = round($data['total_investment']['PMA'] / $usdRate, 2);
        }
        if (isset($data['total_investment']['PMDN'])) {
            $data['total_investment']['PMDN'] = round($data['total_investment']['PMDN'] / $usdRate, 2);
        }

        // Convert additional investments
        if (isset($data['total_additional_investment']['PMA'])) {
            $data['total_additional_investment']['PMA'] = round($data['total_additional_investment']['PMA'] / $usdRate, 2);
        }
        if (isset($data['total_additional_investment']['PMDN'])) {
            $data['total_additional_investment']['PMDN'] = round($data['total_additional_investment']['PMDN'] / $usdRate, 2);
        }

        // Convert additional investment by district
        if (isset($data['additional_investment_by_district']['PMA'])) {
            foreach ($data['additional_investment_by_district']['PMA'] as $district => &$amount) {
                $amount = round($amount / $usdRate, 2);
            }
        }
        if (isset($data['additional_investment_by_district']['PMDN'])) {
            foreach ($data['additional_investment_by_district']['PMDN'] as $district => &$amount) {
                $amount = round($amount / $usdRate, 2);
            }
        }

        // Convert investment by location
        if (isset($data['investment_by_location'])) {
            foreach ($data['investment_by_location'] as $location => &$amount) {
                $amount = round($amount / $usdRate, 2);
            }
        }

        // Convert realization investment
        if (isset($data['realization_investment']['PMA'])) {
            $data['realization_investment']['PMA'] = round($data['realization_investment']['PMA'] / $usdRate, 2);
        }
        if (isset($data['realization_investment']['PMDN'])) {
            $data['realization_investment']['PMDN'] = round($data['realization_investment']['PMDN'] / $usdRate, 2);
        }

        // Update additional investment percentages with converted amounts
        if (isset($data['additional_investment_percentages']['PMA'])) {
            foreach ($data['additional_investment_percentages']['PMA'] as $district => &$info) {
                $info['amount'] = round($info['amount'] / $usdRate, 2);
            }
        }
        if (isset($data['additional_investment_percentages']['PMDN'])) {
            foreach ($data['additional_investment_percentages']['PMDN'] as $district => &$info) {
                $info['amount'] = round($info['amount'] / $usdRate, 2);
            }
        }
    }
}
