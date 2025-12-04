<?php

namespace App\Controllers;

class SecurityMonitoring extends BaseController
{
    public function index()
    {
        return view('security_dashboard');
    }

    public function getThreats()
    {
        try {
            $db = \Config\Database::connect();

            // 24 jam terakhir
            $threats = $db->table('security_logs')
                ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-24 hours')))
                ->orderBy('created_at', 'DESC')
                ->limit(100)
                ->get()
                ->getResultArray();

            $today = date('Y-m-d');

            $total = $db->table('security_logs')
                ->where('DATE(created_at)', $today)
                ->countAllResults();

            $blocked = $db->table('security_logs')
                ->where('DATE(created_at)', $today)
                ->where('status', 'blocked')
                ->countAllResults();

            $critical = $db->table('security_logs')
                ->where('DATE(created_at)', $today)
                ->where('severity', 'critical')
                ->countAllResults();

            $stats = [
                'total_attempts' => $total,
                'total_blocked' => $blocked,
                'block_rate' => $total > 0 ? round(($blocked / $total) * 100, 1) : 0,
                'critical_threats' => $critical,
                'passed' => $total - $blocked,
            ];

            $trend = $this->getTrendData($db);

            $threatTypes = $db->table('security_logs')
                ->select('type, COUNT(*) as count')
                ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-24 hours')))
                ->groupBy('type')
                ->orderBy('count', 'DESC')
                ->get()
                ->getResultArray();

            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'threats' => $threats,
                    'stats' => $stats,
                    'trend' => $trend,
                    'threat_types' => $threatTypes,
                ]
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function getTrendData($db)
    {
        $data = [];
        for ($i = 23; $i >= 0; $i--) {
            $hourLabel = date('H:00', strtotime("-$i hours"));
            $startTime = date('Y-m-d H:00:00', strtotime("-" . ($i + 1) . " hours"));
            $endTime = date('Y-m-d H:00:00', strtotime("-$i hours"));

            $total = $db->table('security_logs')
                ->where('created_at >=', $startTime)
                ->where('created_at <', $endTime)
                ->countAllResults();

            $blocked = $db->table('security_logs')
                ->where('created_at >=', $startTime)
                ->where('created_at <', $endTime)
                ->where('status', 'blocked')
                ->countAllResults();

            $data[] = [
                'time' => $hourLabel,
                'attempts' => $total,
                'blocked' => $blocked,
                'passed' => $total - $blocked
            ];
        }
        return $data;
    }

    public function export()
    {
        try {
            $db = \Config\Database::connect();

            $threats = $db->table('security_logs')
                ->orderBy('created_at', 'DESC')
                ->limit(1000)
                ->get()
                ->getResultArray();

            $filename = "security_logs_" . date('Ymd_His') . ".csv";

            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            $output = fopen('php://output', 'w');

            fputcsv($output, ['IP Address', 'Type', 'Severity', 'Status', 'Time', 'Description', 'URI', 'Payload']);

            foreach ($threats as $t) {
                fputcsv($output, [
                    $t['ip_address'] ?? '',
                    $t['type'] ?? '',
                    $t['severity'] ?? '',
                    $t['status'] ?? '',
                    $t['created_at'] ?? '',
                    $t['description'] ?? '',
                    $t['request_uri'] ?? '',
                    $t['payload'] ?? ''
                ]);
            }

            fclose($output);
            exit;
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
