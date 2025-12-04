<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ThreatDetection implements FilterInterface
{
    private $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        $input = $this->getAllInput($request);
        $ipAddress = $request->getIPAddress();
        $userAgent = $request->header('User-Agent') ? $request->header('User-Agent')->getValue() : '';
        
        // Deteksi ancaman
        $threat = $this->detectThreat($input);
        
        if ($threat) {
            // Log ancaman
            try {
                $this->db->table('security_logs')->insert([
                    'type' => $threat['type'],
                    'ip_address' => $ipAddress,
                    'severity' => $threat['severity'],
                    'status' => 'blocked',
                    'payload' => substr($input, 0, 65535),
                    'uri' => (string)$request->getUri(),
                    'method' => $request->getMethod(),
                    'user_agent' => $userAgent,
                ]);
            } catch (\Exception $e) {
                log_message('error', 'Failed to log threat: ' . $e->getMessage());
            }

            // Block request
            return response()
                ->setStatusCode(403)
                ->setJSON([
                    'success' => false,
                    'message' => 'Suspicious activity detected'
                ]);
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }

    private function getAllInput($request)
    {
        $input = array_merge(
            $request->getGet() ?? [],
            $request->getPost() ?? []
        );
        return json_encode($input);
    }

    private function detectThreat($input)
    {
        // SQL Injection
        if (preg_match("/(OR|AND)\s+\d+\s*=\s*\d+/i", $input)) {
            return ['type' => 'SQL Injection Attempt', 'severity' => 'critical'];
        }

        if (preg_match("/(UNION|SELECT|DROP|INSERT|UPDATE|DELETE)\s+(FROM|INTO|TABLE|WHERE)/i", $input)) {
            return ['type' => 'SQL Injection Attempt', 'severity' => 'critical'];
        }

        // XSS
        if (preg_match("/<script|javascript:|onerror\s*=|onload\s*=/i", $input)) {
            return ['type' => 'XSS Attack', 'severity' => 'high'];
        }

        // Command Injection
        if (preg_match("/[;&|`$()\[\]{}]/", $input)) {
            return ['type' => 'Command Injection Attempt', 'severity' => 'critical'];
        }

        // Path Traversal
        if (preg_match("/\.\.\/|\.\.\\\\|\/etc\/|c:\\\\windows/i", $input)) {
            return ['type' => 'Path Traversal Attack', 'severity' => 'high'];
        }

        return null;
    }
}