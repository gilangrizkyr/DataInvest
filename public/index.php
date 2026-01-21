<?php
/**
 * Debug Index - Simple Test
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ENVIRONMENT', 'development');
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

echo "<h1>Debug Test</h1>";
echo "<h2>Step 1: Loading Paths</h2>";

require dirname(__DIR__) . '/app/Config/Paths.php';
$paths = new \Config\Paths();
echo "Paths loaded<br>";

echo "<h2>Step 2: Loading Autoloader</h2>";
require dirname(__DIR__) . '/vendor/autoload.php';
echo "Vendor autoload loaded<br>";

echo "<h2>Step 3: Defining Constants</h2>";
define('APPPATH', realpath($paths->appDirectory) . DIRECTORY_SEPARATOR);
define('ROOTPATH', realpath(APPPATH . '../') . DIRECTORY_SEPARATOR);
define('SYSTEMPATH', realpath($paths->systemDirectory) . DIRECTORY_SEPARATOR);
echo "APPPATH: " . APPPATH . "<br>";
echo "SYSTEMPATH: " . SYSTEMPATH . "<br>";

echo "<h2>Step 4: Loading Config/Constants</h2>";
require APPPATH . 'Config/Constants.php';
echo "Constants loaded<br>";

echo "<h2>Step 5: Loading Autoloader Classes</h2>";
require SYSTEMPATH . 'Config/AutoloadConfig.php';
require APPPATH . 'Config/Autoload.php';
require SYSTEMPATH . 'Modules/Modules.php';
require APPPATH . 'Config/Modules.php';
require SYSTEMPATH . 'Autoloader/Autoloader.php';
require SYSTEMPATH . 'Config/BaseService.php';
require SYSTEMPATH . 'Config/Services.php';
require APPPATH . 'Config/Services.php';
echo "Config classes loaded<br>";

echo "<h2>Step 6: Initializing Autoloader</h2>";
try {
    $autoloader = \CodeIgniter\Config\Services::autoloader()->initialize(
        new \Config\Autoload(),
        new \Config\Modules()
    )->register();
    echo "Autoloader initialized<br>";
} catch (Throwable $e) {
    echo "<h2>ERROR in Autoloader</h2>";
    echo "Message: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    exit;
}

echo "<h2>Step 7: Loading Common Functions</h2>";
require SYSTEMPATH . 'Common.php';
echo "Common loaded<br>";

echo "<h2>Step 8: Getting Route</h2>";
$route = isset($_GET['r']) ? ltrim($_GET['r'], '/') : 'dashboard';
echo "Route: $route<br>";

echo "<h2>Step 9: Parsing Route</h2>";
$segments = explode('/', $route);
$controllerName = ucfirst(array_shift($segments));
$method = !empty($segments) ? array_shift($segments) : 'index';
echo "Controller: $controllerName<br>";
echo "Method: $method<br>";

echo "<h2>Step 10: Loading Controller</h2>";
$controllerFile = APPPATH . 'Controllers/' . $controllerName . '.php';
echo "Controller file: $controllerFile<br>";

if (!file_exists($controllerFile)) {
    echo "<h2>ERROR: Controller file not found</h2>";
    exit;
}

require_once $controllerFile;
echo "Controller file loaded<br>";

$controllerClass = "App\\Controllers\\" . $controllerName;
echo "Controller class: $controllerClass<br>";

if (!class_exists($controllerClass)) {
    echo "<h2>ERROR: Controller class not found</h2>";
    exit;
}

echo "<h2>Step 11: Creating Controller Instance</h2>";
try {
    $controller = new $controllerClass();
    echo "Controller instance created<br>";
} catch (Throwable $e) {
    echo "<h2>ERROR creating controller</h2>";
    echo "Message: " . $e->getMessage() . "<br>";
    exit;
}

echo "<h2>Step 12: Calling Method</h2>";
if (!method_exists($controller, $method)) {
    echo "<h2>ERROR: Method not found</h2>";
    exit;
}

try {
    echo "Calling $controllerClass->$method()<br>";
    $output = $controller->$method();
    echo "Method called successfully<br>";
    echo "Output type: " . gettype($output) . "<br>";
    if (is_string($output)) {
        echo "<h2>Output</h2>";
        echo "Output length: " . strlen($output) . " bytes<br>";
    }
} catch (Throwable $e) {
    echo "<h2>ERROR in method call</h2>";
    echo "Message: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>DONE</h2>";

