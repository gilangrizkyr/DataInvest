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
$route = isset($_GET['r']) ? ltrim($_GET['r'], '/') : 'dashboard';

// Parse the route to get controller and method
function parseRoute($route) {
    $controller = 'Dashboard';
    $method = 'index';
    $params = [];
    
    $route = ltrim($route, '/');
    
    if (empty($route) || $route === 'dashboard') {
        return [$controller, $method, $params];
    }
    
    $segments = explode('/', $route);
    $controller = ucfirst(array_shift($segments));
    
    if (!empty($segments)) {
        $method = array_shift($segments);
    }
    
    $params = $segments;
    
    return [$controller, $method, $params];
}

list($controllerName, $method, $params) = parseRoute($route);
$controllerFile = APPPATH . 'Controllers/' . $controllerName . '.php';
$controllerClass = "App\\Controllers\\" . $controllerName;

// Load controller
require_once $controllerFile;

// Create proper CI4 request object
$appConfig = new \Config\App();
$appConfig->baseURL = 'https://dpmptsp.tail8af30b.ts.net/';
$appConfig->indexPage = 'index.php';

// Create URI object
$uri = new \CodeIgniter\HTTP\URI('https://dpmptsp.tail8af30b.ts.net/' . $route);

// Create UserAgent
$userAgent = new \CodeIgniter\HTTP\UserAgent();

// Create Request object
$request = \CodeIgniter\Config\Services::request($appConfig, $uri, 'php://input', $userAgent);

// Create Response object  
$response = \CodeIgniter\Config\Services::response($appConfig);

// Get logger
$logger = \CodeIgniter\Config\Services::logger();

// Create controller instance
$controller = new $controllerClass();

// Call initController properly - this sets up $this->request
$controller->initController($request, $response, $logger);

// Check if method exists
if (!method_exists($controller, $method)) {
    header('HTTP/1.1 404 Not Found');
    echo "<h1>404 - Page Not Found</h1>";
    echo "<p>Method '$method' not found in controller '$controllerName'.</p>";
    exit;
}

// Call the method
try {
    $output = call_user_func_array([$controller, $method], $params);
    
    if (is_string($output)) {
        echo $output;
    } elseif ($output instanceof \CodeIgniter\HTTP\ResponseInterface) {
        $output->send();
    }
} catch (Throwable $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo "<h1>500 - Error</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
    if (ENVIRONMENT === 'development') {
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
}

