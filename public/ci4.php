<?php
/**
 * Entry point CI4 yang benar - tanpa mod_rewrite
 * 
 * Usage: /DataInvest/public/ci4.php/dashboard
 */

define('ENVIRONMENT', 'development');
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(FCPATH);

// Parse PATH_INFO dari REQUEST_URI
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';

// Hapus query string
$requestUri = strtok($requestUri, '?');

// Extract path info
$pathInfo = '';
if (strpos($requestUri, $scriptName) !== false) {
    $pathInfo = substr($requestUri, strpos($requestUri, $scriptName) + strlen($scriptName));
} elseif (preg_match('#/DataInvest/public/ci4\.php(/?.*)$#', $requestUri, $m)) {
    $pathInfo = $m[1];
}

// Clean path
$pathInfo = '/' . ltrim($pathInfo, '/');
$pathInfo = rtrim($pathInfo, '/');
if (empty($pathInfo) || $pathInfo === '/') {
    $pathInfo = '/dashboard';
}

// Set server variables untuk CI4
$_SERVER['PATH_INFO'] = $pathInfo;
$_SERVER['SCRIPT_NAME'] = $scriptName;
$_SERVER['REQUEST_URI'] = $pathInfo;
$_SERVER['QUERY_STRING'] = '';

// Bootstrap CI4 menggunakan Boot class
require FCPATH . '../app/Config/Paths.php';
require FCPATH . '../vendor/autoload.php';

$paths = new Config\Paths();

// Load framework menggunakan Boot::bootWeb
exit(\CodeIgniter\Boot::bootWeb($paths));
