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

        // Initialize autoloader manually to load routes
        echo "<h2>Loading Autoloader</h2>";
        require_once $paths->systemDirectory . '/Config/AutoloadConfig.php';
        require_once APPPATH . 'Config/Autoload.php';
        require_once $paths->systemDirectory . '/Modules/Modules.php';
        require_once APPPATH . 'Config/Modules.php';
        require_once $paths->systemDirectory . '/Autoloader/Autoloader.php';
        require_once $paths->systemDirectory . '/Config/BaseService.php';
        require_once $paths->systemDirectory . '/Config/Services.php';
        require_once APPPATH . 'Config/Services.php';
        echo "Files loaded<br>";

        $autoloader = new \CodeIgniter\Autoloader\Autoloader();
        $autoloader->initialize(new \Config\Autoload(), new \Config\Modules())->register();
        echo "Autoloader initialized<br>";

        // Load routes manually
        echo "<h2>Loading Routes</h2>";
        require_once APPPATH . 'Config/Routes.php';
        $routes = \Config\Services::routes();
        echo "Routes loaded<br>";

        // Show available routes
        echo "<h2>Defined Routes</h2>";
        $definedRoutes = $routes->getRoutes();
        echo "<pre>";
        print_r(array_keys($definedRoutes));
        echo "</pre>";

        // Test if dashboard route exists
        echo "<h2>Route Test</h2>";
        echo "Dashboard route exists: " . (isset($definedRoutes['dashboard']) || isset($definedRoutes['/dashboard']) ? 'YES' : 'NO') . "<br>";

        // Try to get route collection
        echo "<h2>Route Collection</h2>";
        echo "Total routes: " . count($definedRoutes) . "<br>";

    } catch (Exception $e) {
        echo "<h2>Error</h2>";
        echo $e->getMessage() . "<br>";
        echo "File: " . $e->getFile() . "<br>";
        echo "Line: " . $e->getLine() . "<br>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
}

