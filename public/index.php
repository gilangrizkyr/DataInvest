<?php
/**
 * CI4 Index - Working Without mod_rewrite
 * Uses query string: index.php?r=/dashboard
 */

define('ENVIRONMENT', 'development');
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

// Load paths
require dirname(__DIR__) . '/app/Config/Paths.php';
$paths = new \Config\Paths();

// Load vendor autoloader
require dirname(__DIR__) . '/vendor/autoload.php';

// Setup basic constants
define('APPPATH', realpath($paths->appDirectory) . DIRECTORY_SEPARATOR);
define('ROOTPATH', realpath(APPPATH . '../') . DIRECTORY_SEPARATOR);
define('SYSTEMPATH', realpath($paths->systemDirectory) . DIRECTORY_SEPARATOR);
define('WRITEPATH', realpath($paths->writableDirectory) . DIRECTORY_SEPARATOR);
define('TESTPATH', realpath($paths->testsDirectory) . DIRECTORY_SEPARATOR);

// Load Config/Constants.php
require APPPATH . 'Config/Constants.php';

// Load autoloader
require SYSTEMPATH . 'Config/AutoloadConfig.php';
require APPPATH . 'Config/Autoload.php';
require SYSTEMPATH . 'Modules/Modules.php';
require APPPATH . 'Config/Modules.php';
require SYSTEMPATH . 'Autoloader/Autoloader.php';
require SYSTEMPATH . 'Config/BaseService.php';
require SYSTEMPATH . 'Config/Services.php';
require APPPATH . 'Config/Services.php';

// Initialize autoloader
\CodeIgniter\Config\Services::autoloader()->initialize(
    new \Config\Autoload(),
    new \Config\Modules()
)->register();

// Load common functions
require SYSTEMPATH . 'Common.php';

// Get the route from query string ?r=/dashboard
$route = 'dashboard'; // default

if (isset($_GET['r'])) {
    $route = ltrim($_GET['r'], '/');
}

// Parse the route to get controller and method
function parseRoute($route) {
    // Default to dashboard
    $controller = 'Dashboard';
    $method = 'index';
    $params = [];
    
    // Remove leading slash
    $route = ltrim($route, '/');
    
    if (empty($route) || $route === 'dashboard') {
        return [$controller, $method, $params];
    }
    
    $segments = explode('/', $route);
    
    // First segment is controller
    $controller = ucfirst(array_shift($segments));
    
    // If there are more segments, second is method
    if (!empty($segments)) {
        $method = array_shift($segments);
    }
    
    // Remaining segments are parameters
    $params = $segments;
    
    return [$controller, $method, $params];
}

list($controllerName, $method, $params) = parseRoute($route);

// Build controller class name
$controllerClass = "App\\Controllers\\" . $controllerName;

// Load the controller file
$controllerFile = APPPATH . 'Controllers/' . $controllerName . '.php';

if (!file_exists($controllerFile)) {
    // Controller not found - show 404
    header('HTTP/1.1 404 Not Found');
    echo "<h1>404 - Page Not Found</h1>";
    echo "<p>Controller '$controllerName' not found.</p>";
    echo "<p>Route: $route</p>";
    exit;
}

// Load controller
require_once $controllerFile;

// Create controller instance
if (!class_exists($controllerClass)) {
    header('HTTP/1.1 404 Not Found');
    echo "<h1>404 - Page Not Found</h1>";
    echo "<p>Class '$controllerClass' not found.</p>";
    exit;
}

// Create controller with dependencies
$controller = new $controllerClass();

// Check if method exists
if (!method_exists($controller, $method)) {
    header('HTTP/1.1 404 Not Found');
    echo "<h1>404 - Page Not Found</h1>";
    echo "<p>Method '$method' not found in controller '$controllerName'.</p>";
    echo "<p>Route: $route</p>";
    exit;
}

// Create a mock request
$request = \CodeIgniter\Config\Services::request();

// Create a mock response
$response = \CodeIgniter\Config\Services::response();

// Get logger
$logger = \CodeIgniter\Config\Services::logger();

// Initialize controller with request, response, and logger
if (method_exists($controller, 'initController')) {
    $controller->initController($request, $response, $logger);
}

// Call the method
try {
    // Call the controller method with parameters
    $output = call_user_func_array([$controller, $method], $params);
    
    // If output is a string, send it
    if (is_string($output)) {
        echo $output;
    } elseif ($output instanceof \CodeIgniter\HTTP\ResponseInterface) {
        // If it's a Response object, send it
        $output->send();
    }
} catch (Throwable $e) {
    // Handle errors
    header('HTTP/1.1 500 Internal Server Error');
    echo "<h1>500 - Error</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
    if (ENVIRONMENT === 'development') {
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
}

