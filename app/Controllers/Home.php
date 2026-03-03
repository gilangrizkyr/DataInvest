<?php

namespace App\Controllers;

use App\Services\DashboardService;

class Home extends BaseController
{
    protected $dashboardService;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->dashboardService = new DashboardService();
    }

    public function index(): string
    {
        // Fetch default dashboard data for public display
        $filters = [
            'upload' => 'all',
            'quarter' => 'all',
            'year' => 'all',
            'quarterly_year' => 'all',
            'currency' => 'IDR'
        ];

        $data = $this->dashboardService->getDashboardData($filters);

        return view('welcome_modern', [
            'title' => 'DataInvest - Portal Informasi Investasi',
            'data' => $data
        ]);
    }
}
