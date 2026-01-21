<?php
/**
 * CI4 Debug - Simple Router Test
 */

namespace {
    define('ENVIRONMENT', 'development');
    define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
    chdir(FCPATH);

    echo "<h1>CI4 Debug - Routes Test</h1>";
    echo "<h2>PHP Version</h2>" . phpversion() . "<br>";

    echo "<h2>Paths</h2>";
    require FCPATH . '../app/Config/Paths.php';
    $paths = new \Config\Paths();
    echo "App: " . realpath($paths->appDirectory) . "<br>";
    echo "System: " . realpath($paths->systemDirectory) . "<br>";

    echo "<h2>Testing</h2>";
    try {
        // Just test if vendor autoload works
        require FCPATH . '../vendor/autoload.php';
        echo "1. Vendor autoload loaded<br>";

        // Test if we can create request
        $appConfig = require FCPATH . '../app/Config/App.php';
        echo "2. App config loaded<br>";

        // Test route file syntax
        echo "3. Checking Routes.php syntax...<br>";
        $routesFile = FCPATH . '../app/Config/Routes.php';
        $routesContent = file_get_contents($routesFile);
        echo "   Routes file size: " . strlen($routesContent) . " bytes<br>";

        // Try to find all route definitions
        preg_match_all("/\\\$routes->(get|post|put|delete|patch)\(['\"]([^'\"]+)['\"]/", $routesContent, $matches);
        $foundRoutes = array_unique($matches[2]);
        echo "   Found " . count($foundRoutes) . " route definitions<br>";

        // Show some routes
        echo "<h3>Sample Routes Found:</h3>";
        echo "<ul>";
        foreach (array_slice($foundRoutes, 0, 10) as $route) {
            echo "<li>$route</li>";
        }
        echo "</ul>";

        // Test if the route file can be parsed
        echo "<h3>Testing Route File Execution</h3>";
        
        // Create a mock RouteCollection
        require $paths->systemDirectory . '/Router/RouteCollection.php';
        require $paths->systemDirectory . '/Router/RouteCollectionInterface.php';
        require $paths->systemDirectory . '/Router/RouteHandler.php';
        require $paths->systemDirectory . '/Router/Router.php';
        require $paths->systemDirectory . '/Router/RouterInterface.php';
        require $paths->systemDirectory . '/Config/Services.php';
        require APPPATH . 'Config/Services.php';
        require $paths->systemDirectory . '/Config/AutoloadConfig.php';
        require APPPATH . 'Config/Autoload.php';
        require $paths->systemDirectory . '/Modules/Modules.php';
        require APPPATH . 'Config/Modules.php';
        require $paths->systemDirectory . '/Autoloader/Autoloader.php';

        // Initialize services first
        $autoloader = new \CodeIgniter\Autoloader\Autoloader();
        $autoloader->initialize(
            new \Config\Autoload(),
            new \Config\Modules()
        )->register();

        // Now create route collection
        $routeCollection = new \CodeIgniter\Router\RouteCollection(
            \Config\Services::locator(),
            $appConfig
        );
        
        // Mock the $routes variable
        $routes = $routeCollection;
        
        // Include routes
        include $routesFile;
        
        echo "4. Routes file executed successfully<br>";
        
        // Get defined routes
        $definedRoutes = $routes->getRoutes();
        echo "<h2>Defined Routes (" . count($definedRoutes) . ")</h2>";
        echo "<pre>" . print_r(array_keys($definedRoutes), true) . "</pre>";

    } catch (Throwable $e) {
        echo "<h2>Error</h2>";
        echo "Message: " . $e->getMessage() . "<br>";
        echo "File: " . $e->getFile() . "<br>";
        echo "Line: " . $e->getLine() . "<br>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
}

