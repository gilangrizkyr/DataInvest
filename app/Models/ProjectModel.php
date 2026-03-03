<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    protected $table = 'projects';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'upload_id',
        'report_id',
        'project_id',
        'project_name',
        'investment_type',
        'period_stage',
        'main_sector',
        'sector_23',
        'business_type',
        'company_name',
        'email',
        'address',
        'location_print',
        'sector_detail',
        'kbli_description',
        'province',
        'district',
        'subdistrict',
        'additional_investment',
        'total_investment',
        'planned_total_investment',
        'fixed_capital_planned',
        'tki',
        'tka',
        'officer_name',
        'problem_description',
        'fixed_capital_explanation',
        'phone_number',
        'country'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get statistics by upload ID
     */
    public function getStatisticsByUpload($uploadId)
    {
        $builder = $this->db->table('upload_statistics');
        return $builder->where('upload_id', $uploadId)->get()->getRowArray();
    }

    /**
     * Get district statistics by upload ID
     */
    public function getDistrictStatistics($uploadId)
    {
        $builder = $this->db->table('district_statistics');
        return $builder->where('upload_id', $uploadId)->get()->getResultArray();
    }

    /**
     * Get sector statistics by upload ID
     */
    public function getSectorStatistics($uploadId)
    {
        $builder = $this->db->table('sector_statistics');
        return $builder->where('upload_id', $uploadId)->get()->getResultArray();
    }

    /**
     * Get country statistics by upload ID
     */
    public function getCountryStatistics($uploadId)
    {
        $builder = $this->db->table('country_statistics');
        return $builder->where('upload_id', $uploadId)->get()->getResultArray();
    }

    /**
     * Get period statistics by upload ID
     */
    public function getPeriodStatistics($uploadId)
    {
        $builder = $this->db->table('period_statistics');
        return $builder->where('upload_id', $uploadId)->get()->getResultArray();
    }

    /**
     * Get projects by upload ID with optional filters
     */
    public function getProjectsByUpload($uploadId, $filters = [])
    {
        $builder = $this->where('upload_id', $uploadId);

        if (!empty($filters['quarter']) && $filters['quarter'] !== 'all') {
            $builder->where('period_stage', $filters['quarter']);
        }

        if (!empty($filters['year']) && $filters['year'] !== 'all') {
            // Note: period_stage contains quarter info, but we might need to filter by year if available
            // For now, we'll assume period_stage filtering is sufficient
        }

        return $builder->findAll();
    }

    /**
     * Get total projects by type from pre-calculated statistics
     */
    public function getTotalProjects($uploadId, $filters = [])
    {
        // For now, we'll use direct calculation with filters since pre-calculated stats don't support filtering
        $builder = $this->select('investment_type, COUNT(*) as count')
            ->where('upload_id', $uploadId);

        if (!empty($filters['quarter']) && $filters['quarter'] !== 'all') {
            $builder->where('period_stage', $filters['quarter']);
        }

        $result = $builder->groupBy('investment_type')->findAll();

        // If no results found with filters, return empty array
        if (empty($result)) {
            return [];
        }

        $totals = ['PMA' => 0, 'PMDN' => 0];
        foreach ($result as $row) {
            $totals[$row['investment_type']] = $row['count'];
        }
        return $totals;
    }

    /**
     * Get total investment by type from pre-calculated statistics
     */
    public function getTotalInvestment($uploadId, $filters = [])
    {
        // For now, we'll use direct calculation with filters since pre-calculated stats don't support filtering
        $builder = $this->select('investment_type, SUM(total_investment) as total')
            ->where('upload_id', $uploadId);

        if (!empty($filters['quarter']) && $filters['quarter'] !== 'all') {
            $builder->where('period_stage', $filters['quarter']);
        }

        $result = $builder->groupBy('investment_type')->findAll();

        // If no results found with filters, return empty array
        if (empty($result)) {
            return [];
        }

        $totals = ['PMA' => 0, 'PMDN' => 0];
        foreach ($result as $row) {
            $totals[$row['investment_type']] = $row['total'];
        }
        return $totals;
    }

    /**
     * Get additional investment by type from pre-calculated statistics
     */
    public function getAdditionalInvestment($uploadId, $filters = [])
    {
        // For now, we'll use direct calculation with filters since pre-calculated stats don't support filtering
        $builder = $this->select('investment_type, SUM(additional_investment) as total')
            ->where('upload_id', $uploadId);

        if (!empty($filters['quarter']) && $filters['quarter'] !== 'all') {
            $builder->where('period_stage', $filters['quarter']);
        }

        $result = $builder->groupBy('investment_type')->findAll();

        $totals = ['PMA' => 0, 'PMDN' => 0];
        foreach ($result as $row) {
            $totals[$row['investment_type']] = $row['total'];
        }
        return $totals;
    }

    /**
     * Get workforce by type from pre-calculated statistics
     */
    public function getWorkforce($uploadId, $filters = [])
    {
        // For now, we'll use direct calculation with filters since pre-calculated stats don't support filtering
        $builder = $this->select('investment_type, SUM(tki) as tki, SUM(tka) as tka')
            ->where('upload_id', $uploadId);

        if (!empty($filters['quarter']) && $filters['quarter'] !== 'all') {
            $builder->where('period_stage', $filters['quarter']);
        }

        $result = $builder->groupBy('investment_type')->findAll();

        $workforce = ['PMA' => ['TKI' => 0, 'TKA' => 0], 'PMDN' => ['TKI' => 0, 'TKA' => 0]];
        foreach ($result as $row) {
            $workforce[$row['investment_type']]['TKI'] = $row['tki'];
            $workforce[$row['investment_type']]['TKA'] = $row['tka'];
        }
        return $workforce;
    }

    /**
     * Get projects by district from pre-calculated statistics
     */
    public function getProjectsByDistrict($uploadId, $filters = [])
    {
        // For now, we'll use direct calculation with filters since pre-calculated stats don't support filtering
        $builder = $this->select('investment_type, subdistrict, COUNT(*) as count')
            ->where('upload_id', $uploadId)
            ->where('subdistrict IS NOT NULL')
            ->where('subdistrict !=', '');

        if (!empty($filters['quarter']) && $filters['quarter'] !== 'all') {
            $builder->where('period_stage', $filters['quarter']);
        }

        $result = $builder->groupBy(['investment_type', 'subdistrict'])->findAll();

        $districts = ['PMA' => [], 'PMDN' => []];
        foreach ($result as $row) {
            $districts[$row['investment_type']][$row['subdistrict']] = $row['count'];
        }

        // Sort each type by count descending
        foreach (['PMA', 'PMDN'] as $type) {
            arsort($districts[$type]);
        }

        return $districts;
    }

    /**
     * Get investment by district from pre-calculated statistics
     */
    public function getInvestmentByDistrict($uploadId, $filters = [])
    {
        // For now, we'll use direct calculation with filters since pre-calculated stats don't support filtering
        $builder = $this->select('subdistrict, SUM(total_investment) as total')
            ->where('upload_id', $uploadId)
            ->where('subdistrict IS NOT NULL')
            ->where('subdistrict !=', '');

        if (!empty($filters['quarter']) && $filters['quarter'] !== 'all') {
            $builder->where('period_stage', $filters['quarter']);
        }

        $result = $builder->groupBy('subdistrict')->orderBy('total', 'DESC')->findAll();

        $investments = [];
        foreach ($result as $row) {
            $investments[$row['subdistrict']] = $row['total'];
        }
        // Already sorted by DESC, but ensure with arsort
        arsort($investments);
        return $investments;
    }

    /**
     * Get sector analysis from pre-calculated statistics
     */
    public function getSectorAnalysis($uploadId)
    {
        $sectorStats = $this->getSectorStatistics($uploadId);
        if (!empty($sectorStats)) {
            $total = array_sum(array_column($sectorStats, 'project_count'));
            $analysis = [];
            foreach ($sectorStats as $stat) {
                $analysis[] = [
                    'sector' => $stat['sector'],
                    'count' => $stat['project_count'],
                    'percentage' => $stat['percentage']
                ];
            }
            // Sort by count descending (highest to lowest)
            usort($analysis, function ($a, $b) {
                return $b['count'] <=> $a['count'];
            });
            return $analysis;
        }

        // Fallback to direct calculation if stats not available
        $result = $this->select('sector_detail, COUNT(*) as count')
            ->where('upload_id', $uploadId)
            ->where('sector_detail IS NOT NULL')
            ->where('sector_detail !=', '')
            ->groupBy('sector_detail')
            ->orderBy('count', 'DESC')
            ->findAll();

        $total = array_sum(array_column($result, 'count'));
        $analysis = [];
        foreach ($result as $row) {
            $analysis[] = [
                'sector' => $row['sector_detail'],
                'count' => $row['count'],
                'percentage' => round(($row['count'] / $total) * 100, 2)
            ];
        }
        // Already sorted by DESC in query, but ensure with usort
        usort($analysis, function ($a, $b) {
            return $b['count'] <=> $a['count'];
        });
        return $analysis;
    }

    /**
     * Get workforce by district from pre-calculated statistics
     */
    public function getWorkforceByDistrict($uploadId, $filters = [])
    {
        // For now, we'll use direct calculation with filters since pre-calculated stats don't support filtering
        $builder = $this->select('investment_type, subdistrict, SUM(tki) as tki, SUM(tka) as tka')
            ->where('upload_id', $uploadId)
            ->where('subdistrict IS NOT NULL')
            ->where('subdistrict !=', '');

        if (!empty($filters['quarter']) && $filters['quarter'] !== 'all') {
            $builder->where('period_stage', $filters['quarter']);
        }

        $result = $builder->groupBy(['investment_type', 'subdistrict'])->findAll();

        $workforce = ['PMA' => [], 'PMDN' => []];
        foreach ($result as $row) {
            $workforce[$row['investment_type']][$row['subdistrict']] = [
                'TKI' => $row['tki'],
                'TKA' => $row['tka']
            ];
        }

        // Sort each district by total workforce (TKI + TKA) descending
        foreach (['PMA', 'PMDN'] as $type) {
            uasort($workforce[$type], function ($a, $b) {
                $totalA = ($a['TKI'] ?? 0) + ($a['TKA'] ?? 0);
                $totalB = ($b['TKI'] ?? 0) + ($b['TKA'] ?? 0);
                return $totalB <=> $totalA;
            });
        }

        return $workforce;
    }

    /**
     * Get projects by country from pre-calculated statistics (PMA only)
     */
    public function getProjectsByCountry($uploadId, $filters = [])
    {
        // For now, we'll use direct calculation with filters since pre-calculated stats don't support filtering (PMA only)
        $builder = $this->select('country, COUNT(*) as count')
            ->where('upload_id', $uploadId)
            ->where('investment_type', 'PMA')
            ->where('country IS NOT NULL')
            ->where('country !=', '');

        if (!empty($filters['quarter']) && $filters['quarter'] !== 'all') {
            $builder->where('period_stage', $filters['quarter']);
        }

        $result = $builder->groupBy('country')->orderBy('count', 'DESC')->findAll();

        $countries = [];
        foreach ($result as $row) {
            $countries[$row['country']] = (int) $row['count'];
        }
        // Already sorted by DESC, but ensure with arsort
        arsort($countries);
        return $countries;
    }

    /**
     * Get ranking by district from pre-calculated statistics
     */
    public function getRankingByDistrict($uploadId, $filters = [])
    {
        // For now, we'll use direct calculation with filters since pre-calculated stats don't support filtering
        $builder = $this->select('subdistrict, COUNT(*) as total_projects')
            ->where('upload_id', $uploadId)
            ->where('subdistrict IS NOT NULL')
            ->where('subdistrict !=', '');

        if (!empty($filters['quarter']) && $filters['quarter'] !== 'all') {
            $builder->where('period_stage', $filters['quarter']);
        }

        $result = $builder->groupBy('subdistrict')->orderBy('total_projects', 'DESC')->findAll();

        // Already sorted by DESC in query, but ensure in array format
        return array_map(function ($row) {
            return [
                'kecamatan' => $row['subdistrict'],
                'jumlah_proyek' => $row['total_projects']
            ];
        }, $result);
    }

    /**
     * Get realization investment from pre-calculated statistics
     */
    public function getRealizationInvestment($uploadId)
    {
        $stats = $this->getStatisticsByUpload($uploadId);
        if ($stats) {
            return [
                'PMA' => $stats['realization_investment_pma'] ?? 0,
                'PMDN' => $stats['realization_investment_pmdn'] ?? 0
            ];
        }

        // Fallback to direct calculation if stats not available
        $result = $this->select('investment_type, SUM(planned_total_investment - total_investment) as realization')
            ->where('upload_id', $uploadId)
            ->groupBy('investment_type')
            ->findAll();

        $realization = ['PMA' => 0, 'PMDN' => 0];
        foreach ($result as $row) {
            $realization[$row['investment_type']] = $row['realization'];
        }
        return $realization;
    }

    /**
     * Get quarterly results from pre-calculated statistics
     */
    public function getQuarterlyResults($uploadId, $filters = [])
    {
        // For now, we'll use direct calculation with filters since pre-calculated stats don't support filtering
        $builder = $this->select('period_stage, COUNT(*) as count')
            ->where('upload_id', $uploadId)
            ->where('period_stage IS NOT NULL')
            ->where('period_stage !=', '');

        if (!empty($filters['quarter']) && $filters['quarter'] !== 'all') {
            $builder->where('period_stage', $filters['quarter']);
        }

        $result = $builder->groupBy('period_stage')->orderBy('period_stage')->findAll();

        $quarters = [];
        foreach ($result as $row) {
            $quarters[$row['period_stage']] = $row['count'];
        }
        return $quarters;
    }

    /**
     * Get additional investment by district from pre-calculated statistics
     */
    public function getAdditionalInvestmentByDistrict($uploadId, $filters = [])
    {
        // For now, we'll use direct calculation with filters since pre-calculated stats don't support filtering
        $builder = $this->select('investment_type, subdistrict, SUM(additional_investment) as total')
            ->where('upload_id', $uploadId)
            ->where('subdistrict IS NOT NULL')
            ->where('subdistrict !=', '');

        if (!empty($filters['quarter']) && $filters['quarter'] !== 'all') {
            $builder->where('period_stage', $filters['quarter']);
        }

        $result = $builder->groupBy(['investment_type', 'subdistrict'])->findAll();

        $investments = ['PMA' => [], 'PMDN' => []];
        foreach ($result as $row) {
            $investments[$row['investment_type']][$row['subdistrict']] = $row['total'];
        }
        return $investments;
    }

    /**
     * Get sector count by company name (PMA and PMDN)
     * Returns count of unique sectors per company for LKPM reporting
     */
    public function getSectorCountByCompany($uploadId, $filters = [])
    {
        log_message('debug', '=== getSectorCountByCompany START ===');
        log_message('debug', 'Upload ID: ' . (is_array($uploadId) ? json_encode($uploadId) : $uploadId));
        log_message('debug', 'Filters: ' . json_encode($filters));

        $getCompanyData = function ($investmentType) use ($uploadId, $filters) {
            $builder = $this->db->table($this->table)
                ->select('company_name AS nama_perusahaan,
                      SUM(additional_investment) AS tambahan_realisasi,
                      SUM(tki) AS jumlah_tki,
                      SUM(tka) AS jumlah_tka,
                      COUNT(DISTINCT project_id) AS jumlah_proyek')
                ->where('investment_type', $investmentType)
                ->where('company_name IS NOT NULL')
                ->where('company_name !=', '')
                ->groupBy('company_name')
                ->orderBy('company_name', 'ASC');

            // Jika upload_id adalah array, gunakan whereIn
            if (is_array($uploadId)) {
                $builder->whereIn('upload_id', $uploadId);
            } else {
                $builder->where('upload_id', $uploadId);
            }

            if (!empty($filters['quarter']) && $filters['quarter'] !== 'all') {
                $builder->where('quarter', $filters['quarter']);
            }

            $result = $builder->get()->getResultArray();

            // Pastikan semua field numerik dikonversi ke float/int agar view bisa number_format
            foreach ($result as &$row) {
                $row['tambahan_realisasi'] = (float) $row['tambahan_realisasi'];
                $row['jumlah_tki'] = (int) $row['jumlah_tki'];
                $row['jumlah_tka'] = (int) $row['jumlah_tka'];
                $row['jumlah_proyek'] = (int) $row['jumlah_proyek'];
            }

            log_message('debug', $investmentType . ' records found: ' . count($result));

            return $result;
        };

        // PMA
        $resultPMA = $getCompanyData('PMA');
        // Sort by tambahan_realisasi descending (highest to lowest)
        usort($resultPMA, function ($a, $b) {
            return $b['tambahan_realisasi'] <=> $a['tambahan_realisasi'];
        });
        $totalPMA = array_sum(array_column($resultPMA, 'tambahan_realisasi'));

        // PMDN
        $resultPMDN = $getCompanyData('PMDN');
        // Sort by tambahan_realisasi descending (highest to lowest)
        usort($resultPMDN, function ($a, $b) {
            return $b['tambahan_realisasi'] <=> $a['tambahan_realisasi'];
        });
        $totalPMDN = array_sum(array_column($resultPMDN, 'tambahan_realisasi'));

        log_message('debug', '=== getSectorCountByCompany END ===');

        return [
            'PMA' => [
                'data' => $resultPMA,
                'total' => $totalPMA
            ],
            'PMDN' => [
                'data' => $resultPMDN,
                'total' => $totalPMDN
            ]
        ];
    }

    /**
     * Delete all projects by upload ID
     */
    public function deleteProjectsByUpload($uploadId)
    {
        return $this->where('upload_id', $uploadId)->delete();
    }
}
