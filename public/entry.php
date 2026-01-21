<?php
/**
 * Entry point CI4 tanpa routing - langsung instantiate controller
 * Tanpa mod_rewrite
 */

// Environment
define('ENVIRONMENT', 'development');
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(FCPATH);

// Parse URI
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
$pathInfo = '';

$requestUri = strtok($requestUri, '?');

// Extract path
if (strpos($requestUri, $scriptName) === 0) {
    $pathInfo = substr($requestUri, strlen($scriptName));
} elseif (preg_match('#/DataInvest/public/entry\.php(/.*)$#', $requestUri, $matches)) {
    $pathInfo = $matches[1];
} else {
    $pathInfo = $requestUri;
}

$pathInfo = '/' . ltrim($pathInfo, '/');
$pathInfo = rtrim($pathInfo, '/');
if ($pathInfo === '/' || $pathInfo === '') $pathInfo = '/dashboard';

$segments = array_values(array_filter(explode('/', $pathInfo)));
$page = strtolower($segments[0] ?? 'dashboard');
$action = strtolower($segments[1] ?? 'index');
$id = $segments[2] ?? null;

// Set server vars
$_SERVER['PATH_INFO'] = $pathInfo;
$_SERVER['SCRIPT_NAME'] = $scriptName;
$_SERVER['REQUEST_URI'] = $pathInfo;

// Bootstrap CI4
require FCPATH . '../app/Config/Paths.php';
require FCPATH . '../vendor/autoload.php';

$paths = new Config\Paths();

// Load required files
require $paths->systemDirectory . '/Common.php';
require $paths->systemDirectory . '/Config/Constants.php';
require $paths->appDirectory . '/Config/App.php';
require $paths->systemDirectory . '/Config/Services.php';
require $paths->systemDirectory . '/HTTP/Request.php';
require $paths->systemDirectory . '/HTTP/Response.php';
require $paths->systemDirectory . '/Controller.php';
require $paths->appDirectory . '/Controllers/BaseController.php';

// Initialize services
$request = \Config\Services::request();
$response = \Config\Services::response();
$logger = \Config\Services::logger();
$session = \Config\Services::session();

// Router bypass - map URL directly to controllers
$controllerMap = [
    'dashboard' => ['controller' => 'App\\Controllers\\Dashboard', 'filter' => 'roleFilter'],
    'auth' => ['controller' => 'App\\Controllers\\Auth', 'filter' => null],
    'login' => ['controller' => 'App\\Controllers\\Auth', 'method' => 'login', 'filter' => 'auth:guest'],
    'logout' => ['controller' => 'App\\Controllers\\Auth', 'method' => 'logout', 'filter' => null],
    'process-login' => ['controller' => 'App\\Controllers\\Auth', 'method' => 'processLogin', 'filter' => null, 'type' => 'POST'],
    'security-monitoring' => ['controller' => 'App\\Controllers\\SecurityMonitoring', 'filter' => 'roleFilter'],
    'user-management' => ['controller' => 'App\\Controllers\\UserManagement', 'filter' => 'roleFilter:superadmin'],
    'faq' => ['controller' => 'App\\Controllers\\Faq', 'filter' => null],
];

// Check if page exists in map
if (!isset($controllerMap[$page])) {
    // Try default mapping
    $page = 'dashboard';
}

// Get controller info
$ctrlInfo = $controllerMap[$page];
$controllerClass = $ctrlInfo['controller'];
$defaultMethod = $ctrlInfo['method'] ?? $action;
$requiredFilter = $ctrlInfo['filter'] ?? null;
$methodType = $ctrlInfo['type'] ?? 'GET';

// Load controller file
$controllerFile = FCPATH . '../app/Controllers/' . str_replace('App\\Controllers\\', '', $controllerClass) . '.php';
if (file_exists($controllerFile)) {
    require_once $controllerFile;
}

// Check if method exists, else use index
$method = method_exists($controllerClass, $defaultMethod) ? $defaultMethod : 'index';

// Check request method
$currentMethod = strtolower($_SERVER['REQUEST_METHOD'] ?? 'get');
if ($methodType !== 'GET' && $currentMethod !== strtolower($methodType)) {
    echo "Method not allowed";
    exit;
}

// Check auth filter
if ($requiredFilter) {
    if (strpos($requiredFilter, 'auth:guest') !== false) {
        // Guest only - redirect if logged in
        if ($session->has('user_id')) {
            header('Location: /DataInvest/public/entry.php/dashboard');
            exit;
        }
    } elseif (strpos($requiredFilter, 'roleFilter') !== false) {
        // Check login
        if (!$session->has('user_id')) {
            // Show login page
            $controllerClass = 'App\\Controllers\\Auth';
            $method = 'login';
            $controllerFile = FCPATH . '../app/Controllers/Auth.php';
            require_once $controllerFile;
        }
    }
}

// Instantiate controller
if (class_exists($controllerClass)) {
    $controller = new $controllerClass($request, $response, $logger);
    $controller->initController($request, $response, $logger);
    
    // Call method with params
    if (method_exists($controller, $method)) {
        // Handle special methods that require POST
        if (in_array($method, ['processLogin', 'processForgotPassword', 'processResetPassword', 'store', 'update', 'upload', 'processMetadata', 'updateMetadata', 'deleteUpload', 'setLanguage'])) {
            $_SERVER['REQUEST_METHOD'] = 'POST';
        }
        
        if (!empty($id)) {
            $controller->$method($id);
        } else {
            $controller->$method();
        }
    } else {
        echo "Method '$method' not found in $controllerClass";
    }
} else {
    echo "Controller class '$controllerClass' not found";
}

