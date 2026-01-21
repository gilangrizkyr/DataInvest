<?php
/**
 * Simple test entry point - bypass routing
 */

// Set proper environment
define('ENVIRONMENT', 'development');

// Set current directory
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(FCPATH);

// Bootstrap CodeIgniter
require_once FCPATH . '../app/Config/Paths.php';
require_once FCPATH . '../vendor/autoload.php';

$paths = new \Config\Paths();

// Get the CodeIgniter instance
$app = \Config\Services::codeigniter();
$app->initialize();

// Get request
$request = \Config\Services::request();

// Debug: show what URI we're getting
echo "<h1>Test Entry Point</h1>";
echo "<p>REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NOT SET') . "</p>";
echo "<p>PATH_INFO: " . ($_SERVER['PATH_INFO'] ?? 'NOT SET') . "</p>";
echo "<p>SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'NOT SET') . "</p>";

// Try to load and display routes
echo "<h2>Defined Routes:</h2>";
echo "<pre>";
try {
    $routes = \Config\Services::routes()->getRoutes();
    print_r($routes);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
echo "</pre>";

// Try to run the app
echo "<h2>App Response:</h2>";
try {
    $response = $app->run($request);
    echo $response->getBody();
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

