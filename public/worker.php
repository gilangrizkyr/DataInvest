<?php
/**
 * Simple CI4 entry point - bypass routing system
 * For servers WITHOUT mod_rewrite
 * 
 * Usage: /DataInvest/public/worker.php/dashboard
 *        /DataInvest/public/worker.php/auth/login
 */

define('ENVIRONMENT', 'development');
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(FCPATH);

// Get path from URL
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';

// Remove query string
$requestUri = strtok($requestUri, '?');

// Extract path after index.php or worker.php
$path = '';
if (strpos($requestUri, $scriptName) !== false) {
    $path = substr($requestUri, strpos($requestUri, $scriptName) + strlen($scriptName));
} elseif (preg_match('#/DataInvest/public/worker\.php(/?.*)$#', $requestUri, $m)) {
    $path = $m[1];
} elseif (preg_match('#/DataInvest/public/(index\.php)?(/?.*)$#', $requestUri, $m)) {
    $path = $m[2];
}

// Clean path
$path = '/' . ltrim($path, '/');
$path = rtrim($path, '/');
if (empty($path) || $path === '/') {
    $path = '/dashboard';
}

// Set PATH_INFO for CI4
$_SERVER['PATH_INFO'] = $path;
$_SERVER['SCRIPT_NAME'] = $scriptName;
$_SERVER['REQUEST_URI'] = $path;

// Bootstrap CI4
require FCPATH . '../app/Config/Paths.php';
require FCPATH . '../vendor/autoload.php';

$paths = new Config\Paths();

// Load CodeIgniter
require $paths->systemDirectory . '/Boot.php';

use CodeIgniter\CodeIgniter;

// Create CI4 instance
$app = new CodeIgniter($paths);
$app->initialize();

// Run with the modified request
$request = \Config\Services::request();

// Force the path
$uri = $request->getUri();
$uri = $uri->setPath($path);

// Run
$response = $app->run($request, true);
echo $response->getBody();

