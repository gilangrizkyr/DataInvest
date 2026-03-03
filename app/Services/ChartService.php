<?php

namespace App\Services;

use App\Models\ProjectModel;

class ChartService
{
    protected $projectModel;
    protected $quarterlyChartService;

    public function __construct()
    {
        $this->projectModel = new ProjectModel();
        $this->quarterlyChartService = new QuarterlyChartService();
    }

    public function generateAllCharts(int $uploadId, array $filterConditions, float $usdRate, array $filters): array
    {
        $projectsByDistrict = $this->projectModel->getProjectsByDistrict($uploadId, $filterConditions);
        $investmentByLocation = $this->projectModel->getInvestmentByDistrict($uploadId, $filterConditions);
        $sectorAnalysis = $this->projectModel->getSectorAnalysis($uploadId);
        $projectsByCountry = $this->projectModel->getProjectsByCountry($uploadId, $filterConditions);
        $workforceByDistrict = $this->projectModel->getWorkforceByDistrict($uploadId, $filterConditions);

        return [
            'district' => $this->generateDistrictChart($projectsByDistrict),
            'locations' => $this->generateLocationChart($investmentByLocation),
            'sectors' => $this->generateSectorChart($sectorAnalysis),
            'countries' => $this->generateCountryChart($projectsByCountry),
            'workforce' => $this->generateWorkforceChart($workforceByDistrict),
            'quarterly_additional_investment' => $this->quarterlyChartService->generate(
                'all',
                $usdRate,
                $filters['currency']
            ),
            'quarterly_additional_investment_all_years' => $this->quarterlyChartService->generateAllYears(
                $usdRate,
                $filters['currency']
            )
        ];
    }

    public function generateDistrictChart(array $districts): array
    {
        $allDistricts = array_unique(array_merge(
            array_keys($districts['PMA'] ?? []),
            array_keys($districts['PMDN'] ?? [])
        ));

        $districtTotals = [];
        foreach ($allDistricts as $district) {
            $total = ($districts['PMA'][$district] ?? 0) + ($districts['PMDN'][$district] ?? 0);
            $districtTotals[$district] = [
                'district' => $district,
                'pma' => $districts['PMA'][$district] ?? 0,
                'pmdn' => $districts['PMDN'][$district] ?? 0,
                'total' => $total
            ];
        }

        // Sort by total descending (highest to lowest)
        uasort($districtTotals, function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        $labels = [];
        $pma = [];
        $pmdn = [];

        foreach ($districtTotals as $data) {
            $labels[] = $data['district'];
            $pma[] = $data['pma'];
            $pmdn[] = $data['pmdn'];
        }

        return compact('labels', 'pma', 'pmdn');
    }

    /**
     * Generate chart data for PMA projects only, sorted by PMA value descending
     */
    public function generateProjectsPmaChart(array $districts): array
    {
        // Sort PMA values descending
        $pmaData = $districts['PMA'] ?? [];
        arsort($pmaData);

        return [
            'labels' => array_keys($pmaData),
            'values' => array_values($pmaData)
        ];
    }

    /**
     * Generate chart data for PMDN projects only, sorted by PMDN value descending
     */
    public function generateProjectsPmdnChart(array $districts): array
    {
        // Sort PMDN values descending
        $pmdnData = $districts['PMDN'] ?? [];
        arsort($pmdnData);

        return [
            'labels' => array_keys($pmdnData),
            'values' => array_values($pmdnData)
        ];
    }

    public function generateLocationChart(array $locations): array
    {
        arsort($locations);
        $top10 = array_slice($locations, 0, 10, true);

        return [
            'labels' => array_keys($top10),
            'values' => array_values($top10)
        ];
    }

    public function generateSectorChart(array $sectors): array
    {
        $labels = [];
        $counts = [];

        foreach ($sectors as $sector) {
            $labels[] = $sector['sector'];
            $counts[] = $sector['count'];
        }

        return compact('labels', 'counts');
    }

    public function generateCountryChart(array $countries): array
    {
        $labels = [];
        $counts = [];

        foreach ($countries as $country => $count) {
            $labels[] = $country ?: 'Tidak Diketahui';
            $counts[] = $count;
        }

        return compact('labels', 'counts');
    }

    public function generateWorkforceChart(array $workforce): array
    {
        $allDistricts = array_unique(array_merge(
            array_keys($workforce['PMA'] ?? []),
            array_keys($workforce['PMDN'] ?? [])
        ));

        $districtTotals = [];
        foreach ($allDistricts as $district) {
            $tki = ($workforce['PMA'][$district]['TKI'] ?? 0) + ($workforce['PMDN'][$district]['TKI'] ?? 0);
            $tka = ($workforce['PMA'][$district]['TKA'] ?? 0) + ($workforce['PMDN'][$district]['TKA'] ?? 0);
            $total = $tki + $tka;

            $districtTotals[$district] = [
                'district' => $district,
                'tki' => $tki,
                'tka' => $tka,
                'total' => $total
            ];
        }

        // Sort by total workforce descending
        uasort($districtTotals, function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        $labels = [];
        $tki = [];
        $tka = [];

        foreach ($districtTotals as $data) {
            $labels[] = $data['district'];
            $tki[] = $data['tki'];
            $tka[] = $data['tka'];
        }

        return compact('labels', 'tki', 'tka');
    }

    public function getEmptyCharts(): array
    {
        return [
            'district' => ['labels' => [], 'pma' => [], 'pmdn' => []],
            'locations' => ['labels' => [], 'values' => []],
            'sectors' => ['labels' => [], 'counts' => []],
            'countries' => ['labels' => [], 'counts' => []],
            'workforce' => ['labels' => [], 'tki' => [], 'tka' => []],
            'quarterly_additional_investment' => [
                'labels' => ['Q1', 'Q2', 'Q3', 'Q4'],
                'values' => [0, 0, 0, 0],
                'year' => 'Semua Tahun'
            ],
            'quarterly_additional_investment_all_years' => []
        ];
    }
}