<?php
/**
 * Entry point CI4 tanpa mod_rewrite
 * 
 * Usage: /DataInvest/public/simple.php/dashboard
 */

define('ENVIRONMENT', 'development');
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(FCPATH);

// ====================================================================
// PENTING: Set PATH_INFO sebelum CI4 bootstrap
// ====================================================================

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';

// Hapus query string
$requestUri = strtok($requestUri, '?');

// Extract path info - ini kuncinya!
$pathInfo = '';

if (strpos($requestUri, $scriptName) !== false) {
    // Contoh: /DataInvest/public/index.php/dashboard
    // Ambil bagian setelah index.php
    $pathInfo = substr($requestUri, strpos($requestUri, $scriptName) + strlen($scriptName));
} elseif (preg_match('#/DataInvest/public/simple\.php(/?.*)$#', $requestUri, $m)) {
    // Contoh: /DataInvest/public/simple.php/dashboard
    $pathInfo = $m[1];
}

// Clean path
$pathInfo = '/' . ltrim($pathInfo, '/');
$pathInfo = rtrim($pathInfo, '/');

// Default ke dashboard jika root
if (empty($pathInfo) || $pathInfo === '/' || $pathInfo === '') {
    $pathInfo = '/dashboard';
}

// Set PATH_INFO - INI YANG PALING PENTING!
$_SERVER['PATH_INFO'] = $pathInfo;
$_SERVER['SCRIPT_NAME'] = $scriptName;
$_SERVER['REQUEST_URI'] = $pathInfo;
$_SERVER['QUERY_STRING'] = '';

// ====================================================================
// Bootstrap CI4
// ====================================================================

require FCPATH . '../app/Config/Paths.php';
require FCPATH . '../vendor/autoload.php';

$paths = new Config\Paths();

// Jalankan CI4
exit(\CodeIgniter\Boot::bootWeb($paths));


