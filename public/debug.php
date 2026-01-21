<?php
/**
 * Simple CI4 Debug
 */

namespace {
    define('ENVIRONMENT', 'development');
    define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
    chdir(FCPATH);

    echo "<h1>CI4 Debug</h1>";
    echo "<h2>PHP Version</h2>" . phpversion() . "<br>";

    echo "<h2>Paths</h2>";
    require FCPATH . '../app/Config/Paths.php';
    $paths = new \Config\Paths();
    echo "App: " . $paths->appDirectory . "<br>";
    echo "System: " . $paths->systemDirectory . "<br>";

    echo "<h2>Loading</h2>";
    try {
        // Use vendor autoload
        require FCPATH . '../vendor/autoload.php';
        echo "Vendor autoload loaded<br>";

        // Load services directly
        $appConfig = require FCPATH . '../app/Config/App.php';
        echo "App config loaded<br>";

        $routes = require FCPATH . '../app/Config/Routes.php';
        echo "Routes loaded<br>";

        // Try to get route collection
        $collector = new \CodeIgniter\Router\RouteCollector(
            new \CodeIgniter\Router\RouteHandler($appConfig)
        );

        // Check if routes has data
        echo "<h2>Routes Type</h2>";
        echo gettype($routes) . "<br>";
        if (is_array($routes)) {
            echo "Count: " . count($routes) . "<br>";
        }

    } catch (Throwable $e) {
        echo "<h2>Error</h2>";
        echo "Message: " . $e->getMessage() . "<br>";
        echo "File: " . $e->getFile() . "<br>";
        echo "Line: " . $e->getLine() . "<br>";
    }
}

