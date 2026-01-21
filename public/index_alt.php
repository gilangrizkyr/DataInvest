<?php
/**
 * Alternative entry point untuk server TANPA mod_rewrite
 * 
 * Cara Penggunaan (di browser):
 * - https://dpmptsp.tail8af30b.ts.net/DataInvest/public/?r=auth/login
 * - https://dpmptsp.tail8af30b.ts.net/DataInvest/public/?r=dashboard
 * - https://dpmptsp.tail8af30b.ts.net/DataInvest/public/?r=security-monitoring
 * - https://dpmptsp.tail8af30b.ts.net/DataInvest/public/?r=user-management
 */

// Get route dari query string 'r'
$r = $_GET['r'] ?? '';

// Handle trailing slashes
$r = rtrim($r, '/');

// Set proper environment
define('ENVIRONMENT', 'development');

// Set current directory
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(FCPATH);

// Bootstrap CodeIgniter
require_once FCPATH . '../app/Config/Paths.php';
require_once FCPATH . '../vendor/autoload.php';

$paths = new \Config\Paths();

// Override base URL
$appConfig = require $paths->appDirectory . '/Config/App.php';
$appConfig->baseURL = 'https://dpmptsp.tail8af30b.ts.net/DataInvest/';

// Set URI jika route diberikan
if (!empty($r)) {
    $_SERVER['REQUEST_URI'] = '/' . $r;
    $_SERVER['PATH_INFO'] = '/' . $r;
    $_SERVER['QUERY_STRING'] = http_build_query(['r' => $r]);
}

// Load framework
require $paths->systemDirectory . '/Boot.php';

exit(\CodeIgniter\Boot::bootWeb($paths));

