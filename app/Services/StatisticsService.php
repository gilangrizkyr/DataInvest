<?php

namespace App\Services;

use App\Models\ProjectModel;

class StatisticsService
{
    protected $projectModel;

    public function __construct()
    {
        $this->projectModel = new ProjectModel();
    }

    public function calculate(int $uploadId, array $filterConditions, float $usdRate): array
    {
        $totalProjects = $this->projectModel->getTotalProjects($uploadId, $filterConditions);
        $totalInvestment = $this->projectModel->getTotalInvestment($uploadId, $filterConditions);
        $totalAdditionalInvestment = $this->projectModel->getAdditionalInvestment($uploadId, $filterConditions);

        // Tambahan: sector count per company
        $sectorCountPMA = $this->projectModel->getSectorCountByCompany(array_merge($filterConditions, ['jenis_investasi' => 'PMA']));
        $sectorCountPMDN = $this->projectModel->getSectorCountByCompany(array_merge($filterConditions, ['jenis_investasi' => 'PMDN']));

        return [
            'total_projects' => $totalProjects,
            'total_investment' => $totalInvestment,
            'total_additional_investment' => $totalAdditionalInvestment,
            'total_investment_usd' => $this->calculateUSDInvestment($totalInvestment, $usdRate),
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
            // Tambahan untuk controller LKPM
            'sector_count_by_company' => [
                'PMA' => [
                    'data' => $sectorCountPMA,
                    'total' => array_sum(array_column($sectorCountPMA, 'tambahan_realisasi')),
                ],
                'PMDN' => [
                    'data' => $sectorCountPMDN,
                    'total' => array_sum(array_column($sectorCountPMDN, 'tambahan_realisasi')),
                ],
            ],
        ];
    }


    public function calculateAdditionalInvestmentPercentages(array $additionalInvestmentByDistrict): array
    {
        $totalAdditionalPMA = array_sum($additionalInvestmentByDistrict['PMA'] ?? []);
        $totalAdditionalPMDN = array_sum($additionalInvestmentByDistrict['PMDN'] ?? []);

        $percentages = [
            'PMA' => [],
            'PMDN' => []
        ];

        foreach ($additionalInvestmentByDistrict['PMA'] ?? [] as $district => $amount) {
            $percentage = $totalAdditionalPMA > 0 ? round(($amount / $totalAdditionalPMA) * 100, 1) : 0;
            $percentages['PMA'][$district] = [
                'percentage' => $percentage,
                'amount' => $amount
            ];
        }

        foreach ($additionalInvestmentByDistrict['PMDN'] ?? [] as $district => $amount) {
            $percentage = $totalAdditionalPMDN > 0 ? round(($amount / $totalAdditionalPMDN) * 100, 1) : 0;
            $percentages['PMDN'][$district] = [
                'percentage' => $percentage,
                'amount' => $amount
            ];
        }

        return $percentages;
    }

    private function calculateUSDInvestment(array $totalInvestment, float $usdRate): array
    {
        return [
            'PMA' => !empty($totalInvestment) ? round(($totalInvestment['PMA'] ?? 0) / $usdRate, 2) : 0,
            'PMDN' => !empty($totalInvestment) ? round(($totalInvestment['PMDN'] ?? 0) / $usdRate, 2) : 0
        ];
    }
}
