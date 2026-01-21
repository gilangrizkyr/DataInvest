<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

echo "<h1>Debug CI4 Boot</h1>";

if (isset($_GET['r'])) {
    $route = ltrim($_GET['r'], '/');
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $_SERVER['REQUEST_URI'] = $requestUri . '/' . $route;
    $_SERVER['PATH_INFO'] = '/' . $route;
    
    echo "Route: $route<br>";
    echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "<br>";
}

require dirname(__DIR__) . '/app/Config/Paths.php';
require dirname(__DIR__) . '/vendor/autoload.php';

$paths = new \Config\Paths();

require $paths->systemDirectory . '/Boot.php';

try {
    exit(\CodeIgniter\Boot::bootWeb($paths));
} catch (Throwable $e) {
    echo "<h2>ERROR:</h2>";
    echo "Message: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
}

