<?php
/**
 * Entry point untuk server TANPA mod_rewrite
 * 
 * Cara Penggunaan:
 * - https://domain/DataInvest/public/?r=auth/login
 * - https://domain/DataInvest/public/?r=dashboard
 * - https://domain/DataInvest/public/?r=security-monitoring
 * - https://domain/DataInvest/public/?r=user-management
 * 
 * ATAU dengan path info langsung:
 * - https://domain/DataInvest/public/router.php/dashboard
 * - https://domain/DataInvest/public/router.php/auth/login
 */

// Set proper environment
define('ENVIRONMENT', 'development');

// Set current directory
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(FCPATH);

// Get route dari query string 'r' atau dari PATH_INFO
$route = $_GET['r'] ?? '';

if (empty($route)) {
    // Coba dari PATH_INFO (jika diakses via router.php/path/to/route)
    $pathInfo = $_SERVER['PATH_INFO'] ?? '';
    if (!empty($pathInfo) && $pathInfo !== '/') {
        $route = ltrim($pathInfo, '/');
    } else {
        $route = '/';
    }
}

// Handle trailing slashes
$route = rtrim($route, '/');

// Handle multiple slashes
$route = preg_replace('#/+#', '/', $route);

// Set URI untuk CodeIgniter
$_SERVER['REQUEST_URI'] = '/' . $route;
$_SERVER['PATH_INFO'] = '/' . $route;
$_SERVER['QUERY_STRING'] = 'r=' . $route;

// Bootstrap CodeIgniter
require_once FCPATH . '../app/Config/Paths.php';
require_once FCPATH . '../vendor/autoload.php';

$paths = new \Config\Paths();

// Load framework
require $paths->systemDirectory . '/Boot.php';

exit(\CodeIgniter\Boot::bootWeb($paths));

