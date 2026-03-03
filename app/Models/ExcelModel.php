<?php

namespace App\Models;

use CodeIgniter\Model;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelModel extends Model
{
    protected $table = ''; // Tidak menggunakan database table
    protected $primaryKey = 'id';
    protected $allowedFields = [];

    protected $projectModel;
    protected $uploadModel;

    public function __construct()
    {
        parent::__construct();
        $this->projectModel = new \App\Models\ProjectModel();
        $this->uploadModel = new \App\Models\UploadModel();
    }

    // Template kolom yang diharapkan (sama untuk PMA dan PMDN)
    private $expectedColumns = [
        "ID Laporan",
        "ID Proyek",
        "Periode Tahap",
        "Sektor Utama",
        "23 Sektor",
        "Jenis Badan Usaha",
        "Nama Perusahaan",
        "Kecamatan",
        "Email",
        "Alamat",
        "Cetak Lokasi",
        "Sektor",
        "Deskripsi KBLI",
        "Provinsi",
        "Kabkot",
        "No Izin",
        "Tambahan Investasi",
        "Total Investasi",
        "Negara",
        "Rencana Total Investasi",
        "TKI",
        "TKA",
        "Nama Petugas",
        "Rencana Modal Tetap",
        "Keterangan Masalah",
        "Penjelasan Modal Tetap",
        "No Telp",
        "PMA/PMDN"
    ];

    // Mapping kolom alternatif untuk file yang tidak lengkap
    private $alternativeColumnMappings = [
        'kecamatan' => ['wilayah', 'district', 'sub district', 'kec'],
        'subdistrict' => ['wilayah', 'district', 'sub district', 'kec'],
        'nama perusahaan' => ['nama', 'company', 'company name', 'perusahaan'],
        'provinsi' => ['province', 'propinsi'],
        'kabkot' => ['kabupaten', 'kota', 'district', 'kab'],
        'total investasi' => ['total investment', 'nilai investasi', 'investasi total'],
        'tambahan investasi' => ['additional investment', 'nilai tambah investasi'],
        'negara' => ['country', 'negara asal'],
    ];

    /**
     * Getter untuk expected columns (untuk digunakan di service)
     */
    public function getExpectedColumns()
    {
        return $this->expectedColumns;
    }

    /**
     * Validasi kolom Excel - sekarang sangat fleksibel, tidak ada kolom wajib
     * Sistem akan berjalan dengan kolom apa saja yang tersedia
     */
    public function validateColumns($filePath)
    {
        log_message('info', 'validateColumns: Loading file ' . $filePath);
        $readerTime = microtime(true);
        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);

        // Optimasi: Hanya baca baris pertama (header) untuk validasi kolom
        $chunkFilter = new class implements \PhpOffice\PhpSpreadsheet\Reader\IReadFilter {
            public function readCell(string $columnAddress, int $row, string $worksheetName = ''): bool
            {
                return $row == 1;
            }
        };
        $reader->setReadFilter($chunkFilter);

        log_message('info', 'Reader initialized in ' . round(microtime(true) - $readerTime, 4) . 's');

        $loadTime = microtime(true);
        $spreadsheet = $reader->load($filePath);
        log_message('info', 'Spreadsheet loaded in ' . round(microtime(true) - $loadTime, 4) . 's');

        $sheetNames = $spreadsheet->getSheetNames();

        // Cek apakah ada minimal satu sheet
        if (empty($sheetNames)) {
            return [
                'valid' => false,
                'missing' => ['File Excel harus memiliki minimal satu sheet'],
                'warnings' => [],
                'canProceed' => false
            ];
        }

        // Jika ada sheet PMA dan PMDN, gunakan itu. Jika tidak, gunakan sheet pertama
        $sheetsToCheck = [];
        $sheetNamesLower = array_map('strtolower', $sheetNames);

        if (in_array('pma', $sheetNamesLower) && in_array('pmdn', $sheetNamesLower)) {
            $pmaIndex = array_search('pma', $sheetNamesLower);
            $pmdnIndex = array_search('pmdn', $sheetNamesLower);
            $sheetsToCheck = [$sheetNames[$pmaIndex], $sheetNames[$pmdnIndex]];
        } else {
            // Gunakan semua sheet yang ada
            $sheetsToCheck = $sheetNames;
        }

        $allMissingColumns = [];

        foreach ($sheetsToCheck as $sheetName) {
            $sheet = $spreadsheet->getSheetByName($sheetName);
            if (!$sheet)
                continue;

            $highestColumn = $sheet->getHighestColumn();
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

            $actualColumns = [];
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $cellValue = $sheet->getCell(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '1')->getValue() ?? '';
                // Bersihkan nama kolom: lowercase, hapus spasi berlebih
                $cleanValue = strtolower(trim($cellValue));
                $cleanValue = preg_replace('/\s+/', ' ', $cleanValue);
                $actualColumns[] = $cleanValue;
            }

            // Catat kolom yang tidak ada dari template lengkap
            $expectedNormalized = array_map(function ($col) {
                $clean = strtolower(trim($col));
                return preg_replace('/\s+/', ' ', $clean);
            }, $this->expectedColumns);
            $allMissing = array_diff($expectedNormalized, $actualColumns);
            $allMissingColumns = array_merge($allMissingColumns, $allMissing);
        }

        $hasAllColumns = empty($allMissingColumns);

        return [
            'valid' => true, // Selalu valid, tidak ada kolom wajib
            'missing' => [], // Tidak ada missing required columns
            'allMissing' => array_unique($allMissingColumns),
            'warnings' => $hasAllColumns ? [] : ['Beberapa kolom tidak ada dalam file Excel. Data akan diproses dengan field yang tersedia.'],
            'canProceed' => true, // Selalu bisa proceed
            'message' => 'File Excel valid. Sistem akan memproses data dengan kolom yang tersedia.',
            'sheetsToProcess' => $sheetsToCheck
        ];
    }

    /**
     * Cari kolom alternatif jika kolom utama tidak ada
     */
    private function findAlternativeColumn($columnName, $columnMap)
    {
        // Cek kolom utama dulu
        if (isset($columnMap[$columnName])) {
            return $columnMap[$columnName];
        }

        // Cek alternatif
        $altColumns = $this->alternativeColumnMappings[$columnName] ?? [];
        foreach ($altColumns as $alt) {
            if (isset($columnMap[$alt])) {
                return $columnMap[$alt];
            }
        }

        return null;
    }

    /**
     * Proses data dari Excel dan simpan ke database
     * Method ini sekarang SANGAT FLEKSIBEL - akan memproses data dengan kolom apa saja yang tersedia
     */
    public function processData($filePath, $uploadId)
    {
        try {
            log_message('info', '=== processData START ===');
            log_message('info', 'File: ' . $filePath . ', Upload ID: ' . $uploadId);

            // Tambah memory limit secara dinamis jika diperlukan
            $currentMemory = memory_get_usage(true);
            log_message('info', 'Current memory usage: ' . round($currentMemory / 1024 / 1024, 2) . ' MB');

            // Validasi file exists
            if (!file_exists($filePath)) {
                log_message('error', 'File tidak ditemukan: ' . $filePath);
                throw new \Exception('File Excel tidak ditemukan. Silakan upload ulang file.');
            }

            // Optimasi: Gunakan reader dengan setReadDataOnly(true)
            $reader = IOFactory::createReaderForFile($filePath);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($filePath);

            $sheetNames = $spreadsheet->getSheetNames();
            $totalRecords = 0;
            $missingColumnsReport = [];
            $processedSheets = [];

            if (empty($sheetNames)) {
                log_message('error', 'File Excel tidak memiliki sheet');
                throw new \Exception('File Excel tidak memiliki sheet. Silakan periksa file Anda.');
            }

            // Tentukan sheet yang akan diproses - case insensitive
            $sheetsToProcess = [];
            $sheetNamesLower = array_map('strtolower', $sheetNames);

            if (in_array('pma', $sheetNamesLower) && in_array('pmdn', $sheetNamesLower)) {
                $pmaIndex = array_search('pma', $sheetNamesLower);
                $pmdnIndex = array_search('pmdn', $sheetNamesLower);
                $sheetsToProcess = [$sheetNames[$pmaIndex], $sheetNames[$pmdnIndex]];
            } else {
                $sheetsToProcess = $sheetNames;
            }

            log_message('debug', "Sheets to process: " . json_encode($sheetsToProcess));

            foreach ($sheetsToProcess as $sheetName) {
                $sheet = $spreadsheet->getSheetByName($sheetName);
                if (!$sheet)
                    continue;

                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                if ($highestRow < 2)
                    continue;

                $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

                // Optimasi: Gunakan rangeToArray untuk mengambil seluruh data sheet sekaligus
                // Ini jauh lebih cepat daripada memanggil getCell() di dalam loop
                $dataArray = $sheet->rangeToArray(
                    'A1:' . $highestColumn . $highestRow,
                    null,
                    true,
                    false,
                    false
                );

                $headers = $dataArray[0];
                $columnMap = [];
                foreach ($headers as $index => $colName) {
                    $cleanColName = strtolower(trim($colName));
                    $cleanColName = preg_replace('/\s+/', ' ', $cleanColName);
                    $columnMap[$cleanColName] = $index;
                }

                // Identifikasi kolom (lanjutkan logika yang sudah ada namun menggunakan dataArray)
                $expectedColumns = $this->expectedColumns;
                $projectsData = [];
                $rowsProcessed = 0;

                for ($i = 1; $i < count($dataArray); $i++) {
                    $row = $dataArray[$i];

                    // Skip empty rows (cek kolom pertama)
                    if (empty($row[0]) && count($row) <= 1)
                        continue;

                    $mappedRow = [];
                    foreach ($expectedColumns as $colName) {
                        $cleanColName = strtolower(trim($colName));
                        $cleanColName = preg_replace('/\s+/', ' ', $cleanColName);

                        $colIndex = $this->findAlternativeColumn($cleanColName, $columnMap);
                        $mappedRow[$colName] = ($colIndex !== null) ? $row[$colIndex] : null;
                    }

                    $subdistrict = trim($mappedRow['Kecamatan'] ?? '');
                    if (empty($subdistrict))
                        continue;

                    $investmentType = strtoupper(trim($mappedRow['PMA/PMDN'] ?? ''));
                    if (empty($investmentType)) {
                        $sheetNameUpper = strtoupper(trim($sheetName));
                        $investmentType = ($sheetNameUpper === 'PMDN') ? 'PMDN' : 'PMA';
                    }

                    $projectsData[] = [
                        'upload_id' => $uploadId,
                        'report_id' => $mappedRow['ID Laporan'] ?? '[Tidak Ada]',
                        'project_id' => $mappedRow['ID Proyek'] ?? '[Tidak Ada]',
                        'project_name' => $mappedRow['Nama Perusahaan'] ?? '[Tidak Ada]',
                        'investment_type' => $investmentType,
                        'period_stage' => $mappedRow['Periode Tahap'] ?? '[Tidak Ada]',
                        'main_sector' => $mappedRow['Sektor Utama'] ?? '[Tidak Ada]',
                        'sector_23' => $mappedRow['23 Sektor'] ?? '[Tidak Ada]',
                        'business_type' => $mappedRow['Jenis Badan Usaha'] ?? '[Tidak Ada]',
                        'company_name' => $mappedRow['Nama Perusahaan'] ?? '[Tidak Ada]',
                        'email' => $mappedRow['Email'] ?? '[Tidak Ada]',
                        'address' => $mappedRow['Alamat'] ?? '[Tidak Ada]',
                        'location_print' => $mappedRow['Cetak Lokasi'] ?? '[Tidak Ada]',
                        'sector_detail' => $mappedRow['Sektor'] ?? '[Tidak Ada]',
                        'kbli_description' => $mappedRow['Deskripsi KBLI'] ?? '[Tidak Ada]',
                        'province' => $mappedRow['Provinsi'] ?? '[Tidak Ada]',
                        'district' => $mappedRow['Kabkot'] ?? '[Tidak Ada]',
                        'subdistrict' => $subdistrict,
                        'additional_investment' => $this->cleanNumericValue($mappedRow['Tambahan Investasi'] ?? 0),
                        'total_investment' => $this->cleanNumericValue($mappedRow['Total Investasi'] ?? 0),
                        'planned_total_investment' => $this->cleanNumericValue($mappedRow['Rencana Total Investasi'] ?? 0),
                        'fixed_capital_planned' => $this->cleanNumericValue($mappedRow['Rencana Modal Tetap'] ?? 0),
                        'tki' => (int) ($mappedRow['TKI'] ?? 0),
                        'tka' => (int) ($mappedRow['TKA'] ?? 0),
                        'officer_name' => $mappedRow['Nama Petugas'] ?? '[Tidak Ada]',
                        'problem_description' => $mappedRow['Keterangan Masalah'] ?? '[Tidak Ada]',
                        'fixed_capital_explanation' => $mappedRow['Penjelasan Modal Tetap'] ?? '[Tidak Ada]',
                        'phone_number' => $mappedRow['No Telp'] ?? '[Tidak Ada]',
                        'country' => $mappedRow['Negara'] ?? '[Tidak Ada]'
                    ];

                    $rowsProcessed++;
                    $totalRecords++;

                    // Batch insert every 500 rows to save memory
                    if (count($projectsData) >= 500) {
                        $this->projectModel->insertBatch($projectsData);
                        $projectsData = [];
                    }
                }

                if (!empty($projectsData)) {
                    $this->projectModel->insertBatch($projectsData);
                }

                $processedSheets[$sheetName] = ['total' => $highestRow - 1, 'processed' => $rowsProcessed];
            }

            $additionalInfo = [
                'total_records' => $totalRecords,
                'processed_records' => $totalRecords,
                'processed_sheets' => json_encode($processedSheets)
            ];
            $this->uploadModel->updateStatus($uploadId, 'completed', $additionalInfo);
            $this->calculateStatistics($uploadId);

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            log_message('info', '=== processData END - Total: ' . $totalRecords . ' ===');
            return $totalRecords;

        } catch (\Exception $e) {
            log_message('error', 'Error in processData: ' . $e->getMessage());
            throw $e;
        }
    }


    /**
     * Clean numeric value from Excel (remove Rp, dots, commas)
     */
    private function cleanNumericValue($value)
    {
        if (is_null($value) || $value === '') {
            return 0;
        }

        $value = str_replace(['Rp', '.', ','], '', $value);
        return (float) $value;
    }

    /**
     * Calculate statistics - only use fallback calculations (no stored procedures)
     */
    private function calculateStatistics($uploadId)
    {
        log_message('info', '=== calculateStatistics START ===');
        log_message('info', 'Upload ID: ' . $uploadId);

        // Always use fallback calculations - skip stored procedures entirely
        log_message('info', 'Using fallback calculations for upload ID: ' . $uploadId);
        $this->fallbackCalculateStatistics($uploadId);

        log_message('info', '=== calculateStatistics END ===');
    }

    /**
     * Fallback calculation using direct SQL queries
     */
    private function fallbackCalculateStatistics($uploadId)
    {
        log_message('info', 'Performing fallback calculations for upload ID: ' . $uploadId);

        try {
            // Calculate upload statistics
            $stats = $this->db->table('projects')
                ->select("
                    COUNT(CASE WHEN investment_type = 'PMA' THEN 1 END) as total_projects_pma,
                    COUNT(CASE WHEN investment_type = 'PMDN' THEN 1 END) as total_projects_pmdn,
                    COALESCE(SUM(CASE WHEN investment_type = 'PMA' THEN total_investment ELSE 0 END), 0) as total_investment_pma,
                    COALESCE(SUM(CASE WHEN investment_type = 'PMDN' THEN total_investment ELSE 0 END), 0) as total_investment_pmdn,
                    COALESCE(SUM(CASE WHEN investment_type = 'PMA' THEN additional_investment ELSE 0 END), 0) as additional_investment_pma,
                    COALESCE(SUM(CASE WHEN investment_type = 'PMDN' THEN additional_investment ELSE 0 END), 0) as additional_investment_pmdn,
                    COALESCE(SUM(CASE WHEN investment_type = 'PMA' THEN tki ELSE 0 END), 0) as total_tki_pma,
                    COALESCE(SUM(CASE WHEN investment_type = 'PMDN' THEN tki ELSE 0 END), 0) as total_tki_pmdn,
                    COALESCE(SUM(CASE WHEN investment_type = 'PMA' THEN tka ELSE 0 END), 0) as total_tka_pma,
                    COALESCE(SUM(CASE WHEN investment_type = 'PMDN' THEN tka ELSE 0 END), 0) as total_tka_pmdn,
                    COALESCE(SUM(CASE WHEN investment_type = 'PMA' THEN (planned_total_investment - total_investment) ELSE 0 END), 0) as realization_investment_pma,
                    COALESCE(SUM(CASE WHEN investment_type = 'PMDN' THEN (planned_total_investment - total_investment) ELSE 0 END), 0) as realization_investment_pmdn
                ")
                ->where('upload_id', $uploadId)
                ->get()
                ->getRowArray();

            if ($stats) {
                // Delete existing stats
                $this->db->table('upload_statistics')->where('upload_id', $uploadId)->delete();

                // Insert new stats
                $stats['upload_id'] = $uploadId;
                $this->db->table('upload_statistics')->insert($stats);
                log_message('info', 'Fallback upload statistics calculated successfully');
            }

            // Calculate district statistics
            $districtStats = $this->db->table('projects')
                ->select("
                    upload_id,
                    subdistrict,
                    SUM(CASE WHEN investment_type = 'PMA' THEN 1 ELSE 0 END) as projects_pma,
                    SUM(CASE WHEN investment_type = 'PMDN' THEN 1 ELSE 0 END) as projects_pmdn,
                    SUM(CASE WHEN investment_type = 'PMA' THEN total_investment ELSE 0 END) as investment_pma,
                    SUM(CASE WHEN investment_type = 'PMDN' THEN total_investment ELSE 0 END) as investment_pmdn,
                    SUM(CASE WHEN investment_type = 'PMA' THEN additional_investment ELSE 0 END) as additional_investment_pma,
                    SUM(CASE WHEN investment_type = 'PMDN' THEN additional_investment ELSE 0 END) as additional_investment_pmdn,
                    SUM(CASE WHEN investment_type = 'PMA' THEN tki ELSE 0 END) as tki_pma,
                    SUM(CASE WHEN investment_type = 'PMDN' THEN tki ELSE 0 END) as tki_pmdn,
                    SUM(CASE WHEN investment_type = 'PMA' THEN tka ELSE 0 END) as tka_pma,
                    SUM(CASE WHEN investment_type = 'PMDN' THEN tka ELSE 0 END) as tka_pmdn
                ")
                ->where('upload_id', $uploadId)
                ->where('subdistrict IS NOT NULL')
                ->where('subdistrict !=', '')
                ->groupBy('subdistrict')
                ->get()
                ->getResultArray();

            if (!empty($districtStats)) {
                $this->db->table('district_statistics')->where('upload_id', $uploadId)->delete();
                foreach ($districtStats as $stat) {
                    $stat['upload_id'] = $uploadId;
                    $this->db->table('district_statistics')->insert($stat);
                }
                log_message('info', 'Fallback district statistics calculated for ' . count($districtStats) . ' districts');
            }

            // Calculate sector statistics
            $totalProjects = $this->db->table('projects')
                ->where('upload_id', $uploadId)
                ->where('sector_detail IS NOT NULL')
                ->where('sector_detail !=', '')
                ->countAllResults();

            if ($totalProjects > 0) {
                // Use binding to avoid SQL injection and with issues variable interpolation
                $sectorStats = $this->db->table('projects')
                    ->select("
                        upload_id,
                        sector_detail as sector,
                        COUNT(*) as project_count,
                        ROUND((COUNT(*) / " . $this->db->escape($totalProjects) . ") * 100, 2) as percentage
                    ")
                    ->where('upload_id', $uploadId)
                    ->where('sector_detail IS NOT NULL')
                    ->where('sector_detail !=', '')
                    ->groupBy('sector_detail')
                    ->orderBy('project_count', 'DESC')
                    ->get()
                    ->getResultArray();

                if (!empty($sectorStats)) {
                    $this->db->table('sector_statistics')->where('upload_id', $uploadId)->delete();
                    foreach ($sectorStats as $stat) {
                        $stat['upload_id'] = $uploadId;
                        $this->db->table('sector_statistics')->insert($stat);
                    }
                    log_message('info', 'Fallback sector statistics calculated for ' . count($sectorStats) . ' sectors');
                }
            }

            log_message('info', 'Fallback calculations completed successfully');

        } catch (\Exception $e) {
            log_message('error', 'Fallback calculation failed: ' . $e->getMessage());
            // Don't re-throw - just log the error and continue
            // The upload was still successful, just statistics couldn't be calculated
        }
    }
}

