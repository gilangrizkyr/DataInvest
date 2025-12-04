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

    /**
     * Validasi kolom Excel - hanya cek kolom yang wajib ada
     */
    public function validateColumns($filePath)
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheetNames = $spreadsheet->getSheetNames();

        if (!in_array('PMA', $sheetNames) || !in_array('PMDN', $sheetNames)) {
            return ['valid' => false, 'missing' => ['Sheet PMA dan PMDN harus ada']];
        }

        // Kolom yang wajib ada (minimal untuk proses data)
        $requiredColumns = [
            "nama perusahaan",
            "provinsi",
            "kabkot",
            "kecamatan",
            "pma/pmdn"
        ];

        $missingColumns = [];

        foreach (['PMA', 'PMDN'] as $sheetName) {
            $sheet = $spreadsheet->getSheetByName($sheetName);
            $highestColumn = $sheet->getHighestColumn();
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

            $actualColumns = [];
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $cellValue = $sheet->getCell(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '1')->getValue() ?? '';
                $actualColumns[] = strtolower(trim($cellValue));
            }

            $requiredColumnsNormalized = array_map(function ($col) {
                return strtolower(trim($col));
            }, $requiredColumns);

            $missing = array_diff($requiredColumnsNormalized, $actualColumns);
            if (!empty($missing)) {
                $missingColumns = array_merge($missingColumns, $missing);
            }
        }

        return ['valid' => empty($missingColumns), 'missing' => array_unique($missingColumns)];
    }

    /**
     * Proses data dari Excel dan simpan ke database
     */
    public function processData($filePath, $uploadId)
    {
        $spreadsheet = IOFactory::load($filePath);
        $totalRecords = 0;

        foreach (['PMA', 'PMDN'] as $sheetName) {
            $sheet = $spreadsheet->getSheetByName($sheetName);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

            // Get actual columns from header row
            $actualColumns = [];
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $cellValue = $sheet->getCell(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '1')->getValue();
                $actualColumns[] = (string) $cellValue;
            }

            // Create map from column name to index (0-based) - case insensitive
            $columnMap = [];
            foreach ($actualColumns as $index => $colName) {
                $columnMap[strtolower(trim($colName))] = $index;
            }

            $expectedColumns = $this->expectedColumns;
            $projectsData = [];

            for ($row = 2; $row <= $highestRow; $row++) {
                $rowData = [];
                foreach ($expectedColumns as $colName) {
                    $index = $columnMap[strtolower(trim($colName))] ?? null;
                    if ($index !== null) {
                        $cellValue = $sheet->getCell(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1) . $row)->getValue();
                        $rowData[] = $cellValue;
                    } else {
                        $rowData[] = null;
                    }
                }
                $rowData = array_combine($expectedColumns, $rowData);

                // Normalize PMA/PMDN to uppercase
                $investmentType = strtoupper(trim($rowData['PMA/PMDN'] ?? ''));

                // Skip projects without subdistrict name
                $subdistrict = trim($rowData['Kecamatan'] ?? '');
                if (empty($subdistrict)) {
                    continue; // Skip this project
                }

                // Prepare project data for database
                $projectData = [
                    'upload_id' => $uploadId,
                    'report_id' => $rowData['ID Laporan'] ?? null,
                    'project_id' => $rowData['ID Proyek'] ?? null,
                    'project_name' => $rowData['Nama Perusahaan'] ?? null,
                    'investment_type' => $investmentType,
                    'period_stage' => $rowData['Periode Tahap'] ?? null,
                    'main_sector' => $rowData['Sektor Utama'] ?? null,
                    'sector_23' => $rowData['23 Sektor'] ?? null,
                    'business_type' => $rowData['Jenis Badan Usaha'] ?? null,
                    'company_name' => $rowData['Nama Perusahaan'] ?? null,
                    'email' => $rowData['Email'] ?? null,
                    'address' => $rowData['Alamat'] ?? null,
                    'location_print' => $rowData['Cetak Lokasi'] ?? null,
                    'sector_detail' => $rowData['Sektor'] ?? null,
                    'kbli_description' => $rowData['Deskripsi KBLI'] ?? null,
                    'province' => $rowData['Provinsi'] ?? null,
                    'district' => $rowData['Kabkot'] ?? null,
                    'subdistrict' => $subdistrict,
                    'license_number' => $rowData['No Izin'] ?? null,
                    'additional_investment' => $this->cleanNumericValue($rowData['Tambahan Investasi'] ?? 0),
                    'total_investment' => $this->cleanNumericValue($rowData['Total Investasi'] ?? 0),
                    'planned_total_investment' => $this->cleanNumericValue($rowData['Rencana Total Investasi'] ?? 0),
                    'fixed_capital_planned' => $this->cleanNumericValue($rowData['Rencana Modal Tetap'] ?? 0),
                    'tki' => (int) ($rowData['TKI'] ?? 0),
                    'tka' => (int) ($rowData['TKA'] ?? 0),
                    'officer_name' => $rowData['Nama Petugas'] ?? null,
                    'problem_description' => $rowData['Keterangan Masalah'] ?? null,
                    'fixed_capital_explanation' => $rowData['Penjelasan Modal Tetap'] ?? null,
                    'phone_number' => $rowData['No Telp'] ?? null,
                    'country' => $rowData['Negara'] ?? null
                ];

                $projectsData[] = $projectData;
                $totalRecords++;
            }

            // Insert projects in batches
            if (!empty($projectsData)) {
                $this->projectModel->insertBatch($projectsData);
            }
        }

        // Update upload record with total records
        $this->uploadModel->updateStatus($uploadId, 'completed', [
            'total_records' => $totalRecords,
            'processed_records' => $totalRecords
        ]);

        // Calculate statistics using stored procedures
        $this->calculateStatistics($uploadId);

        return $totalRecords;
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
     * Calculate statistics using stored procedures
     */
    private function calculateStatistics($uploadId)
    {
        // Call stored procedures to calculate statistics
        $this->db->query("CALL calculate_upload_statistics(?)", [$uploadId]);
        $this->db->query("CALL calculate_district_statistics(?)", [$uploadId]);
        $this->db->query("CALL calculate_sector_statistics(?)", [$uploadId]);
    }
}
