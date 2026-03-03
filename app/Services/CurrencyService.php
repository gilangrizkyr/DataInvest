<?php

namespace App\Services;

class CurrencyService
{
    public function convertToUSD(array &$data, float $usdRate): void
    {
        // Convert total investments
        $this->convertInvestments($data['total_investment'], $usdRate);
        $this->convertInvestments($data['total_additional_investment'], $usdRate);

        // Convert additional investment by district
        if (isset($data['additional_investment_by_district'])) {
            $this->convertDistrictData($data['additional_investment_by_district'], $usdRate);
        }

        // Convert investment by location
        if (isset($data['investment_by_location'])) {
            $this->convertLocationData($data['investment_by_location'], $usdRate);
        }

        // Convert realization investment
        if (isset($data['realization_investment'])) {
            $this->convertInvestments($data['realization_investment'], $usdRate);
        }

        // Update additional investment percentages with converted amounts
        if (isset($data['additional_investment_percentages'])) {
            $this->convertPercentagesData($data['additional_investment_percentages'], $usdRate);
        }

        // Convert charts data
        if (isset($data['charts'])) {
            $this->convertChartsData($data['charts'], $usdRate);
        }
    }

    private function convertChartsData(array &$charts, float $usdRate): void
    {
        // Convert locations chart
        if (isset($charts['locations']['values'])) {
            foreach ($charts['locations']['values'] as &$amount) {
                $amount = round($amount / $usdRate, 2);
            }
        }
    }

    private function convertInvestments(array &$investments, float $usdRate): void
    {
        foreach (['PMA', 'PMDN'] as $type) {
            if (isset($investments[$type])) {
                $investments[$type] = round($investments[$type] / $usdRate, 2);
            }
        }
    }

    private function convertDistrictData(array &$data, float $usdRate): void
    {
        foreach (['PMA', 'PMDN'] as $type) {
            if (isset($data[$type])) {
                foreach ($data[$type] as $district => &$amount) {
                    $amount = round($amount / $usdRate, 2);
                }
            }
        }
    }

    private function convertLocationData(array &$locations, float $usdRate): void
    {
        foreach ($locations as $location => &$amount) {
            $amount = round($amount / $usdRate, 2);
        }
    }

    private function convertPercentagesData(array &$percentages, float $usdRate): void
    {
        foreach (['PMA', 'PMDN'] as $type) {
            if (isset($percentages[$type])) {
                foreach ($percentages[$type] as $district => &$info) {
                    $info['amount'] = round($info['amount'] / $usdRate, 2);
                }
            }
        }
    }
}