<?php
/**
 * Debug Index - Verbose Error Reporting
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('ENVIRONMENT', 'development');
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

echo "<h1>Debug CI4 Index</h1>";
echo "<pre>";

try {
    echo "Step 1: Loading Paths...\n";
    require dirname(__DIR__) . '/app/Config/Paths.php';
    $paths = new \Config\Paths();
    
    echo "Step 2: Loading vendor autoload...\n";
    require dirname(__DIR__) . '/vendor/autoload.php';
    
    echo "Step 3: Defining constants...\n";
    define('APPPATH', realpath($paths->appDirectory) . DIRECTORY_SEPARATOR);
    define('ROOTPATH', realpath(APPPATH . '../') . DIRECTORY_SEPARATOR);
    define('SYSTEMPATH', realpath($paths->systemDirectory) . DIRECTORY_SEPARATOR);
    define('WRITEPATH', realpath($paths->writableDirectory) . DIRECTORY_SEPARATOR);
    
    echo "Step 4: Loading Constants.php...\n";
    require APPPATH . 'Config/Constants.php';
    
    echo "Step 5: Loading autoloader classes...\n";
    require SYSTEMPATH . 'Config/AutoloadConfig.php';
    require APPPATH . 'Config/Autoload.php';
    require SYSTEMPATH . 'Modules/Modules.php';
    require APPPATH . 'Config/Modules.php';
    require SYSTEMPATH . 'Autoloader/Autoloader.php';
    require SYSTEMPATH . 'Config/BaseService.php';
    require SYSTEMPATH . 'Config/Services.php';
    require APPPATH . 'Config/Services.php';
    
    echo "Step 6: Initializing autoloader...\n";
    \CodeIgniter\Config\Services::autoloader()->initialize(
        new \Config\Autoload(),
        new \Config\Modules()
    )->register();
    
    echo "Step 7: Loading Common.php...\n";
    require SYSTEMPATH . 'Common.php';
    
    echo "Step 8: Getting route from query string...\n";
    $route = isset($_GET['r']) ? ltrim($_GET['r'], '/') : 'dashboard';
    echo "Route: $route\n";
    
    echo "Step 9: Parsing route...\n";
    $segments = explode('/', $route);
    $controllerName = ucfirst(array_shift($segments));
    $method = !empty($segments) ? array_shift($segments) : 'index';
    $params = $segments;
    echo "Controller: $controllerName, Method: $method\n";
    
    echo "Step 10: Loading controller file...\n";
    $controllerFile = APPPATH . 'Controllers/' . $controllerName . '.php';
    if (!file_exists($controllerFile)) {
        throw new Exception("Controller file not found: $controllerFile");
    }
    require_once $controllerFile;
    
    echo "Step 11: Creating CI4 objects...\n";
    $appConfig = new \Config\App();
    $appConfig->baseURL = 'https://dpmptsp.tail8af30b.ts.net/';
    
    $uri = new \CodeIgniter\HTTP\URI('https://dpmptsp.tail8af30b.ts.net/' . $route);
    $userAgent = new \CodeIgniter\HTTP\UserAgent();
    
    $request = \CodeIgniter\Config\Services::request($appConfig, $uri, 'php://input', $userAgent);
    $response = \CodeIgniter\Config\Services::response($appConfig);
    $logger = \CodeIgniter\Config\Services::logger();
    
    echo "Step 12: Creating controller instance...\n";
    $controllerClass = "App\\Controllers\\" . $controllerName;
    $controller = new $controllerClass();
    
    echo "Step 13: Calling initController...\n";
    $controller->initController($request, $response, $logger);
    
    echo "Step 14: Checking method...\n";
    if (!method_exists($controller, $method)) {
        throw new Exception("Method not found: $method");
    }
    
    echo "Step 15: Calling $controllerClass->$method()...\n";
    $output = call_user_func_array([$controller, $method], $params);
    
    echo "Method returned type: " . gettype($output) . "\n";
    
    if (is_string($output)) {
        echo "Output length: " . strlen($output) . " bytes\n";
    }
    
    echo "\n=== SUCCESS ===\n";
    
} catch (Throwable $e) {
    echo "\n=== ERROR ===\n";
    echo "Type: " . get_class($e) . "\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "</pre>";

