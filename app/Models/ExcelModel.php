<?php

namespace App\Models;

use CodeIgniter\Model;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelModel extends Model
{
    protected $table = ''; // Tidak menggunakan database table
    protected $primaryKey = 'id';
    protected $allowedFields = [];

    // Template kolom yang diharapkan (sama untuk PMA dan PMDN)
    private $expectedColumns = [
        "No.",
        "ID Laporan",
        "ID Proyek",
        "Periode Tahap",
        "Sektor Utama",
        "23 Sektor",
        "Jenis Badan Usaha",
        "Nama Perusahaan",
        "",
        "Email",
        "Alamat",
        "Cetak Lokasi",
        "Sektor",
        "Deskripsi KBLI",
        "Wilayah",
        "Provinsi",
        "Kabkot",
        "No Izin",
        "Tambahan Investasi",
        "Total Investasi",
        "Negara",
        "Rencana Total Investasi",
        "Proyek",
        "TKI",
        "TKA",
        "Nama Petugas",
        "Rencana Modal Tetap",
        "Keterangan Masalah",
        "Penjelasan Modal Tetap",
        "No Telp",
        "PMA/PMDN",
        "Kecamatan"
    ];

    /**
     * Validasi kolom Excel
     */
    public function validateColumns($filePath)
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheetNames = $spreadsheet->getSheetNames();

        if (!in_array('PMA', $sheetNames) || !in_array('PMDN', $sheetNames)) {
            return ['valid' => false, 'missing' => ['Sheet PMA dan PMDN harus ada']];
        }

        $missingColumns = [];

        foreach (['PMA', 'PMDN'] as $sheetName) {
            $sheet = $spreadsheet->getSheetByName($sheetName);
            $highestColumn = $sheet->getHighestColumn();
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

            $actualColumns = [];
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $cellValue = $sheet->getCell(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '1')->getValue() ?? '';
                $actualColumns[] = $cellValue;
            }

            $expectedColumns = $this->expectedColumns;
            $missing = array_diff($expectedColumns, $actualColumns);
            $extra = array_diff($actualColumns, $expectedColumns);
            if (!empty($missing) || !empty($extra)) {
                $missingColumns = array_merge($missingColumns, $missing, $extra);
            }
        }

        return ['valid' => empty($missingColumns), 'missing' => array_unique($missingColumns)];
    }

    /**
     * Proses data dari Excel
     */
    public function processData($filePath)
    {
        $spreadsheet = IOFactory::load($filePath);
        $data = ['PMA' => [], 'PMDN' => []];

        foreach (['PMA', 'PMDN'] as $sheetName) {
            $sheet = $spreadsheet->getSheetByName($sheetName);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

            // Get actual columns from header row
            $actualColumns = [];
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $cellValue = $sheet->getCell(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col) . '1')->getValue() ?? '';
                $actualColumns[] = $cellValue;
            }

            // Create map from column name to index (0-based)
            $columnMap = array_flip($actualColumns);

            $expectedColumns = $this->expectedColumns;
            for ($row = 2; $row <= $highestRow; $row++) {
                $rowData = [];
                foreach ($expectedColumns as $colName) {
                    $index = $columnMap[$colName] ?? null;
                    if ($index !== null) {
                        $cellValue = $sheet->getCell(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1) . $row)->getValue();
                        $rowData[] = $cellValue;
                    } else {
                        $rowData[] = null; // If column missing, set to null
                    }
                }
                $data[$sheetName][] = array_combine($expectedColumns, $rowData);
            }
        }

        // Gabungkan data untuk analisis
        $allData = array_merge($data['PMA'], $data['PMDN']);

        return [
            'raw' => $allData,
            'total_projects' => [
                'PMA' => count($data['PMA']),
                'PMDN' => count($data['PMDN'])
            ],
            'total_investment' => $this->calculateTotalInvestment($allData),
            'projects_by_district' => $this->calculateProjectsByDistrict($allData),
            'investment_by_location' => $this->calculateInvestmentByLocation($allData),
            'sector_analysis' => $this->analyzeSectors($allData),
            'workforce' => $this->analyzeWorkforce($allData),
            'projects_by_country' => $this->calculateProjectsByCountry($allData),
            'additional_investment' => $this->analyzeAdditionalInvestment($allData),
            'realization_investment' => $this->analyzeRealizationInvestment($allData),
            'quarterly_results' => $this->analyzeQuarterlyResults($allData)
        ];
    }

    private function calculateTotalInvestment($data)
    {
        $totalPMA = 0;
        $totalPMDN = 0;

        foreach ($data as $row) {
            $investment = (float) str_replace(['Rp', '.', ','], '', $row['Total Investasi'] ?? 0);
            if ($row['PMA/PMDN'] === 'PMA') {
                $totalPMA += $investment;
            } else {
                $totalPMDN += $investment;
            }
        }

        return ['PMA' => $totalPMA, 'PMDN' => $totalPMDN];
    }

    private function calculateProjectsByDistrict($data)
    {
        $districts = [];

        foreach ($data as $row) {
            $district = $row['Kecamatan'] ?? 'Unknown';
            $type = $row['PMA/PMDN'] === 'PMA' ? 'PMA' : 'PMDN';
            if (!isset($districts[$type])) {
                $districts[$type] = [];
            }
            if (!isset($districts[$type][$district])) {
                $districts[$type][$district] = 0;
            }
            $districts[$type][$district]++;
        }

        return $districts;
    }

    private function calculateInvestmentByLocation($data)
    {
        $locations = [];

        foreach ($data as $row) {
            $location = $row['Kecamatan'] ?? 'Unknown';
            $investment = (float) str_replace(['Rp', '.', ','], '', $row['Total Investasi'] ?? 0);
            if (!isset($locations[$location])) {
                $locations[$location] = 0;
            }
            $locations[$location] += $investment;
        }

        return $locations;
    }

    private function analyzeSectors($data)
    {
        $sectors = [];

        foreach ($data as $row) {
            $sector = $row['Sektor'] ?? 'Unknown';
            if (!isset($sectors[$sector])) {
                $sectors[$sector] = 0;
            }
            $sectors[$sector]++;
        }

        $total = count($data);
        $analysis = [];
        foreach ($sectors as $sector => $count) {
            $analysis[] = [
                'sector' => $sector,
                'count' => $count,
                'percentage' => round(($count / $total) * 100, 2)
            ];
        }

        return $analysis;
    }

    private function analyzeWorkforce($data)
    {
        $workforce = ['PMA' => ['TKI' => 0, 'TKA' => 0], 'PMDN' => ['TKI' => 0, 'TKA' => 0]];

        foreach ($data as $row) {
            $type = $row['PMA/PMDN'] === 'PMA' ? 'PMA' : 'PMDN';
            $tki = (int) ($row['TKI'] ?? 0);
            $tka = (int) ($row['TKA'] ?? 0);
            $workforce[$type]['TKI'] += $tki;
            $workforce[$type]['TKA'] += $tka;
        }

        return $workforce;
    }

    private function calculateProjectsByCountry($data)
    {
        $countries = [];

        foreach ($data as $row) {
            $country = $row['Negara'] ?? 'Unknown';
            if (!isset($countries[$country])) {
                $countries[$country] = 0;
            }
            $countries[$country]++;
        }

        return $countries;
    }

    private function analyzeAdditionalInvestment($data)
    {
        $additional = [];

        foreach ($data as $row) {
            $addInv = $row['Tambahan Investasi'] ?? 'Unknown';
            if (!isset($additional[$addInv])) {
                $additional[$addInv] = 0;
            }
            $additional[$addInv]++;
        }

        return $additional;
    }

    private function analyzeRealizationInvestment($data)
    {
        $realization = ['PMA' => 0, 'PMDN' => 0];

        foreach ($data as $row) {
            $planned = (float) str_replace(['Rp', '.', ','], '', $row['Rencana Total Investasi'] ?? 0);
            $actual = (float) str_replace(['Rp', '.', ','], '', $row['Total Investasi'] ?? 0);
            $type = $row['PMA/PMDN'] === 'PMA' ? 'PMA' : 'PMDN';
            $realization[$type] += $actual - $planned;
        }

        return $realization;
    }

    private function analyzeQuarterlyResults($data)
    {
        $quarters = [];

        foreach ($data as $row) {
            $period = $row['Periode Tahap'] ?? 'Unknown';
            if (!isset($quarters[$period])) {
                $quarters[$period] = 0;
            }
            $quarters[$period]++;
        }

        return $quarters;
    }
}
