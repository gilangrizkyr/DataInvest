<?php
/**
 * Debug CI4 bootstrap
 */

namespace {
    define('ENVIRONMENT', 'development');
    define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
    chdir(FCPATH);

    echo "<h1>CI4 Debug</h1>";

    // Show PHP info
    echo "<h2>PHP Version</h2>";
    echo phpversion();

    // Show what paths we have
    echo "<h2>Paths</h2>";
    require FCPATH . '../app/Config/Paths.php';
    $paths = new \Config\Paths();
    echo "App Dir: " . $paths->appDirectory . "<br>";
    echo "System Dir: " . $paths->systemDirectory . "<br>";

    // Try to load CodeIgniter
    echo "<h2>Loading CI4</h2>";
    try {
        require $paths->systemDirectory . '/Boot.php';
        echo "Boot.php loaded<br>";

        // Get the request
        $request = \Config\Services::request();
        echo "Request service created<br>";

        // Get URI
        $uri = $request->getUri();
        echo "URI: " . $uri->getPath() . "<br>";

        // Try to create CI4 instance
        $app = new \CodeIgniter\CodeIgniter($paths);
        echo "CI4 instance created<br>";

        // Show available routes
        echo "<h2>Routes</h2>";
        $routes = \Config\Services::routes()->getRoutes();
        echo "<pre>";
        print_r(array_keys($routes));
        echo "</pre>";

    } catch (Exception $e) {
        echo "<h2>Error</h2>";
        echo $e->getMessage();
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
}

