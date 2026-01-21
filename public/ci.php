<?php
/**
 * Bootstrap CI4 yang benar - bypass routing system
 * Langsung eksekusi controller tanpa mod_rewrite
 */

// Set environment
define('ENVIRONMENT', 'development');

// Set FCPATH
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(FCPATH);

// Parse URI dari REQUEST_URI
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
$pathInfo = '';

// Hapus query string
$requestUri = strtok($requestUri, '?');

// Extract path info dari REQUEST_URI
// Contoh: /DataInvest/public/ci.php/dashboard -> /dashboard
if (strpos($requestUri, $scriptName) === 0) {
    $pathInfo = substr($requestUri, strlen($scriptName));
} else {
    // Coba cari DataInvest/public/ci.php di URI
    $pattern = '#/DataInvest/public/ci\.php(/.*)$#';
    if (preg_match($pattern, $requestUri, $matches)) {
        $pathInfo = $matches[1];
    } else {
        $pathInfo = $requestUri;
    }
}

// Clean path info
$pathInfo = '/' . ltrim($pathInfo, '/');
$pathInfo = rtrim($pathInfo, '/');
if ($pathInfo === '') $pathInfo = '/';

// Parse segments
$segments = array_values(array_filter(explode('/', $pathInfo)));
$controllerName = !empty($segments[0]) ? strtolower($segments[0]) : 'dashboard';
$method = !empty($segments[1]) ? strtolower($segments[1]) : 'index';
$params = array_slice($segments, 2);

// Set server variables untuk CI4
$_SERVER['PATH_INFO'] = $pathInfo;
$_SERVER['SCRIPT_NAME'] = $scriptName;
$_SERVER['REQUEST_URI'] = $pathInfo;

// Bootstrap CI4
require FCPATH . '../app/Config/Paths.php';
require FCPATH . '../vendor/autoload.php';

$paths = new Config\Paths();

// Load CodeIgniter boot
require $paths->systemDirectory . '/Boot.php';

use CodeIgniter\CodeIgniter;

// Get CI4 instance
$app = new CodeIgniter($paths);

// Initialize
$app->initialize();

// Get request
$request = \Config\Services::request();

// Set the URI
$uri = $request->getUri();

// Force the URI segments
$uri->setPath($pathInfo);

// Try to run the app
try {
    $response = $app->run($request, true);
    echo $response->getBody();
} catch (Exception $e) {
    echo "<h1>Error</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

